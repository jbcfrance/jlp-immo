<?php
$GLOBALS['ACTIONS'] = array (
  'defaultaction' => 'default',
  'accueilaction' => 'accueil',
  'passerelleaction' => 'passerelle',
  'contactaction' => 'contact',
  'programmeneufaction' => 'programmeneuf',
  'annonceaction' => 'annonce',
  'acheteraction' => 'acheter',
  'mentionlegaleaction' => 'mentionlegale',
  'graphsaction' => 'graphs',
  'modulesaction'=>'modules',
);

foreach ( $GLOBALS['ACTIONS'] as $sClass => $sFile ) {
	$GLOBALS['AUTO_LOAD'][ $sClass ]			= BASE_PATH.'actions/' . $sFile . '.php';
	$GLOBALS['AUTO_LOAD'][ $sClass.'_base' ]	= BASE_PATH.'actions/bases/' . $sFile.'_base.php';
}
?>