<?php
App::uses('FeedbackSystemAppController', 'FeedbackSystem.Controller');
/**
 * FeedbackQuestions Controller
 *
 */
class FeedbackQuestionsController extends FeedbackSystemAppController {


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
		$fbcategoryid = $this->FeedbackQuestion->FeedbackManage->find('first',['conditions'=>['FeedbackManage.staff_id'=> $staffid]]);

		$this->Paginator->settings = array('limit' => $pagination_value,'page' => 1, 'contain' => ['FeedbackCategory'],'conditions'=>['FeedbackQuestion.inform_id'=>0,'FeedbackQuestion.recstatus'=>1]);
		$this->set('feedbackQuestions', $this->Paginator->paginate());
		$this->Paginator->settings = array('limit' => $pagination_value,'page' => 1, 'contain' => ['FeedbackCategory'],'conditions'=>['FeedbackQuestion.inform_id'=>1,'FeedbackQuestion.recstatus'=>1]);
		$this->set('feedbackQuestionform', $this->Paginator->paginate());
		if ($this->request->is('post','put')) {
		$feedback_question_id = $this->request->data['question'];
		$this->FeedbackQuestion->query('UPDATE feedback_questions SET inform_id = 1 WHERE id IN('.implode(',',$feedback_question_id).')');
		return $this->redirect(array('action' => 'index'));
		}
	}


	public function indexquestionform(){
		if ($this->request->is('post','put')) {
		$feedback_question_id = $this->request->data['formquestion'];
		$this->FeedbackQuestion->query('UPDATE feedback_questions SET inform_id = 0 WHERE id IN('.implode(',',$feedback_question_id).')');
		return $this->redirect(array('action' => 'index'));
	}
}


	public function index_adm() {
	   	$this->loadModel('Setting');
		$data = $this->Setting->find('first');
		$pagination_value = $data['Setting']['pagination_value'];
		$staffid=$this->Auth->user('staff_id');
		$fbcategoryid = $this->FeedbackQuestion->FeedbackManage->find('first',['conditions'=>['FeedbackManage.staff_id'=> $staffid]]);
		$this->Paginator->settings = array('limit' => $pagination_value,'page' => 1, 'contain' => ['FeedbackCategory'],'conditions'=>['FeedbackQuestion.inform_id'=>0,'FeedbackQuestion.recstatus'=>1,'FeedbackQuestion.feedback_category_id'=>$fbcategoryid['FeedbackManage']['feedback_category_id']]);
		$this->set('feedbackQuestions', $this->Paginator->paginate());
		$this->Paginator->settings = array('limit' => $pagination_value,'page' => 1, 'contain' => ['FeedbackCategory'],'conditions'=>['FeedbackQuestion.inform_id'=>1,'FeedbackQuestion.recstatus'=>1,'FeedbackQuestion.feedback_category_id'=>$fbcategoryid['FeedbackManage']['feedback_category_id']]);
		$this->set('feedbackQuestionform', $this->Paginator->paginate());
		if ($this->request->is('post','put')) {
		$feedback_question_id = $this->request->data['question'];
		$this->FeedbackQuestion->query('UPDATE feedback_questions SET inform_id = 1 WHERE id IN('.implode(',',$feedback_question_id).')');
		return $this->redirect(array('action' => 'index_adm'));
		}
	}


	public function indexquestionform1(){
		if ($this->request->is('post','put')) {
		$feedback_question_id = $this->request->data['formquestion'];
		$this->FeedbackQuestion->query('UPDATE feedback_questions SET inform_id = 0 WHERE id IN('.implode(',',$feedback_question_id).')');
		return $this->redirect(array('action' => 'index_adm'));
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
		if (!$this->FeedbackQuestion->exists($id)) {
			throw new NotFoundException(__('Invalid feedback question'));
		}
		$options = array('conditions' => array('FeedbackQuestion.' . $this->FeedbackQuestion->primaryKey => $id));
		$this->set('feedbackQuestion', $this->FeedbackQuestion->find('first', $options));
	}

    
	//Admin will add queestion of his institution
	public function add_adm_question() {
		if ($this->request->is('post')) {
			$this->loadModel('Setting');
			$data = $this->Setting->find('first');
				$this->FeedbackQuestion->create();
				$this->request->data['FeedbackQuestion']['institution_id']=$this->Session->read('institution_id');
				$staffid = $this->Auth->user('staff_id');
				$categoryid = $this->request->data['FeedbackQuestion']['feedback_category_id'];
				$staff = $this->FeedbackQuestion->FeedbackCategory->FeedbackManage->find('first',[
					                                                          'conditions'=>
					                                                                    ['FeedbackManage.feedback_category_id'=>$categoryid,
				
					                                                                           'FeedbackManage.recstatus'=>1]]);
				$this->loadModel('FeedbackManage');				
				$this->request->data['FeedbackQuestion']['manage_id'] = $staff['FeedbackManage']['id'];
				$this->request->data['FeedbackCategory']['id'] = $this->request->data['FeedbackQuestion']['feedback_category_id'];
				$this->request->data['FeedbackQuestion']['inform_id']=0;
				if ($this->FeedbackQuestion->save($this->request->data,true,array('feedback_category_id','text','creator_id','manage_id'))) {
			    	if($this->FeedbackQuestion->save($this->request->data)) {
						$this->Session->setFlash(__('The feedback question has been saved.'), 'default', array('class' => 'alert alert-success'));
						return $this->redirect(array('action' => 'index'));
					}	
			} else {
				$this->Session->setFlash(__('The feedback question could not be saved. Please, try again.'));
			}
		}
			unset($this->request->data['FeedbackQuestion']['feedback_category_id']);
			$categories = $this->FeedbackQuestion->FeedbackCategory->find('list',array(
			'conditions' => array('FeedbackCategory.recstatus' => 1,'FeedbackCategory.flag' => 1,'FeedbackCategory.institution_id'=>$this->Session->read('institution_id'))
			));
			$this->set(compact('categories'));
	}


	public function add_cord_question() {
		if ($this->request->is('post')) {
			$this->loadModel('Setting');
			$data = $this->Setting->find('first');
			$this->FeedbackQuestion->create();
			$staffid = $this->Auth->user('staff_id');
			$this->loadModel('FeedbackManage');
			$feedback_manage = $this->FeedbackQuestion->FeedbackCategory->FeedbackManage->find('first',['conditions'=>['FeedbackManage.staff_id'=>$staffid,'FeedbackManage.recstatus'=>1]]);
			$this->request->data['FeedbackQuestion']['manage_id'] = $feedback_manage['FeedbackManage']['id'];
			$this->request->data['FeedbackQuestion']['feedback_category_id'] = $feedback_manage['FeedbackManage']['feedback_category_id'];
			if ($this->FeedbackQuestion->save($this->request->data)) {
				$this->Session->setFlash(__('The feedback question has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index_adm'));
			} else {
				$this->Session->setFlash(__('The feedback question could not be saved. Please, try again.'));
			}
		}
	}


	public function category_show() {
		if ($this->request->is('post')) {
			$this->redirect(array('controller' => 'feedback_answers','action' => 'index',$this->request->data['FeedbackQuestion']['feedback_category_id']));
		}
			$feedbackCategories = $this->FeedbackQuestion->FeedbackManage->FeedbackCategory->find('list',['conditions' => ['FeedbackCategory.recstatus' => 1,
																						'FeedbackCategory.flag'=>1]]);
			$this->set(compact('feedbackCategories'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->FeedbackQuestion->exists($id)) {
			throw new NotFoundException(__('Invalid feedback question'));
		}
		$this->request->data['FeedbackQuestion']['id'] = $id;
		if ($this->request->is(array('post', 'put'))) {
			if ($this->FeedbackQuestion->save($this->request->data,true,array('id','text'))) {
				$this->Session->setFlash(__('The feedback question has been saved.'), 'default', array('class' => 'alert alert-success'));
				if(Auth::hasRoles(['fbadmin'])) {
						return $this->redirect(array('action' => 'index'));
					}
					else
					{
						return $this->redirect(array('action' => 'index_adm'));
					}
			} else {
				$this->Session->setFlash(__('The feedback question could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('FeedbackQuestion.' . $this->FeedbackQuestion->primaryKey => $id));
			$this->request->data = $this->FeedbackQuestion->find('first', $options);
		}
	}


	public function deactivate($id = null) {
        if ($this->request->is(['post', 'put'])) {
            $this->FeedbackQuestion->id = $id;
            if (!$this->FeedbackQuestion->exists()) {
                throw new NotFoundException(__('Invalid FeedbackQuestion'));
            }
            $this->request->data['FeedbackQuestion']['id'] = $id;
            $this->request->data['FeedbackQuestion']['recstatus'] = 0;
            $this->request->data['FeedbackQuestion']['inform_id'] = 0;
            if ($this->FeedbackQuestion->save($this->request->data,true,array('id','recstatus','modifier_id','inform_id'))) {
				$this->Session->setFlash(__('The question has been deactivated.'), 'alert', array('class' => 'alert-success'));				
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
            $this->FeedbackQuestion->id = $id;
            if (!$this->FeedbackQuestion->exists()) {
                throw new NotFoundException(__('Invalid FeedbackQuestion'));
            }       
            $this->request->data['FeedbackQuestion']['id'] = $id;
            $this->request->data['FeedbackQuestion']['recstatus'] = 1;
            if ($this->FeedbackQuestion->save($this->request->data,['id','recstatus','modifier_id'])) {
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