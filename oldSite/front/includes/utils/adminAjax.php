<?php

if(isset($_POST["action"])) {
	switch($_POST["action"]) {
		case "delPrgNeuf":
			if(isset($_GET["ImgName"])){
				unlink("/../../web/images/programmeneufs/".$_GET['idPrgNeuf']."/Mini_".$_GET["ImgName"]);
				unlink("/../../web/images/programmeneufs/".$_GET['idPrgNeuf']."/Pre_".$_GET["ImgName"]);
				unlink("/../../web/images/programmeneufs/".$_GET['idPrgNeuf']."/Big_".$_GET["ImgName"]);
				unlink("/../../web/images/programmeneufs/".$_GET['idPrgNeuf']."/".$_GET["ImgName"]);
			}
			break;
		case "delImgActu":
			if(isset($_POST["ImgName"])){
				if(unlink("/../../web/images/actualite/Mini/".$_POST["ImgName"])){
				echo "OK";
				}else{
				echo "down";
				}
				unlink("/../../web/images/actualite/Big/".$_POST["ImgName"]);
				
			}
			break;
		case "delImgPres":
			if(isset($_GET["ImgName"])){
				unlink("/../../web/images/presentation/Mini/".$_GET["ImgName"]);
				unlink("/../../web/images/presentation/Big/".$_GET["ImgName"]);
			}
			break;
	}
}
?>