<?php
class SQLComposerInstaller
{
	const MAX_INSTAL_STEP = 4;
	
	private $sScript = '';
	
	public function __construct ()
	{
		if ( session_id()==='' ) {
			session_start();
		}
		
		if ( ! isset($_SESSION['SQLCOMPOSER']['INSTALL_STEP']) || $_SESSION['SQLCOMPOSER']['INSTALL_STEP']>=self::MAX_INSTAL_STEP) {
			$_SESSION['SQLCOMPOSER'] = array();
			$_SESSION['SQLCOMPOSER']['INSTALL_STEP'] = 0;
		}
		
		
		echo $this->getHeader();
		switch ( $_SESSION['SQLCOMPOSER']['INSTALL_STEP'] ) {
			case 0 : {
				$this->getDatabaseDatas();
			} break;
			case 1 : {
				if ( $_SESSION['SQLCOMPOSER']['DB_TYPE'] == 'MySQL PDO' ) {
					$this->showMySqlPDO();
				}
			} break;
			case 2 : {
				if ( $_SESSION['SQLCOMPOSER']['DB_TYPE'] == 'MySQL PDO' ) {
					$this->showMySqlPDOAutoJoin();
				}
			} break;
			case 3 : {
				if ( $_SESSION['SQLCOMPOSER']['DB_TYPE'] == 'MySQL PDO' ) {
					$this->showMySqlPDOCreateFiles();
				}
			} break;
		}
		
		echo $this->getFooter();
	}
	
	public function getHeader () 
	{
		echo '<?xml version="1.0" encoding="UTF-8"?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
<title>Installation de SQLCopmposer</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="project_pannel.php?url=core/project_pannel/project_pannel.css&t=1319316131" />
<style type="text/css">
.form_list {
	list-style: none outside none;
}
.form_list li {
	clear:both;
	padding:5px 0;
}
.form_list label {
	float:left;
	width:300px;
	text-align:right;
}
.form_list span.field {
	float:left;
	margin-left:20px;
}

.autojoin_table {
	width:100%;
	border-bottom:1px solid #000;
	border-right:1px solid #000;
}
.autojoin_table td {
	border-top:1px solid #000;
	border-left:1px solid #000;
	padding:3px 5px;
}
.no_auto-join {
	text-decoration:none;
	font-weight:bold;
	color:#000;
}
</style>
</head>
<body>
<h1>Installation de SQLComposer</h1>
';
	}
	
	public function getFooter () 
	{
		echo '<script type="text/javascript">'. $this->sScript .'</script></body></html>';
	}
	
	
	public function getDatabaseDatas ()
	{
		if ( isset($_POST['valid_connexion_form']) ) {
			switch ( $_POST['db_type'] ) {
				case 'mysql_pdo' : {
					$aPDODatas = $_POST['mysql_pdo'];
					if ( ! empty($aPDODatas['db_user']) && ! empty($aPDODatas['db_chain']) ) {
						$_SESSION['SQLCOMPOSER']['INSTALL_STEP']++;
						$_SESSION['SQLCOMPOSER']['DB_TYPE'] = 'MySQL PDO';
						$_SESSION['SQLCOMPOSER']['CONNEXION_DATAS'] = $aPDODatas;
						return $this->showMySqlPDO();
					}
				} break;
				default :
					echo 'ouep mais non, désolé... on fait pas ce type de DB chez nous...';
			}
		}
		
		$this->sScript .= <<<SCRIPT_TEXT
document.getElementById('id_db_type').onchange = function() {
	var sClassToShow = 'for_'+ this.value;
	var aLIs = document.getElementById('connexion_list_datas').getElementsByTagName('li');
	
	for ( var i=1, iMax=aLIs.length-1 ; i<iMax ; i++ ) {
		if ( aLIs[i].className==sClassToShow || aLIs[i].class==sClassToShow ) {
			aLIs[i].style.display = "list-item";
		} else {
			aLIs[i].style.display = "none";
		}
	}
}
SCRIPT_TEXT;
	?>
<form method="post" action="">
<fieldset>
	<legend>Initialisation de la base de données</legend>
	<ul class="form_list" id="connexion_list_datas">
		<li>
			<label for="id_db_type">Type de base de données</label>
			<span class="field">
				<select id="id_db_type" name="db_type">
					<option value="0">- - - - -</option>
					<option value="mysql">MySQL (utilisant mysql_)</option>
					<option value="mysql_pdo">MySQL (utilisant PDO)</option>
				</select>
			</span>
		</li>
		
		<!-- START: MySQL PDO -->
		<li class="for_mysql_pdo" style="display:none;">
			<label for="id_mysql_pdo_db_user">User</label>
			<span class="field">
				<input type="text" name="mysql_pdo[db_user]" id="id_mysql_pdo_db_user" value="<?php echo @$_POST['mysql_pdo']['db_user']?>" />
			</span>
		</li>
		<li class="for_mysql_pdo" style="display:none;">
			<label for="id_mysql_pdo_db_pass">Password</label>
			<span class="field">
				<input type="text" name="mysql_pdo[db_pass]" id="id_mysql_pdo_db_pass" value="<?php echo @$_POST['mysql_pdo']['db_pass']?>" />
			</span>
		</li>
		<li class="for_mysql_pdo" style="display:none;">
			<label for="id_mysql_pdo_db_chain">Chaine de connexion</label>
			<span class="field">
				<input type="text" name="mysql_pdo[db_chain]" id="id_mysql_pdo_db_chain" value="<?php echo @$_POST['mysql_pdo']['db_chain']?>" />
				<i>
				(Forme : mysql:host=HOST;dbname=DB en remplacant HOST et DB par les valeurs appropriées)
				</i>
			</span>
		</li>
		<!-- END: MySQL PDO -->
		
		<li>
			<label>&nbsp;</label>
			<span class="field">
				<input type="submit" name="valid_connexion_form" value="Valider" />
			</span>
		</li>
	</ul>
</fieldset>
</form>
	<?php
	}
	
	public function showMySqlPDO ()
	{
		if ( isset($_POST['validerTables']) ) {
			$aSelectedsTables = array();
			$aTablesDesc = $_SESSION['SQLCOMPOSER']['TABLES'];
			
			for ( $i=0, $iMax=count($aTablesDesc) ; $i<$iMax ; $i++ ) {
				if ( @$_POST['selected_table-'. $aTablesDesc[$i]['NAME'] ]==1 ) {
					$aSelectedsTables[] = $aTablesDesc[$i]['NAME'];
				}
			}
			
			if ( count($aSelectedsTables)>0 ) {
				$_SESSION['SQLCOMPOSER']['INSTALL_STEP']++;
				$_SESSION['SQLCOMPOSER']['SELECTED_TABLES'] = $aSelectedsTables;
				return $this->showMySqlPDOAutoJoin();
			}
		}
		if ( ! isset($_SESSION['SQLCOMPOSER']['TABLES']) ) {
			$sUser = $_SESSION['SQLCOMPOSER']['CONNEXION_DATAS']['db_user'];
			$sPass = $_SESSION['SQLCOMPOSER']['CONNEXION_DATAS']['db_pass'];
			$sBase = $_SESSION['SQLCOMPOSER']['CONNEXION_DATAS']['db_chain'];
			
			try {
				$oDB = new PDO($sBase, $sUser, $sPass);
			} catch (PDOException $e) {
				echo'<pre>';print_r( $e );echo'</pre>';
				return $e;
			}
			
			
			$aTables = $oDB->query('SHOW TABLES');
			$aTablesDesc = array();
			while ( $aTable=$aTables->fetch() ) {
				$aTableDesc = array();
				$aTableDesc['NAME'] = $aTable[0];
				$aTableDesc['FIELDS'] = array();
				
				$aTableFields = $oDB->query('SHOW COLUMNS FROM `'. $aTable[0] .'`');
				
				while ( $aTableField=$aTableFields->fetch() ) {
					$aTableDesc['FIELDS'][] = array(
						'NAME'	=> $aTableField[0],
						'TYPE'	=> $aTableField[1],
						'KEY'	=> $aTableField[3],
					);
				}
				$aTablesDesc[] = $aTableDesc;
			}
			
			$_SESSION['SQLCOMPOSER']['TABLES'] = $aTablesDesc;
		}
		$aTablesDesc = $_SESSION['SQLCOMPOSER']['TABLES'];
?>

<form method="post" action="">
<fieldset>
	<legend>Sélection des tables à exporter</legend>
	<ul class="form_list">
		<?php for ( $i=0, $iMax=count($aTablesDesc) ; $i<$iMax ; $i++ ): ?>
		<li>
			<label for="id_selected_table-<?php echo $aTablesDesc[$i]['NAME'] ?>"><?php echo $aTablesDesc[$i]['NAME'] ?></label>
			<span class="field">
				<input type="checkbox" id="id_selected_table-<?php echo $aTablesDesc[$i]['NAME'] ?>" name="selected_table-<?php echo $aTablesDesc[$i]['NAME'] ?>" value="1" checked="checked" />
			</span>
		</li>
		<?php endfor; ?>
		<li>
			<label>&nbsp;</label>
			<span class="field">
				<input type="submit" value="Valider" name="validerTables" />
			</span>
		</li>
	</ul>
</fieldset>
</form>
<?php
	}
	
	public function showMySqlPDOAutoJoin ()
	{
		if ( isset($_POST['valideAutoJoins']) ) {
			$aJoins = array();
			if ( isset($_POST['field_from']) ) {
				foreach ( $_POST['field_from'] as $iKey => $sTableFrom ) {
					list($sTableFrom, $sFieldFrom) = explode('.', $_POST['field_from'][$iKey]);
					list($sTableTo, $sFieldTo) = explode('.', $_POST['field_to'][$iKey]);
					
					$aJoins[] = array(
						'TABLE_FROM'	=> $sTableFrom,
						'FIELD_FROM'	=> $sFieldFrom,
						'TABLE_TO'		=> $sTableTo,
						'FIELD_TO'		=> $sFieldTo,
					);
				}
			}
			
			$_SESSION['SQLCOMPOSER']['AUTO_JOINS'] = $aJoins;
			$_SESSION['SQLCOMPOSER']['INSTALL_STEP']++;
			return $this->showMySqlPDOCreateFiles();
		} else {
			$aSelectedTables = $_SESSION['SQLCOMPOSER']['SELECTED_TABLES'];
			$aTablesDesc = $_SESSION['SQLCOMPOSER']['TABLES'];
			
			$aTablesSelectedDesc = array();
			for ( $i=0, $iMax=count($aSelectedTables) ; $i<$iMax ; $i++ ) {
				$sSelectedTable = $aSelectedTables[$i];
				
				for ( $j=0, $jMax=count($aTablesDesc) ; $j<$jMax ; $j++ ) {
					if ( strtolower($sSelectedTable)==strtolower($aTablesDesc[$j]['NAME']) ) {
						$aTablesSelectedDesc[ $sSelectedTable ] = $aTablesDesc[$j];
						break;
					}
				}
			}
			
			$aDefaultAutoJoins = array();
			foreach ( $aTablesSelectedDesc as $sTable1 => $aTDesc1 ) {
				foreach ( $aTablesSelectedDesc as $sTable2 => $aTDesc2 ) {
					if ( $aTDesc1['NAME']==$aTDesc2['NAME'] ) {
						continue;
					}
					
					// Fields :
					foreach ( $aTDesc1['FIELDS'] as $iField => $aField1 ) {
						foreach ( $aTDesc2['FIELDS'] as $iField => $aField2 ) {
							if ( $aField1['NAME']==$aField2['NAME'] ) {
								$aDefaultAutoJoins[] = array(
									'TABLE_FROM'	=> $sTable1,
									'TABLE_TO'		=> $sTable2,
									'FIELD_FROM'	=> $aField1['NAME'],
									'FIELD_TO'		=> $aField2['NAME'],
								);
							}
						}
					}
				}
			}
		
			$iNbLines = count($aDefaultAutoJoins);
			$this->sScript .= <<<SCRIPT_TEXT
var aLinksDel = document.getElementById('auto_joins_list').getElementsByTagName('a');
for ( var i=0, iMax=aLinksDel.length ; i<iMax ; i++ ) {
	var oLink = aLinksDel[i];
	if ( oLink.class=='no_auto-join' || oLink.className=='no_auto-join' ) {
		oLink.onclick = function() {
			document.getElementById('auto_joins_list').removeChild( this.parentNode.parentNode );
			return false;
		}
	}
}

var iNbLines = $iNbLines;
var oButtonAdd = document.getElementById('auto_join-add').onclick = function() {
	iNbLines++;
	
	var oTR		= document.createElement('tr');
	var oTD1	= document.createElement('td');
	var oTD2	= document.createElement('td');
	var oTD3	= document.createElement('td');
	
	oTD1.innerHTML = document.getElementById('field_from').value;
	oTD2.innerHTML = document.getElementById('field_to').value;
	oTD3.innerHTML = '<a href="#" class="no_auto-join" title="Supprimer">X</a>'+
					'<input type="hidden" name="field_from['+ iNbLines +']" value="'+ document.getElementById('field_from').value +'" />'+
					'<input type="hidden" name="field_to['+ iNbLines +']" value="'+ document.getElementById('field_to').value +'" />';
	
	oTR.appendChild( oTD1 );
	oTR.appendChild( oTD2 );
	oTR.appendChild( oTD3 );
	
	oTR.getElementsByTagName('a')[0].onclick = function() {
		document.getElementById('auto_joins_list').removeChild( this.parentNode.parentNode );
		return false;
	}
	
	document.getElementById('auto_joins_list').appendChild(oTR);
}
SCRIPT_TEXT;
?>
<form method="post" action="">
<fieldset>
	<legend>Création des jointures automatiques</legend>
	<table class="autojoin_table" cellpadding="0" cellspacing="0">
		<tbody id="auto_joins_list">
			<?php for ( $i=0, $iMax=count($aDefaultAutoJoins) ; $i<$iMax ; $i++ ): ?>
			<tr>
				<td>
					<?php echo $aDefaultAutoJoins[$i]['TABLE_FROM'] ?>.<?php echo $aDefaultAutoJoins[$i]['FIELD_FROM'] ?>
				</td>
				<td>
					<?php echo $aDefaultAutoJoins[$i]['TABLE_TO'] ?>.<?php echo $aDefaultAutoJoins[$i]['FIELD_TO'] ?>
				</td>
				<td>
					<a href="#" class="no_auto-join" title="Supprimer">X</a>
					<input type="hidden" name="field_from[<?php echo $i ?>]" value="<?php echo $aDefaultAutoJoins[$i]['TABLE_FROM'] .'.'. $aDefaultAutoJoins[$i]['FIELD_FROM'] ?>" />
					<input type="hidden" name="field_to[<?php echo $i ?>]" value="<?php echo $aDefaultAutoJoins[$i]['TABLE_TO'] .'.'. $aDefaultAutoJoins[$i]['FIELD_TO'] ?>" />
				</td>
			</tr>
			<?php endfor; ?>
			
		</tbody>
			
		<tfoot>
			<tr>
				<td>
					<select id="field_from">
					<?php foreach ( $aTablesSelectedDesc as $sTableName => $aTable ): ?>
						<optgroup label="<?php echo $sTableName ?>">
							<?php for ( $i=0, $iMax=count($aTable['FIELDS']) ; $i<$iMax ; $i++ ): ?>
							<option value="<?php echo $sTableName .'.'. $aTable['FIELDS'][$i]['NAME'] ?>"><?php echo $aTable['FIELDS'][$i]['NAME']; ?></option>
						<?php endfor; ?>
						</optgroup>
					<?php endforeach; ?>
					</select>
				</td>
				<td>
					<select id="field_to">
					<?php foreach ( $aTablesSelectedDesc as $sTableName => $aTable ): ?>
						<optgroup label="<?php echo $sTableName ?>">
							<?php for ( $i=0, $iMax=count($aTable['FIELDS']) ; $i<$iMax ; $i++ ): ?>
							<option value="<?php echo $sTableName .'.'. $aTable['FIELDS'][$i]['NAME'] ?>"><?php echo $aTable['FIELDS'][$i]['NAME']; ?></option>
						<?php endfor; ?>
						</optgroup>
					<?php endforeach; ?>
					</select>
				</td>
				<td>
					<input type="button" value="Créer" class="create_auto-join" id="auto_join-add" />
				</td>
			</tr>
			
			<tr>
				<td align="right" colspan="3">
					<input type="submit" value="Valider" name="valideAutoJoins" />
				</td>
			</tr>
		</tfoot>
	</table>
</fieldset>
</form>
<?php
		}
	}

	public function showMySqlPDOCreateFiles ()
	{
		$aTables	= $_SESSION['SQLCOMPOSER']['TABLES'];
		$aAutoJoins	= $_SESSION['SQLCOMPOSER']['AUTO_JOINS'];
		
		$sBaseDir	= __DIR__ . DIRECTORY_SEPARATOR . 'base' . DIRECTORY_SEPARATOR;
		$sIncDir	= __DIR__ . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
		
		$sBaseTablePath			= $sBaseDir .'tables' . DIRECTORY_SEPARATOR;
		$sBaseCollectionPath	= $sBaseDir .'collections' . DIRECTORY_SEPARATOR;
		$sBaseRecordPath		= $sBaseDir .'records' . DIRECTORY_SEPARATOR;
		
		$sTablePath				= $sIncDir .'tables'. DIRECTORY_SEPARATOR;
		$sCollectionPath		= $sIncDir .'collections'. DIRECTORY_SEPARATOR;
		$sRecordPath			= $sIncDir .'records'. DIRECTORY_SEPARATOR;
		
		if ( ! is_dir($sBaseDir) )				mkdir( $sBaseDir );
		if ( ! is_dir($sIncDir) )				mkdir( $sIncDir );
		if ( ! is_dir($sBaseTablePath) )		mkdir( $sBaseTablePath );
		if ( ! is_dir($sBaseCollectionPath) )	mkdir( $sBaseCollectionPath );
		if ( ! is_dir($sBaseRecordPath) )		mkdir( $sBaseRecordPath );
		if ( ! is_dir($sTablePath) )			mkdir( $sTablePath );
		if ( ! is_dir($sCollectionPath) )		mkdir( $sCollectionPath );
		if ( ! is_dir($sRecordPath) )			mkdir( $sRecordPath );
		
		$aTablesList = array();
		for ( $i=0, $iMax=count($aTables) ; $i<$iMax ; $i++ ) {
			$aTable = $aTables[$i];
			
			$sTableName	= $aTable['NAME'];
			$aFields	= $aTable['FIELDS'];
			
			$aTablesList[] = $sTableName;
			
			$aFieldsList = array();
			$aFieldsKeys = array();
			for ( $j=0, $jMax=count($aFields) ; $j<$jMax ; $j++ ) {
				$aField = $aFields[$j];
				
				$aFieldsList[] = $aField['NAME'];
				if ( $aField['KEY'] == 'PRI' ) {
					$aFieldsKeys[] = $aField['NAME'];
				}
			}
			
			$aTableJoins = array();
			for ( $j=0, $jMax=count($aAutoJoins) ; $j<$jMax ; $j++ ) {
				$aAutoJoin = $aAutoJoins[$j];
				if ( strtolower($aAutoJoin['TABLE_FROM']) == strtolower($sTableName) ) {
					$aTableJoins[] = $aAutoJoin;
				}
			}
			
			////////////////////////////////////////////////////////////////////////////////////////
			// Table Base :
			$sFile = '<?php
class '. $sTableName .'Base extends SQLComposerTable
{
	protected static $oConnexion = null;
	
	public static function getCollectionClassName ()
	{
		return \''. $sTableName .'Collection\';
	}
	public static function getRecordClassName ()
	{
		return \''. $sTableName .'Record\';
	}
	
	public static function getTableName ()
	{
		return \''. $sTableName .'\';
	}
	
	public static function getFields ()
	{
		return '. var_export($aFieldsList, true) .';
	}
	
	public static function getKeys ()
	{
		return '. var_export($aFieldsKeys, true) .';
	}
	
	public static function getAutoJoins ()
	{
		return '. var_export($aTableJoins, true) .';
	}
	
	public static function setConnexion ($oConnexion)
	{
		self::$oConnexion = $oConnexion;
	}
	
	public static function getConnexion ()
	{
		return self::$oConnexion;
	}
	
	public static function select ()
	{
		$aParams = func_get_args();
		return new ComposerSelectMySqlPDO(__CLASS__, $aParams, self::getConnexion());
	}
	
	public static function insert ()
	{
		$aParams = func_get_args();
		return new ComposerInsertMySqlPDO(__CLASS__, $aParams, self::getConnexion());
	}
	
	public static function update ()
	{
		$aParams = func_get_args();
		return new ComposerUpdateMySqlPDO(__CLASS__, $aParams, self::getConnexion());
	}
	
	public static function delete ()
	{
		$aParams = func_get_args();
		return new ComposerDeleteMySqlPDO(__CLASS__, $aParams, self::getConnexion());
	}
}
';
			$rFile = fopen($sBaseTablePath . $sTableName .'_table_base.php', 'w+');
			fwrite($rFile, $sFile);
			fclose($rFile);
			
			
			////////////////////////////////////////////////////////////////////////////////////////
			// Collection Base :
			$sFile = '<?php
class '. $sTableName .'CollectionBase extends SQLComposerCollection
{
	public static function getTableClassName ()
	{
		return \''. $sTableName .'\';
	}
	public static function getRecordClassName ()
	{
		return \''. $sTableName .'Record\';
	}
}
';
			$rFile = fopen($sBaseCollectionPath . $sTableName .'_collection_base.php', 'w+');
			fwrite($rFile, $sFile);
			fclose($rFile);
			
			
			////////////////////////////////////////////////////////////////////////////////////////
			// Record Base :
			$sFile = '<?php
class '. $sTableName .'RecordBase extends SQLComposerRecord
{
	public static function getTableClassName ()
	{
		return \''. $sTableName .'\';
	}
	public static function getCollectionClassName ()
	{
		return \''. $sTableName .'Collection\';
	}
}
';
			$rFile = fopen($sBaseRecordPath . $sTableName .'_record_base.php', 'w+');
			fwrite($rFile, $sFile);
			fclose($rFile);
			
			
			////////////////////////////////////////////////////////////////////////////////////////
			// Table :
			$sFile = '<?php
class '. $sTableName .' extends '. $sTableName .'Base
{
}
';
			// Ne pas reecrire ces fichiers si ils existent :
			if ( !file_exists($sTablePath . $sTableName .'_table.php') ) {
				$rFile = fopen($sTablePath . $sTableName .'_table.php', 'w+');
				fwrite($rFile, $sFile);
				fclose($rFile);
			}
			
			////////////////////////////////////////////////////////////////////////////////////////
			// Collection :
			$sFile = '<?php
class '. $sTableName .'Collection extends '. $sTableName .'CollectionBase
{
}
';
			// Ne pas reecrire ces fichiers si ils existent :
			if ( !file_exists($sCollectionPath . $sTableName .'_table.php') ) {
				$rFile = fopen($sCollectionPath . $sTableName .'_collection.php', 'w+');
				fwrite($rFile, $sFile);
				fclose($rFile);
			}
			
			
			////////////////////////////////////////////////////////////////////////////////////////
			// Record :
			$sFile = '<?php
class '. $sTableName .'Record extends '. $sTableName .'RecordBase
{
}
';
			// Ne pas reecrire ces fichiers si ils existent :
			if ( !file_exists($sRecordPath . $sTableName .'_table.php') ) {
				$rFile = fopen($sRecordPath . $sTableName .'_records.php', 'w+');
				fwrite($rFile, $sFile);
				fclose($rFile);
			}
			
			echo $sTableName . ' : OK<br />';
		}
		
		echo '<hr />';
		$sFile = '<?php
/* System */
require_once(\'sqlcomposer.php\');
require_once(\'system/sqlcomposer_connexion.php\');
require_once(\'system/interface_composer_delete.php\');
require_once(\'system/interface_composer_insert.php\');
require_once(\'system/interface_composer_select.php\');
require_once(\'system/interface_composer_update.php\');

require_once(\'system/sql_composer_table.php\');
require_once(\'system/sql_composer_collection.php\');
require_once(\'system/sql_composer_record.php\');

/* SGBD */
require_once(\'mysql_pdo/composer_mysql_pdo.php\');
require_once(\'mysql_pdo/composer_mysql_pdo_connexion.php\');
require_once(\'mysql_pdo/composer_mysql_pdo_delete.php\');
require_once(\'mysql_pdo/composer_mysql_pdo_insert.php\');
require_once(\'mysql_pdo/composer_mysql_pdo_select.php\');
require_once(\'mysql_pdo/composer_mysql_pdo_update.php\');

$aTables = '. var_export($aTablesList, true) .';

$oConnexion = SQLComposer::initDB(array(
	\'type\'		=> \'mysql_pdo\',
	\'user\'		=> \''. $_SESSION['SQLCOMPOSER']['CONNEXION_DATAS']['db_user'] .'\',
	\'pass\'		=> \''. $_SESSION['SQLCOMPOSER']['CONNEXION_DATAS']['db_pass'] .'\',
	\'connexion\'	=> \''. $_SESSION['SQLCOMPOSER']['CONNEXION_DATAS']['db_chain'] .'\',
));

$sBaseDir	= __DIR__ . DIRECTORY_SEPARATOR . \'base\' . DIRECTORY_SEPARATOR;
$sIncPath	= dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . Environment::getDefault(\'INCLUDES_PATH\')
						. DIRECTORY_SEPARATOR .\'sqlcomposer\'. DIRECTORY_SEPARATOR;
		
for ( $i=0, $iMax=count($aTables) ; $i<$iMax ; $i++ ) {
	$sTable = $aTables[$i];
	
	/* Bases */
	require_once($sBaseDir . \'tables\'. DIRECTORY_SEPARATOR . $sTable .\'_table_base.php\');
	require_once($sBaseDir . \'collections\'. DIRECTORY_SEPARATOR . $sTable .\'_collection_base.php\');
	require_once($sBaseDir . \'records\'. DIRECTORY_SEPARATOR . $sTable .\'_record_base.php\');
	
	/* Files */
	require_once($sIncPath .\'tables\'. DIRECTORY_SEPARATOR . $sTable .\'_table.php\');
	require_once($sIncPath .\'collections\'. DIRECTORY_SEPARATOR . $sTable .\'_collection.php\');
	require_once($sIncPath .\'records\'. DIRECTORY_SEPARATOR . $sTable .\'_records.php\');
	
	/* Initialisation de la connexion par rapport a la table : */
	$sTableClass = $sTable .\'Base\';
	$sTableClass::setConnexion( $oConnexion );
}
';
		$rFile = fopen( __DIR__ .'/includes.php', 'w+');
		fwrite($rFile, $sFile);
		fclose($rFile);
		
		unset($_SESSION['SQLCOMPOSER']);
	}
}

new SQLComposerInstaller();
