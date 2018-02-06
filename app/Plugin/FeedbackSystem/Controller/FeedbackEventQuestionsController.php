<?php
App::uses('FeedbackSystemAppController', 'FeedbackSystem.Controller');
/**
 * FeedbackEventQuestions Controller
 *
 * @property FeedbackEventQuestion $FeedbackEventQuestion
 * @property PaginatorComponent $Paginator
 */
class FeedbackEventQuestionsController extends FeedbackSystemAppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

/**
 * index method
 *
 * @return void
 */

	public function index() {	
	    $this->loadModel('Setting');
		$data = $this->Setting->find('first');
		$pagination_value = $data['Setting']['pagination_value'];
		$staffid=$this->Auth->user('staff_id');
		$fbeventid = $this->FeedbackEventQuestion->FeedbackEventManage->find('first',['conditions'=>['FeedbackEventManage.staff_id'=> $staffid]]);
		$this->Paginator->settings = array('limit' => $pagination_value,'page' => 1, 'contain' => ['FeedbackEvent'],'conditions'=>['FeedbackEventQuestion.inform_id'=>0,'FeedbackEventQuestion.recstatus'=>1]);
		$this->set('feedbackEventQuestions', $this->Paginator->paginate());
		$this->Paginator->settings = array('limit' => $pagination_value,'page' => 1, 'contain' => ['FeedbackEvent'],'conditions'=>['FeedbackEventQuestion.inform_id'=>1,'FeedbackEventQuestion.recstatus'=>1]);
		$this->set('feedbackEventQuestionform', $this->Paginator->paginate());
		if ($this->request->is('post','put')) {
		$feedback_question_id = $this->request->data['question'];
		$this->FeedbackEventQuestion->query('UPDATE feedback_event_questions SET inform_id = 1 WHERE id IN('.implode(',',$feedback_question_id).')');
		return $this->redirect(array('action' => 'index'));
		}
	}


	public function indexquestionform(){
		if ($this->request->is('post','put')) {
		$feedback_question_id = $this->request->data['formquestion'];
		$this->FeedbackEventQuestion->query('UPDATE feedback_event_questions SET inform_id = 0 WHERE id IN('.implode(',',$feedback_question_id).')');
		return $this->redirect(array('action' => 'index'));
		}
	}


	public function index_adm() {
	   	$this->loadModel('Setting');
		$data = $this->Setting->find('first');
		$pagination_value = $data['Setting']['pagination_value'];
		$staffid=$this->Auth->user('staff_id');
		$fbeventid = $this->FeedbackEventQuestion->FeedbackEventManage->find('first',['conditions'=>['FeedbackEventManage.staff_id'=> $staffid]]);
		$this->Paginator->settings = array('limit' => $pagination_value,'page' => 1, 'contain' => ['FeedbackEvent'],'conditions'=>['FeedbackEventQuestion.inform_id'=>0,'FeedbackEventQuestion.recstatus'=>1,'FeedbackEventQuestion.feedback_event_id'=>$fbeventid['FeedbackEventManage']['feedback_event_id']]);
		$this->set('feedbackEventQuestions', $this->Paginator->paginate());
		$this->Paginator->settings = array('limit' => $pagination_value,'page' => 1, 'contain' => ['FeedbackEvent'],'conditions'=>['FeedbackEventQuestion.inform_id'=>1,'FeedbackEventQuestion.recstatus'=>1,'FeedbackEventQuestion.feedback_event_id'=>$fbeventid['FeedbackEventManage']['feedback_event_id']]);
		$this->set('feedbackEventQuestionform', $this->Paginator->paginate());
		if ($this->request->is('post','put')) {
		$feedback_question_id = $this->request->data['question'];
		$this->FeedbackEventQuestion->query('UPDATE feedback_event_questions SET inform_id = 0 WHERE id IN('.implode(',',$feedback_question_id).')');
		return $this->redirect(array('action' => 'index'));
		}
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->FeedbackEventQuestion->exists($id)) {
			throw new NotFoundException(__('Invalid feedback event question'));
		}
		$options = array('conditions' => array('FeedbackEventQuestion.' . $this->FeedbackEventQuestion->primaryKey => $id));
		$this->set('feedbackEventQuestion', $this->FeedbackEventQuestion->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */

	public function add_adm_question() {
		if ($this->request->is('post')) {
			$this->loadModel('Setting');
			$data = $this->Setting->find('first');
				$this->FeedbackEventQuestion->create();
				$this->request->data['FeedbackEventQuestion']['institution_id']=$this->Session->read('institution_id');
				$staffid = $this->Auth->user('staff_id');
				$categoryid = $this->request->data['FeedbackEventQuestion']['feedback_event_id'];
				
				$staff = $this->FeedbackEventQuestion->FeedbackEvent->FeedbackEventManage->find('first',[
					                                                          'conditions'=>
					                                                                    ['FeedbackEventManage.feedback_event_id'=>$categoryid,
				
					                                                                           'FeedbackEventManage.recstatus'=>1]]);
				
				$this->loadModel('FeedbackEventManage');
				
				$this->request->data['FeedbackEventQuestion']['event_manage_id'] = $staff['FeedbackEventManage']['id'];
				$this->request->data['FeedbackEvent']['id'] = $this->request->data['FeedbackEventQuestion']['feedback_event_id'];
				$this->request->data['FeedbackEventQuestion']['inform_id']=0;

				if ($this->FeedbackEventQuestion->save($this->request->data,true,array('feedback_event_id','text','creator_id','event_manage_id'))) {
					if($this->FeedbackEventQuestion->save($this->request->data)) {
							$this->Session->setFlash(__('The feedback question has been saved.'), 'default', array('class' => 'alert alert-success'));
							return $this->redirect(array('action' => 'index_adm'));
						}		
				} else {
					$this->Session->setFlash(__('The feedback question could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-success'));
				}
			}
			unset($this->request->data['FeedbackEventQuestion']['feedback_event_id']);
			$events = $this->FeedbackEventQuestion->FeedbackEvent->find('list',array(
				'conditions' => array('FeedbackEvent.recstatus' => 1,'FeedbackEvent.flag' => 1,'FeedbackEvent.institution_id'=>$this->Session->read('institution_id'))
				));
			$this->set(compact('events'));
	}

	public function add() {	
		if ($this->request->is('post')) {
			$this->loadModel('Setting');
			$data = $this->Setting->find('first');
				$this->FeedbackEventQuestion->create();
				$this->request->data['FeedbackEventQuestion']['user_id'] = $this->Auth->user('id');
				$eventid = $this->request->data['FeedbackEventQuestion']['feedback_event_id'];
				$staff = $this->FeedbackEventQuestion->FeedbackEvent->FeedbackEventManage->find('first',[
				                                                          'conditions'=>
				                                                                    ['FeedbackEventManage.feedback_event_id'=>$eventid,
				                                                                           'FeedbackEventManage.recstatus'=>1]]);
			
				$this->loadModel('FeedbackEventManage');
				$this->request->data['FeedbackEventQuestion']['event_manage_id'] = $staff['FeedbackEventManage']['id'];
				$this->request->data['FeedbackEvent']['id'] = $this->request->data['FeedbackEventQuestion']['feedback_event_id'];
				$this->request->data['FeedbackEventQuestion']['inform_id']=0;
				if ($this->FeedbackEventQuestion->save($this->request->data,true,array('feedback_event_id','text','creator_id','event_manage_id'))) {			    
			    	if($this->FeedbackEventQuestion->save($this->request->data)) {
						$this->Session->setFlash(__('The feedback question has been saved.'), 'default', array('class' => 'alert alert-success'));
						return $this->redirect(array('action' => 'index'));
					}	
				}
			 	else {
				$this->Session->setFlash(__('The feedback question could not be saved. Please, try again.'));
				}	
		}
		unset($this->request->data['FeedbackEventQuestion']['feedback_event_id']);
		$institutions = $this->FeedbackEventQuestion->Staff->Institution->find('list');
	    $events = $this->FeedbackEventQuestion->FeedbackEvent->find('list');	
		$this->set(compact('institutions','events'));
	}


	public function add_cord_question() {
		if ($this->request->is('post')) {
			$this->loadModel('Setting');
			$data = $this->Setting->find('first');
				$this->FeedbackEventQuestion->create();
				$staffid = $this->Auth->user('staff_id');
				$this->loadModel('FeedbackEventManage');	
				$feedback_manage = $this->FeedbackEventQuestion->FeedbackEvent->FeedbackEventManage->find('first',['conditions'=>['FeedbackEventManage.staff_id'=>$staffid,'FeedbackEventManage.recstatus'=>1]]);
				$this->request->data['FeedbackEventQuestion']['event_manage_id'] = $feedback_manage['FeedbackEventManage']['id'];
				$this->request->data['FeedbackEventQuestion']['feedback_event_id'] = $feedback_manage['FeedbackEventManage']['feedback_event_id'];
				
				if ($this->FeedbackEventQuestion->save($this->request->data)) {
					$this->Session->setFlash(__('The feedback question has been saved.'), 'default', array('class' => 'alert alert-success'));
					return $this->redirect(array('action' => 'index_adm'));
				} else {
					$this->Session->setFlash(__('The feedback question could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-success'));
				}
			}
	}



	public function event_show() {
		if ($this->request->is('post')) {
			$this->redirect(array('controller' => 'feedback_event_answers','action' => 'index',$this->request->data['FeedbackEventQuestion']['feedback_event_id']));
		}
			$feedbackEvents = $this->FeedbackEventQuestion->FeedbackEventManage->FeedbackEvent->find('list',['conditions' => ['FeedbackEvent.recstatus' => 1,
																						'FeedbackEvent.flag'=>1]]);
			$this->set(compact('feedbackEvents'));
	}
/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->FeedbackEventQuestion->exists($id)) {
			throw new NotFoundException(__('Invalid feedback event question'));
		}
		$this->request->data['FeedbackEventQuestion']['id'] = $id;		
		if ($this->request->is(array('post', 'put'))) {
			if ($this->FeedbackEventQuestion->save($this->request->data,true,array('id','text'))) {
				$this->Session->setFlash(__('The feedback event question has been saved.'), 'default', array('class' => 'alert alert-success'));
				if(Auth::hasRoles(['fbadmin'])) {
						return $this->redirect(array('action' => 'index'));
					}
					else
					{
						return $this->redirect(array('action' => 'index_adm'));
					}
			} else {
				$this->Session->setFlash(__('The feedback event question could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('FeedbackEventQuestion.' . $this->FeedbackEventQuestion->primaryKey => $id));
			$this->request->data = $this->FeedbackEventQuestion->find('first', $options);
		}
	}


	public function deactivate($id = null) {
        if ($this->request->is(['post', 'put'])) {
            $this->FeedbackEventQuestion->id = $id;
            if (!$this->FeedbackEventQuestion->exists()) {
                throw new NotFoundException(__('Invalid FeedbackEventQuestion'));
            }
            $this->request->data['FeedbackEventQuestion']['id'] = $id;
            $this->request->data['FeedbackEventQuestion']['recstatus'] = 0;
             $this->request->data['FeedbackEventQuestion']['inform_id'] = 0;
            if ($this->FeedbackEventQuestion->save($this->request->data,true,['id','recstatus','modifier_id','inform_id'])) {
				$this->Session->setFlash(__('The question has been deactivated.'), 'default', array('class' => 'alert alert-success'));
            } else {
                $this->Session->setFlash(__('The question cannot be deactivated. Please, try again.'));
            }
            if(Auth::hasRoles(['fbadmin'])) {
						return $this->redirect(array('action' => 'index'));
					}
					else
					{
						return $this->redirect(array('action' => 'index_adm'));
					}            
        }
    }


    public function activate($id = null) {
        if ($this->request->is(['post', 'put'])) {
            $this->FeedbackEventQuestion->id = $id;
            if (!$this->FeedbackEventQuestion->exists()) {
                throw new NotFoundException(__('Invalid FeedbackEventQuestion'));
            }           
            $this->request->data['FeedbackEventQuestion']['id'] = $id;
            $this->request->data['FeedbackEventQuestion']['recstatus'] = 1;
            if ($this->FeedbackEventQuestion->save($this->request->data,['id','recstatus','modifier_id'])) {
                $this->Session->setFlash(__('The question has been activated.'), 'default', array('class' => 'alert alert-success'));
            } else {
                $this->Session->setFlash(__('The question cannot be activated. Please, try again.'));
            }
          if(Auth::hasRoles(['fbadmin'])) {
						return $this->redirect(array('action' => 'index'));
					}
					else
					{
						return $this->redirect(array('action' => 'index_adm'));
					}
        }
    }

}