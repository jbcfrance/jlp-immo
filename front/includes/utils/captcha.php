<?php
/* Script Captcha – Nassim Kacha – http://lebricabrac.wordpress.com */

/* Note :
- Il faut installer la bibliothèque GD de PHP (voir dans ‘php.ini’).

- Notre captcha sera insensible à la case.
*/

session_start(); // Ouverture de session

/* Fonction qui génère le code du captcha */
function getCode($longueur) {
$caracteres = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ'; // Jeu de caractères utilisé pour générer le code du captcha.
$code = ''; // Initialisation d’une chaine vide
for ($i=0; $i<$longueur; $i++) {
$code .= $caracteres{ mt_rand( 0, strlen($caracteres)-1 ) };
}
return $code;
}

/* Génération d’un code de 5 caractères */
$code = getCode(5); // valeur modifiable

/* Stockage de la valeur du captcha */
$_SESSION['captcha'] = md5($code);

/* Récupération de chacun des caractères pour l’affichage du captcha */
$char1 = substr($code,0,1);
$char2 = substr($code,1,1);
$char3 = substr($code,2,1);
$char4 = substr($code,3,1);
$char5 = substr($code,4,1);

/* Police d’éciture */
$font = "../../web/utils/OptimusPrinceps.ttf"; // A modifier selon votre fichier ttf

/* Création de l’image du captcha */
$image = imagecreatefrompng('../../web/utils/captcha.png');

/* Création de la couleur du code */
$couleur = imagecolorallocate($image, 255,0,0); // Noir, peut être modifié

/* Insertion du code dans le captcha */
imagettftext($image, 16, 0, 5, 22, $couleur, $font, $char1);
imagettftext($image, 16, 15, 25, 22, $couleur, $font, $char2);
imagettftext($image, 16, 0, 45, 22, $couleur, $font, $char3);
imagettftext($image, 16, 20, 65, 22, $couleur, $font, $char4);
imagettftext($image, 16, -10, 80, 22, $couleur, $font, $char5);

/* Header pour indiquer au browser qu’il s’agit d’une image PNG */
header('Content-Type: image/png');

/* Envoie de l’image au browser */
imagepng($image);

/* Destruction de l’image après envoi pour optimiser l’utilisation de la mémoire */
imagedestroy($image);
?>