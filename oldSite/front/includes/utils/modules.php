<?php 

if(isset($_POST["action"])) {
	switch($_POST["action"]) {
		case "DemandeInfo" : 
			require_once 'Mail.php';
			
			
			
			$smtpinfo["host"] = "auth.smtp.1and1.fr"; 
			$smtpinfo["port"] = "25"; 
			$smtpinfo["auth"] = true; 
			$smtpinfo["username"] = "webmaster@jlp-immo.com"; 
			$smtpinfo["password"] = "123456789aA";
			
			$mail_object =& Mail::factory("smtp",$smtpinfo);
			switch($_POST['type_form']){
				case 'annonce':
					$headers["From"] ="Message automatique du site JLP IMMO <site@jlp-immo.com>";
					$headers["To"] = "To: Contact Agence JLP-Immo <contact@jlp-immo.com>"; 
					$headers["Subject"] ='Site JLP-IMMO : Demande d\'information - Annonce';
					$headers["Reply-To"] = "NO REPLY <noreply@jlp-immo.com>"; 
					$headers["Content-Type"] = "text/plain; charset=utf-8";
					$corps="".$_POST["demandeInfo_client_nom"] ." vous demande des informations sur l'annonce référence :  ".$_POST["annonce_reference"]." - lien : http://www.jlp-immo.com/annonce/".$_POST["annonce_id"].".\n Voici son addresse mail : ".$_POST["demandeInfo_client_mail"]." et son téléphone ".$_POST["demandeInfo_client_tel"]."";
				break;
				case 'progneuf':
					$headers["From"] ="Message automatique du site JLP IMMO <site@jlp-immo.com>";
					$headers["To"] = "To: Contact Agence JLP-Immo <contact@jlp-immo.com>"; 
					$headers["Subject"] ='Site JLP-IMMO : Demande d\'information - Programme neuf';
					$headers["Reply-To"] = "NO REPLY <noreply@jlp-immo.com>"; 
					$headers["Content-Type"] = "text/plain; charset=utf-8";
					$corps="".$_POST["demandeInfo_client_nom"] ." vous demande des informations sur le programme neuf :  http://www.jlp-immo.com/prgneuf/".$_POST["annonce_reference"].".\n Voici son addresse mail : ".$_POST["demandeInfo_client_mail"]." et son téléphone ".$_POST["demandeInfo_client_tel"]."";
				break;	
			}
			
					$send_result = $mail_object->send("contact@jlp-immo.com",$headers,$corps);
					//mail("jbcfrance@gmail.com",$titre,$corps,$headers);
			//header('Location: http://jlpimmo.dyndns.org/annonce/'.$_POST['annonce_id']);
			echo "Message envoyé, merci";

		break;
		case "SendToAFriend" : 
			require_once 'Mail.php';
			$headers["From"] = "From:".$_POST["sendToAFriend_client_nom"]."<".$_POST["sendToAFriend_client_mail"].">";
			$headers["To"] = "To: <".$_POST["sendToAFriend_client_friendmail"].">"; 
			$headers["Subject"] = $_POST["sendToAFriend_client_nom"]." vous envoi une annonce du site www.jlp-immo.com";
			$headers["Content-Type"] =  "text/plain; charset=utf-8";
			$smtpinfo["host"] = "auth.smtp.1and1.fr"; 
			$smtpinfo["port"] = "25"; 
			$smtpinfo["auth"] = true; 
			$smtpinfo["username"] = "webmaster@jlp-immo.com"; 
			$smtpinfo["password"] = "123456789aA";
			
			$mail_object =& Mail::factory("smtp",$smtpinfo);
			$corps = "Lien/link: http://www.jlp-immo.com/annonce/".$_POST["sendToAFriend_annonce_id"]."\n";
			$corps .= $_POST["sendToAFriend_client_message"];
			$send_result = $mail_object->send($_POST["sendToAFriend_client_friendmail"],$headers,$corps);
			//header('Location: http://jlpimmo.dyndns.org/annonce/'.$_POST['sendToAFriend_annonce_id']);
			echo "Message envoyé, merci";			
			
		break;
	
	}
	
}
function moduleRechercheRapide() {
		$oTrad = new traduction();
		$aTypeBien = array();
		$aLocalite = array();
		$oQuery = ANNONCE::SELECT('typeBien','VillePublique')->exec();
		foreach($oQuery as $vField) {
			if( !in_array($vField->getTypeBien(),$aTypeBien))
				$aTypeBien[] = $vField->getTypeBien();
			if( !in_array($vField->getVillePublique(),$aLocalite))
				$aLocalite[] = $vField->getVillePublique();
		}
		
		$oForm = new Form();
		$oForm->setTpl("templates/modules/rechercheRapide/form.htm");
		$oForm->addParam ( 'name', 'formRapide'  );

		$oForm->addParam ( 'class', 'classForm'  );
		$oForm->addParam ( 'submitValue',$oTrad->getTrad('FastSubmit'));
		$oForm->addParam ( 'submitOnClick','this.form.submit();');
		
		$oForm->setAction( linkTo('%s', 'acheter/traitementresultatsrapide') );

		$oForm->setMethod('post');
		$oForm->start();
		$aSelect = new Select($oTrad->getTrad('typeBien'), 				array('name'=>'typeDeBien_fast'));
		$aSelect->setTpl("templates/modules/rechercheRapide/input_select.htm");
		$aSelect->start();
		new Option('', $oTrad->getTrad('typeBien'));
		foreach($aTypeBien as $vTypeBien) {
			new Option($vTypeBien,ucfirst($oTrad->getTrad($vTypeBien)));
		}
		$aSelect->close();
		
		$bSelect = new Select($oTrad->getTrad('villePublique'), 				array('name'=>'localite_fast'));
		$bSelect->start();
		$bSelect->setTpl("templates/modules/rechercheRapide/input_select.htm");
		new Option('', $oTrad->getTrad('villeAAfficher'));
		$aArrayInit = $aLocalite;
		$aArrayFinal = array();
		foreach($aArrayInit as $k=>$v){
			$sResult = strtolower($v);
			$sResult = ucwords($sResult);
			foreach (array('-', '\'') as $delimiter) {
			  if (strpos($sResult, $delimiter)!==false) {
				$sResult =implode($delimiter, array_map('ucfirst', explode($delimiter, $sResult)));
			  }
			}
			if( !in_array($sResult,$aArrayFinal)){
					$aArrayFinal[] = $sResult;	
			}
		}
		sort($aArrayFinal);
		$aLocalite = array();
		$aLocalite = $aArrayFinal;
		foreach($aLocalite as $vLocalite) {
			new Option($vLocalite,$vLocalite);
		}
		$bSelect->close();
		
		$cSelect = new Select($oTrad->getTrad('montant'), 				array('name'=>'montant_fast'));
		$cSelect->start();
		$cSelect->setTpl("templates/modules/rechercheRapide/input_select.htm");
		new Option('', $oTrad->getTrad('montant'));
		new Option('0-100000',"0-100000 &euro;");
		new Option('100000-200000',"100000-200000 &euro;");
		new Option('200000-300000',"200000-300000 &euro;");
		new Option('300000-400000',"300000-400000 &euro;");
		new Option('400000-500000',"400000-500000 &euro;");
		new Option('500000-600000',"500000-600000 &euro;");
		new Option('600000-700000',"600000-700000 &euro;");
		new Option('700000-+',"700000 &euro; et plus");
		$cSelect->close();
		$oForm->close();	
		return $oForm;
		 
}

function moduleCallMeBack ($reference) {
		$oTrad = new traduction();
		$oForm = new Form();
		$oForm->setTpl("templates/modules/callMeBack/form.htm");
		$oForm->addParam ( 'name', 'formCallMeBack'  );

		$oForm->addParam ( 'class', 'classForm'  );
		$oForm->addParam ( 'submitValue',$oTrad->getTrad('CallSubmit'));
		$oForm->addParam ( 'formWidth','200px');
		$oForm->addParam ( 'submitOnClick','javascript:validCallMeBack();return false;');
		
		$oForm->setMethod( 'get');
		$oForm->setAction( linkTo('%s', '#ModulesCallMeBack') );

		$oForm->start();
		new text	('Votre nom',array('name'=>'client_nom','id'=>'callMeBack_client_nom','size'=>'20'),"templates/modules/callMeBack/input_text.htm");
		new text	('Numéros de téléphone',array('name'=>'client_tel','id'=>'callMeBack_client_tel','size'=>'20'),"templates/modules/callMeBack/input_text.htm");
		new hidden	('',array('name'=>'reference','size'=>'20','id'=>'callMeBack_reference','value'=>$reference),"templates/modules/callMeBack/input_hidden.htm");
		new captcha ('',array('name'=>'captchaCallMeBack','id_input_captcha'=>'callMeBack_captcha'),"templates/modules/callMeBack/input_captcha.htm");
		$oForm->close();	
		return $oForm;
}
?>