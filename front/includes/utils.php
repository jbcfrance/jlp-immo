<?php
function colorSQLRequest ( $sSQL ) {
	$aBlue = array('abs','absolute','access','acos','add','add_months','adddate','admin','after','aggregate','all','allocate','alter','any','app_name','are','array','as','asc','ascii','asin','assertion','at','atan','atn2','audit','authid','authorization','autonomous_transaction','avg','before','begin','benchmark','between','bfilename','bin','binary','binary_checksum','binary_integer','bit','bit_count','bit_and','bit_or','blob','body','boolean','both','breadth','bulk','by','call','cascade','cascaded','case','cast','catalog','ceil','ceiling','char','char_base','character','charindex','chartorowid','check','checksum','checksum_agg','chr','class','clob','close','cluster','coalesce','col_length','col_name','collate','collation','collect','column','comment','commit','completion','compress','concat','concat_ws','connect','connection','constant','constraint','constraints','constructorcreate','contains','containsable','continue','conv','convert','corr','corresponding','cos','cot','count','count_big','covar_pop','covar_samp','cross','cube','cume_dist','current','current_date','current_path','current_role','current_time','current_timestamp','current_user','currval','cursor','cycle','data','datalength','databasepropertyex','date','date_add','date_format','date_sub','dateadd','datediff','datename','datepart','day','db_id','db_name','deallocate','dec','declare','decimal','decode','default','deferrable','deferred','degrees','dense_rank','depth','deref','desc','describe','descriptor','destroy','destructor','deterministic','diagnostics','dictionary','disconnect','difference','distinct','do','domain','double','dump','dynamic','each','else','elsif','empth','encode','encrypt','end','end-exec','equals','escape','every','except','exception','exclusive','exec','execute','exists','exit','exp','export_set','extends','external','extract','false','fetch','first','first_value','file','float','floor','file_id','file_name','filegroup_id','filegroup_name','filegroupproperty','fileproperty','for','forall','foreign','format','formatmessage','found','freetexttable','from','from_days','fulltextcatalog','fulltextservice','function','general','get','get_lock','getdate','getansinull','getutcdate','global','go','goto','grant','greatest','group','grouping','having','heap','hex','hextoraw','host','host_id','host_name','hour','ident_incr','ident_seed','ident_current','identified','identity','if','ifnull','ignore','immediate','in','increment','index','index_col','indexproperty','indicator','initcap','initial','initialize','initially','inner','inout','input','instr','instrb','int','integer','interface','intersect','interval','into','is','is_member','is_srvrolemember','is_null','is_numeric','isdate','isnull','isolation','iterate','java','join','key','lag','language','large','last','last_day','last_value','lateral','lcase','lead','leading','least','left','len','length','lengthb','less','level','like','limit','limited','ln','lpad','local','localtime','localtimestamp','locator','lock','log','log10','long','loop','lower','ltrim','make_ref','map','match','max','maxextents','mid','min','minus','minute','mlslabel','mod','mode','modifies','modify','module','month','months_between','names','national','natural','naturaln','nchar','nclob','new','new_time','newid','next','next_day','nextval','no','noaudit','nocompress','nocopy','none','not','nowait','null','nullif','number','number_base','numeric','nvl','nvl2','object','object_id','object_name','object_property','ocirowid','oct','of','off','offline','old','on','online','only','opaque','open','operator','operation','option','ord','order','ordinalityorganization','others','out','outer','output','package','pad','parameter','parameters','partial','partition','path','pctfree','percent_rank','pi','pls_integer','positive','positiven','postfix','pow','power','pragma','precision','prefix','preorder','prepare','preserve','primary','prior','private','privileges','procedure','public','radians','raise','rand','range','rank','ratio_to_export','raw','rawtohex','read','reads','real','record','recursive','ref','references','referencing','reftohex','relative','release','release_lock','repeat','resource','restrict','result','return','returns','reverse','revoke','right','rollback','rollup','round','routine','row','row_number','rowid','rowidtochar','rowlabel','rownum','rows','rowtype','rpad','rtrim','savepoint','schema','scroll','scope','search','second','section','seddev_samp','separate','sequence','session','session_user','set','sets','share','sign','sin','sinh','size','smallint','some','soundex','space','specific','specifictype','sql','sqlcode','sqlerrm','sqlexception','sqlstate','sqlwarning','sqrt','start','state','statement','static','std','stddev','stdev_pop','strcmp','structure','subdate','substr','substrb','substring','substring_index','subtype','successful','sum','synonym','sys_context','sys_guid','sysdate','system_user','table','tan','tanh','temporary','terminate','than','then','time','timestamp','timezone_abbr','timezone_minute','timezone_hour','timezone_region','to','to_char','to_date','to_days','to_number','to_single_byte','trailing','transaction','translate','translation','treat','trigger','trim','true','trunc','type','ucase','uid','under','union','unique','unknown','unnest','upper','usage','use','user','userenv','using','validate','value','values','var_pop','var_samp','varchar','varchar2','variable','variance','varying','view','vsize','when','whenever','where','with','without','while','with','work','write','year','zone');
	for ( $i=0, $iMax=count($aBlue) ; $i<$iMax ; $i++ )
		$sSQL = str_ireplace(' '.$aBlue[$i].' ',' <span style="color:#0000ff;">' . strtoupper($aBlue[$i]) . '</span> ',$sSQL);
	
	$sStart = substr($sSQL, 0, strpos($sSQL, ' ') );
	if ( in_array(strtolower($sStart), $aBlue) )$sSQL = '<span style="color:#0000ff;">'.strtoupper($sStart).'</span>'.substr($sSQL, strpos($sSQL, ' '));
	
	$sEnd = substr($sSQL, strrpos($sSQL, ' ')+1 );
	if ( in_array(strtolower($sEnd), $aBlue) )$sSQL = substr($sSQL, 0, strrpos($sSQL, ' ')) . ' <span style="color:#0000ff;">'.strtoupper($sEnd).'</span>';
	
	
	$aGreen = array('select','insert','delete','update','show','and','create','drop','rename','replace','truncate','or','xor');
	for ( $i=0, $iMax=count($aGreen) ; $i<$iMax ; $i++ )
		$sSQL = str_ireplace(' '.$aGreen[$i].' ',' <span style="color:#66cc66;">' . strtoupper($aGreen[$i]) . '</span> ',$sSQL);
	
	$sStart = substr($sSQL, 0, strpos($sSQL, ' ') );
	if ( in_array(strtolower($sStart), $aGreen) )$sSQL = '<span style="color:#66cc66;">'.strtoupper($sStart).'</span>'.substr($sSQL, strpos($sSQL, ' '));
	
	$sEnd = substr($sSQL, strrpos($sSQL, ' ')+1 );
	if ( in_array(strtolower($sEnd), $aGreen) )$sSQL = substr($sSQL, 0, strrpos($sSQL, ' ')) . ' <span style="color:#66cc66	;">'.strtoupper($sEnd).'</span>';
	
	return $sSQL;
}

function linkTo($sContainer='',$sAction='default',$sMethod='default',$aParams=array()){
	return sprintf($sContainer, new Links($sAction,$sMethod,$aParams) );
}

function urlTo($sAction='default',$sMethod='default',$aParams=array()){
	return linkTo('%s',$sAction,$sMethod,$aParams);
}

function urlToFile($sFile){
	return HTML_ROOT_PATH.$sFile;
}

function imageTo($sSrc,$aParams=array()){
	$sParams=' ';
	foreach($aParams as $k=>$v)$sParams.=$k.'="'.str_replace('"','\\"',$v).'" ';
	return '<img src="'.HTML_ROOT_PATH.'web/images/'.$sSrc.'"'.$sParams.'/>';
}


define('POST',		1);
define('GET',		2);
define('COOKIE',	4);
define('SESSION',	8);

function params ( $sParam, $aPriority=null, &$sType=null ) {
	if ( $sType!==null ) {
		if ( isset($_POST[ $sParam ]) ) {
			$sType|=POST;
		} else if ( isset($_GET[ $sParam ]) ) {
			$sType|=GET;
		} else if ( isset($_COOKIE[ $sParam ]) ) {
			$sType|=COOKIES;
		} else if ( isset($_SESSION[ $sParam ]) ) {
			$sType|=SESSION;
		}
	}
	
	if ( $aPriority===null ) {
		$aPriority = array(1,2,4,8);
	}
	
	for ( $i=0 ; $i<4 ; $i++ ) {
		if ( $aPriority[$i]==POST ) {
			$aPriority[$i]=$_POST;
		} else if ( $aPriority[$i]==GET ) {
			$aPriority[$i]=$_GET;
		} else if ( $aPriority[$i]==COOKIE ) {
			$aPriority[$i]=$_COOKIE;
		} else if ( $aPriority[$i]==SESSION ) {
			$aPriority[$i]=$_SESSION;
		}
	}
	
	for ( $i=0 ; $i<4 ; $i++ ) {
		$aArraySearch = $aPriority[$i];
		
		if ( isset($aArraySearch[$sParam]) ) {
			return $aArraySearch[$sParam];
		}
	}
}


// Fonction pour faire du LOG :
function MLog($s){
	$rLog = fopen(dirname( dirname(__FILE__) ).'/log.txt', 'a+');
	fwrite( $rLog, print_r($s,true) . "\n" );
	fclose($rLog);
}

function FLog($s,$f){
	$rLog = fopen(dirname( dirname(__FILE__) ).'/'.$f, 'a+');
	fwrite( $rLog, print_r($s,true) . "\n" );
	fclose($rLog);
}

// nettoyage de liens pour les URL :
function cleanURL ($sLink) {
	$aLinkParts = explode('/', $sLink);
	$aLinkRetour = array();
	foreach ( $aLinkParts as $sPart ) {
		$sPart = strtolower( strtr($sPart,'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜ¯àâãäåçèéêëìíîï©£òóôõöùúûü~ÿ','AAAAAACEEEEIIIIOOOOOUUUUYaaaaaceeeeiiiioooooouuuuyyy') );
		$sPart = preg_replace('@([^a-z0-9])@', '', $sPart);
		if ( $sPart != '' ) {
			$aLinkRetour[] = $sPart;
		}
	}
	
	return implode('/', $aLinkRetour);
}

function linkChangeContain ($sLink) {
	$sLink = cleanURL($sLink);
	return 'href="'.HTML_ROOT_PATH.$sLink.'"';
}
