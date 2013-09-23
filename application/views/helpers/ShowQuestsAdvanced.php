<?php
class Zend_View_Helper_ShowQuestsAdvanced extends Zend_View_Helper_Abstract{
	public function showQuestsAdvanced($quests = null,$userTable = null){
		$today = date("Y-m-d");
		$html = '';
		$prof;
		if($quests != null){
			$html .= '<ul>';
			foreach($quests as $quest){
				if($quest->category == 'VL'){
					$quest->category = 'Vorlesung';
				}elseif($quest->category == 'PR'){
					$quest->category = 'Praktikum';
				}
				$prof = $userTable->find($quest->profId)->current();
				if($quest->expirationDate >= $today){
					$html .= '<li>';
					$html .= '<h3>' .$quest->courseName .'</h3>';
					$html .= '<div>' .$quest->category .'</div>';
					$html .= '<div>' .$prof->fullName .'</div>';
					$html .= '<div>Läuft bis: ' .$this->view->formattedDate($quest->expirationDate) .'</div>';
					$html .= '<div id="running"> Ergebnisse können erst nach Abschluss angezeigt werden</div>';
					$html .= '</li>';	
				}else{
					$html .= '<li class="expired">';
					$html .= '<h3>' .$quest->courseName .'</h3>';
					$html .= '<div>' .$quest->category .'</div>';
					$html .= '<div>' .$prof->fullName .'</div>';
					$html .= '<div id="show"><a href="' .$this->view->baseUrl() . '/admin/secretary/show?id='.$quest->id .'"><button>Antworten anzeigen</button></a></div>';
					$html .= '<div id="csv"><a href="' .$this->view->baseUrl() . '/admin/secretary/csv?id='.$quest->id .'"><button>Ergebnisse als CSV herunterladen</button></a></div>';
					$html .= '<div id="delete"><a href="' .$this->view->baseUrl() . '/admin/secretary/delete?id='.$quest->id .'"><button>Umfrage löschen</button></a></div>';
					$html .= '</li>';
				}
			}
			$html .= '</ul>';
		}else{
			$html .= 'Keine Umfragen getätigt';
		}
		return $html;
	}
}