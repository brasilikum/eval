<?php
class Zend_View_Helper_ShowHeader extends Zend_View_Helper_Abstract{
	public function showHeader(){
		$html = '<header>
					<h1>EvalOs</h1>
					<h2>Evaluationssystem für Lehrveranstaltungen der Hochschule Osnabrück</h2>
				</header>';
		return $html;
	}
}