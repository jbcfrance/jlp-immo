<?php
/*
Fichier genere par SQLComposer. Il est deconseille de le modifier (si vous devez faire une nouvelle
generation de ce fichier, toutes modification serait perdue). Faites plutot des extensions et
utilise la methode :
	SQLComposer::createExtendedObject

qui vous permettra de generer des fichiers de la classe que vous souhaitez.
*/
class ANNONCE_BASE extends SQLComposer{
	// STATIC //
	private static $sTableName='annonce';
	private static $aRealFields=array (
  0 => 'annonce_id',
  1 => 'annonce_agence_id',
  2 => 'annonce_negociateur_id',
  3 => 'status_annonce',
  4 => 'reference',
  5 => 'numMandat',
  6 => 'typeMandat',
  7 => 'categorieOffre',
  8 => 'typeBien',
  9 => 'categorie',
  10 => 'dateCreation',
  11 => 'dateModification',
  12 => 'dateDebutMandat',
  13 => 'dateEcheanceMandat',
  14 => 'dateDisponibiliteOuLiberation',
  15 => 'adresse',
  16 => 'codePostalPublic',
  17 => 'villePublique',
  18 => 'villeAAfficher',
  19 => 'pays',
  20 => 'quartier',
  21 => 'environnement',
  22 => 'proximite',
  23 => 'transports',
  24 => 'montant',
  25 => 'charges',
  26 => 'loyer',
  27 => 'depotGarantie',
  28 => 'fraisDivers',
  29 => 'loyerGarage',
  30 => 'ageTete',
  31 => 'typeRente',
  32 => 'taxeHabitation',
  33 => 'taxeFonciere',
  34 => 'fraisDeNotaireReduits',
  35 => 'pieces',
  36 => 'chambres',
  37 => 'sdb',
  38 => 'nbSallesDEau',
  39 => 'nbWC',
  40 => 'nbParking',
  41 => 'nbGarages',
  42 => 'niveaux',
  43 => 'nbEtages',
  44 => 'etage',
  45 => 'surface',
  46 => 'surfaceCarrezOuHabitable',
  47 => 'surfaceTerrain',
  48 => 'surfaceSejour',
  49 => 'surfaceTerrasse',
  50 => 'surfaceBalcon',
  51 => 'accesHandicape',
  52 => 'alarme',
  53 => 'ascenseur',
  54 => 'balcon',
  55 => 'bureau',
  56 => 'cave',
  57 => 'cellier',
  58 => 'dependances',
  59 => 'dressing',
  60 => 'gardien',
  61 => 'interphone',
  62 => 'lotissement',
  63 => 'meuble',
  64 => 'mitoyenne',
  65 => 'piscine',
  66 => 'terrasse',
  67 => 'anciennete',
  68 => 'anneeConstruction',
  69 => 'exposition',
  70 => 'typeChauffage',
  71 => 'natureChauffage',
  72 => 'modeChauffage',
  73 => 'typeCuisine',
  74 => 'coupDeCoeur',
  75 => 'texte',
  76 => 'textAnglais',
  77 => 'urlVisiteVirtuelle',
  78 => 'photoCoeur',
  79 => 'photoMedium',
  80 => 'listePhotoOrig',
  81 => 'photoThumb',
  82 => 'photoOrigMd5',
  83 => 'consommationenergie',
  84 => 'emissionges',
);
	private static $aFieldsTranslation=array ();
	private static $aFields=array (
  0 => 'annonce_id',
  1 => 'annonce_agence_id',
  2 => 'annonce_negociateur_id',
  3 => 'status_annonce',
  4 => 'reference',
  5 => 'numMandat',
  6 => 'typeMandat',
  7 => 'categorieOffre',
  8 => 'typeBien',
  9 => 'categorie',
  10 => 'dateCreation',
  11 => 'dateModification',
  12 => 'dateDebutMandat',
  13 => 'dateEcheanceMandat',
  14 => 'dateDisponibiliteOuLiberation',
  15 => 'adresse',
  16 => 'codePostalPublic',
  17 => 'villePublique',
  18 => 'villeAAfficher',
  19 => 'pays',
  20 => 'quartier',
  21 => 'environnement',
  22 => 'proximite',
  23 => 'transports',
  24 => 'montant',
  25 => 'charges',
  26 => 'loyer',
  27 => 'depotGarantie',
  28 => 'fraisDivers',
  29 => 'loyerGarage',
  30 => 'ageTete',
  31 => 'typeRente',
  32 => 'taxeHabitation',
  33 => 'taxeFonciere',
  34 => 'fraisDeNotaireReduits',
  35 => 'pieces',
  36 => 'chambres',
  37 => 'sdb',
  38 => 'nbSallesDEau',
  39 => 'nbWC',
  40 => 'nbParking',
  41 => 'nbGarages',
  42 => 'niveaux',
  43 => 'nbEtages',
  44 => 'etage',
  45 => 'surface',
  46 => 'surfaceCarrezOuHabitable',
  47 => 'surfaceTerrain',
  48 => 'surfaceSejour',
  49 => 'surfaceTerrasse',
  50 => 'surfaceBalcon',
  51 => 'accesHandicape',
  52 => 'alarme',
  53 => 'ascenseur',
  54 => 'balcon',
  55 => 'bureau',
  56 => 'cave',
  57 => 'cellier',
  58 => 'dependances',
  59 => 'dressing',
  60 => 'gardien',
  61 => 'interphone',
  62 => 'lotissement',
  63 => 'meuble',
  64 => 'mitoyenne',
  65 => 'piscine',
  66 => 'terrasse',
  67 => 'anciennete',
  68 => 'anneeConstruction',
  69 => 'exposition',
  70 => 'typeChauffage',
  71 => 'natureChauffage',
  72 => 'modeChauffage',
  73 => 'typeCuisine',
  74 => 'coupDeCoeur',
  75 => 'texte',
  76 => 'textAnglais',
  77 => 'urlVisiteVirtuelle',
  78 => 'photoCoeur',
  79 => 'photoMedium',
  80 => 'listePhotoOrig',
  81 => 'photoThumb',
  82 => 'photoOrigMd5',
  83 => 'consommationenergie',
  84 => 'emissionges',
);
	private static $aTypes=array (
  'reference' => 'TEXT',
  'categorie' => 'TEXT',
  'adresse' => 'TEXT',
  'villePublique' => 'TEXT',
  'villeAAfficher' => 'TEXT',
  'pays' => 'TEXT',
  'quartier' => 'TEXT',
  'environnement' => 'TEXT',
  'proximite' => 'TEXT',
  'transports' => 'TEXT',
  'typeRente' => 'TEXT',
  'anciennete' => 'TEXT',
  'typeChauffage' => 'TEXT',
  'natureChauffage' => 'TEXT',
  'modeChauffage' => 'TEXT',
  'typeCuisine' => 'TEXT',
  'coupDeCoeur' => 'TEXT',
  'urlVisiteVirtuelle' => 'TEXT',
  'photoCoeur' => 'TEXT',
  'photoMedium' => 'TEXT',
  'photoThumb' => 'TEXT',
  'photoOrigMd5' => 'TEXT',
);
	private static $aTableKeys=array (
  0 => 'annonce_id',
);
	private static $aTableJoin=array (
  'programme_annonce' => 
  array (
    'annonce_id' => 'annonce_id',
  ),
  'agence' => 
  array (
    'annonce_agence_id' => 'agence_id',
  ),
  'negociateur' => 
  array (
    'annonce_negociateur_id' => 'negociateur_id',
  ),
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
  0 => 'annonce_id',
  1 => 'annonce_agence_id',
  2 => 'annonce_negociateur_id',
  3 => 'status_annonce',
  4 => 'reference',
  5 => 'numMandat',
  6 => 'typeMandat',
  7 => 'categorieOffre',
  8 => 'typeBien',
  9 => 'categorie',
  10 => 'dateCreation',
  11 => 'dateModification',
  12 => 'dateDebutMandat',
  13 => 'dateEcheanceMandat',
  14 => 'dateDisponibiliteOuLiberation',
  15 => 'adresse',
  16 => 'codePostalPublic',
  17 => 'villePublique',
  18 => 'villeAAfficher',
  19 => 'pays',
  20 => 'quartier',
  21 => 'environnement',
  22 => 'proximite',
  23 => 'transports',
  24 => 'montant',
  25 => 'charges',
  26 => 'loyer',
  27 => 'depotGarantie',
  28 => 'fraisDivers',
  29 => 'loyerGarage',
  30 => 'ageTete',
  31 => 'typeRente',
  32 => 'taxeHabitation',
  33 => 'taxeFonciere',
  34 => 'fraisDeNotaireReduits',
  35 => 'pieces',
  36 => 'chambres',
  37 => 'sdb',
  38 => 'nbSallesDEau',
  39 => 'nbWC',
  40 => 'nbParking',
  41 => 'nbGarages',
  42 => 'niveaux',
  43 => 'nbEtages',
  44 => 'etage',
  45 => 'surface',
  46 => 'surfaceCarrezOuHabitable',
  47 => 'surfaceTerrain',
  48 => 'surfaceSejour',
  49 => 'surfaceTerrasse',
  50 => 'surfaceBalcon',
  51 => 'accesHandicape',
  52 => 'alarme',
  53 => 'ascenseur',
  54 => 'balcon',
  55 => 'bureau',
  56 => 'cave',
  57 => 'cellier',
  58 => 'dependances',
  59 => 'dressing',
  60 => 'gardien',
  61 => 'interphone',
  62 => 'lotissement',
  63 => 'meuble',
  64 => 'mitoyenne',
  65 => 'piscine',
  66 => 'terrasse',
  67 => 'anciennete',
  68 => 'anneeConstruction',
  69 => 'exposition',
  70 => 'typeChauffage',
  71 => 'natureChauffage',
  72 => 'modeChauffage',
  73 => 'typeCuisine',
  74 => 'coupDeCoeur',
  75 => 'texte',
  76 => 'textAnglais',
  77 => 'urlVisiteVirtuelle',
  78 => 'photoCoeur',
  79 => 'photoMedium',
  80 => 'listePhotoOrig',
  81 => 'photoThumb',
  82 => 'photoOrigMd5',
  83 => 'consommationenergie',
  84 => 'emissionges',
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
		
	// JOIN ON programme_annonce
	public function join_programme_annonce(){
		$aParams=func_get_args();
		$sJoinTable=programme_annonce::getTableName();
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
		
		$aSendsParams[]=$sThisTable.'.annonce_id';
		$aSendsParams[]=array(array(programme_annonce::Field($sJoinTable.'.annonce_id')));
		$aSendsParams[]=array('=');
		
		$this->autoJoin($aSendsParams);
		return $this;
	}
	
	// JOIN ON agence
	public function join_agence(){
		$aParams=func_get_args();
		$sJoinTable=agence::getTableName();
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
		
		$aSendsParams[]=$sThisTable.'.agence_id';
		$aSendsParams[]=array(array(agence::Field($sJoinTable.'.annonce_agence_id')));
		$aSendsParams[]=array('=');
		
		$this->autoJoin($aSendsParams);
		return $this;
	}
	
	// JOIN ON negociateur
	public function join_negociateur(){
		$aParams=func_get_args();
		$sJoinTable=negociateur::getTableName();
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
		
		$aSendsParams[]=$sThisTable.'.negociateur_id';
		$aSendsParams[]=array(array(negociateur::Field($sJoinTable.'.annonce_negociateur_id')));
		$aSendsParams[]=array('=');
		
		$this->autoJoin($aSendsParams);
		return $this;
	}

}
