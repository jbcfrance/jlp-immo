<?php
/*
AUTEUR		: Tavman (Romain SENECHAL)
CONTACT		: tavman@gmail.com
DATE		: 22/04/2008
DESCRIPTION	: Ce fichier permet d'initialiser les données dont la classe SQLComposer a besoin.
LICENCE		: http://creativecommons.org/licenses/by-nc-sa/2.0/fr/
*/

/*
TODO :


* faire des extends de la classe Fields :
	- gestion des dates
	- des sommes etc.
	- des trucs speciaux quoi

* Lorsqu'on fait une jointure sur plusieurs tables, si l'une des tables deja jointe a une jointure
automatique sur la nouvelle table, voir comment on pourrait faire cette jointure auto...

* Revoir la gestion des erreurs :

Liste des Exceptions :
1 : oubli d'un join devant ALIAS
2 : oubli d'un join devant ON
3 : oubli de la fermeture d'un BLOC avant e mettre un GROUP BY, un ORDER BY ou un LIMIT
4 : enchainement d'une ouverture PUIS d'une fermeture sans rien dedans....
5 :	fermeture d'un bloc sans ouverture avant
6 :	Enchainement de 2 operateurs (a voir... si on fait un truc 'LARGE', on peut dire que le dernier est prioritaire
7 : Erreur dans l'ordre des etapes
8 : Mauvaise etape appellee ( on dans group by, ca puduku )
9 : appel de ON avant ALIAS

*/
require_once('sql_sgbd_interface.php');

/**
 * Autor		: Romain SENECHAL
 * Date			: 22/04/2008
 *
 * Name			: class Field
 * Description	: Classe permettant de gerer les chamsp speciaux
*/
class Field {
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: private static $aClassesModified
	 * Description	: Liste associative des classes ayant des champs speciaux
	*/
	private static $aClassesModified		= array();
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: public static function getOccurence
	 * Description	: Retourne une occurence de Field
	 * @params		: - $sClassName		: Nom de la classe appellante
	 * @params		: - $sName=null		: Valeur du champs
	 * @params		: - $sAlias=null	: Alias du champs
	 * @return		: une occurence de Field
	*/
	public static function getOccurence ( $sClassName, $sName=null, $sAlias=null ) {
		$oField = new Field( $sName, $sAlias );
		self::$aClassesModified[ $sClassName ][] = $oField;
		return $oField;
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: public static function addFieldsToClasses
	 * Description	: Initialise les classes qui ont des champs suplementaires
	 * @return		: - void
	*/
	public static function addFieldsToClasses () {
		foreach ( self::$aClassesModified as $kClassesModified => $vClassesModified ) {
			foreach ( $vClassesModified as $vField ) {
				if ( $vField->getAlias() != null ) {
					call_user_func(array( $kClassesModified, 'addField'), $vField->getAlias(), $vField->getName() );
				} else {
					call_user_func(array( $kClassesModified, 'addField'), $vField->getName() );
				}
			}
		}
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: public static function rewindFields
	 * Description	: Re-initialise les classes ayant eu des champs suplementaires
	 * @return		: void
	*/
	public static function rewindFields () {
		foreach ( self::$aClassesModified as $kClassesModified => $vClassesModified ) {
			call_user_func(array( $kClassesModified, 'rewindFields') );
		}
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: private $sName
	 * Description	: Valeur du champ
	*/
	private $sName	= null;
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: private $sAlias
	 * Description	: Alias du champ
	*/
	private $sAlias	= null;
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: final private function __construct
	 * Description	: constructeur
	 * @params		: - $sName	: Valeur du champs
	 * @params		: - $sAlias	: Alias du champs
	*/
	private function __construct ( $sName=null, $sAlias=null ) {
		if ( $sName != null ) {
			$this->setName( $sName );
		}
		if ( $sAlias != null ) {
			$this->setAlias( $sAlias );
		}
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: public function setName
	 * Description	: Initialise la valeur du champs
	 * @params		: - $sName	: Valeur du champs
	 * @return		: void
	*/
	public function setName ( $sName ) {
		$this->sName = $sName;
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: public function getName
	 * Description	: Retourne la valeur du champs
	 * @return		: - Valeur du champs
	*/
	public function getName () {
		return $this->sName;
	}

	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: public function setAlias
	 * Description	: Initialise l'alias du champs
	 * @params		: - $sAlias	: Alias du champs
	 * @return		: - void
	*/
	public function setAlias ( $sAlias ) {
		$this->sAlias = $sAlias;
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: public function getAlias
	 * Description	: Retourne l'alias du champs
	 * @return		: - L'alias du champs
	*/
	public function getAlias () {
		return $this->sAlias;
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: public function getField
	 * Description	: Retourne la String a mettre dans la requete SQL
	 * @return		: - String a mettre dans la requete SQL
	*/
	public function getField () {
		if ( $this->sAlias != null ) {
			return $this->sName.' '.$this->sAlias;
		} else {
			return $this->sName;
		}
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: public function __toString
	 * @return		: - String
	*/
	public function __toString(){
		return $this->getField();
	}
}


/**
 * Autor		: Romain SENECHAL
 * Date			: 26/04/2008
 *
 * Name			: class FIELD_JOCKER
 * Description	: Classe permettant de creer un champ *, important pour
					les auto_increment et plein d'autres choses...
*/
class FIELD_JOCKER extends Field {
	public function __construct () {
		$this->setName('*');
	}
}

/**
 * Autor		: Romain SENECHAL
 * Date			: 26/04/2008
 *
 * Name			: class FIELD_NOW
 * Description	: Classe permettant de creer un champ NOW() dans une requete
*/
class FIELD_NOW extends Field {
	public function __construct () {
		$this->setName('NOW()');
	}
}

/**
 * Autor		: Romain SENECHAL
 * Date			: 26/04/2008
 *
 * Name			: class FIELD_NULL
 * Description	: Classe permettant de creer un champ NULL dans une requete
*/
class FIELD_NULL extends Field {
	public function __construct () {
		$this->setName('NULL');
	}
}

/**
 * Autor		: Romain SENECHAL
 * Date			: 26/04/2008
 *
 * Name			: class FIELD_COUNT
 * Description	: Classe permettant de creer un champ NULL dans une requete
*/
class FIELD_COUNT extends Field {
	public function __construct () {
		$this->setName('count(*)');
	}
}

/**
 * Autor		: Romain SENECHAL
 * Date			: 22/04/2008
 *
 * Name			: class BindVariable
 * Description	: Classe permettant de gerer les variable pour les bind
*/
class BindVariable extends Field {
}


abstract class SQLComposer {
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/01/2009
	 *
	 * Description	: Les differents types de retour de la methode exec() requete
	*/
	const	COMPLETE_OBJECT_MODE	= 1,
			SIMPLE_OBJECT_MODE		= 2,
			ASSOC_MODE				= 3,
			ARRAY_MODE				= 4;
			
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Description	: Les differents types de requete
	*/
	public static	$TYPE_REQ_SELECT	= 1,
					$TYPE_REQ_INSERT	= 2,
					$TYPE_REQ_UPDATE	= 3,
					$TYPE_REQ_DELETE	= 4,
					$TYPE_REQ_REPLACE	= 5,
					$TYPE_REQ_LOCK		= 7,
					$TYPE_REQ_UNLOCK	= 8,
					$TYPE_REQ_TRUNCATE	= 9,
					$TYPE_REQ_DROP		= 10,
					$TYPE_REQ_SHOW		= 11,
					$TYPE_REQ_ALTER		= 12,
					$TYPE_REQ_CREATE	= 13,
					$TYPE_REQ_RENAME	= 14;
	
	private static	$aBinds;
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: private static $bIsConnected
	 * Description	: true si la connexion a etee initialisee, sinon false
	*/
	private static $bIsConnected = false;
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: private static $aClassToCreate
	 * Description	: Liste des elements pouvant composer la requetes
	*/
	private static $aClassToCreate = array();
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: private static $aTableToCreate
	 * Description	: Liste des classes pouvant composer la requetes
	*/
	private static $aTableToCreate = array();
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: private static $oSGBDPasserelle
	 * Description	: Lien vers le SGBD
	*/
	private static $oSGBDPasserelle = array();
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: private static $aQueries
	 * Description	: Liste des requetes executees
	*/
	private static $aQueries = array();
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: final public static function connect
	 * Description	: Initialise la connexion
	 * @params		: - $sHost		: HOST
	 * @params		: - $sUserName	: USER
	 * @params		: - $sPassword	: PASSWORD
	 * @params		: - $sDBName	: DATABASE
	 * @return		: void
	*/
	final public static function connect ( $aParams ) {
		switch ( strtolower(SQL_COMPOSER_SGBD) ) {
			case 'mysql' : {
				require_once('db_definers/composer_mysql.php');
				self::$oSGBDPasserelle = new Composer_Mysql( $aParams );
				self::$bIsConnected = true;
			} break;
		}
	}
	
	// Gestion des classes etendues :
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: public static function createExtendedObject
	 * Description	: Gere les extension de classes
	 * @params		: - $sParentClass	: Nom de la classe parente
	 * @params		: - $sChildClass	: Nom de la classe fille
	 * @return		: void
	*/
	public static function createExtendedObject ( $sParentClass, $sChildClass ) {
		self::$aClassToCreate[ strtolower($sParentClass) ] = $sChildClass;
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: protected static function getObjectClassName
	 * Description	: Retourne le nom de l'extension d'une classe si elle en a une, sinon, retourne le nom de la classe
	 * @return		: - Nom de la classe de l'instance a creer
	*/
	public static function getObjectClassName ( $sTableName ) {
		if ( isset( self::$aClassToCreate[ strtolower($sTableName) ] ) ) {
			return self::$aClassToCreate[ strtolower($sTableName) ];
		} else {
			return $sTableName;
		}
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: protected static function setTableClassName
	 * Description	: Retourne le nom de l'extension d'une classe si elle en a une, sinon, retourne le nom de la classe
	 * @return		: - Nom de la classe de l'instance a creer
	*/
	public static function setTableClassName ( $sTableName, $sClassName ) {
		self::$aTableToCreate[ strtolower($sClassName) ] = $sTableName;
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: protected static function getTableClassName
	 * Description	: Retourne le nom de l'extension d'une classe si elle en a une, sinon, retourne le nom de la classe
	 * @return		: - Nom de la classe de l'instance a creer
	*/
	public static function getTableClassName ( $sClassName ) {
		if ( isset( self::$aTableToCreate[ strtolower($sClassName) ] ) ) {
			return self::$aTableToCreate[ strtolower($sClassName) ];
		} else {
			return $sClassName;
		}
	}
	
	/**
	 * Autor		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: public static function BindVar
	 * Description	: retourne un champs special pour les binds
	 * Params		: - $sVarName	: le nom de la variable
	 * Return		: une occurence de BindVariable
	*/
	public static function BindVar ( $sVarName ) {
		return BindVariable::getOccurence('SQLComposer', '%'.$sVarName.'%');
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: public static abstract function Field
	 * Description	: creer un champs special
	 * @params		: - $sField	: valeur du champs a creer
	 * @params		: - $sAlias	: alias du champs
	 * @return		: - une instance de la classe Field si possible, sinon false
	*/
	public static function getExecutedQueries () {
		return self::$aQueries;
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: public static abstract function Field
	 * Description	: creer un champs special
	 * @params		: - $sField	: valeur du champs a creer
	 * @params		: - $sAlias	: alias du champs
	 * @return		: - une instance de la classe Field si possible, sinon false
	*/
	public static abstract function Field ( $sField, $sAlias=null );
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: public static abstract function getTableName
	 * Description	: Retourne le nom de la table
	 * @return		: - Nom de la table
	*/
	public static abstract function getTableName ();
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: public static abstract function getFields
	 * Description	: Retourne la liste des champs de la table (y compris les champs speciaux si ils ont etes initialises)
	 * @return		: - Liste des champs de la table
	*/
	public static abstract function getFields ();
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: public static abstract function getJoin
	 * Description	: Retourne la liste des tables jointes et les champs de liaison
	 * @return		: Liste des tables jointes
	*/
	public static abstract function getJoin ();
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: public static abstract function getOccurence
	 * Description	: Retourne une occurence de la classe
	 * @return		: - Une occurence de la classe
	*/
	public static abstract function getOccurence ();
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: final public static function is_SQLComposer
	 * Description	: Defini si une variable est une instance de SQLComposer ou d'une classe fille (attention : la fonction is_subclass_of ne marche pas avec les classes abstraites)
	 * @params		: - $sClass	: Nom ou objet de la classe
	 * @return		: - true si $sClass est une instance etendue ou non de SQLComposer, sinon false 
	*/
	final public static function is_SQLComposer ( $sClass ) {
		if ( is_object($sClass) ) {
			$sClass = get_class($sClass);
		}
		
		if ( ! in_array($sClass, get_declared_classes() ) ) {
			return false;
		}
	    do {
			if( strtolower($sClass) === 'sqlcomposer' ) {
				return true;
			}
		} while( false != ($sClass = get_parent_class($sClass)) );
	    return false;
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: final protected static function is_Field
	 * Description	: Defini si une variable est une instance de Field ou d'une classe fille (attention : la fonction is_subclass_of ne marche pas avec les classes abstraites)
	 * @params		: - $sClass	: Nom ou objet de la classe
	 * @return		: - true si $sClass est une instance etendue ou non de Field, sinon false 
	*/               
	final public static function is_Field ($sClass){
		if ( is_object($sClass) ) {
			$sClass = get_class($sClass);
		}
		if ( ! in_array($sClass, get_declared_classes() ) ) {
			return false;
		}
		
	    do {
			if( strtolower($sClass) === 'field' ) {
				return true;
			}
		} while( false != ($sClass = get_parent_class($sClass)) );
	    return false;
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: final protected static function isOperator
	 * Description	: Verifie si une variable est un operateur pour une requete SQL ou non
	 * @params		: - La variable a tester
	 * @return		: - true si la variable est un operateur, sinon false
	*/
	final protected static function isOperator ( $mTryStr ) {
		return self::$oSGBDPasserelle->isOperator( $mTryStr );
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: final protected static function isArrayOperator
	 * Description	: Verifie si une variable est une liste d'operateur pour une requete SQL ou non
	 * @params		: - La variable a tester
	 * @return		: - true si la variable est une liste d'operateur, sinon false
	*/
	final protected static function isArrayOperator ( $mTryArray ) {
		if ( is_array($mTryArray) ) {
			foreach( $mTryArray as $kTryArray => $vTryArray ) {
				if ( ! self::isOperator( $vTryArray ) ) {
					return false;
				}
			}
			return true;
		}
		return false;
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: final protected static function isLiaison
	 * Description	: Verifie si une variable est un operateur de liaison pour une requete SQL ou non
	 * @params		: - La variable a tester
	 * @return		: - true si la variable est un operateur de liaison, sinon false
	*/
	final protected static function isLiaison ( $mTryStr ) {
		return self::$oSGBDPasserelle->isLiaison( $mTryStr );
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: final protected static function isJointure
	 * Description	: Verifie si une variable est un operateur de jointure pour une requete SQL ou non
	 * @params		: - La variable a tester
	 * @return		: - true si la variable est un operateur de jointure, sinon false
	*/
	final protected static function isJointure ( $sTryStr ) {
		return self::$oSGBDPasserelle->isJointure( $sTryStr );
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: final protected static function getArrayDepth
	 * Description	: Calcul la profondeur d'une liste
	 * @params		: - la liste dont on veut connaitre la profondeur
	 * @return		: - la profondeur la plus grande de la liste
	*/
	final protected static function getArrayDepth ( $mTryArray ) {
		if ( !is_array($mTryArray) ) {
			return 0;
		} else {
			$iMaxDepth = 0;
			foreach ( $mTryArray as $kTryArray => $vTryArray ) {
				$iDepth = self::getArrayDepth( $vTryArray );
				if ( $iDepth > $iMaxDepth ) {
					$iMaxDepth = $iDepth;
				}
			}
			return $iMaxDepth+1;
		}
	}
	
	
####################################################################################################
	
	##### METHODES DE CREATION DE REQUETE #####
	// Distinct
	final public function Distinct () {
		if ( $this->iRequestStep>1 ) {
			throw new Exception('7');
		}
		
		$aParams = func_get_args();
		$this->addRequestElement('DISTINCT', $aParams);
		
		return $this;
	}
	// Jointures
	final public function Join () {
		if ( $this->iRequestStep>2 ) {
			throw new Exception('7');
		}
		
		if ( $this->bAutoJoinIsOpen ) {
			$this->bAutoJoinIsOpen = false;
			if ( count($this->aAutoJoinParams) == 1 ) {
				$this->On($this->aAutoJoinParams[0]);
			} else if ( count($this->aAutoJoinParams) == 2 ) {
				$this->On($this->aAutoJoinParams[0], $this->aAutoJoinParams[1]);
			} else if ( count($this->aAutoJoinParams) == 3 ) {
				$this->On($this->aAutoJoinParams[0], $this->aAutoJoinParams[1], $this->aAutoJoinParams[2]);
			}
		}
		
		$this->iRequestStep = 2;
		$this->bJoinIsOpen = true;
		$this->bOnIsOpen = false;
		$aParams = func_get_args();
		$this->addRequestElement('JOIN', $aParams);
		return $this;
	}
	final public function Alias () {
		if ( $this->iRequestStep>2 ) {
			throw new Exception('8');
		}
		if ( $this->bOnIsOpen ) {
			throw new Exception('9');
		}
		
		$aParams = func_get_args();
		$this->addRequestElement('ALIAS', $aParams);
		
		return $this;
	}
	final public function On () {
		if ( ! $this->bJoinIsOpen ) {
			throw new Exception('2');
		}
		if ( $this->iRequestStep!=2 ) {
			throw new Exception('8');
		}
		
		if ( $this->bAutoJoinIsOpen ) {
			$this->bAutoJoinIsOpen = false;
		}
		
		$this->bOnIsOpen = true;
		$aParams = func_get_args();
		$this->addRequestElement('ON', $aParams);
		return $this;
	}
	final protected function autoJoin () {
		if ( $this->iRequestStep>2 ) {
			throw new Exception('7');
		}
		
		if ( $this->bAutoJoinIsOpen ) {
			$this->bAutoJoinIsOpen = false;
			if ( count($this->aAutoJoinParams) == 1 ) {
				$this->On($this->aAutoJoinParams[0]);
			} else if ( count($this->aAutoJoinParams) == 2 ) {
				$this->On($this->aAutoJoinParams[0], $this->aAutoJoinParams[1]);
			} else if ( count($this->aAutoJoinParams) == 3 ) {
				$this->On($this->aAutoJoinParams[0], $this->aAutoJoinParams[1], $this->aAutoJoinParams[2]);
			}
		}
		
		
		$this->iRequestStep = 2;
		$this->bJoinIsOpen = true;
		$this->bOnIsOpen = false;
		$aParams = func_get_args();
		
		$this->addRequestElement('JOIN', array($aParams[0][0], $aParams[0][1]==null?'LEFT':$aParams[0][1]) );
		$this->bAutoJoinIsOpen = true;
		$this->aAutoJoinParams = array();
		
		if ( self::isJointure( $aParams[0][1] ) ) {
			switch( count($aParams[0]) ) {
				case 3 :	$this->aAutoJoinParams[] = $aParams[0][2];	break;
				case 4 :	$this->aAutoJoinParams[] = $aParams[0][2];
							$this->aAutoJoinParams[] = $aParams[0][3];	break;
				case 5 :	$this->aAutoJoinParams[] = $aParams[0][2];
							$this->aAutoJoinParams[] = $aParams[0][3];
							$this->aAutoJoinParams[] = $aParams[0][4];	break;
			}
		} else {
			switch( count($aParams[0]) ) {
				case 2 :	$this->aAutoJoinParams[] = $aParams[0][1];	break;
				case 3 :	$this->aAutoJoinParams[] = $aParams[0][1];
							$this->aAutoJoinParams[] = $aParams[0][2];	break;
				case 4 :	$this->aAutoJoinParams[] = $aParams[0][1];
							$this->aAutoJoinParams[] = $aParams[0][2];
							$this->aAutoJoinParams[] = $aParams[0][3];	break;
			}
		}
		
		return $this;
	}
	// Where :
	final public function Where () {
		if ( $this->iRequestStep>3 ) {
			throw new Exception('7');
		}
		
		if ( $this->bAutoJoinIsOpen ) {
			$this->bAutoJoinIsOpen = false;
			if ( count($this->aAutoJoinParams) == 1 ) {
				$this->On($this->aAutoJoinParams[0]);
			} else if ( count($this->aAutoJoinParams) == 2 ) {
				$this->On($this->aAutoJoinParams[0], $this->aAutoJoinParams[1]);
			} else if ( count($this->aAutoJoinParams) == 3 ) {
				$this->On($this->aAutoJoinParams[0], $this->aAutoJoinParams[1], $this->aAutoJoinParams[2]);
			}
		}
		$this->iRequestStep = 3;
		
		$aParams = func_get_args();
		$this->addRequestElement('WHERE', $aParams);
		return $this;
	}
	// Group by :
	final public function GroupBy () {
		if ( $this->iRequestStep>4 ) {
			throw new Exception('7');
		}
		
		if ( $this->bAutoJoinIsOpen ) {
			$this->bAutoJoinIsOpen = false;
			if ( count($this->aAutoJoinParams) == 1 ) {
				$this->On($this->aAutoJoinParams[0]);
			} else if ( count($this->aAutoJoinParams) == 2 ) {
				$this->On($this->aAutoJoinParams[0], $this->aAutoJoinParams[1]);
			} else if ( count($this->aAutoJoinParams) == 3 ) {
				$this->On($this->aAutoJoinParams[0], $this->aAutoJoinParams[1], $this->aAutoJoinParams[2]);
			}
		}
		$this->iRequestStep = 4;
		
		$aParams = func_get_args();
		$this->addRequestElement('GROUPBY', $aParams);
		return $this;
	}
	// Order by :
	final public function OrderBy () {
		if ( $this->iRequestStep>5 ) {
			throw new Exception('7');
		}
		
		if ( $this->bAutoJoinIsOpen ) {
			$this->bAutoJoinIsOpen = false;
			if ( count($this->aAutoJoinParams) == 1 ) {
				$this->On($this->aAutoJoinParams[0]);
			} else if ( count($this->aAutoJoinParams) == 2 ) {
				$this->On($this->aAutoJoinParams[0], $this->aAutoJoinParams[1]);
			} else if ( count($this->aAutoJoinParams) == 3 ) {
				$this->On($this->aAutoJoinParams[0], $this->aAutoJoinParams[1], $this->aAutoJoinParams[2]);
			}
		}
		$this->iRequestStep = 5;
		
		$aParams = func_get_args();
		$this->addRequestElement('ORDERBY', $aParams);
		return $this;
	}
	// Limit :
	final public function Limit () {
		if ( $this->iRequestStep>6 ) {
			throw new Exception('7');
		}
		
		if ( $this->bAutoJoinIsOpen ) {
			$this->bAutoJoinIsOpen = false;
			if ( count($this->aAutoJoinParams) == 1 ) {
				$this->On($this->aAutoJoinParams[0]);
			} else if ( count($this->aAutoJoinParams) == 2 ) {
				$this->On($this->aAutoJoinParams[0], $this->aAutoJoinParams[1]);
			} else if ( count($this->aAutoJoinParams) == 3 ) {
				$this->On($this->aAutoJoinParams[0], $this->aAutoJoinParams[1], $this->aAutoJoinParams[2]);
			}
		}
		$this->iRequestStep = 6;
		
		$aParams = func_get_args();
		$this->addRequestElement('LIMIT', $aParams);
		return $this;
	}
	// SET
	final public function Set () {
		$aParams = func_get_args();
		$this->addRequestElement('SET', $aParams);
		return $this;
	}
	final public function Values () {
		$aParams = func_get_args();
		$this->addRequestElement('VALUES', $aParams);
		return $this;
	}
	// OPERATEURS :
	final public function _AND_ () {
		if ( ( $this->iRequestStep!=2 ) && ( $this->iRequestStep!=3 ) ) {
			throw new Exception('8');
		}
		
		$aParams = func_get_args();
		$this->addRequestElement('AND', $aParams);
		return $this;
	}
	final public function _OR_ () {
		if ( ( $this->iRequestStep!=2 ) && ( $this->iRequestStep!=3 ) ) {
			throw new Exception('8');
		}
		
		$aParams = func_get_args();
		$this->addRequestElement('OR', $aParams);
		return $this;
	}
	final public function _XOR_ () {
		if ( ( $this->iRequestStep!=2 ) && ( $this->iRequestStep!=3 ) ) {
			throw new Exception('8');
		}
		
		$aParams = func_get_args();
		$this->addRequestElement('XOR', $aParams);
		return $this;
	}
	
	// RESULTAT DE LA REQUETE :
	
	/**
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: final public function Exec
	 * Description	: Execute une requete pre-composee
	 * @params		: - $iCreateMode	: mode de retour de la requete si c'est un SELECT
	 * @return		: - une liste d'objet correspondant a la premiere classe appelante, sinon void
	 *
	 * NOTE			: Si $iCreateMode est une string, alors on execute la requete puis on retourne une liste correspondant a mysql_fetch_assoc
	*/
	final public function Exec ( $iCreateMode=1 ) {
		if ( $this->bAutoJoinIsOpen ) {
			$this->bAutoJoinIsOpen = false;
			if ( count($this->aAutoJoinParams) == 1 ) {
				$this->On($this->aAutoJoinParams[0]);
			} else if ( count($this->aAutoJoinParams) == 2 ) {
				$this->On($this->aAutoJoinParams[0], $this->aAutoJoinParams[1]);
			} else if ( count($this->aAutoJoinParams) == 3 ) {
				$this->On($this->aAutoJoinParams[0], $this->aAutoJoinParams[1], $this->aAutoJoinParams[2]);
			}
		}
		
		if ( is_string($iCreateMode) ) {
			$rRequest = self::$oSGBDPasserelle->query( $sSqlReq );
			$aReturn = array();
			while ( $aRow = self::$oSGBDPasserelle->getAssoc($rRequest) ) {
				$aReturn[] = $aRow;
			}
			self::$oSGBDPasserelle->free($rRequest);
			return $aReturn;
		} else {
			if ( $this->sSqlReq === null ) {
				$sSqlReq = $this->compose();
				$this->sSqlReq = $sSqlReq;
			} else {
				$sSqlReq = $this->sSqlReq;
			}
		}
		if ( ! self::$bIsConnected ) {
			return false;
		}
		
		// Sauvegarde de la requete :
		self::$aQueries[] = $sSqlReq;
		$rRequest = self::$oSGBDPasserelle->query( $sSqlReq );
		Field::rewindFields();
		
		if ( $this->getType() != self::$TYPE_REQ_SELECT ) {
			return $rRequest;
		}
		Field::addFieldsToClasses();
		
		## ALLER, CA COMMENCE ICI ##
		if ( $iCreateMode == self::COMPLETE_OBJECT_MODE ) {
			// Valeurs inserees :
			$aObjectsValues = array();
			
			if ( $rRequest && $aRow = self::$oSGBDPasserelle->getAssoc($rRequest) ) {
				$aRow = array_change_key_case( $aRow, CASE_UPPER);
				// Les champs selectionnes :
				$aFieldsSelected = array_keys($aRow);
				// les tables de la requete :
				$aTables = array_values(array_unique( $this->aTablesOpeneds ));
				// Remettre les bonnes classes au tables :
				
				for ( $i=0, $iMax=count($aTables) ; $i<$iMax ; $i++ ) {
					//	$aTables[$i]		= array();
					$aTables[$i] = self::getTableClassName($aTables[$i]);
				}
				
				// Les cles primaires des tables de la requete :
				$aKeysByTable = array();
				for ( $i=0, $iMax=count($aTables) ; $i<$iMax ; $i++ ) {
					$aObjectsValues[ $aTables[$i] ] = array();
				}
				
				// on fait la boucle (on a deja un aRow dans le if juste au dessus) :
				do {
					$aRow = array_change_key_case( $aRow, CASE_UPPER);
					$aObjectsOfRow = array();
					for ( $i=0, $iMax=count($aTables) ; $i<$iMax ; $i++ ) {
						$oObj = call_user_func(array( $aTables[$i], 'getOccurence'), $aRow);
						// Substitution :
						if ( isset($aObjectsValues[ $aTables[$i] ][ serialize($oObj->values) ]) ) {
							$oObj = $aObjectsValues[ $aTables[$i] ][ serialize($oObj->values) ];
						} else {
							$aObjectsValues[ $aTables[$i] ][ serialize($oObj->values) ]	= $oObj;
						}
						$aObjectsOfRow[ $aTables[$i] ]	= $oObj;
					}
					
					
					for ( $i=0, $iMax=count($aTables) ; $i<$iMax ; $i++ ) {
						for ( $j=0, $jMax=count($aTables) ; $j<$jMax ; $j++ ) {
							if ( $aTables[$i] != $aTables[$j] ) {
								// Evite d'avoir des doublons pour des requetes sur 3 tables ou plus...
								// Et meme avec le nettoyage apres, ca coute pas trop cher en temps...
								$aObjectsOfRow[ $aTables[$i] ]->joins[ $aTables[$j] ][ serialize($aObjectsOfRow[ $aTables[$j] ]->values) ]	= $aObjectsOfRow[ $aTables[$j] ];
							}
						}
					}
				} while ( $aRow = & self::$oSGBDPasserelle->getAssoc($rRequest) );
				self::$oSGBDPasserelle->free($rRequest);
				
				// Nettoyage des jointures
				for ( $i=0, $iMax=count($aTables) ; $i<$iMax ; $i++ ) {
					foreach ($aObjectsValues[ $aTables[$i] ] as &$oValue ) {
						for ( $j=0, $jMax=count($aTables) ; $j<$jMax ; $j++ ) {
							if ( ($i!=$j) && (isset($oValue->joins[ $aTables[$j] ])) ) {
								$oValue->joins[ strtoupper($aTables[$j]) ] = array_values($oValue->joins[ $aTables[$j] ]);
								if($aTables[$j]!==strtoupper($aTables[$j])){
									unset($oValue->joins[ $aTables[$j] ]);
								}
							}
						}
					}
				}
				
				// on remet tout en ordre pour avoir des cles potables :
				return array_values($aObjectsValues[ self::getTableClassName( self::getObjectClassName( $this->getTableName() ) ) ] );
			}
			
			return array();
		} else
		if ( $iCreateMode == self::SIMPLE_OBJECT_MODE ) {
			$aTables = array_unique( $this->aTablesOpeneds );
			$aObjectsValues = array();
			$iCount = 0;
			
			while ( $aRow = & self::$oSGBDPasserelle->getAssoc($rRequest) ) {
				$aRow = array_change_key_case( $aRow, CASE_UPPER);
				
				for ( $i=0, $iMax=count($aTables) ; $i<$iMax ; $i++ ) {
					$aObjectsValues[ $aTables[$i] ][ $iCount ]	= call_user_func(array( self::getObjectClassName($aTables[$i]), 'getOccurence'), $aRow);
				}
					
				for ( $i=0, $iMax=count($aTables) ; $i<$iMax ; $i++ ) {
					for ( $j=0, $jMax=count($aTables) ; $j<$jMax ; $j++ ) {
						if ( $aTables[$i] != $aTables[$j] ) {
							$aObjectsValues[ $aTables[$i] ][ $iCount ]->joins[ $aTables[$j] ][0] = $aObjectsValues[ $aTables[$j] ][ $iCount ];
						}
					}
				}
				
				$iCount++;
			}
			self::$oSGBDPasserelle->free($rRequest);
			return $aObjectsValues[ $this->getTableName() ];
		} else
		if ( $iCreateMode == self::ASSOC_MODE ) {
			$rRequest = self::$oSGBDPasserelle->query( $sSqlReq );
			
			$aReturn = array();
			while ( $aRow = self::$oSGBDPasserelle->getAssoc($rRequest) ) {
				$aReturn[] = $aRow;
			}
			self::$oSGBDPasserelle->free($rRequest);
			return $aReturn;
		} else
		if ( $iCreateMode == self::ARRAY_MODE ) {
			$rRequest = self::$oSGBDPasserelle->query( $sSqlReq );
			
			$aReturn = array();
			while ( $aRow = self::$oSGBDPasserelle->getArray($rRequest) ) {
				$aReturn[] = $aRow;
			}
			self::$oSGBDPasserelle->free($rRequest);
			return $aReturn;
		}
		
		return array();
	}
	
	/**
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: final public function Get
	 * Description	: Compose la requete et la retourne
	 * @return		: - String : la requete
	*/
	final public function Get () {
		if ( $this->bAutoJoinIsOpen ) {
			$this->bAutoJoinIsOpen = false;
			if ( count($this->aAutoJoinParams) == 1 ) {
				$this->On($this->aAutoJoinParams[0]);
			} else if ( count($this->aAutoJoinParams) == 2 ) {
				$this->On($this->aAutoJoinParams[0], $this->aAutoJoinParams[1]);
			} else if ( count($this->aAutoJoinParams) == 3 ) {
				$this->On($this->aAutoJoinParams[0], $this->aAutoJoinParams[1], $this->aAutoJoinParams[2]);
			}
		}
		
		if ( $this->sSqlReq === null ) {
			$sSqlReq = $this->compose();
			$this->sSqlReq = $sSqlReq;
		} else {
			$sSqlReq = $this->sSqlReq;
		}
		
		return $sSqlReq;
	}
	
	/**
	 * Auteur		: Romain SENECHAL
	 * Date			: 07/11/2008
	 *
	 * Name			: final public function getAssoc
	 * Description	: Execute une requete et retourne un resultat selon Key et Value passe en parametre
	 * @return		: - Array
	*/
	final public function getAssoc ($sKey, $sValue) {
		foreach ( $this->aRequestElements as &$aParams ) {
			if (isset($aParams['TYPE']) && strtolower($aParams['TYPE']) == 'select') {
				$aParams['PARAMS'] = array($sKey, $sValue);
			}
		}
		
		$aResults	= $this->exec( self::ARRAY_MODE );
		$aReturn	= array();
		
		foreach ( $aResults as $oValues ) {
			$aReturn[ $oValues[0] ] = $oValues[1];
		}
		return $aReturn;
	}
	
	/**
	 * Auteur		: Romain SENECHAL
	 * Date			: 07/11/2008
	 *
	 * Name			: final public function getAssoc
	 * Description	: Execute une requete et retourne un resultat selon Key et Value passe en parametre
	 * @return		: - Array
	*/
	final public function getOne ( $iCreateMode=1 ) {
		$bSet = false;
		foreach ( $this->aRequestElements as &$aParams ) {
			if (isset($aParams['TYPE']) && strtolower($aParams['TYPE']) == 'limit') {
				$aParams['PARAMS'] = array(1);
				$bSet = true;
			}
		}
		
		if ( ! $bSet ) {
			$this->Limit(1);
		}
		
		$aResults = $this->exec( $iCreateMode );
		if ( count($aResults) ) {
			return $aResults[0];
		} else {
			return false;
		}
	}
	
	/**
	 * Autor		: Romain SENECHAL
	 * Date			: 07/11/2008
	 *
	 * Name			: final public function addBind
	 * Description	: Creation d'une requete preparee
	 * Params		: - $sBindName		: nom de la requete preparee
	 * Params		: - $oSqlComposer	: requete
	 * Return		: true si la requete a bien etee preparee, sinon false.
	*/
	public function getLastInsertID () {
		return self::$oSGBDPasserelle->getLastInsertID();
	}
	
	/**
	 * Autor		: Romain SENECHAL
	 * Date			: 07/11/2008
	 *
	 * Name			: final public function addBind
	 * Description	: Creation d'une requete preparee
	 * Params		: - $sBindName		: nom de la requete preparee
	 * Params		: - $oSqlComposer	: requete
	 * Return		: true si la requete a bien etee preparee, sinon false.
	*/
	final public function addBind ( $sBindName, $oSqlComposer ) {
		if ( ! is_string($sBindName) ) {
			return false;
		}
		if ( ! self::is_SQLComposer( $oSqlComposer ) ) {
			return false;
		}
		$sReq = $oSqlComposer->get();
		
		$sPattern	= '@%(.*)%@ismU';
		$aMatches	= array();
		preg_match_all($sPattern, $sReq, $aMatches);
		$aVars = array_unique($aMatches[1]);
		
		self::createBind($sBindName, array('REQ'=>$sReq, 'VARS'=>$aVars) );
	}
	
	/**
	 * Autor		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: final public function useBind
	 * Description	: permet d'utilise un bind
	 * Params		: - $sBindName	: nom du bind a utiliser
	 * Params		: - $aVars		: liste des valeurs a remplacer
	 * Return		: true si la requete a bien ete executee, sinon false
	*/
	final public function useBind ( $sBindName, $aVars ) {
		return self::$oSGBDPasserelle->useBind( $sBindName, $aVars );
	}
	
	/**
	 * Autor		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: private function createBind
	 * Description	: ajoutte un bind
	 * Params		: - $sBindName	: nom de la requete
	 * Params		: - $aParams	: parametres de la requete
	 * Return		: void
	*/
	private static function createBind ( $sBindName, $aParams ) {
		return self::$oSGBDPasserelle->createBind( $sBindName, $aParams );
	}
	
	/**
	 * Autor		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: private function createBind
	 * Description	: retourne un bind
	 * Params		: - $sBindName	: nom de la requete
	 * Return		: une array represantant le bind
	*/
	private static function getBind ( $sBindName ) {
		return self::$aBinds[ $sBindName ];
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 23/10/2008
	 *
	 * Name			: public static function getError
	 * Description	: Retourne les erreurs
	 * @return		: - Erreur SQL
	*/
	public static function getError () {
		return self::$oSGBDPasserelle->getError();
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 23/10/2008
	 *
	 * Name			: public static function getErrno
	 * Description	: Retourne les numero des erreurs
	 * @return		: - Numero d'erreur
	*/
	public static function getErrno () {
		return self::$oSGBDPasserelle->getErrno();
	}
	
	/*
		Methodes pour les classes filles
	*/
	public $values			= array();
	public $joins			= array();
	
	// Methodes magiques :
	public function __toString () {
		return $this->get();
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: public function __CALL (methode magique CALL)
	 * Description	: Gestion des jointures et des accesseurs (pour les valeurs ou les jointures)
	 * @params		: - $sMethod	: Methode appellee
	 * @params		: - $aArgs		: Parametres passes
	 * @return		: $this
	 *
	 * NOTE			: sous aucun pretexte cette methode ne doit etre appelee directement !!!!!
	*/
	public function __CALL ($sMethod, $aArgs) {
		$sMethod = strtoupper($sMethod);
		$this->joins = array_change_key_case($this->joins, CASE_UPPER);
		
		$aResultsJoin = array();	preg_match('@JOIN_(.*)@', $sMethod, $aResultsJoin);
		$aResultsGet = array();		preg_match('@(GET|GET_){1}(.*)@', $sMethod, $aResultsGet);
		$aResultsSet = array();		preg_match('@(SET|SET_){1}(.*)@', $sMethod, $aResultsSet);
		
		if ( count($aResultsJoin) ) {
			// on fait une jointure sur une table :
			$this->join(call_user_func(array($aResultsJoin[1], 'getTableName') ), $aArgs[0]);
			return $this;
		}
		// VALUES :
		else if	(	( count($aResultsGet) )
					&&	( isset( $this->values[ strtoupper($aResultsGet[2]) ] ) )
			) {
			// on veut GET un champs :
			return $this->values[ strtoupper($aResultsGet[2]) ];
		} else if	(	( count($aResultsSet) )
					&&	( !empty($aArgs) )
			) {
			// on veut SET un champs :
			$this->values[ strtoupper($aResultsSet[2]) ] = $aArgs[0];
			return $this;
		}
		// JOINS
		else if	(	( count($aResultsGet) )
					&&	( isset( $this->joins[ strtoupper($aResultsGet[2]) ] ) )
			) {
			// on veut GET une jointure :
			if ( !empty($aArgs) && isset($aArgs[0], $this->joins[ strtoupper($aResultsGet[2]) ][ $aArgs[0] ]) ) {
				// On veut un element particulier
				return $this->joins[ strtoupper($aResultsGet[2]) ][ $aArgs[0] ];
			} else {
				// On veut tous les elements
				return $this->joins[ strtoupper($aResultsGet[2]) ];
			}
		} else if	(	( count($aResultsSet) )
					&&	( isset( $this->joins[ strtoupper($aResultsSet[2]) ] ) )
					&&	( !empty($aArgs) )
			) {
			// on veut SET une jointure :
			$this->joins[ strtoupper($aResultsSet[2]) ] = $aArgs[0];
			return $this;
		} else {
			return array();
		}
	}
	
	protected function Select	( $oTableObj, $aParams ) {
		$oTableObj->setType( self::$TYPE_REQ_SELECT );
		$oTableObj->addRequestElement('SELECT', $aParams);
		$oTableObj->iRequestStep = 0;
		return $oTableObj;
	}
	protected function Insert	( $oTableObj, $aParams ) {
		$oTableObj->setType( self::$TYPE_REQ_INSERT );
		$oTableObj->addRequestElement('INSERT', $aParams);
		$oTableObj->iRequestStep = 0;
		return $oTableObj;
	}
	protected function Update	( $oTableObj, $aParams ) {
		$oTableObj->setType( self::$TYPE_REQ_UPDATE );
		$oTableObj->addRequestElement('UPDATE', $aParams);
		$oTableObj->iRequestStep = 0;
		return $oTableObj;
	}
	protected function Delete	( $oTableObj, $aParams ) {
		$oTableObj->setType( self::$TYPE_REQ_DELETE );
		$oTableObj->addRequestElement('DELETE', $aParams);
		$oTableObj->iRequestStep = 0;
		return $oTableObj;
	}
	protected function Replace	( $oTableObj, $aParams ) {
		$oTableObj->setType( self::$TYPE_REQ_REPLACE );
		$oTableObj->addRequestElement('REPLACE', $aParams);
		$oTableObj->iRequestStep = 0;
		return $oTableObj;
	}
	protected function Show		( $oTableObj, $aParams ) {
		$oTableObj->setType( self::$TYPE_REQ_SHOW );
		$oTableObj->addRequestElement('SHOW', $aParams);
		$oTableObj->iRequestStep = 0;
		return $oTableObj;
	}
	
	private $iType				= 0;
	private $aRequestElements	= array();
	
	// Positionnement dans la composition de la requete actuelle :
	private $iRequestStep		= 0;
	// true lorsqu'on met ON, false lorsqu'on appel join
	private $bOnIsOpen			= false;
	// true si autoJoin vient d'etre appellee
	private $bAutoJoinIsOpen	= false;
	// Tables composants la requete
	public $aTablesOpeneds	= array();
	// String de la requete :
	private $sSqlReq = null;
	
	private function setType ( $iType ) {
		$this->iType = $iType;
	}
	public function getType () {
		return $this->iType;
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: final private function addRequestElement
	 * Description	: Ajoutte un element a la requete en cours
	 * @params		: - $sType		: type d'element (SELECT, UPDATE, JOIn, WHERE etc.)
	 * @params		: - $aParams	: liste des parametres composant l'element
	 * @return		: void
	*/
	final private function addRequestElement ( $sType, $aParams ) {
		$this->aRequestElements[] = array(
			'TYPE'		=> $sType,
			'PARAMS'	=> $aParams,
		);
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: final private function getRequestElement
	 * Description	: Retourne la liste des elements composants la requete en cours
	 * @return		: - Liste des elements de la requete en cours
	*/
	final public function getRequestElement () {
		return $this->aRequestElements;
	}
	
	// Methodes qui composent la String de la requete :
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: final private function composeWhereClause
	 * Description	: compose une clause WHERE
	 * @params		: - $aParams	: liste des parametres de composition de la clause
	 * @return		: - String correspondant a la clause WHERE
	*/
	final public function composeWhereClause ( $aParams ) {
		/*
			SOLUCE !!!
			tout remettre au format de quand on a 4 params...
			alors les parametres par defaut :
			il faut au moins 2 valeurs :
			- String query
			- valeurs (en Array ou pas...)
			
			par defaut,
				Array[2] = array('=', '=', '=', '=', '='...)
				Array[3] = 'OR'
			c'est bcp plus facile comme ca.
		*/
		
		// Ce switch permet de recomposer le contenu
		switch (true) {
			case (
						( count($aParams)==4 )
					&&	( is_string($aParams[0]) )
					&&	( is_array($aParams[1]) )
					&&	( self::isArrayOperator($aParams[2]) )
					&&	( self::isLiaison($aParams[3] ) )
				) :	// on a deja tout sous la main...
					// String query / Array valeurs / Array OPERATEUR / String LIAISON
					break;
			case (
						( count($aParams)==3 )
					&&	( is_string($aParams[0]) )
					&&	( is_array($aParams[1]) )
					&&	( self::getArrayDepth($aParams[1]) == 2 )
					&&	( self::isArrayOperator($aParams[2]) )
				) : {
					// String query / Array valeurs / Array OPERATEUR
						$aParams[3] = 'OR';
					} break;
			case (
						( count($aParams)==3 )
					&&	( is_string($aParams[0]) )
					&&	( is_array($aParams[1]) )
					&&	( self::getArrayDepth($aParams[1]) == 3 )
					&&	( self::isArrayOperator($aParams[2]) )
				) : {
					// String query / Array valeurs avec un IN / Array OPERATEUR
						$aParams[3] = 'OR';
					} break;
			case (
						( count($aParams)==3 )
					&&	( is_string($aParams[0]) )
					&&	( is_array($aParams[1]) )
					&&	( self::getArrayDepth($aParams[1]) == 2 )
					&&	( self::isLiaison($aParams[2]) )
				) : {
					// String query / Array valeurs / String LIAISON
						$aParams[3] = $aParams[2];
						$aParams[2] = array_fill(0, count(reset( $aParams[1] )), '=');
						foreach ( $aParams[1] as $kParams => $vParams ) {
							if ( self::is_SQLComposer($vParams) ) {
								$aParams[2][$kParams] = 'IN';
							}
						}
					} break;
			case (
						( count($aParams)==3 )
					&&	( is_string($aParams[0]) )
					&&	( is_array($aParams[1]) )
					&&	( self::getArrayDepth($aParams[1]) == 3 )
					&&	( self::isLiaison($aParams[2]) )
				) : {
					// String query / Array valeurs avec un IN / String LIAISON
						$aParams[3] = $aParams[2];
						$aParams[2] = array_fill(0, count(reset( $aParams[1] )), '=');
						foreach ( $aParams[1] as $kParams => $vParams ) {
							if ( ( is_array($vParams) ) || ( self::is_SQLComposer($vParams) ) ) {
								$aParams[2][$kParams] = 'IN';
							}
						}
					} break;
			case (
						( count($aParams)==3 )
					&&	( is_string($aParams[0]) )
					&&	( self::isOperator($aParams[1]) )
					&&	(	( is_int($aParams[2]) )
						||	( is_string($aParams[2]) )
						||	( self::is_SQLComposer($aParams[2]) )
						||	( self::is_Field($aParams[2]) )
						)
				) : {
					// champs / operateur / valeur
						$aValues = array( array($aParams[2]) );
						$aParams[2] = array( $aParams[1] );
						$aParams[1] = $aValues;
						$aParams[3] = 'OR';
					} break;
			case (
						( count($aParams)==3 )
					&&	( is_string($aParams[0]) )
					&&	( self::isOperator($aParams[1]) )
					&&	( is_array($aParams[2]) )
				) : {
					// champs / operateur / valeur avec IN
						$aValues = array( array($aParams[2]) );
						$aParams[2] = array( 'IN' );
						$aParams[1] = $aValues;
						$aParams[3] = 'OR';
					} break;
			case (
						( count($aParams)==2 )
					&&	( is_string($aParams[0]) )
					&&	( is_array($aParams[1]) )
					&&	( self::getArrayDepth($aParams[1])==1 )
				) : {
					// String query / Array Valeurs
						$aParams[1] = array( $aParams[1] );
						$aParams[2] = array_fill(0, count($aParams[1][0]), '=');
						$aParams[3] = 'OR';
					} break;
			case (
						( count($aParams)==2 )
					&&	( is_string($aParams[0]) )
					&&	( is_array($aParams[1]) )
					&&	( self::getArrayDepth($aParams[1])>=2 )
				) : {
					// String query / Array Valeurs
						$aParams[1] = $aParams[1];
						$aParams[2] = array_fill(0, count($aParams[1][0]), '=');
						foreach ( $aParams[1][0] as $kParams => $vParams ) {
							if ( self::is_SQLComposer($vParams) ) {
								$aParams[2][ $kParams ] = 'IN';
							}
						}
						$aParams[3] = 'OR';
					} break;
			case (
						( count($aParams)==2 )
					&&	( is_string($aParams[0]) )
					&&	(	( is_int($aParams[1]) )
						||	( is_string($aParams[1]) )
						||	( self::is_SQLComposer($aParams[1]) )
						||	( self::is_Field($aParams[1]) )
						)
				) : {
					// String query / Misc Valeurs
						if ( self::is_SQLComposer($aParams[1]) ) {
							$aParams[2] = array('IN');
						} else {
							$aParams[2] = array('=');
						}
						$aParams[1] = array( array( $aParams[1] ) );
						$aParams[3] = 'OR';
					} break;
			case (
						( count($aParams)==2 )
					&&	( is_string($aParams[0]) )
					&&	( is_array($aParams[1]) )
				) : {
					// String query / Misc Valeurs
						$aParams[1] = array($aParams[1]);
						$aParams[2] = array_fill(0, count($aParams[1]), '=');
						$aParams[3] = 'OR';
					} break;
			default : {
				// String query / Misc Valeur / Misc Valeur /...
				if ( ( count($aParams)>1 ) && ( is_string($aParams[0]) ) ) {
					$aValues = array();
					$aOperators = array();
					
					for ( $i=1, $iMax=count($aParams) ; $i<$iMax ; $i++ ) {
						if ( ( is_int($aParams[$i]) ) || ( is_string($aParams[$i]) ) ) {
							$aValues[]		= $aParams[$i];
							$aOperators[]	= '=';
						} else if ( is_int($aParams[$i]) ) {
							$aValues[]		= $aParams[$i];
							$aOperators[]	= 'IN';
						} else {
							return '(1=1)';
						}
					}
					$aParams = array(
						0	=> $aParams[0],
						1	=> array( $aValues ),
						2	=> $aOperators,
						3	=> 'OR',
					);
				} else {
					return '(1=1)';
				}
			}
		}
		
		return self::$oSGBDPasserelle->composeWhereClause( $aParams[0], $aParams[1], $aParams[2], $aParams[3] );
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: final private function composeUpdateClause
	 * Description	: Compose les clauses SET et VALUE d'une requete UPDATE
	 * @params		: - $aFields	: contenue des champs SET
	 * @params		: - $aValues	: contenue des champs VALUE
	 * @return		: - String correspondant a la clause
	*/
	final private function composeUpdateClause ( $aFields, $aValues ) {
		return self::$oSGBDPasserelle->composeUpdateClause( $aFields, $aValues );
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: final private function composeInsertClause
	 * Description	: Compose les clauses SET et VALUE d'une requete INSERT
	 * @params		: - $aFields	: contenue des champs SET
	 * @params		: - $aValues	: contenue des champs VALUE
	 * @return		: - String correspondant a la clause
	*/
	final private function composeInsertClause ( $aFields, $aValues ) {
		return self::$oSGBDPasserelle->composeInsertClause( $aFields, $aValues );
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: final private function compose
	 * Description	: Compose une requete SQL
	 * @return		: - retourne une requete SQL
	*/
	final private function compose () {
		return self::$oSGBDPasserelle->compose( $this );
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 08/11/2008
	 *
	 * Name			: public function getSelectedFields
	 * Description	: Renvoit les champs selectionne dans une requete type SELECT
	 * @return		: - Array
	*/
	public function getSelectedFields () {
		if ( $this->aRequestElements[0]['TYPE'] == 'SELECT' ) {
			return $this->aRequestElements[0]['PARAMS'];
		}
		return array();
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 08/11/2008
	 *
	 * Name			: public function setSelectedFields
	 * Description	: Modifie les champs selectionne dans une requete type SELECT
	 * @params		: - Array
	*/
	public function setSelectedFields ($aFields) {
		if ( $this->aRequestElements[0]['TYPE'] == 'SELECT' ) {
			$this->aRequestElements[0]['PARAMS'] = $aFields;
		}
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 08/11/2008
	 *
	 * Name			: public function getLimit
	 * Description	: Renvoit les valeurs selectionne dans une clause LIMIT
	 * @return		: - Array
	*/
	public function getLimit () {
		foreach ( $this->aRequestElements as $iPos => $aElem ) {
			if ( $aElem['TYPE'] == 'LIMIT' ) {
				return $aElem['PARAMS'];
			}
		}
		return false;
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 08/11/2008
	 *
	 * Name			: public function setLimit
	 * Description	: Modifie les valeurs selectionne dans une clause LIMIT
	 * @params		: - Array
	*/
	public function setLimit ($iFirst, $iAdd=null) {
		foreach ( $this->aRequestElements as $iPos => &$aElem ) {
			if ( $aElem['TYPE'] == 'LIMIT' ) {
				$aFields = array();
				$aFields[] = $iFirst;
				if($iAdd!==null){
					$aFields[] = $iAdd;
				}
				
				$aElem['PARAMS'] = $aFields;
				return;
			}
		}
		if($iAdd!==null){
			$this->Limit($iFirst, $iAdd);
		} else {
			$this->Limit($iFirst);
		}
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 08/11/2008
	 *
	 * Name			: public function getSelectedFields
	 * Description	: Renvoit les champs selectionne dans une clause ORDER BY
	 * @return		: - Array
	*/
	public function getOrder () {
		foreach ( $this->aRequestElements as $iPos => $aElem ) {
			if ( $aElem['TYPE'] == 'ORDERBY' ) {
				return $aElem['PARAMS'];
			}
		}
		return false;
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 08/11/2008
	 *
	 * Name			: public function setSelectedFields
	 * Description	: Modifie les champs selectionne dans une clause ORDER BY
	 * @params		: - Array
	*/
	public function setOrder ($sField) {
		foreach ( $this->aRequestElements as $iPos => &$aElem ) {
			if ( $aElem['TYPE'] == 'ORDERBY' ) {
				$aElem['PARAMS'] = array($sField);
				return;
			}
		}
		if($iAdd!==null){
			$this->OrderBy($sField);
		} else {
			$this->OrderBy($sField);
		}
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 20/01/2009
	 *
	 * Name			: public function getGroup
	 * Description	: Renvoit les champs selectionne dans une clause GROUP BY
	 * @return		: - Array
	*/
	public function getGroup () {
		foreach ( $this->aRequestElements as $iPos => $aElem ) {
			if ( $aElem['TYPE'] == 'GROUPBY' ) {
				return $aElem['PARAMS'];
			}
		}
		return false;
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 20/01/2009
	 *
	 * Name			: public function setGroup
	 * Description	: Modifie les champs selectionne dans une clause GROUP BY
	 * @params		: - Array
	*/
	public function setGroup ($sField) {
		foreach ( $this->aRequestElements as $iPos => &$aElem ) {
			if ( $aElem['TYPE'] == 'GROUPBY' ) {
				$aElem['PARAMS'] = array($sField);
				return;
			}
		}
		if($iAdd!==null){
			$this->GroupBy($sField);
		} else {
			$this->GroupBy($sField);
		}
	}
}

if ( realpath($_SERVER['SCRIPT_FILENAME']) == realpath(__FILE__) ) {
	if ( file_exists('sql_composer_instal.php') ) {
		require_once('sql_composer_instal.php');
		exit;
	}
}
if ( file_exists( realpath(dirname(__FILE__).'/sql_composer__config.php')) ) {
	require_once( realpath(dirname(__FILE__).'/sql_composer__config.php') );
}
?>