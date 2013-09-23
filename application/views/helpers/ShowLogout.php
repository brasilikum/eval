<?php
class Zend_View_Helper_ShowLogout extends Zend_View_Helper_Abstract{
	public function showLogout($profname = null){
		$html = '<div id="logout">';
		$html .= 'User: ' .$profname;
		$html .= '<form style="display:inline" action="' .$this->view->baseUrl() .'/user/logout">
    				<input type="submit" value="Logout" display: inline>
				 </form>';
		$html .= '</div>';
		return $html;
	}
}