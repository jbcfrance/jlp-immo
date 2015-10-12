<?php

if ( ! defined('CLASSES_PATH') ) {
	define('CLASSES_PATH', '../classes');
}


// Header :
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
<title>Page d'installation de SQLComposer</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<style type="text/css">
body {
	margin:0px;
	font-size:1em;
}

#menu, #contain {
	float:left;
}
#menu {
	width:20%;
	font-size:12pt;
}
#contain {
	width:80%;
	font-size:12pt;
}
#contain table {
	padding:10px;
	border:0px none #000000;
}
#contain input, #contain select {
	font-size:10pt;
}

.label {
	text-align:right;
	padding-right:15px;
}
</style>
</head>
<body>

<div id="contain">
<?php

if ( ! isset($_GET['step']) ) {
	$_GET['step'] = 0;
}

switch ( $_GET['step'] ) {
	case 1 : {
		if ( !validStep1() ) {
			$_GET['step']--;
		}
	} break;
	case 2 : {
		if ( !validStep2() ) {
			$_GET['step']--;
		}
	} break;
	case 3 : {
		if ( !validStep3() ) {
			$_GET['step']--;
		}
	} break;
}

switch ( $_GET['step'] ) {
	case 0 : {
		step1();
	} break;
	case 1 : {
		step2();
	} break;
	case 2 : {
		step3();
	} break;
	case 3 : {
		step4();
	} break;
}


// ETAPE 1
function step1 () {
	?>
<form method="post" action="?step=1">
	<table cellpadding="5" cellspacing="0" border="1" width="100%">
		<colgroup>
			<col width="25%"/>
			<col width="75%"/>
		</colgroup>
		<tbody>
			<tr>
				<th colspan="2" align="left">
				Paramètres de la base de donnée :
				</th>
			</tr>
			
			<tr>
				<td class="label">Host</td>
				<td><input type="text" name="HOST" size="50" tabindex="1" /></td>
			</tr>
			
			<tr>
				<td class="label">User</td>
				<td><input type="text" name="USER" size="50" tabindex="2" /></td>
			</tr>
			
			<tr>
				<td class="label">Password</td>
				<td><input type="text" name="PASSWORD" size="50" tabindex="3" /></td>
			</tr>
			
			<tr>
				<td class="label">Base de donnée</td>
				<td><input type="text" name="DB" size="50" tabindex="4" /></td>
			</tr>
			
			<tr>
				<th colspan="2"><input type="submit" value="Valider" tabindex="5" /></th>
			</tr>
		</tbody>
	</table>
</form>
	<?php
}

function validStep1 () {
	$rDB = @mysql_connect( $_POST['HOST'], $_POST['USER'], $_POST['PASSWORD'] );
	if ( mysql_error() ) {
		return false;
	}
	@mysql_select_db($_POST['DB'], $rDB);
	if ( mysql_error() ) {
		die(mysql_error());
		return false;
	}
	
	$_SESSION['DB'] = array(
		'HOST'		=> $_POST['HOST'],
		'USER'		=> $_POST['USER'],
		'PASSWORD'	=> $_POST['PASSWORD'],
		'DB'		=> $_POST['DB']
	);
	return true;
}

// ETAPE 2
function step2 () {
	?>
<form method="post" action="?step=2">
	<table cellpadding="5" cellspacing="0" border="1" width="100%">
		<colgroup>
			<col width="25%"/>
			<col width="75%"/>
		</colgroup>
		<tbody>
			<tr>
				<th colspan="2" align="left">
				Sélectionnez les tables :
				</th>
			</tr>
<?php

$rDB = mysql_connect( $_SESSION['DB']['HOST'], $_SESSION['DB']['USER'], $_SESSION['DB']['PASSWORD'] );
mysql_select_db($_SESSION['DB']['DB'], $rDB);
$sSQL = 'SHOW TABLES';
$rReq = @mysql_query($sSQL);
if ( mysql_error() ) {
	return false;
}
$_SESSION['ALL_TABLES'] = array();

while ( $aDatas = mysql_fetch_row($rReq) ) {
	$_SESSION['ALL_TABLES'][] = $aDatas[0];
	?>
	<tr>
		<td><input type="checkbox" id="table_<?php echo $aDatas[0]; ?>" name="<?php echo $aDatas[0]; ?>" checked="checked" /></td>
		<td>
			<label for="table_<?php echo $aDatas[0]; ?>"><?php echo $aDatas[0]; ?>			</label>
		</td>
	</tr>
	<?php
}
?>
			<tr>
				<th colspan="2"><input type="submit" value="Valider" tabindex="5" /></th>
			</tr>
		</tbody>
	</table>
</form>
	<?php
}
function validStep2 () {
	unset($_SESSION['JOINS']);
	return true;
}

// ETAPE 3
function step3 () {
	$rDB = mysql_connect( $_SESSION['DB']['HOST'], $_SESSION['DB']['USER'], $_SESSION['DB']['PASSWORD'] );
	mysql_select_db($_SESSION['DB']['DB'], $rDB);

	if ( ! isset($_SESSION['JOINS']) ) {
		$aTables = array();
		foreach ( $_SESSION['ALL_TABLES'] as $sTable ) {
			if ( isset( $_POST[ $sTable ] ) ) {
				$aTables[ $sTable ] = array();
			}
		}
		$_SESSION['TABLES'] = $aTables;
		
		foreach ( $_SESSION['TABLES'] as $sTable => $aTableDesc ) {
			$sSQL = 'SHOW COLUMNS FROM ' . $sTable;
			$rReq = mysql_query($sSQL);
			while ( $aDatas = mysql_fetch_assoc($rReq) ) {
				$sField = $aDatas['Field'];
				$_SESSION['TABLES'][ $sTable ][ $sField ] = $aDatas;
			}
		}
		
		// Trouver les jointures :
		$aJoin = array();
		foreach ( $_SESSION['TABLES'] as $sTable => $aTableDesc ) {
			$aJoin[ $sTable ] = array();
			foreach ( $aTableDesc as $sField => $aTableField ) {
				$aJoin[ $sTable ][ $sField ] = array();
			}
		}
		
		foreach ( $_SESSION['TABLES'] as $sTableFrom => $aTableFrom ) {
			foreach ( $_SESSION['TABLES'] as $sTableTo => $aTableTo ) {
				if ( $sTableFrom == $sTableTo ) {
					continue;
				}
				$aFieldsFrom	= array_change_key_case( array_keys($aTableFrom), CASE_UPPER);
				$aFieldsTo		= array_change_key_case( array_keys($aTableTo), CASE_UPPER);
				
				foreach ( $aFieldsFrom as $sFrom ) {
					foreach ( $aFieldsTo as $sTo ) {
						if ( strpos($sFrom, $sTo)!==false ) {
							$aJoin[ $sTableFrom ][ $sFrom ][] = array(
								'TABLE'	=> $sTableTo,
								'FIELD'	=> $sTo
							);
						}
					}
				}
			}
		}
		$_SESSION['JOINS'] = $aJoin;
	} else {
		$aJoin = $_SESSION['JOINS'];
	}
	
	$sOptions = '';
	$sOptions .= '<option value="">- - - - -</option>';
	foreach ( $_SESSION['TABLES'] as $sTable => $aTableDesc ) {
		$sOptions .= '<optgroup label="'.$sTable.'">';
		foreach ( $aTableDesc as $sField => $aTableField ) {
			$sOptions .= '<option value="'.$sTable.'::'.$sField.'">' . $sField . '</option>';
		}
		$sOptions .= '</optgroup>';
	}
	
	?>
<form method="post" action="?step=3">
	<table cellpadding="5" cellspacing="0" border="1" width="100%">
		<colgroup>
			<col width="45%"/>
			<col width="45%"/>
			<col width="10%"/>
		</colgroup>
		<tbody>
			<tr>
				<th colspan="3" align="left">
				Gestion des jointures automatiques :
				</th>
			</tr>
	<?php
	foreach ( $_SESSION['JOINS'] as $sTable => $aFields ) {
		foreach ( $aFields as $sField => $aJoins ) {
			foreach ( $aJoins as $aJoinTo ) {
				?>
				<tr>
					<td><?php echo $sTable ?>.<?php echo $sField ?></td>
					<td><?php echo $aJoinTo['TABLE'] ?>.<?php echo $aJoinTo['FIELD'] ?></td>
					<td><input type="submit" name="del::<?php echo $sTable ?>::<?php echo $sField ?>::<?php echo $aJoinTo['TABLE'] ?>::<?php echo $aJoinTo['FIELD'] ?>" value="X" /></td>
				</tr>
				<?php
			}
		}
	}
	?>
			<tr>
				<td>
					<select name="FROM">
					<?php echo $sOptions; ?>
					</select>
				</td>
				<td>
					<select name="TO">
					<?php echo $sOptions; ?>
					</select>
				</td>
				<td><input type="submit" name="addField" value="Ajoutter"/></td>
			</tr>
			<tr>
				<th colspan="3"><input type="submit" value="Valider" tabindex="5" /></th>
			</tr>
		</tbody>
	</table>
</form>
	<?php
}

function validStep3 () {
	if ( isset($_POST['addField']) ) {
		if ( (isset($_POST['FROM'], $_POST['TO'])) && ($_POST['FROM']!=null) && ($_POST['TO']!=null) ) {
			$aFrom	= explode('::', $_POST['FROM']);
			$aTo	= explode('::', $_POST['TO']);
			
			$_SESSION['JOINS'][ $aFrom[0] ][ $aFrom[1] ][] = array(
				'TABLE'	=> $aTo[0],
				'FIELD'	=> $aTo[1]
			);
			$_SESSION['JOINS'][ $aTo[0] ][ $aTo[1] ][] = array(
				'TABLE'	=> $aFrom[0],
				'FIELD'	=> $aFrom[1]
			);
		}
		
		return false;
	}
	
	foreach ( $_POST as $k => $v ) {
		if ( strpos($k, 'del')!==false ) {
			$aDel = explode('::', $k);
			
			if ( count($aDel) == 5 ) {
				$aJoins = $_SESSION['JOINS'][ $aDel[1] ][ $aDel[2] ];
				foreach ( $aJoins as $i => $aJoin ) {
					if ( ( $aJoin['TABLE'] == $aDel[3] ) && ( $aJoin['FIELD'] == $aDel[4] ) ) {
						unset( $_SESSION['JOINS'][ $aDel[1] ][ $aDel[2] ][$i] );
					}
				}
			}
			return false;
		}
	}
	return true;
}

// ETAPE 4
function step4 () {
	$rDB = mysql_connect( $_SESSION['DB']['HOST'], $_SESSION['DB']['USER'], $_SESSION['DB']['PASSWORD'] );
	mysql_select_db($_SESSION['DB']['DB'], $rDB);
	
	if ( ! is_dir(CLASSES_PATH) ) {
		mkdir(CLASSES_PATH, '0777');
	}
	if ( ! is_dir(CLASSES_PATH.'/bases') ) {
		mkdir(CLASSES_PATH.'/bases', '0777');
	}
	
	?>
<ul>
	<?php
	
	$aFilesBase	= array();
	$aFiles		= array();
	
	
	foreach ( $_SESSION['TABLES'] as $sTable => $aTable ) {
		$aFieldsName	= array();	// FIELDS
		$aType			= array();	// TYPE
		$aPrimary		= array();	// PRIMARY KEYS
		
		
		foreach ( $aTable as $sField => $aField ) {
			$aFieldsName[] = $sField;
			
			if ( $aField['Key'] == 'PRI' ) {
				$aPrimary[] = $sField;
			}
			
			$sType = strtolower( $aField['Type'] );
			if (
					( strpos($sType, 'varchar') )
				||	( strpos($sType, 'text') )
				||	( strpos($sType, 'char') )
				||	( strpos($sType, 'tinyblob') )
				||	( strpos($sType, 'tinytext') )
				||	( strpos($sType, 'blob') )
				||	( strpos($sType, 'mediumblob') )
				||	( strpos($sType, 'mediumtext') )
				||	( strpos($sType, 'longblob') )
				||	( strpos($sType, 'longtext') )
			) {
				$aType[ $sField ] = 'TEXT';
			} else if (
					( strpos($sType, 'tinyint') )
				||	( strpos($sType, 'smallint') )
				||	( strpos($sType, 'mediumint') )
				||	( strpos($sType, 'int') )
				||	( strpos($sType, 'bigint') )
				||	( strpos($sType, 'float') )
				||	( strpos($sType, 'double') )
				||	( strpos($sType, 'decimal') )
				||	( strpos($sType, 'bigint') )
				||	( strpos($sType, 'bool') )
			) {
				$aType[ $sField ] = 'NUMBER';
			} else if (
					( strpos($sType, 'date') )
				||	( strpos($sType, 'datetime') )
				||	( strpos($sType, 'timestamp') )
				||	( strpos($sType, 'time') )
				||	( strpos($sType, 'year') )
			) {
				$aType[ $sField ] = 'DATE';
			} else if (
					( strpos($sType, 'enum') )
				||	( strpos($sType, 'set') )
			) {
				$aType[ $sField ] = 'MULTIPLE';
			} else if (
					( strpos($sType, 'binary') )
				||	( strpos($sType, 'varbinary') )
			) {
				$aType[ $sField ] = 'BINARY';
			}
		}
		
		$aJoin = array();
		
		if ( isset($_SESSION['JOINS'][ $sTable ]) ) {
			foreach ( $_SESSION['JOINS'][ $sTable ] as $sField => $aFields ) {
				foreach ( $aFields as $aField ) {
					$aJoin[ $aField['TABLE'] ][ $sField ] = $aField['FIELD'];
				}
			}
		} else {
			$aJoin = array();
		}
		
		$sJoinFunc = '';
		foreach ( $aJoin as $sTableTo => $aTables ) {
			$aForJoin1 = array();
			$aForJoin2 = array();
			$aForJoin3 = array();
			foreach ( $aTables as $sFieldTo => $sFieldFrom ) {
				$aForJoin1[] = '$sThisTable.\'.'. $sFieldFrom .'\'';
				$aForJoin2[] = $sTableTo.'::Field($sJoinTable.\'.'.$sFieldTo.'\')';
				$aForJoin3[] = '\'=\'';
			}
			
			$sTMP = '
		$aSendsParams[]='. join($aForJoin1, '.\' AND \'.') .';
		$aSendsParams[]=array(array('. join($aForJoin2, ', ') .'));
		$aSendsParams[]=array('. join($aForJoin3, ', ') .');
		';
			// Composition de la fonction :
			$sGabari = '	
	// JOIN ON %TABLE_TO%
	public function join_%TABLE_TO%(){
		$aParams=func_get_args();
		$sJoinTable=%TABLE_TO%::getTableName();
		$sThisTable=$this->getTableName();
		$aSendsParams=array($sJoinTable);
		foreach($aParams as $kParams=>$vParams){
			if(is_array($vParams)){
				foreach($vParams as $kP=>$vP){
					$aSendsParams[]=$vP;
				}
			}else{
				$aSendsParams[]=$vParams;
			}
		}
		'.$sTMP.'
		$this->autoJoin($aSendsParams);
		return $this;
	}
';
				
			$sGabari = str_replace('%TABLE_TO%',	$sTableTo,	$sGabari);
			
			$sJoinFunc .= $sGabari;
		}
		
		
		// Composition du fichier BASE :
		$sGabari = '<?php
/*
Fichier genere par SQLComposer. Il est deconseille de le modifier (si vous devez faire une nouvelle
generation de ce fichier, toutes modification serait perdue). Faites plutot des extensions et
utilise la methode :
	SQLComposer::createExtendedObject

qui vous permettra de generer des fichiers de la classe que vous souhaitez.
*/
class %TABLE_NAME_UPPER%_BASE extends SQLComposer{
	// STATIC //
	private static $sTableName=\'%TABLE_NAME%\';
	private static $aRealFields=%FIELDS%;
	private static $aFieldsTranslation=array ();
	private static $aFields=%FIELDS%;
	private static $aTypes=%TYPES%;
	private static $aTableKeys=%KEYS%;
	private static $aTableJoin=%JOINS%;
	
	public static function getTableName(){
		return self::$sTableName;
	}
	public static function setTableName($sTableName){
		SQLComposer::setTableClassName( __CLASS__, $sTableName );
		self::$sTableName = $sTableName;
	}
	public static function getFields(){
		array_walk(self::$aFields, create_function(\'&$v\', \'$v=strtoupper($v);\'));
		return self::$aFields;
	}
	public static function getRealFields(){
		array_walk(self::$aRealFields, create_function(\'&$v\', \'$v=strtoupper($v);\'));
		return self::$aRealFields;
	}
	public static function rewindFields(){
		self::$aFields=%FIELDS%;
	}
	public static function addField($sFieldName, $sFieldRealName=null){
		self::$aFields[]=$sFieldName;
		if(null!==$sFieldRealName){
			$aExplodedField = explode(\'.\', $sFieldRealName);
			$sFieldRealName = end($aExplodedField);
			self::$aFieldsTranslation[strtoupper($sFieldName)]=strtoupper($sFieldRealName);
		}
	}
	public static function getJoin(){
		return self::$aTableJoin;
	}
	public static function Field($sField,$sAlias=null){
		$sPattern=\'@([[:alnum:]_]*)@\';
		$aMatches=array();
		preg_match_all($sPattern,$sField,$aMatches);
		$aFields=self::getFields();
		for($i=0,$iMax=count($aMatches[0]);$i<$iMax;$i++){
			if(in_array(strtoupper($aMatches[0][$i]),$aFields)){
				return Field::getOccurence(self::getTableName(),$sField,$sAlias);
			}
		}
		return $sField;
	}
	public static function getOccurence($aValues=array()){
		$sClassName=SQLComposer::getObjectClassName(__CLASS__);
		$oCreatedObject=new $sClassName();
		if(!empty($aValues)){
			$aFields=self::getFields();
			$aRealFields=self::getRealFields();
			$aSignature=array();
			foreach($aFields as $vFields){
				if(isset($aValues[strtoupper($vFields)])){
					$oCreatedObject->values[strtoupper($vFields)]=$aValues[strtoupper($vFields)];
					if(is_string($aRealFields) && !isset($aValues[strtoupper($aRealFields)])){
						$oCreatedObject->values[self::$aFieldsTranslation[strtoupper($vFields)]]=$aValues[strtoupper($vFields)];
					}
				}
			}
		}
		return $oCreatedObject;
	}
	
	// OBJECT //
	public function Select($bForceStatic=false){
		$sClassName=SQLComposer::getObjectClassName(__CLASS__);
		if((!$bForceStatic)&&(isset($this))&&(get_class($this)==$sClassName)){
			$aKeys=self::$aTableKeys;
			$oReq=parent::Select(self::getOccurence(),$aParams);
			for($i=0,$iMax=count($aKeys);$i<$iMax;$i++){
				$sFunc=\'get\'.$aKeys[$i];
				if(is_array($mValue=$this->$sFunc())){
					return false;
				}else{
					if($i==0){
						$oReq->Where($aKeys[$i],$mValue);
					}else{
						$oReq->_AND_($aKeys[$i],$mValue);
					}
				}
			}
			$aObj=$oReq->exec();
			if((is_array($aObj))&&(isset($aObj[0]))){
				$this->values=$aObj[0]->values;
				return true;
			}else{
				return false;
			}
		}else{
			$aParams=func_get_args();
			return parent::Select(self::getOccurence(),$aParams);
		}
	}
	public function Insert($bForceStatic=false){
		$sClassName=SQLComposer::getObjectClassName(__CLASS__);
		if((!$bForceStatic)&&(isset($this))&&(get_class($this)==$sClassName)){
			$aFields=array_unique(self::getFields());
			$oReq=parent::Insert(self::getOccurence(),$aParams);
			$aFieldsIn=array();
			$aValuesIn=array();
			for($i=0,$iMax=count($aFields);$i<$iMax;$i++){
				$sFunc=\'get\'.$aFields[$i];
				if((($mValue=$this->$sFunc())!==array())&&(!empty($aFields[$i]))){
					$aFieldsIn[]=$aFields[$i];
					$aValuesIn[]=$mValue;
				}   
			}
			return $oReq->Set($aFieldsIn)->Values($aValuesIn)->Exec();
		}else{
			$aParams=func_get_args();
			return parent::Insert(self::getOccurence(),$aParams);
		}
	}
	public function Update($bForceStatic=false){
		$sClassName=SQLComposer::getObjectClassName(__CLASS__);
		if((!$bForceStatic)&&(isset($this))&&(get_class($this)==$sClassName)){
			$aFields=array_unique(self::getFields());
			$aKeys=self::$aTableKeys;
			$oReq=parent::Update(self::getOccurence(),$aParams);
			$aFieldsIn=array();
			$aValuesIn=array();
			for($i=0,$iMax=count($aFields);$i<$iMax;$i++){
				$sFunc=\'get\'.$aFields[$i];
				if((($mValue=$this->$sFunc())!==array())&&(!empty($aFields[$i]))){
					$aFieldsIn[]=$aFields[$i];
					$aValuesIn[]=$mValue;
				}
			}
			$oReq->Set($aFieldsIn)->Values($aValuesIn);
			$aKeys=self::$aTableKeys;
			for($i=0,$iMax=count($aKeys);$i<$iMax;$i++){
				$sFunc=\'get\'.$aKeys[$i];
				if(is_array($mValue=$this->$sFunc())){
					return false;
				}else{
					if ( $i==0 ) {
						$oReq->Where($aKeys[$i],$mValue);
					} else {
						$oReq->_AND_($aKeys[$i],$mValue);
					}
				}
			}
			return $oReq->Exec();
		}else{
			$aParams=func_get_args();
			return parent::Update(self::getOccurence(),$aParams);
		}
	}
	public function Delete($bForceStatic=false){
		$sClassName=SQLComposer::getObjectClassName(__CLASS__);
		if((!$bForceStatic)&&(isset($this))&&(get_class($this)==$sClassName)){
			$aKeys=self::$aTableKeys;
			$oReq=parent::Delete(self::getOccurence(),$aParams);
			for ($i=0,$iMax=count($aKeys);$i<$iMax;$i++){
				$sFunc=\'get\'.$aKeys[$i];
				if(is_array($mValue=$this->$sFunc())){
					return false;
				}else{
					if($i==0){
						$oReq->Where($aKeys[$i],$mValue);
					}else{
						$oReq->_AND_($aKeys[$i],$mValue);
					}
				}
			}
			return $oReq->exec();
		}else{
			$aParams=func_get_args();
			return parent::Delete(self::getOccurence(),$aParams);
		}
	}
	
	// JOIN :
	%JOINS_FUNC%
}
';
		$sJoin			= var_export($aJoin, true);
		$sFieldsName	= var_export($aFieldsName, true);
		$sPrimary		= var_export($aPrimary, true);
		$sType			= var_export($aType, true);
		
		$sGabari = str_replace('%TABLE_NAME_UPPER%',	strtoupper($sTable),	$sGabari);
		$sGabari = str_replace('%TABLE_NAME%',			$sTable,				$sGabari);
		$sGabari = str_replace('%TYPES%',				$sType,					$sGabari);
		$sGabari = str_replace('%KEYS%',				$sPrimary,				$sGabari);
		$sGabari = str_replace('%FIELDS%',				$sFieldsName,			$sGabari);
		$sGabari = str_replace('%JOINS%',				$sJoin,					$sGabari);
		$sGabari = str_replace('%JOINS_FUNC%',			$sJoinFunc,				$sGabari);
		
		$rFile = fopen(CLASSES_PATH . '/bases/'.$sTable.'_base.class.php', 'w+');
		fputs($rFile, $sGabari);
		fclose($rFile);
		
		?>
<li><span style="color:#00CC00;font-weight:bold;">OK</span> : Génération du fichier <?php echo CLASSES_PATH ?> /bases/<?php echo $sTable ?>_base.class.php</li>
		<?php
		
		if ( ! is_file(CLASSES_PATH . '/'.$sTable.'.class.php') ) {
			$sGabari = '<?php
class '.strtoupper($sTable).' extends '.strtoupper($sTable).'_BASE {
}
?>';
		
			$rFile = fopen(CLASSES_PATH . '/'.$sTable.'.class.php', 'w+');
			fputs($rFile, $sGabari);
			fclose($rFile);
			?>
<li><span style="color:#00CC00;font-weight:bold;">OK</span> : Génération du fichier <?php echo CLASSES_PATH ?>/<?php echo $sTable ?>.class.php</li>
			<?php
			
		}
			
		$aFilesBase[ strtoupper($sTable) ]	= CLASSES_PATH.'/bases/'.$sTable.'_base.class.php';
		$aFiles[ strtoupper($sTable) ]		= CLASSES_PATH.'/'.$sTable.'.class.php';
	}
	
	$sGabari = '<?php
// Type du SGBD :
define(\'SQL_COMPOSER_SGBD\', \'mysql\');

// Connexion :
SQLComposer::connect( array(
	\'HOST\'	=> \''.$_SESSION['DB']['HOST'].'\',
	\'USER\'	=> \''.$_SESSION['DB']['USER'].'\',
	\'PASS\'	=> \''.$_SESSION['DB']['PASSWORD'].'\',
	\'BASE\'	=> \''.$_SESSION['DB']['DB'].'\'
	)
);

// Includes :
';
	foreach ( $aFilesBase as $sClassName => $sFile ) {
		$sGabari .= '$GLOBALS[\'AUTO_LOAD\'][\''.$sClassName.'_BASE\'] = dirname(__FILE__).\'/'.$sFile.'\';
';
	}

	$sGabari .= '
// Extentions :
';

	foreach ( $aFiles as $sClassName => $sFile ) {
		$sGabari .= '$GLOBALS[\'AUTO_LOAD\'][\''.$sClassName.'\'] = dirname(__FILE__).\'/'.$sFile.'\';
';
	}
	$sGabari .= '
';

	foreach ( $aFiles as $sTable => $sFile ) {
		$sGabari .= 'SQLComposer::createExtendedObject(\''.$sTable.'_BASE\', \''.$sTable.'\');
';
	}
	
	$rFile = fopen(dirname(__FILE__).DIRECTORY_SEPARATOR.'sql_composer__config.php', 'w+');
	fputs($rFile, $sGabari);
	fclose($rFile);
	
	?>
</ul>
	<?php
}


session_write_close();
?>
</div>
</body>
</html>