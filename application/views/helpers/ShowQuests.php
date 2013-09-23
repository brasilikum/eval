<?php
class Zend_View_Helper_ShowQuests extends Zend_View_Helper_Abstract{
	public function showQuests($quests = null){
		$today = date("Y-m-d");
		$html = '';
		if($quests != null){
			$html .= '<ul>';
			foreach($quests as $quest){
				if($quest->expirationDate >= $today){
					$html .= '<li>';	
				}else{
					$html .= '<li class="expired">';
				}
				$html .= '<h3>' .$quest->courseName .'</h3>';
				$html .= '<div>' .$quest->category .'</div>';
				$html .= '<div>' .$this->view->formattedDate($quest->expirationDate) .'</div>';
				$html .= '</li>';
			}
			$html .= '</ul>';
		}else{
			$html .= 'Keine Umfragen getätigt';
		}
		return $html;
	}
}