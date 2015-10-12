<?php
class PersonalForm extends Form {
	public function getInsert () {
		if ( strtolower($this->getMethod()) == 'post' ) {
			$aMethod = array(POST);
		} else {
			$aMethod = array(GET);
		}
		
		if ( params('form_type', $aMethod) == __CLASS__ ) {
			$this->aOnInsertValidations = array();
			foreach ( $this->aInsertDefiner as $sFieldName => $aFieldParams ) {
				$this->aInsertValues[ $sFieldName ] = params($sFieldName,$aMethod);
				$this->aOnInsertValidations[ $sFieldName ] = $aFieldParams[3];
			}
			$bFormWasSent = true;
		} else {
			$bFormWasSent = false;
		}
		
		$this->start();
		
		if ( $bFormWasSent ) {
			$this->bValid = $this->bValid && $this->validInsert('format');
			
			foreach ( $this->aInsertDefiner as $sFieldName => $aFieldParams ) {
				$this->bValid = $this->bValid && $this->validInsert($sFieldName);
				if ( !empty($this->aInsertValues[ $sFieldName ]) ) {
					$aFieldParams[2]['value'] = $this->aInsertValues[ $sFieldName ];
				}
				$aFieldParams[2]['name'] = $sFieldName;
				$aFieldParams[2]['id'] = 'id_'.$sFieldName;
				$oInput = new $aFieldParams[0]($aFieldParams[1], $aFieldParams[2]);
				
				if ( isset( $this->aValuesGroup[ $sFieldName ] ) ) {
					if ( strtolower($aFieldParams[0]) == 'select' ) {
						$oInput->start();
						$i = 0;
						foreach ( $this->aValuesGroup[ $sFieldName ] as $sValue => $sLabel ) {
							$aParams = array();
							if ( $this->aInsertValues[ $sFieldName ] == $sValue ) {
								$aParams['selected'] = 'selected';
							}
							$aParams['id'] = $sFieldName.'_v'.$i;
							new Option($sValue, $sLabel, $aParams);
							$i++;
						}
						$oInput->close();
					}
				}
			}
			new Hidden('hidden', array('name'=>'form_type', 'value'=>__CLASS__ ));
			if ( $this->bValid ) {
				// Le formulaire est valide :
				$this->submitInsert();
				return true;
			}
		} else {
			foreach ( $this->aInsertDefiner as $sFieldName => $aFieldParams ) {
				$oInput = new $aFieldParams[0]($aFieldParams[1], $aFieldParams[2]);
				
				if ( isset( $this->aValuesGroup[ $sFieldName ] ) ) {
					if ( strtolower($aFieldParams[0]) == 'select' ) {
						$oInput->start();
						foreach ( $this->aValuesGroup[ $sFieldName ] as $sValue => $sLabel ) {
							$aParams = array();
							new Option($sValue, $sLabel, $aParams);
						}
						$oInput->close();
					}
				}
			}
			new Hidden('hidden', array('name'=>'form_type', 'value'=>__CLASS__ ));
		}
		$this->close();
	}
	
	public function validInsert ( $sName ) {
		if ( isset( $this->aOnInsertValidations[$sName] ) ) {
			if ( is_array($this->aOnInsertValidations[$sName]) ) {
				foreach ( $this->aOnInsertValidations[$sName] as $oVerificator ) {
					if ( ! $oVerificator->verification( $this->aInsertValues[$sName] ) ) {
						$oVerificator->getErrors();
						return false;
					}
				}
			} else {
				if ( ! $this->aOnInsertValidations[$sName]->verification( $this->aInsertValues[$sName] ) ) {
					$this->aOnInsertValidations[$sName]->getErrors();
					return false;
				}
			}
		}
		return true;
	}
	
	public function submitInsert () {
		$oInsert = new $this->sTable();
		foreach ( $this->aInsertValues as $sField => $sValue ) {
			$sSetField = 'set_' . $sField;
			$oInsert->$sSetField( $sValue );
		}
		$oInsert->insert();
		Action::getActualAction()->redirect( $this->aOnInsert[0], $this->aOnInsert[1] );
	}
}

interface FormValidator {
	public function verification($mToVerif);
}


class StringValidator implements FormValidator {
	private	$aVerifs	= array(),
			$aTrads		= array(
							'type'		=> 'La valeur n\'est pas une chaine de caractere.',
							'max'		=> 'La chaine de caractères est trop longue.',
							'min'		=> 'La chaine de caractères est trop courte.',
							'not'		=> 'La chaine de caractères contient certains caracteres interdits.',
							'ereg'		=> 'La chaine de caractères contient ertaine sexpressions interdites.',
							'notnull'	=> 'La chaine de caractères ne doit pas être vide.',
						),
			$aProblems	= array();
	
	public function __construct( $aVerifs, $aErrorsTrad=array() ) {
		if ( ! is_array($aVerifs) ) {
			$aVerifs = array($aVerifs);
		}
		$this->aVerifs = array_change_key_case($aVerifs, CASE_LOWER);
		
		foreach ( $aErrorsTrad as $sType => $sTrad ) {
			$this->setProblemTrad($sType, $sTrad);
		}
	}
	
	public function setProblemTrad ($sProbType, $sProbTrad) {
		$this->aTrads[$sProbType] = $sProbTrad;
		return $this;
	}
	
	public function getErrors () {
		foreach ( $this->aProblems as $sProbType => $sProbTrad ) {
			new Error( $this->aTrads[$sProbTrad] );
		}
	}
	
	public function verification($mToVerif){
		$bValid = true;
		if ( ! is_string($mToVerif) ) {
			$bValid = false;
			$this->aProblems[] = 'type';
		}
		if ( $bValid ) {
			foreach ( $this->aVerifs as $sTypeVerif => $mParams ) {
				switch ( (string)$sTypeVerif ) {
					case 'max':{
						if( strlen($mToVerif) > $mParams ) {
							$bValid = false;
							$this->aProblems[] = 'max';
						}
						break;
					}
					case 'min':{
						if( strlen($mToVerif) < $mParams ) {
							$bValid = false;
							$this->aProblems[] = 'min';
						}
						break;
					}
					case 'not':{
						if( strpos($mToVerif, $mParams)!==false ) {
							$bValid = false;
							$this->aProblems[] = 'not';
						}
						break;
					}
					case 'reg':{
						if( ! ereg($mParams, $mToVerif) ) {
							$bValid = false;
							$this->aProblems[] = 'reg';
						}
						break;
					}
					default:break;
				}
				
				switch ( (string)$mParams ) {
					case 'notnull':{
						if( strlen($mToVerif) == 0 ) {
							$bValid = false;
							$this->aProblems[] = 'notnull';
						}
						break;
					}
				}
			}
		}
		return $bValid;
	}
}
?>