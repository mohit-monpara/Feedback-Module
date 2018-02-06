<?php
App::uses('FeedbackSystemAppController', 'FeedbackSystem.Controller');
/**
 * FeedbackEventManages Controller
 *
 */
class FeedbackEventManagesController extends FeedbackSystemAppController {

/**
 * Scaffold
 *
 * @var mixed
 */
	public $components = array('Paginator');


	public function index() {	
		$this->loadModel('Setting');
		$data = $this->Setting->find('first', array('recursive' => - 1));
		$pagination_value = $data['Setting']['pagination_value'];
		$this->Paginator->settings = array('limit' => $pagination_value,'page' => 1, 'contain' => ['FeedbackEvent','Staff'=>['Institution','Department']]);
		$this->set('feedbackEventManages', $this->Paginator->paginate());
	}

	public function index_admin() {	
		$this->loadModel('Setting');
		$data = $this->Setting->find('first', array('recursive' => - 1));
		$pagination_value = $data['Setting']['pagination_value'];
		$this->Paginator->settings = array('limit' => $pagination_value,'page' => 1, 'contain' => ['FeedbackEvent','Staff'=>['Department']]);
		$this->set('feedbackEventManages', $this->Paginator->paginate());
	}

	
	public function view($id = null) {
		if (!$this->FeedbackEventManage->exists($id)) {
			throw new NotFoundException(__('Invalid feedback event manage'));
		}
		$options = array('conditions' => array('FeedbackEventManage.' . $this->FeedbackEventManage->primaryKey => $id),'recursive'=>-1,'contain'=>['FeedbackEvent','Staff']);
		$this->set('feedbackEventManage', $this->FeedbackEventManage->find('first', $options));
	}


	public function add() {
		if ($this->request->is('post') && $this->request->data['FeedbackEventManage']['staff_id'] != 0) {
			$this->FeedbackEventManage->create();
			if ($this->FeedbackEventManage->save($this->request->data,true,array('feedback_event_id','staff_id','creator_id'))) {
			    $this->request->data['FeedbackEvent']['flag'] = 1;
			    $this->request->data['FeedbackEvent']['id'] = $this->request->data['FeedbackEventManage']['feedback_event_id'];
				if($this->FeedbackEventManage->FeedbackEvent->save($this->request->data,true,['id','flag','modifier_id'])) {
			    	$this->loadModel('UserRole');
			    	$this->loadModel('User');
			    	$staffid = $this->request->data['FeedbackEventManage']['staff_id'];
			    	$data = $this->FeedbackEventManage->Staff->User->find('first',['conditions'=>['User.staff_id'=>$staffid]]);			    	
			    	$insti = $this->FeedbackEventManage->Staff->find('first',['conditions'=>['Staff.id'=>$staffid]]);
			    	$this->request->data['UserRole']['institution_id'] = $insti['Staff']['institution_id'];
			    	$this->request->data['UserRole']['user_id'] = $data['User']['id'];
			    	$this->request->data['UserRole']['department_id'] = $insti['Staff']['department_id'];
			    	$this->request->data['UserRole']['role_id'] = Configure::read('fbeventcoordinator');
			    	$this->request->data['UserRole']['staff_id'] = $this->request->data['FeedbackEventManage']['staff_id'];
			    	if($this->UserRole->save($this->request->data)) {
						$this->Session->setFlash(__('The feedback coordinator has been saved.'), 'default', array('class' => 'alert alert-success'));
						return $this->redirect(array('action' => 'index'));
					}	
				}
			} else {
				$this->Session->setFlash(__('The feedback coordinator could not be saved. Please, try again.'));
			}
		}		
		unset($this->request->data);
		$institutions = $this->FeedbackEventManage->Staff->Institution->find('list');
		$feedbackEvents = [];
		$departments = [];
		$staffs = [];
		$this->set(compact('institutions','departments', 'staffs','feedbackEvents'));
	}


	public function add_adm_coordinator() {
		if ($this->request->is('post') && $this->request->data['FeedbackEventManage']['staff_id'] != 0) {
			$this->FeedbackEventManage->create();
			
			if ($this->FeedbackEventManage->save($this->request->data,true,array('feedback_event_id','staff_id','creator_id'))) {
		$this->request->data['FeedbackEvent']['flag'] = 1;
			    $this->request->data['FeedbackEvent']['id'] = $this->request->data['FeedbackEventManage']['feedback_event_id'];
				if($this->FeedbackEventManage->FeedbackEvent->save($this->request->data,true,['id','flag','modifier_id'])) {
			    	$this->loadModel('UserRole');
			    	$this->loadModel('User');
			    	$staffid = $this->request->data['FeedbackEventManage']['staff_id'];
			    	$data = $this->FeedbackEventManage->Staff->User->find('first',['conditions'=>['User.staff_id'=>$staffid]]);
			    	$insti = $this->FeedbackEventManage->Staff->find('first',['conditions'=>['Staff.id'=>$staffid]]);
			    	$this->request->data['UserRole']['institution_id'] = $insti['Staff']['institution_id'];
			    	$this->request->data['UserRole']['user_id'] = $this->Auth->user('id');
			    	$this->request->data['UserRole']['department_id'] = $insti['Staff']['department_id'];
			    	$this->request->data['UserRole']['role_id'] = Configure::read('fbeventcoordinator');
			    	$this->request->data['UserRole']['staff_id'] = $this->request->data['FeedbackEventManage']['staff_id'];
			    	if($this->UserRole->save($this->request->data)) {
						$this->Session->setFlash(__('The feedback coordinator has been saved.'), 'default', array('class' => 'alert alert-success'));
						return $this->redirect(array('action' => 'index'));
					}	
				}
			} else {
				$this->Session->setFlash(__('The feedback coordinator could not be saved. Please, try again.'));
			}
		}
		
		unset($this->request->data);
		$feedbackEvents = $this->FeedbackEventManage->FeedbackEvent->find('list',['conditions' => ['FeedbackEvent.recstatus' => 1,
																					'FeedbackEvent.flag'=>0]]);
		$departments = $this->FeedbackEventManage->Staff->Department->find('list');
		$staffs = [];
		$this->set(compact('departments', 'staffs','feedbackEvents'));
		
	}


	public function deactivate($id = null) {
        if ($this->request->is(['post', 'put'])) {
            $this->FeedbackEventManage->id = $id;
            if (!$this->FeedbackEventManage->exists()) {
                throw new NotFoundException(__('Invalid FeedbackEventManage'));
            }
            $this->request->data['FeedbackEventManage']['id'] = $id;
            $this->request->data['FeedbackEventManage']['recstatus'] = 0;
            if ($this->FeedbackEventManage->save($this->request->data,true,['id','recstatus','modifier_id'])) {
            	$data = $this->FeedbackEventManage->find('first',['conditions'=>['FeedbackEventManage.id'=>$id]]);
            	$feedback_event_id = $data['FeedbackEventManage']['feedback_event_id'];
            	$this->request->data['FeedbackEvent']['id'] = $feedback_event_id;
			    $this->request->data['FeedbackEvent']['flag'] = 0;
			    if($this->FeedbackEventManage->FeedbackEvent->save($this->request->data,true,['id','flag','modifier_id'])){
			    	$this->loadModel('UserRole');
			    	$this->loadModel('User');
			    	$staffid = $data['FeedbackEventManage']['staff_id'];
			    	$data = $this->User->find('first',['conditions'=>['User.staff_id'=>$staffid]]);
			    	$this->request->data['UserRole']['user_id'] = $data['User']['id'];
			    	$data1 = $this->UserRole->find('first',['conditions'=>['UserRole.user_id'=>$data['User']['id'],
			    														  'UserRole.recstatus'=>1]]);
			    	$this->request->data['UserRole']['id'] = $data1['UserRole']['id'];
			    	$this->request->data['UserRole']['recstatus'] = 0;
			    	if($this->UserRole->save($this->request->data,true,['id','recstatus','modifier_id'])) {
						$this->Session->setFlash(__('The Coordinator has been deactivated.'), 'default', array('class' => 'alert alert-success'));
						if(Auth::hasRoles(['fbadmin'])) {
						return $this->redirect(array('action' => 'index_admin'));
						}
						else
						{
						return $this->redirect(array('action' => 'index'));
						}
					}	
            	}
            } else {
                $this->Session->setFlash(__('The Coordinator cannot be deactivated. Please, try again.'));
            }
            return $this->redirect(['action' => 'index']);
        }
    }
    
    
    public function activate($id = null) {
        if ($this->request->is(['post', 'put'])) {
            $this->FeedbackEventManage->id = $id;
            if (!$this->FeedbackEventManage->exists()) {
                throw new NotFoundException(__('Invalid FeedbackEventManage'));
            }
        	$this->request->data['FeedbackEventManage']['id'] = $id;
            $this->request->data['FeedbackEventManage']['recstatus'] = 1;
            if ($this->FeedbackEventManage->save($this->request->data,true,['id','recstatus','modifier_id'])) {
            	$data = $this->FeedbackEventManage->find('first',['conditions'=>['FeedbackEventManage.id'=>$id]]);
            	$feedback_event_id = $data['FeedbackEventManage']['feedback_event_id'];
            	$this->request->data['FeedbackEvent']['id'] = $feedback_event_id;
			    $this->request->data['FeedbackEvent']['flag'] = 1;
			    if($this->FeedbackEventManage->FeedbackEvent->save($this->request->data,true,['id','flag','modifier_id'])){
			    	$this->loadModel('UserRole');
			    	$this->loadModel('User');
			    	$staffid = $data['FeedbackEventManage']['staff_id'];
			    	$data = $this->User->find('first',['conditions'=>['User.staff_id'=>$staffid]]);
			    	$this->request->data['UserRole']['user_id'] = $data['User']['id'];
			    	$data1 = $this->UserRole->find('first',['conditions'=>['UserRole.user_id'=>$data['User']['id'],
			    														  'UserRole.recstatus'=>0]]);
			    	$this->request->data['UserRole']['id'] = $data1['UserRole']['id'];
			    	$this->request->data['UserRole']['recstatus'] = 1;
			    	if($this->UserRole->save($this->request->data,true,['id','recstatus','modifier_id'])) {
						$this->Session->setFlash(__('The Coordinator has been Activated.'), 'default', array('class' => 'alert alert-success'));
						if(Auth::hasRoles(['fbadmin'])) {
						return $this->redirect(array('action' => 'index_admin'));
						}
						else
						{
						return $this->redirect(array('action' => 'index'));
						}
					}	
            	}
            } else {
                $this->Session->setFlash(__('The Coordinator cannot be Activated. Please, try again.'));
            }
            return $this->redirect(['action' => 'index']);
        }
    }
    
}