<?php
if(isset($_GET["ImgName"])){
	
	unlink("../../web/images/programmeneufs/".$_GET['idPrgNeuf']."/Mini_".$_GET["ImgName"]);
	unlink("../../web/images/programmeneufs/".$_GET['idPrgNeuf']."/Pre_".$_GET["ImgName"]);
	unlink("../../web/images/programmeneufs/".$_GET['idPrgNeuf']."/Big_".$_GET["ImgName"]);
	unlink("../../web/images/programmeneufs/".$_GET['idPrgNeuf']."/".$_GET["ImgName"]);
	
}
?>