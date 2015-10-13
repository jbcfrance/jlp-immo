<?php
/**
 * Auteur			: Jean-Baptiste CIEPKA
 * Date				: Janvier 2011
 *
 * Name				: Class graphsAction
 * Description		: Cette classe génére les graphiques DPE et GES des annonces.
 * @templates dir 	: templates/graphs
 *
*/
class graphsAction extends graphsAction_BASE {
	/**
	 * Default action for graphs
	 *	date	: 2009-09-15 11:55:42
	 */ 
	public function doDefault ($aParams=array()) {
		$this->setLayout("imgLayout");
		header('Content-type: image/png');
		$oGraph = new Graph($aParams['type_graph'],$aParams['data_graph']);
		$this['oGraph']  = $oGraph->traceImg();
	}
	
}
?>