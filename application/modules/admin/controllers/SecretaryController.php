<?php

class Admin_SecretaryController extends Zend_Controller_Action
{
	protected $answerToQuestionTable;
	protected $questionTable;
	protected $questionnaireTable;
	protected $userTable;
	protected $submittersTable;

	//initiating database
	public function init()
	{
		$this->answerToQuestionTable = new Application_Model_DbTable_AnswerToQuestionTable();
		$this->questionnaireTable = new Application_Model_DbTable_QuestionnaireTable();
		$this->questionTable = new Application_Model_DbTable_QuestionTable();
		$this->userTable = new Application_Model_DbTable_UserTable();
		$this->submittersTable = new Application_Model_DbTable_SubmittersTable();
		$this->profRow = $this->userTable
							 ->fetchAll($this->userTable
							 ->select()
							 ->where('id = ?',Application_Plugin_auth_AccessControl::getUserId()))
							 ->current();
		$this->view->profName = $this->profRow->fullName;	
	}

	public function indexAction()
	{		
		$quests = $this->questionnaireTable->fetchAll($this->questionnaireTable
													->select()
													->order('expirationDate DESC'));

		$userTable = $this->userTable;

		$this->view->userTable = $userTable;
		$this->view->quests = $quests;
	}

	//deleting a questionnaire
	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		$where = $this->questionnaireTable->find($id);
		$row = $where->current();		
		$row->delete();		
		$this->_redirect('/admin/secretary');
	}

	//a function that displays questionnaires which are finished
	public function showAction()
	{
		$counter;
		$html;
		$id = $this->getRequest()->getParam('id');

		$where = $this->questionnaireTable->find($id);
		$row = $where->current();

		$answersToQuestion = $row->findDependentRowset('Application_Model_DbTable_AnswerToQuestionTable');
		$questions = $this->questionTable->fetchAll();

		foreach ($questions as $question) {
			if($question->category == $row->category){
				if($question->type == 'radio' || $question->type == 'musel'){					
					$value = 0;
				}
				$counter = 0;
				echo '<h3>'.$question->text.'</h3>';
				foreach ($answersToQuestion as $answerToQuestion) {	
					if($answerToQuestion->questionId == $question->id){
						if($answerToQuestion->answertext){
							if($answerToQuestion->questionId == 82 || $answerToQuestion->questionId == 96){
								if($answerToQuestion->questionId == 82){
									$note = $this->questionTable
							  					 ->fetchAll($this->answerToQuestionTable->select()
						     					 ->where('answerhash = ?', $answerToQuestion->answerhash)
						     					 ->where('questionId = ?', '81'))
						     					 ->current()->answernumber;
									echo $answerToQuestion->answertext.' ('.$note.')';
								}else{
									$note = $this->questionTable
							  					 ->fetchAll($this->answerToQuestionTable->select()
						     					 ->where('answerhash = ?', $answerToQuestion->answerhash)
						     					 ->where('questionId = ?', '95'))
						     					 ->current()->answernumber;
									echo  $answerToQuestion->answertext.' ('.$note.')';
								}
							}else{echo $answerToQuestion->answertext;}
						}else{
							if($answerToQuestion->answernumber > 0){
								$counter++;
								$value = $value + $answerToQuestion->answernumber;
							}
						}
					}	
				 }
				 if($counter > 0){
				 	$value = $value/$counter;
				 	echo 'Durchschnitt: '.$value;
				 }
			}
		}
	}

	//writes the results of a questionnaire into a csv-file
	public function csvAction()
	{	
		//reading id of the questionnaire, which is supposed to be written into the file
		$questionnaireId = $this->getRequest()->getParam('id');

		//innitiating the arrays for the particular csv lines
		$csvColumn = array();
		$csvRowLabels = array();
		$qestionIds = array();
		$average = array();
		$columnCounter = 0;
		$csvRowCounter = 0;


		//returning the actual questionnaire
		$questionnaire = $this->questionnaireTable
			     					  ->find($questionnaireId)
			     					  ->current();

		//returning the questions of the given questionnaire
		$categoryQuestions = $this->questionTable
								  ->fetchAll($this->questionTable->select()
							      ->where('category = ?', $questionnaire->category)
						          ->order('prio DESC'));


		//returning the answerhashes in order to iterate
		$submitters =  $this->submittersTable
						    ->fetchAll($this->submittersTable->select()
	     				    ->where('questionnaireId = ?', $questionnaireId));
		//writing first csv-line
		foreach($categoryQuestions as $question){
			$questionIds[$csvRowCounter] = $question->id;
			$csvRowLabels[$csvRowCounter] = $question->text;
			$csvRowCounter++;
		}
		$csvColumn[$csvRowCounter] = $csvRowLabels;
		$columnCounter++;



		//writing csv-lines from answers
		foreach ($submitters as $submitter) {
			$answers = array();
			$csvRowCounter = 0;
			//returning the answers from current submitter
			$submitterAnswers = $this->answerToQuestionTable
								  ->fetchAll($this->answerToQuestionTable->select()
							      ->where('answerhash = ?', $submitter->answerhash));
			//iterating through questions in order to give the right answer to the right question
			foreach ($questionIds as $id) {
						foreach ($submitterAnswers as $answer) {
						      	if($answer->questionId == $id){
						      		if($answer->answertext){
						      			$answers[$csvRowCounter] = $answer->answertext;
						      		}else{
						      			$answers[$csvRowCounter] = $answer->answernumber;
						      		}
						      		$csvRowCounter++;
						      	}
						}	
			 }

			 $csvColumn[$columnCounter] = $answers;
			 $columnCounter++;	 				      
		}

		foreach($questionIds as $id){
			$answerAverage = 0;
			$averageCounter = 0;
			if($id == 1 || $id == 4){
				$average[0] = 'Durchschnitt';
				$averageRowCounter = 1;
			}else{
				$answers = $this->answerToQuestionTable
						    ->fetchAll($this->answerToQuestionTable
						    ->select()
						    ->where('questionId = ?', $id));
					foreach($answers as $answer){
						if($answer->answernumber){
							$answerAverage = $answerAverage + $answer->answernumber;
							$averageCounter++;
						}
						
					}
					if($averageCounter > 0){
						$average[$averageRowCounter] = $answerAverage / $averageCounter;
						$averageRowCounter++;	
					}	    
			}
			

		}

		$csvColumn[$columnCounter] = $average;


		$fp = fopen('../CSV-Ausgaben/'.$questionnaire->courseName.'.csv', 'w');

		//writing the csv-file
		foreach ($csvColumn as $fields) {
   			 fputcsv($fp, $fields);
		}

		fclose($fp);


		$this->_redirect('/admin/secretary');
	}

}

