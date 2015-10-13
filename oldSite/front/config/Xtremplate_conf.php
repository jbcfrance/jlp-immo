<?php
/*
Fichier de configuration des regexp d'XTremplate
*/
// BASE :
XTremplate::addREGRule('@{\*(.*)\*}@is', '<?/* $1 */?>', 'notes_1');

// VARIABLES :
XTremplate::addREGRule('@{= *([^}]*) *\| *([^}]*) *}@', '<?php echo $2($1); ?>', 'vars2');
XTremplate::addREGRule('@{= *([^}]*) *}@', '<?php echo $1; ?>', 'vars1');
// END :
XTremplate::addREGRule('@<!-- *end *-->@is', '<?php } ?>', 'end');

// LOOPS :
XTremplate::addREGRule('@<!-- *break *-->@is', '<?php break; ?>', 'break');
XTremplate::addREGRule('@<!-- *continue *-->@is', '<?php continue; ?>', 'continue');
// FOR :
XTremplate::addREGRule('@<!-- *for *: *([^ ]*) +from *: *([^ ]*) +to *: *([^ ]*) +add *: *([^ ]*) *-->@is', '<?php for($1=$2 ; $1<$3 ; $1+=$4) { ?>', 'for1');
XTremplate::addREGRule('@<!-- *for *: *([^ ]*) +from *: *([^ ]*) +to *: *([^ ]*) *-->@is', '<?php for($1=$2 ; $1<$3 ; $1++) { ?>', 'for2');
XTremplate::addREGRule('@<!-- *for *: *([^ ]*) +to *: *([^ ]*) *-->@is', '<?php for($1=0 ; $1<$2 ; $1++) { ?>', 'for3');
XTremplate::addREGRule('@<!-- *for *: *([^ ]*) +to *: *([^ ]*) +add *: *([^ ]*) *-->@is', '<?php for($1=0 ; $1<$2 ; $1+=$3) { ?>', 'for4');
// FOREACH :
XTremplate::addREGRule('@<!-- *foreach *: *(.*) +key *: *(.*) +value *: *(.*) *-->@i', '<?php foreach($1 as $2 => $3) { ?>', 'foreach1');
XTremplate::addREGRule('@<!-- *foreach *: *(.*) +value *: *(.*) *-->@i', '<?php foreach($1 as $2) { ?>', 'foreach2');
XTremplate::addREGRule('@<!-- *foreach *: *(.*) +key *: *(.*) *-->@i', '<?php foreach($1 as $2 => $___null) { ?>', 'foreach3');
// CONDITION :
XTremplate::addREGRule('@<!-- *if *: *(.*) *-->@i', '<?php if ($1) { ?>', 'if');
XTremplate::addREGRule('@<!-- *elseif *: *(.*) *-->@i', '<?php } else if ($1) { ?>', 'elseif1');
XTremplate::addREGRule('@<!-- *else if *: *(.*) *-->@i', '<?php } else if ($1) { ?>', 'elseif2');
XTremplate::addREGRule('@<!-- *else *-->@i', '<?php } else { ?>', 'else');
// SWITCH :
XTremplate::addREGRule('@<!-- *switch *: *([^ ]*) *-->@is', '<?php switch ($1) { default:break; ?>', 'switch');
XTremplate::addREGRule('@<!-- *case *: *([^ ]*) *-->@is', '<?php case $1 : ?>', 'case');
XTremplate::addREGRule('@<!-- *default *-->@is', '<?php default : ?>', 'default');
// INCLUDE :
XTremplate::addREGRule(
'@<!-- *include *: *([^](\-\-\>)[]*) *-->@is',
'<?php include($1); ?>',
'include');
// FUNCTION :
XTremplate::addREGRule('@<!-- *call *: *(.*) *-->@i', '<?php $1; ?>', 'function');
// ALL VARS :
XTremplate::addREGRule('@<!-- *GET_ALL_VARS *-->@is', '<?php $___vars = get_defined_vars(); ?><pre><?php print_r($___vars); ?></pre>', 'get_all');
// BLOCK :
XTremplate::addREGRule(
'@<!-- *block *: *([^](\-\-\>)[]*) +params:([^](\-\-\>)[]*) *-->(.*)<!-- *end_block *-->@isSU',
'<?php function $1 ( $2 ) {?>$3<?php } ?>',
'block2');

XTremplate::addREGRule(
'@<!-- *block *: *([^](\-\-\>)[]*) *-->(.*)<!-- *end_block *-->@isSU',
'<?php function $1 () {?>$2<?php } ?>',
'block1');
// CYCLE :
XTremplate::addREGRule('@<!-- *cycle *: *([^ ]*) *-->@ise', "'<?php
echo \$___CYCLES_'.'$1'.'[\$___inc_'.'$1'.'];
\$___inc_'.'$1'.'++;
\$___inc_'.'$1'.' %= \$___cnt_'.'$1'.';
?>'", 'cycle1');
XTremplate::addREGRule('@<!-- *cycle *: *([^ ]*) *=> *([^]$|(\-\-\>)[]*) *-->@ise', "'<?php
if( !isset(\$___CYCLES_'.'$1'.')) {
	\$___CYCLES_'.'$1'.'	= array(\"'.implode(explode(',','$2'),'\",\"').'\");
	\$___inc_'.'$1'.'		= 0;
	\$___cnt_'.'$1'.'		= count(\$___CYCLES_'.'$1'.');
}
echo \$___CYCLES_'.'$1'.'[\$___inc_'.'$1'.'];
\$___inc_'.'$1'.'++;
\$___inc_'.'$1'.' %= \$___cnt_'.'$1'.';
?>'", 'cycle2');
XTremplate::addREGRule('@<!-- *cycle *: *([^ ]*) *=> *(\$[^]|(\-\-\>)[]*) *-->@ise', "'<?php
if( !isset(\$___CYCLES_'.'$1'.')) {
	\$___CYCLES_'.'$1'.'	= $2;
	\$___inc_'.'$1'.'		= 0;
	\$___cnt_'.'$1'.'		= count(\$___CYCLES_'.'$1'.');
}
echo \$___CYCLES_'.'$1'.'[\$___inc_'.'$1'.'];
\$___inc_'.'$1'.'++;
\$___inc_'.'$1'.' %= \$___cnt_'.'$1'.';
?>'", 'cycle3');



XTremplate::addREGRule(
	'@\{%(.*)%\}@eU',
	'"<?php echo XTremplate_To_JLib(\'$1\') ?>"',
	'JLib_CHANGE_1');


function XTremplate_To_JLib ($sJLibAction){
	$aActions = explode('->', $sJLibAction);
	$aJLibConvert = '';
	
	for($i=0,$iMax=count($aActions);$i<$iMax;$i++){
		$aMatches=array();
		if(ereg('FADE_OUT\(.*\'(.*)\'\.*)', $aActions[$i], $aMatches)){
			$aJLibConvert[] = '[\'fade\',{\'target\':JLib.get(\''.$aMatches[1].'\'),\'from\':100,\'to\':0}]';
		} else if(ereg('CHANGE\(.*\'(.*)\'.*,.*\'(.*)\'\.*)', $aActions[$i], $aMatches)){
			$aJLibConvert[] = '[\'changeContent\',{\'target\':JLib.get(\''.$aMatches[1].'\'),\'url\':\''.HTML_ROOT_PATH.$aMatches[2].'?'.PARAM_GET_CONTENT.'=true\'}]';
		} else if(ereg('FADE_IN\(.*\'(.*)\'\.*)', $aActions[$i], $aMatches)){
			$aJLibConvert[] = '[\'fade\',{\'target\':JLib.get(\''.$aMatches[1].'\'),\'from\':0,\'to\':100}]';
		}
		/*
		MOVE_TO
		SIZE_TO
		
		*/
	}
	return 'new JLib.eventsChain(' . join($aJLibConvert, ',') .');';
}


// LightBox addon for XTremplate :
XTremplate::addREGRule(
	'@<!--\s*light_box\s*:\s*([^\s:]*)(\s+pattern\s*:\s*([^\']*|[^"]*)|\s+(tpl\s*:\s*([^\s:]*))|\s+(width\s*:\s*([^\s:]*))|\s+(height\s*:\s*([^\s:]*))|\s+(params\s*:\s*([^\s:]*))){0,4}\s*-->@isemU',
	'"<?php echo lightBox::getDirect(\'$1\',\'$3\',\'$5\',\'$7\',\'$9\', \'$11\'); ?>"',
	'LightBox'
);

// Traduction de variable selon la session
XTremplate::addREGRule('@<!-- *InitTrad *-->@is','<?php $oTrad=new Traduction();?>','initTrad');

XTremplate::addREGRule('@<!-- *graphDPE *: *(.*) *-->@i','<?php $oGraph = new Graph(\'DPE\',$1);echo $oGraph->traceImg();?>','graphdpe');

XTremplate::addREGRule('@{\@= *([^}]*) *}@', '<?php 
echo $oTrad->getTrad($1); ?>', 'vars3');



/* REGRule des modules */
XTremplate::addREGRule('@<!-- *formRechercheRapide *-->@is', '<?php echo "
<div class=\'ModuleRechercherapide\'>
	<div class=\'ModuleRechercherapideForm\'>".
		moduleRechercheRapide()
	."</div>
</div>";
 ?>', 'formRechercheRapide');
XTremplate::addREGRule('@<!-- *formCallMeBack *: *(.*) *-->@i', '<?php echo "
<div class=\'ModuleCallMeBack\'>
	<div class=\'ModuleCallMeBackForm\'>".
		moduleCallMeBack($1)
	."</div>
</div>";
 ?>', 'formCallMeBack');
?>