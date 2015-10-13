<?php
class FileException extends Exception {
	const	FILE_NOT_EXISTS			= 1,
			CANT_OPEN_FILE			= 2,
			PATH_NOT_EXISTS			= 3,
			BAD_MODE				= 4,	// mode d'ouverture du fichier n'est pas logique
			CANT_RENAME_FILE		= 5,
			CANT_CLOSE_FILE			= 6,
			CANT_COPY_FILE			= 7,
			CANT_DELETE_FILE		= 8,
			CANT_MODIFY				= 9,
			CANT_READ_FILE_DATAS	= 10,
			DONT_HAVE_RIGHT			= 11,
			NOT_A_RESSOURCE			= 12;

	public function __construct ( $iError ) {
		if ( self::FILE_NOT_EXISTS === $iError ) {
		} else if ( self::FILE_NOT_EXISTS		=== $iError ) {
			parent::__construct('Le fichier n\'existe pas');
			parent::__construct( $iError );
		} else if ( self::CANT_OPEN_FILE		=== $iError ) {
			parent::__construct('Impossible d\'ouvrir le fichier');
		} else if ( self::PATH_NOT_EXISTS		=== $iError ) {
			parent::__construct('Le dossier n\'existe pas');
		} else if ( self::BAD_MODE				=== $iError ) {
			parent::__construct('Le mode d\'ouverture est incohérent');
		} else if ( self::CANT_RENAME_FILE		=== $iError ) {
			parent::__construct('Impossible de renommer ce fichier (vérifier si il n\'est pas ouvert par une autre source)');
		} else if ( self::CANT_CLOSE_FILE		=== $iError ) {
			parent::__construct('Impossible de refermer ce fichier (je vois pas comment ca pourrait arriver mais bon...)');
		} else if ( self::CANT_COPY_FILE		=== $iError ) {
			parent::__construct('Impossible de créer ce fichier dans le dossier choisi (vérifier si un fichier du même nom n\'existe pas dans ce dossier)');
		} else if ( self::CANT_DELETE_FILE		=== $iError ) {
			parent::__construct('Impossible de supprimer ce fichier');
		} else if ( self::CANT_MODIFY			=== $iError ) {
			parent::__construct('Impossible de modifier ce fichier');
		} else if ( self::CANT_READ_FILE_DATAS	=== $iError ) {
			parent::__construct('Impossible de lire les données de ce fichier');
		} else if ( self::DONT_HAVE_RIGHT		=== $iError ) {
			parent::__construct('Le mode d\'ouverture du fichier ne permet pas d\'effectuer cette action');
		} else if ( self::NOT_A_RESSOURCE		=== $iError ) {
			parent::__construct('La variable n\'est pas une ressource.');
		} else {
			parent::__construct( $iError );
		}
	}
}

class UtilFile {
	const	READ			= 1,	// lecture
			WRITE			= 2,	// ecriture
			CREATE			= 4,	// creation si n'existe pas
			POINTER_END		= 8,	// place le pointeur a la fin
			POINTER_START	= 16,	// place le pointeur au debut
			CREATE_ONLY		= 32;	// retourne une erreur si le fichier existe

	const	WINDOWS_LINE	= 1,	// \r\n
			UNIX_LINE		= 2,	// \n
			MAC_LINE		= 3;	// \r

	private static	$sDefaultPath	= null;

	/**
	 *	Initialise le dossier par defaut
	 */
	public static function setDefaultPath ($sDefaultPath) {
		self::$sDefaultPath = $sDefaultPath;
	}

	/**
	 *	Retourne le dossier par defaut, si il n'a pas ete initialise, retourne le dossier contenant CE fichier
	 */
	public static function getDefaultPath () {
		if ( null === self::$sDefaultPath ) {
			return realpath( dirname(__FILE__) );
		} else {
			return realpath( self::$sDefaultPath );
		}
	}

	private	$sFileName		= null,		// nom du fichier
			$sPath			= null,		// repertoire du fichier
			$sExt			= null,		// Extension du fichier
			$sContent		= null,		// Contenu
			$rRess			= null,		// ressource du fichier (resultat du fopen)
			$sMimeType		= null,		// Type MIME du fichier
			$iMode			= 0,		// Mode d'ouverture
			$iLine			= 0,		// Ligne actuelle
			$iChar			= 0,		// Caractere actuel
			$iSize			= 0,		// Taille du fichier (en octet)
			$bIsOpen		= false,	// true si le fichier est ouvert, sinon false
			$bSaveOnClose	= false,	// true si le fichier enregistrer a la fermeture, sinon false
			$bIsUploaded	= false,	// true si le fichier a ete uploade sinon false
			$sOrigName		= '';		// si le fichier a ete uploade, contiendra le vrai nom du fichier

	/**
	 *	__construct
	 */
	public function __construct (	$sFileURL		= '',
									$iMode			= 38,	// self::WRITE | self::CREATE | self::POINTER_START
									$iPointerPos	= 0,
									$bIsUploaded	= false,
									$sOrigName	= ''
								) {
		$this->setFileName($sFileURL);
		$this->setMode($iMode);
		$this->setUploaded($bIsUploaded);
		$this->setOrigName($sOrigName);

		if ( 0 !== $iPointerPos ) {
			$this->moveTo($iPointerPos);
		}
	}

	/**
	 *	__destruct
	 */
	public function __destruct () {
		$this->close();
	}

	/**
	 *	Permet d'enregistrer automatiquement le fichier a la fermeture.
	 */
	public function saveOnClose () {
		$this->bSaveOnClose = true;
	}

	/**
	 *	Renomme le fichier
	 */
	public function rename ($sName) {
		$bIsOpen = $this->isOpen();
		if ( $bIsOpen ) {
			$iPosition = $this->getPointerPosition();
			$this->close();
		} else {
			$iPosition = 0;
		}
		
		if ( @rename( $this->getFileUri(), $this->getPath() . $sName) ) {
			$this->setFileName($sName);
			if ( $bIsOpen ) {
				$this->open();
				$this->moveTo($iPosition);
			}
		} else {
			throw new FileException( FileException::CANT_RENAME_FILE );
		}
	}

	/**
	 *	Copie le fichier dans un autre repertoire
	 *	retourne le nouveau fichier
	 */
	public function copy ($sDir) {
		if ( DIRECTORY_SEPARATOR !== substr($sDir, strlen($sDir)-1) ) {
			$sDir .= DIRECTORY_SEPARATOR;
		}
		$bWasOpen = $this->isOpen();
		// Si le fichier est ouvert :
		if ( $bWasOpen ) {
			$iPos = $this->getPointerPosition();
			$this->close();
		}
		
		if ( $this->getUploaded() ) {
			if ( ! @move_uploaded_file($this->getOrigName(), $sDir.$this->getFileName()) ) {
				throw new FileException( FileException::CANT_COPY_FILE );
			}
		} else {
			if ( ! @copy($this->getFileUri(), $sDir.$this->getFileName()) ) {
				throw new FileException( FileException::CANT_COPY_FILE );
			}
		}
		// Si le fichier est ouvert :
		if ( $bWasOpen ) {
			$this->moveTo($iPos);
		}

		return new UtilFile($sDir.$this->getFileName(), $this->getMode() );
	}

	/**
	 *	Copie le fichier dans un autre repertoire et le renomme
	 *	retourne le nouveau fichier
	 */
	public function renameAndCopy ($sName, $sDir) {
		if ( DIRECTORY_SEPARATOR !== substr($sDir, strlen($sPath)-1) ) {
			$sDir .= DIRECTORY_SEPARATOR;
		}

		if ( $this->getUploaded() ) {
			if ( ! @move_uploaded_file($this->getOrigName(), $sDir.$sName) ) {
				throw new FileException( FileException::CANT_COPY_FILE );
			}
		} else {
			if ( ! @copy($this->getFileUri(), $sDir.$sName) ) {
				throw new FileException( FileException::CANT_COPY_FILE );
			}
		}

		return new UtilFile($sDir.$sName, $this->getMode() );
	}

	/**
	 *	Supprime un fichier
	 */
	public function delete () {
		$this->close();
		if ( ! @unlink($this->getFileUri()) ) {
			throw new FileException( FileException::CANT_DELETE_FILE );
		}
	}

	/**
	 *	Rewind le fichier
	 */
	public function rewind () {
		$this->open();
		rewind($this->getRessource());
	}

	/**
	 *	Lit tout le fichier
	 */
	public function readAll () {
		$this->open();
		$iPos = $this->getPointerPosition();
		$this->rewind();
		$this->sContent = $this->readEnd();
		$this->moveTo( $iPos );
	}

	/**
	 *	Lit le fichier jusqu'a la fin (deplace le curseur)
	 */
	public function readEnd () {
		$this->open();
		if ( $this->getSize()-$this->getPointerPosition() > 0 ) {
			return fread( $this->getRessource(), $this->getSize()-$this->getPointerPosition() );
		} else {
			return '';
		}
	}

	/**
	 *	Vide le contenu d'un fichier
	 */
	public function clear () {
		$this->rewind();
		$this->truncate(0);
	}

	/**
	 *	Vide une partie du contenu du fichier
	 */
	public function truncate ($iSize) {
		if ( self::WRITE!==($this->getMode()&self::WRITE) ) {
			throw new FileException(FileException::DONT_HAVE_RIGHT);
		}
		ftruncate( $this->getRessource(), $iSize );
	}

	/**
	 *	Retoure la ressource correspondante
	 */
	public function getRessource () {
		return $this->rRess;
	}

	/**
	 *	Initialise la ressource
	 */
	public function setRessource ($rRess) {
		if ( is_resource($rRess) ) {
			$this->rRess = $rRess;
		} else {
			throw new FileException( FileException::NOT_A_RESSOURCE );
		}
	}

	/**
	 *	Ouvre le fichier si besoin
	 */
	public function open () {
		if ( ! $this->isOpen() ) {
			if ( ! ($rRess=@fopen( $this->getFileUri(), $this->getTraductionMode() )) ) {
				throw new FileException( FileException::CANT_OPEN_FILE );
			} else {
				$this->setRessource($rRess);
				$this->setOpen();
			}
		}
	}

	/**
	 *	Referme un fichier
	 */
	public function close () {
		if ( $this->isOpen() ) {
			if ( $this->bSaveOnClose && self::WRITE===($this->getMode()&self::WRITE) ) {
				$this->save();
			}

			if ( ! @fclose($this->getRessource()) ) {
				throw new FileException( FileException::CANT_CLOSE_FILE );
			}
			$this->setClose();
		} else if ( $this->bSaveOnClose ) {
			$this->open();
			$this->save();
			$this->close();
		}
	}

	/**
	 *	Retourne true si le fichier est ouvert, sinon false
	 */
	public function isOpen () {
		return $this->bIsOpen;
	}

	/**
	 *	Initialise le fichier comme etant ouvert
	 */
	private function setOpen () {
		$this->bIsOpen = true;
	}

	/**
	 *	Initialise le fichier comme etant ferme
	 */
	private function setClose () {
		$this->bIsOpen = false;
	}

	/**
	 *	retourne le dossier d'un fichier
	 */
	public function getPath () {
		$sPath = '';
		if ( null === $this->sPath ) {
			$sPath = self::getDefaultPath();
		} else {
			$sPath = $this->sPath;
		}

		if ( DIRECTORY_SEPARATOR !== substr($sPath, strlen($sPath)-1) ) {
			$sPath .= DIRECTORY_SEPARATOR;
		}
		return $sPath;
	}

	/**
	 *	Initialise le dossier d'un fichier (voir dans le destruct)
	 */
	public function setPath ($sPath) {
		if ( ! realpath($sPath) ) {
			throw new FileException( FileException::PATH_NOT_EXISTS );
		}
		$this->sPath = $sPath=realpath($sPath);
	}

	/**
	 *	Retourne le chemin d'un fichier sous forme d'array
	 */
	public function getPathTree () {
		return split(DIRECTORY_SEPARATOR, $this->getPath());
	}

	/**
	 *	Initialise le chemin d'un fichier via un d'array
	 */
	public function setPathTree ($aTree) {
		$this->setPath( join($aTree, DIRECTORY_SEPARATOR) );
	}

	/**
	 *	Initialise le nom d'un fichier
	 */
	public function setFileName ($sFileName) {
		if ( strpos($sFileName, DIRECTORY_SEPARATOR) ) {
			$this->setPath( substr($sFileName, 0, strrpos($sFileName, DIRECTORY_SEPARATOR)+1 ) );
			$sFileName = substr($sFileName, strrpos($sFileName, DIRECTORY_SEPARATOR)+1 );
		}
		$this->sFileName = $sFileName;
	}

	/**
	 *	Retourne le nom d'un fichier
	 */
	public function getFileName () {
		return $this->sFileName;
	}

	/**
	 *	Retourne l'extension d'un fichier
	 */
	public function getExtension () {
		if ( null===$this->sExt ) {
			$sFile = $this->getFileName();
			$this->sExt = substr($sFile, strrpos($sFile, '.')+1 );
		}
		return $this->sExt;
	}

	/**
	 *	Retourne l'emplacement reel du fichier
	 */
	public function getFileUri () {
		return $this->getPath() . $this->getFileName();
	}

	/**
	 *	Retourne le contenu d'un fichier
	 */
	public function getContent () {
		if ( self::READ!==($this->getMode()&self::READ) ) {
			throw new FileException(FileException::DONT_HAVE_RIGHT);
		}
		if ( null === $this->sContent ) {
			$this->readAll();
		}
		return $this->sContent;
	}

	/**
	 *	Initialise le contenu d'un fichier
	 */
	public function setContent ($sContent, $bAutoSave=true) {
		$this->clear();
		$this->add($sContent, $bAutoSave);
		$this->sContent = $sContent;
	}

	/**
	 *	Ajoutte du contenu a un fichier (positionne au pointeur)
	 */
	public function add ($sContent, $bAutoSave=true) {
		if ( self::WRITE!==($this->getMode()&self::WRITE) ) {
			throw new FileException(FileException::DONT_HAVE_RIGHT);
		}
		$this->open();

		if ($bAutoSave) {
			if ( ! @fwrite($this->getRessource(), $sContent) ) {
				throw new FileException( FileException::CANT_MODIFY );
			}
		}
		$this->sContent = null;
	}

	/**
	 *	Ajoutte un retour chariot au fichier
	 */
	public function addLine ($iMode=1) {
		switch($iMode){
			case self::WINDOWS_LINE:
				$this->add( chr(13).chr(10) );
				break;
			case self::UNIX_LINE:
				$this->add( chr(10) );
				break;
			case self::MAC_LINE:
				$this->add( chr(13) );
				break;
		}
	}

	/**
	 *	Enregistre le contenu d'un fichier
	 */
	public function save () {
		$this->setContent( $this->getContent(), true);
	}

	/**
	 *	Retourne le mode d'ouverture du fichier
	 */
	public function getMode () {
		return $this->iMode;
	}

	/**
	 *	Initialise le mode d'ouverture du fichier
	 */
	public function setMode ($iMode) {
		$this->iMode = $iMode;
	}

	/**
	 *	Initialise la position du pointeur (alias de setPointerPosition)
	 */
	public function moveTo ($iPos) {
		$this->setPointerPosition($iPos);
	}

	/**
	 *	Initialise la position du pointeur
	 */
	public function setPointerPosition ($iPos) {
		$this->open();
		fseek( $this->getRessource(), $iPos);
	}

	/**
	 *	Retourne la position du pointeur
	 */
	public function getPointerPosition () {
		if ( ! ($iPos=@ftell($this->getRessource())) ) {
			return 0;
		} else {
			return $iPos;
		}
	}

	/**
	 *	Retourne la taille d'un fichier
	 */
	public function getSize () {
		if ( ! ($iSize=@filesize($this->getFileUri()) ) ) {
			throw new FileException( FileException::CANT_READ_FILE_DATAS );
		}
		return $iSize;
	}

	/**
	 *	Retourne le type mime d'un fichier
	 *	Retourne FALSE si fonction mime_content_type n'existe pas ou si le type MIME n'as pas ete determine
	 */
	public function getMimeType () {
		if ( function_exists('mime_content_type') ) {
			if ( ! ($sMime=@mime_content_type($this->getFileUri())) ) {
				return false;
			} else {
				return $sMime;
			}
		} else {
			return false;
		}
	}

	/**
	 *	Retourne la ligne courante
	 */
	public function getLine () {
		if ( self::READ!==($this->getMode()&self::READ) ) {
			throw new FileException(FileException::DONT_HAVE_RIGHT);
		}
		$this->open();
		if ( ! ($sLine=@fgets($this->getRessource())) ) {
			throw new FileException( FileException::CANT_READ_FILE_DATAS );
		}
		return $sLine;
	}

	/**
	 *	Retourne le caractere courant (deplace le pointeur)
	 */
	public function getChar () {
		if ( self::READ!==($this->getMode()&self::READ) ) {
			throw new FileException(FileException::DONT_HAVE_RIGHT);
		}
		$this->open();
		if ( ! ($sChar=@fgetc($this->getRessource())) ) {
			throw new FileException( FileException::CANT_READ_FILE_DATAS );
		}
		return $sChar;
	}

	/**
	 *	Retourne le contenu d'un fichier (deplace le pointeur)
	 */
	public function __toString () {
		if ( self::READ!==($this->getMode()&self::READ) ) {
			return '';
		}
		return $this->getContent();
	}

	/**
	 *	Retourne true si on peut ouvrir le fichier ou non
	 */
	private function isOpenable () {
		return		@file_exists( $this->getFileUri() )
				&&	@is_readable( $this->getFileUri() );
	}

	/**
	 *	Initialise si le fichier a ete uploade ou non :
	 */
	private function setUploaded ($bIsUploaded) {
		$this->bIsUploaded = $bIsUploaded;
	}

	/**
	 *	Retourne true si le fichier a ete uploade, sinon false
	 */
	public function getUploaded () {
		return $this->bIsUploaded;
	}
	
	/**
	 *	Initialise le nom d'origine du fichier (utile si il a ete uploade)
	 */
	private function setOrigName ($sOrigName) {
		$this->sOrigName = $sOrigName;
	}

	/**
	 *	Retourne le nom d'origine du fichier (utile si il a ete uploade)
	 */
	public function getOrigName () {
		return $this->sOrigName;
	}
	
	
	/**
	 *	Retourne true si on peut ouvrir le fichier ou non
	 */
	
	/*
		READ			= 1
		WRITE			= 2
		CREATE			= 4
		POINTER_END		= 8
		POINTER_START	= 16
		CREATE_ONLY		= 32
	*/
	private function getTraductionMode () {
		$iMode = $this->getMode();
		if ( in_array($iMode, array(1,17) ) ) {
			return 'r';
		} else if ( in_array($iMode, array(0,2,3,18,19) ) ) {
			return 'r+';
		} else if ( in_array($iMode, array(4,20,22) ) ) {
			return 'w';
		} else if ( in_array($iMode, array(5,7,21,23) ) ) {
			return 'w+';
		} else if ( in_array($iMode, array(10,14) ) ) {
			return 'a';
		} else if ( in_array($iMode, array(8,9,11,12,13,15) ) ) {
			return 'a+';
		} else if ( in_array($iMode, array(32,33) ) ) {
			return 'x';
		} else if ( in_array($iMode, array(34,35,36,37,38,39,49,50,51,52,53,54,55) ) ) {
			return 'x+';
		}else {
			throw new FileException( FileException::BAD_MODE );
		}
	}
}



// AUTO GESTION DES FICHIERS :
$GLOBALS['FILES'] = array();
if(!empty($_FILES)){
	foreach($_FILES as $sFieldName => $aFile){
		$oFile = new UtilFile($aFile['tmp_name'], 38, 0, true, $aFile['tmp_name']);
		$oFile->setFileName( $aFile['name'] );
		$GLOBALS['FILES'][$sFieldName] = $oFile;
	}
}