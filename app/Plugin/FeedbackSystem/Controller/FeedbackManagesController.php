<?php
App::uses('FeedbackSystemAppController', 'FeedbackSystem.Controller');
/**
 * FeedbackManages Controller
 *
 * @property FeedbackManage $FeedbackManage
 * @property PaginatorComponent $Paginator
 */
class FeedbackManagesController extends FeedbackSystemAppController {

/**
 * Components *
 * @var array
 */
	public $components = array('Paginator');

/**
 * index method
 *
 * @return void
 */
//for developer
	public function index() {
		$this->loadModel('Setting');
		$data = $this->Setting->find('first');
		$pagination_value = $data['Setting']['pagination_value'];
		$this->Paginator->settings = array('limit' => $pagination_value,'page' => 1, 'contain' => ['FeedbackCategory','Staff'=>['Institution','Department']]);
		$this->set('feedbackManages', $this->Paginator->paginate());
	}


/**
 * index method
 *
 * @return void
 */

//for Admin
	public function index_admin() {
		$this->loadModel('Setting');
		$data = $this->Setting->find('first', array('recursive' => - 1));
		$pagination_value = $data['Setting']['pagination_value'];
		$this->Paginator->settings = array('limit' => $pagination_value,'page' => 1, 'contain' => ['FeedbackCategory','Staff'=>['Department']]);
		$this->set('feedbackManages', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->FeedbackManage->exists($id)) {
			throw new NotFoundException(__('Invalid feedback manage'));
		}
		$options = array('conditions' => array('FeedbackManage.' . $this->FeedbackManage->primaryKey => $id),'recursive'=>-1,'contain'=>['FeedbackCategory','Staff']);
		$this->set('feedbackManage', $this->FeedbackManage->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */

//add method(Super-admin will add Coordinator)ie:superadmin_add_coordinator//developer
	public function add() {
		if ($this->request->is('post') && $this->request->data['FeedbackManage']['staff_id'] != 0) {
			$this->FeedbackManage->create();
			if ($this->FeedbackManage->save($this->request->data,true,array('feedback_category_id','staff_id','creator_id'))) {
			    $this->request->data['FeedbackCategory']['flag'] = 1;
			    $this->request->data['FeedbackCategory']['id'] = $this->request->data['FeedbackManage']['feedback_category_id'];
				if($this->FeedbackManage->FeedbackCategory->save($this->request->data,true,['id','flag','modifier_id'])) {
			    	$this->loadModel('UserRole');
			    	$this->loadModel('User');
			    	$staffid = $this->request->data['FeedbackManage']['staff_id'];
			    	$data = $this->FeedbackManage->Staff->User->find('first',['conditions'=>['User.staff_id'=>$staffid]]);
			    	$insti = $this->FeedbackManage->Staff->find('first',['conditions'=>['Staff.id'=>$staffid]]);
			    	$this->request->data['UserRole']['institution_id'] = $insti['Staff']['institution_id'];
			    	$this->request->data['UserRole']['user_id'] = $data['User']['id'];
			    	$this->request->data['UserRole']['department_id'] = $insti['Staff']['department_id'];
			    	$this->request->data['UserRole']['role_id'] = Configure::read('fbcoordinator');
			    	$this->request->data['UserRole']['staff_id'] = $this->request->data['FeedbackManage']['staff_id'];
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
			$institutions = $this->FeedbackManage->Staff->Institution->find('list');
			$feedbackCategories = [];
			$departments = [];
			$staffs = [];
			$this->set(compact('institutions','departments', 'staffs','feedbackCategories'));
	}



//Admin will add Coordinator to category
public function add_adm_coordinator() {
		if ($this->request->is('post') && $this->request->data['FeedbackManage']['staff_id'] != 0) {
			$this->FeedbackManage->create();
			if ($this->FeedbackManage->save($this->request->data,true,array('feedback_category_id','staff_id','creator_id'))) {
				$this->request->data['FeedbackCategory']['flag'] = 1;
			    $this->request->data['FeedbackCategory']['id'] = $this->request->data['FeedbackManage']['feedback_category_id'];
				if($this->FeedbackManage->FeedbackCategory->save($this->request->data,true,['id','flag','modifier_id'])) {
			    	$this->loadModel('UserRole');
			    	$this->loadModel('User');
			    	$staffid = $this->request->data['FeedbackManage']['staff_id'];
			    	$data = $this->FeedbackManage->Staff->User->find('first',['conditions'=>['User.staff_id'=>$staffid]]);
			    	$insti = $this->FeedbackManage->Staff->find('first',['conditions'=>['Staff.id'=>$staffid]]);
			    	$this->request->data['UserRole']['institution_id'] = $insti['Staff']['institution_id'];
			    	$this->request->data['UserRole']['user_id'] = $this->Auth->user('id');
			    	$this->request->data['UserRole']['department_id'] = $insti['Staff']['department_id'];
			    	$this->request->data['UserRole']['role_id'] = Configure::read('fbcoordinator');
			    	$this->request->data['UserRole']['staff_id'] = $this->request->data['FeedbackManage']['staff_id'];
			    	if($this->UserRole->save($this->request->data)) {
						$this->Session->setFlash(__('The feedback coordinator has been saved.'), 'default', array('class' => 'alert alert-success'));
						return $this->redirect(array('action' => 'index_admin'));
					}	
				}
			} else {
				$this->Session->setFlash(__('The feedback coordinator could not be saved. Please, try again.'));
			}
		}
		
			unset($this->request->data);
			$feedbackCategories = $this->FeedbackManage->FeedbackCategory->find('list',['conditions' => ['FeedbackCategory.recstatus' => 1,
																						'FeedbackCategory.flag'=>0]]);
			$departments = $this->FeedbackManage->Staff->Department->find('list');
			$staffs = [];
			$this->set(compact('departments', 'staffs','feedbackCategories'));
	}
/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
*/
	public function list_categories() {
        $this->request->onlyAllow('ajax');
        $id = $this->request->query('id');
        if (!$id) {
          throw new NotFoundException();
        }
	  	$this->disableCache();
		$categories = $this->FeedbackCategory->getListByCategory($id);
        $this->set(compact('categories'));
        $this->set('_serialize', array('categories'));
    }


    public function list_categoriewise() {
        $this->request->onlyAllow('ajax');
        $id = $this->request->query('id');
        if (!$id) {
          throw new NotFoundException();
        }
	  	$this->disableCache();
		$categories = $this->FeedbackManage->FeedbackCategory->getListByCategorywise($id);
		var_dump($categories);
        $this->set(compact('categories'));
        $this->set('_serialize', array('categories'));
    }


    public function deactivate($id = null) {
        if ($this->request->is(['post', 'put'])) {
            $this->FeedbackManage->id = $id;
            if (!$this->FeedbackManage->exists()) {
                throw new NotFoundException(__('Invalid Unable to Manage'));
            }
            $this->request->data['FeedbackManage']['id'] = $id;
            $this->request->data['FeedbackManage']['recstatus'] = 0;
            if ($this->FeedbackManage->save($this->request->data,true,['id','recstatus','modifier_id'])) {
            	$data = $this->FeedbackManage->find('first',['conditions'=>['FeedbackManage.id'=>$id]]);
            	$feedback_category_id = $data['FeedbackManage']['feedback_category_id'];
            	$this->request->data['FeedbackCategory']['id'] = $feedback_category_id;
			    $this->request->data['FeedbackCategory']['flag'] = 0;
			    if($this->FeedbackManage->FeedbackCategory->save($this->request->data,true,['id','flag','modifier_id'])){
			    	$this->loadModel('UserRole');
			    	$this->loadModel('User');
			    	$staffid = $data['FeedbackManage']['staff_id'];
			    	$data = $this->User->find('first',['conditions'=>['User.staff_id'=>$staffid]]);
			    	$this->request->data['UserRole']['user_id'] = $data['User']['id'];
			    	$data1 = $this->UserRole->find('first',['conditions'=>['UserRole.user_id'=>$data['User']['id'],
			    														  'UserRole.recstatus'=>1]]);
			    	$this->request->data['UserRole']['id'] = $data1['UserRole']['id'];

			    	$this->request->data['UserRole']['recstatus'] = 0;

			    	if($this->UserRole->save($this->request->data,true,['id','recstatus','modifier_id'])) {
						$this->Session->setFlash(__('The Staff has been deactivated.'), 'default', array('class' => 'alert alert-success'));
						
					}	
            	}
            } else {
                $this->Session->setFlash(__('The Staff cannot be deactivated. Please, try again.'));
            }
            if(Auth::hasRoles(['fbadmin'])) {
						return $this->redirect(array('action' => 'index_admin'));
					}
					else
					{
						return $this->redirect(array('action' => 'index'));
					}
        }
    }
    

    public function activate($id = null) {
        if ($this->request->is(['post', 'put'])) {
            $this->FeedbackManage->id = $id;
            if (!$this->FeedbackManage->exists()) {
                throw new NotFoundException(__('Unable to Manage'));
            }           
            $this->request->data['FeedbackManage']['id'] = $id;
            $this->request->data['FeedbackManage']['recstatus'] = 1;
            if ($this->FeedbackManage->save($this->request->data,true,['id','recstatus','modifier_id'])) {
            	$data = $this->FeedbackManage->find('first',['conditions'=>['FeedbackManage.id'=>$id]]);
            	$feedback_category_id = $data['FeedbackManage']['feedback_category_id'];
            	$this->request->data['FeedbackCategory']['id'] = $feedback_category_id;
			    $this->request->data['FeedbackCategory']['flag'] = 1;
            	if($this->FeedbackManage->FeedbackCategory->save($this->request->data,true,['id','flag','modifier_id'])){
			    	$this->loadModel('UserRole');
			    	$this->loadModel('User');
			    	$staffid = $data['FeedbackManage']['staff_id'];
			    	$data = $this->User->find('first',['conditions'=>['User.staff_id'=>$staffid]]);
			    	$this->request->data['UserRole']['user_id'] = $data['User']['id'];
			    	$data1 = $this->UserRole->find('first',['conditions'=>['UserRole.user_id'=>$data['User']['id'],
			    														  'UserRole.recstatus'=>0]]);
			    	$this->request->data['UserRole']['id'] = $data1['UserRole']['id'];

			    	$this->request->data['UserRole']['recstatus'] = 1;

					if($this->UserRole->save($this->request->data,true,['id','recstatus','modifier_id'])) {
						$this->Session->setFlash(__('The Staff has been activated.'), 'default', array('class' => 'alert alert-success'));
					} 
				}
			}
            else 
            {
                $this->Session->setFlash(__('The Manager cannot be activated. Please, try again.'));
            }
            if(Auth::hasRoles(['fbadmin'])) {
						return $this->redirect(array('action' => 'index_admin'));
					}
					else
					{
						return $this->redirect(array('action' => 'index'));
					}
        }
    }
}
