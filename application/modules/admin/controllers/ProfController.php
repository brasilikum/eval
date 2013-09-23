<?php

class Admin_ProfController extends Zend_Controller_Action
{

	protected $answerToQuestionTable;
	protected $questionTable;
	protected $questionnaireTable;
	protected $userTable;
	protected $participantTable;


	//initilizing database
	public function init()
	{
		$this->answerToQuestionTable = new Application_Model_DbTable_AnswerToQuestionTable();
		$this->questionnaireTable = new Application_Model_DbTable_QuestionnaireTable();
		$this->questionTable = new Application_Model_DbTable_QuestionTable();
		$this->userTable = new Application_Model_DbTable_UserTable();
		$this->participantTable = new Application_Model_DbTable_ParticipantTable();
		$this->profRow = $this->userTable
							 ->fetchAll($this->userTable
							 ->select()
							 ->where('id = ?',Application_Plugin_auth_AccessControl::getUserId()))
							 ->current();
		$this->view->profName = $this->profRow->fullName;
	}

	public function indexAction()
	{		
		$this->view->quests = $this->profRow->findDependentRowset($this->questionnaireTable, null, $this->questionnaireTable->select()->order('expirationDate DESC'));	 	
	}

	//creating a new questionnaire
	public function createAction()
	{
		$form = $this->_getCreationForm();
		$request = $this->getRequest();
		$id = $this->getRequest()->getParam('id');
		$emails;

		if($id){
			echo 'Emailadressen falsch, bitte neu eingeben';
		}


		if ($request->isPost())
		{

			if ($form->isValid($_POST))
			{
				$validator = new Zend_Validate_EmailAddress();
				$newQuestionnaire = $this->questionnaireTable->createRow();

				$newQuestionnaire->courseName = $form->getValue('courseName');
				$newQuestionnaire->expirationDate = $form->getValue('expirationDate');
				$newQuestionnaire->semester = $form->getValue('semester');
				$newQuestionnaire->category = $form->getValue('category');
				$emails = $form->getValue('emails');
				$newQuestionnaire->profId = Application_Plugin_auth_AccessControl::getUserId();
				


			     $tok = strtok($emails,',');
			     $emailAdresses = array();
			     $counter = 0;

			     while($tok){
			     	if($validator->isValid($tok)){
			     		$emailAdresses[$counter] = $tok;
			     	}else{
			     		$this->_redirect('/admin/prof/create?id=1');
			     	}
			     	$tok = strtok(',');
			     	$counter++;
			     }

			     $questionnaireId = $newQuestionnaire->save();


				$this->_sendMails($questionnaireId,$emailAdresses);
			}
		}	

	
			
		$this->view->quit = '<a  href=" '. $this->view->baseUrl() . '/admin/prof">abbrechen</a></div><br/>';
		$this->view->form = $form;
			

	}

	//returns a form for the new questionnaire
	public function _getCreationForm(){

	    $form = new Zend_Form();
		$form->setMethod('post');

		$expirationDate = new Zend_Form_Element_Text('expirationDate',array('label' => 'Evaluation läuft bis', 'required' => true));
		$expirationDate->setAttrib('placeholder', '2013-09-23');
		$expirationDate->addFilter('StripTags');
		$expirationDate->addValidator(new Zend_Validate_Date);

		$courseName = new Zend_Form_Element_Text('courseName', array('label' => 'Modulname', 'required' => true));
		$semester = new Zend_Form_Element_Text('semester', array('label' => 'Semester', 'required' => true));

		$category = new Zend_Form_Element_Radio('category', array('label' => 'Art der Veranstaltung', 'required' => true));
		$category->setMultiOptions(array('VL' => "Vorlesung", 'PR' => "Praktikum"));

		$emails = new Zend_Form_Element_Textarea('emails', array('label' => 'E-Mail-Adressen', 'required' => true));
		$emails->setAttrib('placeholder', 'max-muster@online.de,bei-spiel@web.de');
		$submit = new Zend_Form_Element_Submit('submit', array('label' => 'Abschicken'));
		$form->addElements(array($courseName, $semester, $category,$expirationDate,$emails, $submit));

		return $form;

	}

	//function that iterates through the mails, and uses the mail adapter to send specific hashes to the students
	public function _sendMails($questionnaireId, $mailAdresses){

		foreach($mailAdresses as $address){
			     	$mail = new Zend_Mail();
			     	$answerhash = sha1(uniqid(mt_rand(), true));
			     	$newParticipant = $this->participantTable->createRow();

			     	$newParticipant->hash = $answerhash;
			     	$newParticipant->questionnaireId = $questionnaireId;

			     	$modulname = $this->questionnaireTable
			     					  ->find($questionnaireId)
			     					  ->current()
			     					  ->courseName;

			   
			     	$newParticipant->save();

			     	$mail->setBodyText('Lieber Student,

			   			Klicken Sie auf den nachfolgenden Link um das Modul zu bewerten.
			     		http://localhost:8888/EvaluationSystem_for_Courses/public/evaluate/evaluate?id='.$answerhash.'')
    					 ->addTo($address)
    					 ->setSubject('Evaluation des Moduls '.$modulname.'')
  						 ->send();
			     	
			     }

			    $this->_redirect('/admin/prof');
	}
}
