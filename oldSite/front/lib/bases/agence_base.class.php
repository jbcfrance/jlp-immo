<?php
/*
Fichier genere par SQLComposer. Il est deconseille de le modifier (si vous devez faire une nouvelle
generation de ce fichier, toutes modification serait perdue). Faites plutot des extensions et
utilise la methode :
	SQLComposer::createExtendedObject

qui vous permettra de generer des fichiers de la classe que vous souhaitez.
*/
class AGENCE_BASE extends SQLComposer{
	// STATIC //
	private static $sTableName='agence';
	private static $aRealFields=array (
  0 => 'agence_id',
  1 => 'raisonSocialeAgence',
  2 => 'enseigneAgence',
  3 => 'adresseAgence',
  4 => 'codePostalAgence',
  5 => 'villeAgence',
  6 => 'paysAgence',
  7 => 'telephoneAgence',
  8 => 'faxAgence',
  9 => 'emailAgence',
  10 => 'siteWebAgence',
);
	private static $aFieldsTranslation=array ();
	private static $aFields=array (
  0 => 'agence_id',
  1 => 'raisonSocialeAgence',
  2 => 'enseigneAgence',
  3 => 'adresseAgence',
  4 => 'codePostalAgence',
  5 => 'villeAgence',
  6 => 'paysAgence',
  7 => 'telephoneAgence',
  8 => 'faxAgence',
  9 => 'emailAgence',
  10 => 'siteWebAgence',
);
	private static $aTypes=array (
  'raisonSocialeAgence' => 'TEXT',
  'enseigneAgence' => 'TEXT',
  'adresseAgence' => 'TEXT',
  'villeAgence' => 'TEXT',
  'paysAgence' => 'TEXT',
  'emailAgence' => 'TEXT',
  'siteWebAgence' => 'TEXT',
);
	private static $aTableKeys=array (
  0 => 'agence_id',
);
	private static $aTableJoin=array (
);
	
	public static function getTableName(){
		return self::$sTableName;
	}
	public static function setTableName($sTableName){
		SQLComposer::setTableClassName( __CLASS__, $sTableName );
		self::$sTableName = $sTableName;
	}
	public static function getFields(){
		array_walk(self::$aFields, create_function('&$v', '$v=strtoupper($v);'));
		return self::$aFields;
	}
	public static function getRealFields(){
		array_walk(self::$aRealFields, create_function('&$v', '$v=strtoupper($v);'));
		return self::$aRealFields;
	}
	public static function rewindFields(){
		self::$aFields=array (
  0 => 'agence_id',
  1 => 'raisonSocialeAgence',
  2 => 'enseigneAgence',
  3 => 'adresseAgence',
  4 => 'codePostalAgence',
  5 => 'villeAgence',
  6 => 'paysAgence',
  7 => 'telephoneAgence',
  8 => 'faxAgence',
  9 => 'emailAgence',
  10 => 'siteWebAgence',
);
	}
	public static function addField($sFieldName, $sFieldRealName=null){
		self::$aFields[]=$sFieldName;
		if(null!==$sFieldRealName){
			$aExplodedField = explode('.', $sFieldRealName);
			$sFieldRealName = end($aExplodedField);
			self::$aFieldsTranslation[strtoupper($sFieldName)]=strtoupper($sFieldRealName);
		}
	}
	public static function getJoin(){
		return self::$aTableJoin;
	}
	public static function Field($sField,$sAlias=null){
		$sPattern='@([[:alnum:]_]*)@';
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
				$sFunc='get'.$aKeys[$i];
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
				$sFunc='get'.$aFields[$i];
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
				$sFunc='get'.$aFields[$i];
				if((($mValue=$this->$sFunc())!==array())&&(!empty($aFields[$i]))){
					$aFieldsIn[]=$aFields[$i];
					$aValuesIn[]=$mValue;
				}
			}
			$oReq->Set($aFieldsIn)->Values($aValuesIn);
			$aKeys=self::$aTableKeys;
			for($i=0,$iMax=count($aKeys);$i<$iMax;$i++){
				$sFunc='get'.$aKeys[$i];
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
				$sFunc='get'.$aKeys[$i];
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
	
}
