<?php
/**
 * Auteur			: Jean-Baptiste CIEPKA
 * Date				: Janvier 2011
 *
 * Name				: Class modulesAction
 * Description		: Cette classe génére les bloc modulaires des annonces.
 * @templates dir 	: templates/modules
 *
*/
class modulesAction extends modulesAction_BASE {
	/**
	 * Default action for graphs
	 *	date	: 2009-09-15 11:55:42
	 */ 
	public function doDefault ($aParams=array()) {
		$this->setLayout("blankLayout");
	}
	
	public function doDemandeInfo ($aParams=array()) {
		$this->setLayout("blankLayout");
		$this['sAnnonceId']  = $aParams[0];
		$this['sAnnonceRef']  = $aParams[1];
		$this['sTypeForm']  = $aParams[2];
	}
	
	public function doSendToAFriend ($aParams=array()) {
		$this->setLayout("blankLayout");
		$this['sAnnonceId']  = $aParams[0];
		$this['sAnnonceRef']  = $aParams[1];
	}
	
	public function doShare($aParams=array()){
		$this->setLayout("blankLayout");
		$this['sAnnonceId']  = $aParams['annonce_id'];
		$this['sAnnonceRef']  = $aParams['reference'];
	}
	
	public function doPrint ($aParams=array()){
		$this->setLayout("blankLayout");
		include(INCLUDE_PATH.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'mypdf.php');
		
		$this['sAnnonceRef']  = $aParams[1];
		$iAnnonce_id = $aParams[0];
		$oTrad=new Traduction();
		$oQuery = PROGRAMME_ANNONCE::SELECT('programme_annonce.annonce_id','programme_annonce.programme_id','programme.programme_titre','programme.programme_id')->Join_ANNONCE('LEFT')->ON('annonce.annonce_id',ANNONCE::Field('programme_annonce.annonce_id'))->Join_PROGRAMME('LEFT')->ON('programme.programme_id',PROGRAMME::Field('programme_annonce.programme_id'))->WHERE('programme_annonce.annonce_id',$iAnnonce_id)->exec();
		if(!empty($oQuery)){
			foreach($oQuery as $kProg=>$vProg){
				$bAnnonceInProg = true;
				$this['iProgramme_id'] = $vProg->getProgramme_id();
			}
		}else{
			$bAnnonceInProg = false;
			$this['iProgramme_id'] = '';
		}
		$oQuery = ANNONCE::SELECT()->Join_NEGOCIATEUR('LEFT')->ON('annonce_negociateur_id',NEGOCIATEUR::Field('negociateur_id'))->Join_AGENCE('LEFT')->ON('annonce_agence_id',AGENCE::Field('agence_id'))->WHERE('annonce_id',$iAnnonce_id)->exec();
		$this['annonce']  = $oQuery;
		/*echo"<pre>";print_r($oQuery);echo"</pre>";die();*/
		foreach($oQuery as $kQuery=>$vQuery) {
			$this->setTitle("JLP-Immo - ".$oTrad->getTrad($vQuery->gettypeBien())." ".$vQuery->getvillePublique()."" );
			$this['sTitleH2'] = $oTrad->getTrad($vQuery->gettypeBien())." ".$vQuery->getvillePublique();
			if($bAnnonceInProg){
				$this['sClassBandeau'] = 'BandeauProgNeuf';
			}elseif($vQuery->getcoupDeCoeur() == 'oui'){
				$this['sClassBandeau'] = 'BandeauCdc';
			}else{
				$this['sClassBandeau'] = 'BandeauNormal';
			}
			
			// Génération du PDF
			$pdf =new MYPDF('P','mm','A4');
			$pdf->AddPage();
			$aW = array('210','105','52.5','26');
			$aH = array('title'=>'40','line'=>'15');
			$pdf->SetTextColor(255,255,255);
			$pdf->SetFillColor(81,15,21);
			$pdf->SetLineWidth('0.2');
			$pdf->Line(105,10,200,10);
			$pdf->SetXY(110,10);
			// logo
			$pdf->Image("web/images/logo.png",105,13,90,0,'PNG');
			$pdf->SetLineWidth('0.2');
			$pdf->Line(10,36,200,36);
			$pdf->SetXY(10,38);
			$pdf->SetFont('Arial','',18);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetFillColor(159,21,29);
			$pdf->Cell(150,10,strtoupper(utf8_decode($oTrad->getTrad($vQuery->gettypeBien())." ".$vQuery->getvillePublique())),0,0,'L',true);
			if(round($vQuery->getmontant())==$vQuery->getmontant()) {
				$pdf->Cell(40,10,strtoupper(number_format($vQuery->getmontant(),0,","," ").' '.chr(128).' *'),0,0,'R',true);	
			}else{
				$pdf->Cell(40,10,strtoupper(number_format($vQuery->getmontant(),2,","," ").' '.chr(128).' *'),0,0,'R',true);	
			}
			$pdf->Line(10,48,200,48);
			$aImg = explode('|',$vQuery->getlistePhotoOrig());
			$pdf->SetXY(10,55);
			if(isset($aImg[0])){
				$pdf->Image("web/import/annonces/".$aImg[0],10,55,92.5,0,'JPEG');
			}else{
				$pdf->Cell(90,10,"",0,2,'L',false);
			}
			$pdf->SetXY(100,55);
			if(isset($aImg[1])){
				$pdf->Image("web/import/annonces/".$aImg[1],107.5,55,92.5,0,'JPEG');
			}else{
				$pdf->Cell(90,10,"",0,2,'L',false);
			}
			$sConso = $vQuery->getconsommationenergie();
			$aDpe = array(
			"A"=>array("min"=>"0","max"=>"50"),
			"B"=>array("min"=>"51","max"=>"90"),
			"C"=>array("min"=>"91","max"=>"150"),
			"D"=>array("min"=>"151","max"=>"230"),
			"E"=>array("min"=>"231","max"=>"330"),
			"F"=>array("min"=>"331","max"=>"450"),
			"G"=>array("min"=>"451","max"=>"10000"));
			$sTempPos = '';
			foreach($aDpe as $k=>$v){
				if($sConso>=$v['min'] && $sConso<=$v['max']){
					$sTempPos = $k;
					continue;
				}
			}
			
			if(isset($sConso) && !empty($sConso) && $sConso!=0){
				$pdf->Image('web/images/dpe/graph_mini_dpe_'.strtolower($sTempPos).'.png',10,185,0,0);
			}
			$pdf->Line(10,125,199.8,125);
			$pdf->SetXY(10,127);
			$pdf->SetFont('Arial','',18);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetFillColor(159,21,29);
			$pdf->Cell(190,10,strtoupper(utf8_decode("Description")),0,0,'L',true);
			$pdf->Line(10,137,199.8,137);
			$pdf->SetXY(10,140);
			$pdf->SetFont('Arial','',10);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetDrawColor(255,255,255);
			$pdf->SetFillColor(200,200,200);
			$pdf->MultiCell(95,6,utf8_decode($vQuery->gettexte()),0,'J',false);
			$pdf->SetXY(115,140);
			$pdf->Cell(40,5,utf8_decode($oTrad->getTrad("reference")),1,0,'L',true);
			$pdf->Cell(40,5,utf8_decode($vQuery->getreference()),1,0,'R',false);
			$pdf->SetXY(115,145);
			$pdf->Cell(40,5,utf8_decode($oTrad->getTrad("chambres")),1,0,'L',true);
			$pdf->Cell(40,5,utf8_decode($vQuery->getchambres()),1,0,'R',false);
			$pdf->SetXY(115,150);
			$pdf->Cell(40,5,utf8_decode($oTrad->getTrad("nbSallesDEau")),1,0,'L',true);
			$pdf->Cell(40,5,utf8_decode($vQuery->getnbSallesDEau()+$vQuery->getsdb()),1,0,'R',false);
			$pdf->SetXY(115,155);
			$pdf->Cell(40,5,utf8_decode($oTrad->getTrad("nbParking")),1,0,'L',true);
			$pdf->Cell(40,5,utf8_decode($vQuery->getnbParking()),1,0,'R',false);
			$pdf->SetXY(115,160);
			$pdf->Cell(40,5,utf8_decode($oTrad->getTrad("surface")),1,0,'L',true);
			$pdf->Cell(40,5,utf8_decode($vQuery->getsurface()."m²"),1,0,'R',false);
			$pdf->SetXY(115,165);
			$pdf->Cell(40,5,utf8_decode($oTrad->getTrad("surfaceTerrain")),1,0,'L',true);
			$pdf->Cell(40,5,utf8_decode($vQuery->getsurfaceTerrain()."m²"),1,0,'R',false);
			$pdf->SetXY(115,170);
			$pdf->Cell(40,5,utf8_decode($oTrad->getTrad("ascenseur")),1,0,'L',true);
			$pdf->Cell(40,5,utf8_decode($vQuery->getascenseur()),1,0,'R',false);
			$pdf->SetXY(115,175);
			$pdf->Cell(40,5,utf8_decode($oTrad->getTrad("nbGarages")),1,0,'L',true);
			$pdf->Cell(40,5,utf8_decode($vQuery->getnbGarages()),1,0,'R',false);
			$pdf->SetXY(115,180);
			$pdf->Cell(40,5,utf8_decode($oTrad->getTrad("typeChauffage")),1,0,'L',true);
			$pdf->Cell(40,5,utf8_decode($vQuery->gettypeChauffage()),1,0,'R',false);
			$pdf->SetDrawColor(0,0,0);
			$pdf->SetXY(10,195);
			$pdf->Line(10,193,199.8,193);
			$pdf->SetFont('Arial','',18);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetFillColor(159,21,29);
			$pdf->Cell(190,10,utf8_decode("OBSERVATIONS"),0,0,'L',true);
			$pdf->Line(10,205,199.8,205);
			$pdf->SetXY(10,212);
			$pdf->SetTextColor(0,0,0);
			$pdf->Cell(190,55,"",1,0,'L',false);
			// Titre
			/*$pdf->SetLineWidth('2');
			$pdf->Line(0,100,105,100);
			$pdf->SetLineWidth('0.2');
			$pdf->Line(105,100,210,100);*/
			// Bloc 1
			
			// Detail
			
			// Bloc 2
			
			// Bloc 3
			
			
			
			
			/*$pdf->SetFont('Arial','B',18);
			$pdf->SetXY(50,105);
			$pdf->Cell($aW[1],$aH['title'],utf8_decode($vQuery->getvillePublique()),0,2,'L',false);
			$pdf->Cell(50,30,strtoupper($vQuery->getAgence(0)->getenseigneAgence()),0,2,'L',false);
			$pdf->Cell(50,30,$vQuery->getNegociateur(0)->getprenomNegociateur(),0,2,'L',false);
			$pdf->Cell(50,30,strtoupper($vQuery->getNegociateur(0)->getnomNegociateur()),0,2,'L',false);
			$pdf->Cell(50,30,utf8_decode("Tél: 04-50-78-27-44"),0,2,'L',false);
			$pdf->Cell(80,30,utf8_decode($oTrad->getTrad($vQuery->gettypeBien())).',',0,0,'L',false);
			$pdf->SetFont('Arial','',16);
			$pdf->Cell(50,30,$vQuery->getpieces().utf8_decode($oTrad->getTrad('pieces')),0,2,'L',false);
			$pdf->Cell(50,30,utf8_decode('Réf : ').$vQuery->getreference(),0,2,'L',false);
			if(round($vQuery->getmontant())==$vQuery->getmontant()) {
				$pdf->Cell(165,31,number_format($vQuery->getmontant(),0,","," ").' '.chr(128).' FAI*',0,2,'R',false);	
			}else{
				$pdf->Cell(165,31,number_format($vQuery->getmontant(),2,","," ").' '.chr(128).' FAI*',0,2,'R',false);	
			}*/
			//$pdf->Image(urlTo("graphs/DPEb/".$vQuery->getconsommationenergie()),0,0,0,0,'PNG');
			//$aImg = explode('|',$vQuery->getlistePhotoOrig());
			
			//$pdf->Image("web/images/annonces/".$vQuery->getphotoThumb(),0,0,0,0,'JPEG');
			
			/*$pdf->SetXY(20,370);
			$pdf->Cell(30,30," ",0,0,'L',true);
			$pdf->Cell(80,30,"Commentaires",0,0,'L',false);
			$pdf->SetXY(20,410);
			$pdf->SetFont('Arial','',12);
			$pdf->MultiCell(0,20,utf8_decode($vQuery->gettexte()),0,'J',false);
			$pdf->SetXY(20,600);*/
			/*$pdf->SetFont('Arial','',18);
			$pdf->Cell(30,30," ",0,0,'L',true);
			$pdf->Cell(80,30,"Description du bien",0,0,'L',false);*/
			/*$aAgence = array(
				1=>"http://".$vQuery->getAgence(0)->getsiteWebAgence(),
				2=>$vQuery->getAgence(0)->getadresseAgence(),
				3=>$vQuery->getAgence(0)->getcodePostalAgence()
			);
			$pdf->SetXY(20,600);
			$pdf->MultiCell(100,15,"1\n2\n3",0,'L',false);
			$pdf->SetXY(120,600);
			$pdf->MultiCell(100,15,"1\n2\n3",0,'L',false);
			$pdf->SetXY(220,600);
			$pdf->MultiCell(100,15,"1\n2\n3",0,'L',false);*/
			$pdf->Output();
			
			
			
			
			
		}
		
		/*
		$pdf =new PDF('P','pt','A4',$aDataBiens['bien_num_mandat']);
			$pdf->setBienNumMandat($aDataBiens['bien_num_mandat']);
			$pdf->setTypeFiche('Vitrine');
			$pdf->AddPage();
			$pdf->SetFont('Arial','B',10);
			$pdf->SetTextColor(255,255,255);
/*			$pdf->SetXY(-55,20);
			$pdf->Cell(50,10,strtoupper($aDataPole['pole_enseigne']),0,2,'R');
			$pdf->SetFont('Arial','',9);
			$pdf->Cell(50,5,$aDataPole['pole_nom_gerant'],0,2,'R');
			$pdf->Cell(50,5,utf8_decode('Tél. ').$aDataPole['pole_tel'],0,2,'R');*/
			/*$pdf->SetXY(-256,16);
			$pdf->SetFont('Arial','B',18);
			$pdf->Cell(230,32,strtoupper($aDataBiens['bien_adresse_ville']),0,2,'R');
			$pdf->SetXY(-306,52);
			$pdf->Cell(280,32,$aDataBiens['bien_type'].', '.$aDataBiens['bien_nb_piece'].' pièces',0,2,'R');
			$pdf->SetXY(37,128);
			//$pdf->SetFillColor(222);
			//$pdf->Cell(536,365,'',0,2,'L');
			list($w,$h)=getimagesize($aDataPhoto['photo_file']);
			$pdf->Image(BASE_PATH.'images/bien/'.$aDataBiens['bien_id'].'/pdf/vitrine/'.$aDataPhoto['photo_file']);
			$pdf->Image(BASE_PATH.'images/pdf/vitrine/cartouche.png',391,447);
			/*CARTOUCHE DE PRIX*/
			/*$pdf->SetXY(391,450);
			$pdf->SetFont('Arial','B',16);
			if(round($aDataBiens['bien_prix_vente'])==$aDataBiens['bien_prix_vente']) {
				$pdf->Cell(165,31,number_format($aDataBiens['bien_prix_vente'],0,","," ").' '.chr(128).' FAI*',0,2,'R');	
			}else{
				$pdf->Cell(165,31,number_format($aDataBiens['bien_prix_vente'],2,","," ").' '.chr(128).' FAI*',0,2,'R');	
			}
			$pdf->SetFont('Arial','B',12);
			$pdf->Cell(165,40,'Réf : '.$aDataBiens['bien_num_mandat'],0,2,'R');
			$pdf->SetXY(10,550);
			$pdf->SetFont('Arial','',10);
			$pdf->SetTextColor(0,0,0);
			$pdf->Cell(575,220,'',0,0,'L');
			$pdf->Write(15,$aDataBiens['bien_description']);
			$pdf->SetTextColor(255,255,255);
			//echo $aDataBiens['bien_description();
			$pdf->Output('FicheVitrine_'.$aDataBiens['bien_num_mandat'].'.pdf','I');
		*/
		
		
	}
	
}
?>