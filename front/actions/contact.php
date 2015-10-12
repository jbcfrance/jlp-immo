<?php
/**
 * Auteur			: Jean-Baptiste CIEPKA
 * Date				: Decembre 2009
 *
 * Name				: Class contactAction
 * Description		: Cette class génére la page de contact
 * @templates dir 	: templates/contact
 *
*/
class contactAction extends contactAction_BASE {
	/**
	 * Default action for contact
	 *	date	: 2009-09-29 14:05:50
	 */
	public function doDefault ($aParams=array()) {
		$this->addJavaScript(SCRIPTS.'contact.js');
		$this->addMeta('description',utf8_encode('JLP-IMMO - Immobilier Saint Gervais, Le Fayet, Passy et Sallanches, Agence immobilière Saint-Gervais, Le Fayet, Passy, Sallanches. Achat, Vente, Gestion, Conseils, Estimations'));
		Xtremplate::$vars['sTitleH1'] = utf8_encode("Contacter l'agence JLP-IMMO à Saint Gervais");
		if($_SESSION['contact_res']){
			$_SESSION['contact_res'] = false;
			$_SESSION['contact_pb'] = false;
		}else{
			if($_COOKIE['ContactUse'] == 'oui') {
			$_SESSION['contact_resultat'] = '';
			}else{
			$_SESSION['contact_mail'] = '';
			$_SESSION['contact_nom'] = '';
			$_SESSION['contact_resultat'] = '';
			}
		}
		$aImgs = self::searchdir('web/images/contact/agence');
		foreach($aImgs as $kImg=>$vImg){
			$sImgName = str_replace("web/images/contact/agence/",'',$vImg);
			if($sImgName != '')
			$aImages[] = $sImgName;
		}
		Xtremplate::$vars['aImages'] = $aImages;
		//echo"<pre>";print_r($aImages);echo"</pre>";die();
	}
	
	public function doContact ($aParams=array()) {
		require_once 'Mail.php';
		$headers["From"] = "Message automatique du site JLP IMMO <contact@jlp-immo.com>";
		$headers["To"] = "To: Contact Agence JLP-Immo <contact@jlp-immo.com>";  
		$headers["Subject"] = "Contact du site JLP-Immo.com : ".params('contact_nom',array(POST));
		$headers["Reply-To"] = params('contact_mail',array(POST)); 
		$headers["Content-Type"] = "text/plain; charset=utf-8"; 
		echo "<pre>";
		$smtpinfo["host"] = "auth.smtp.1and1.fr"; 
		$smtpinfo["port"] = "587"; 
		$smtpinfo["auth"] = true; 
		$smtpinfo["username"] = "webmaster@jlp-immo.com"; 
		$smtpinfo["password"] = "123456789aA"; 
		
		
		$mail_object =& Mail::factory("smtp",$smtpinfo);
		
		
		$corps = "Nom du client : ".params('contact_nom',array(POST))."\n";
		$corps .= "Mail du client : ".params('contact_mail',array(POST))."\n";
		$corps .= "Message : \n". stripslashes(params('contact_message',array(POST)));
		
		if(params('contact_sujet',array(POST)) !='') {
			$_SESSION['contact_resultat'] = "Robot Spotted";
			$_SESSION['contact_res'] = true;
			$_SESSION['contact_pb'] = false;	
		}else{
			if(params('contact_mail',array(POST)) != '' && params('contact_nom',array(POST)) != '') {
			if ($_COOKIE["ContactUse"]!="oui" && $_SESSION['contact_mail'] != params('contact_mail',array(POST)) && $_SESSION['contact_nom'] != params('contact_nom',array(POST))) {
				setcookie("ContactUse","oui",(time()+3600));
				$send_result = $mail_object->send("contact@jlp-immo.com",$headers,$corps);
				if (PEAR::isError($send_result)) 
				{
					setcookie("ContactUse","non",(time()+3600));
				  print "<p>ERROR PEAR :: !! :: ";
				  die($send_result->getMessage()); 
				}
				$_SESSION['contact_mail'] = params('contact_mail',array(POST));
				$_SESSION['contact_nom'] = params('contact_nom',array(POST));
				if($_SESSION["lang"]=='en') {
						$_SESSION['contact_resultat'] =  "Your mail have been send";
					}else{
						$_SESSION['contact_resultat'] = "Message envoy&eacute; avec succ&egrave;s.";
					}
				
				$_SESSION['contact_res'] = true;
				$_SESSION['contact_pb'] = false;
			}else{
				if($_SESSION["lang"]=='en') {
						$_SESSION['contact_resultat'] =  "You can't send 2 messages with the same name and mail without 1 hour between them.";
					}else{
						$_SESSION['contact_resultat'] =  "Vous avez envoyé un message il y a moins d'une heure avec le même nom et la même adresse email.";
					}
				
				$_SESSION['contact_res'] = true;
				$_SESSION['contact_pb'] = false;
			}
			}else{
			if($_SESSION["lang"]=='en') {
						$_SESSION['contact_resultat'] =  "Please fill in your name and email.";
					}else{
						$_SESSION['contact_resultat'] =  "Veuillez remplir votre nom et votre mail.";
					}
			$_SESSION['contact_res'] = true;
			$_SESSION['contact_pb'] = true;
			
			}
		}
		$this->redirect('contact','default');
	}
	
	public function doDemandeInfo ($aParams=array()) {
		$this->setLayout("blankLayout");
		Xtremplate::$vars['ref'] = $aParams['ref'];
		echo "Ref: ".$aParams['ref'];

		if($_SESSION['lang']== 'en' ){
			$this->setTitle("JLP-Immo - Ask for information" );
		}else{
			$this->setTitle("JLP-Immo - Demande d'informations" );
		}
		
		$this->addJavaScript(SCRIPTS.'modules.js');
	}
	
	public function doTestGraph($aParams=array()){
	}
	
	 // $path : path to browse
	// $maxdepth : how deep to browse (-1=unlimited)
	// $mode : "FULL"|"DIRS"|"FILES"
	// $d : must not be defined
	private function searchdir ( $path , $maxdepth = -1 , $mode = "FULL" , $d = 0 )
	{
	   if ( substr ( $path , strlen ( $path ) - 1 ) != '/' ) { $path .= '/' ; }     
	   $dirlist = array () ;
	   if ( $mode != "FILES" ) { $dirlist[] = $path ; }
	   if ( $handle = opendir ( $path ) )
	   {
		   while ( false !== ( $file = readdir ( $handle ) ) )
		   {
			   if ( $file != '.' && $file != '..' )
			   {
				   $file = $path . $file ;
				   if ( ! is_dir ( $file ) ) { if ( $mode != "DIRS" ) { $dirlist[] = $file ; } }
				   elseif ( $d >=0 && ($d < $maxdepth || $maxdepth < 0) )
				   {
					   $result = $this->searchdir ( $file . '/' , $maxdepth , $mode , $d + 1 ) ;
					   $dirlist = array_merge ( $dirlist , $result ) ;
				   }
		   }
		   }
		   closedir ( $handle ) ;
	   }
	   if ( $d == 0 ) { natcasesort ( $dirlist ) ; }
	   return ( $dirlist ) ;
	} 
}
?>