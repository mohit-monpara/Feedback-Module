<?php
App::uses('FeedbackSystemAppController', 'FeedbackSystem.Controller');
/**
 * FeedbackCategories Controller
 *
 */
class FeedbackCategoriesController extends FeedbackSystemAppController {

/**
 * Scaffold
 *
 * @var mixed
 */


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
		$this->Paginator->settings = array('limit' => $pagination_value,'page' => 1,'recursive'=>-1,'contain'=>['Institution'],'FeedbackCategory.recstatus'=>1);
		$this->set('categories', $this->Paginator->paginate());
	}

/**
 * index method
 *
 * @return void
 */
	public function index_institution() {
		$this->loadModel('Setting');
		$data = $this->Setting->find('first');
		$pagination_value = $data['Setting']['pagination_value'];
		$this->Paginator->settings = array('limit' => $pagination_value,'page' => 1,'recursive'=>-1,'conditions'=>['FeedbackCategory.institution_id'=>$this->Session->read('institution_id')]);
		$this->set('categories', $this->Paginator->paginate());
	}


/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->FeedbackCategory->exists($id)) {
			throw new NotFoundException(__('Invalid feedback category'));
		}
		$options = ['conditions' => ['FeedbackCategory.' . $this->FeedbackCategory->primaryKey => $id],'contain'=>['Institution'=>['name']]];
		$this->set('feedbackcategory', $this->FeedbackCategory->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->FeedbackCategory->create();
			$this->request->data['FeedbackCategory']['name'] = ucfirst(strtolower($this->request->data['FeedbackCategory']['name']));
			$this->request->data['FeedbackCategory']['flag'] = 0;
			if ($this->FeedbackCategory->save($this->request->data)) {
				$this->Session->setFlash(__('The feedback category has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The feedback category could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-success'));
			}
		}
			$institutions = $this->FeedbackCategory->Institution->find('list');
			$this->set(compact('institutions'));
	}

	public function category_add(){
		if($this->request->is('post')){
			$this->FeedbackCategory->create();
			$this->request->data['FeedbackCategory']['name']= ucfirst(strtolower($this->request->data['FeedbackCategory']['name']));
			$this->request->data['FeedbackCategory']['institution_id']= $this->Session->read('institution_id');
			$this->request->data['FeedbackCategory']['flag'] = 0;
			if ($this->FeedbackCategory->save($this->request->data)) {
				$this->Session->setFlash(_('The Feedback Category has been saved'),'default',array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			}
			else{
				$this->Session->setFlash(__('The feedback category could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-success'));
			}
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->FeedbackCategory->exists($id)) {
			throw new NotFoundException(__('Invalid feedback category'));
		}
		if ($this->request->is(array('post', 'put'))) {
			$this->request->data['FeedbackCategory']['name'] = ucfirst(strtolower($this->request->data['FeedbackCategory']['name']));
			if ($this->FeedbackCategory->save($this->request->data)) {
				$this->Session->setFlash(__('The feedback category has been saved.'), 'default', array('class' => 'alert alert-success'));
				if(Auth::hasRoles(['fbadmin'])) {
						return $this->redirect(array('action' => 'index_institution'));
					}
					else
					{
						return $this->redirect(array('action' => 'index'));
					}
			} else {
				$this->Session->setFlash(__('The feedback category could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-success'));
			}
		} else {
			$options = array('conditions' => array('FeedbackCategory.' . $this->FeedbackCategory->primaryKey => $id));
			$this->request->data = $this->FeedbackCategory->find('first', $options);
		}		
	}


	public function deactivate($id = null) {
        if ($this->request->is(array('post', 'put'))) {
            $this->FeedbackCategory->id = $id;
            if (!$this->FeedbackCategory->exists()) {
                throw new NotFoundException(__('Invalid Category'));
            }
            $this->request->data['FeedbackCategory']['id'] = $id;
            $this->request->data['FeedbackCategory']['flag'] = 0;
            $this->request->data['FeedbackCategory']['recstatus'] = 0;
            if ($this->FeedbackCategory->save($this->request->data,true,array('id','recstatus'))) {
                $this->Session->setFlash(__('The feedbackcategory has been deactivated.'), 'default', array('class' => 'alert alert-success'));
            } else {
                $this->Session->setFlash(__('The feedbackcategory cannot be deactivated. Please, try again.'), 'default', array('class' => 'alert alert-success'));
            }
           if(Auth::hasRoles(['fbadmin'])) {
						return $this->redirect(array('action' => 'index_institution'));
					}
					else
					{
						return $this->redirect(array('action' => 'index'));
					}
        }
    }
    
    
    public function activate($id = null) {
        if ($this->request->is(array('post', 'put'))) {
            $this->FeedbackCategory->id = $id;
            if (!$this->FeedbackCategory->exists()) {
                throw new NotFoundException(__('Invalid category'));
            }
            $this->request->data['FeedbackCategory']['id'] = $id;
            $this->request->data['FeedbackCategory']['flag'] = 0;
            $this->request->data['FeedbackCategory']['recstatus'] = 1;
            if ($this->FeedbackCategory->save($this->request->data,true,array('id','recstatus'))) {
                $this->Session->setFlash(__('The feedbackcategory has been activated.'), 'default', array('class' => 'alert alert-success'));
            } 
            else
            {
                $this->Session->setFlash(__('The feedbackcategory cannot be activated. Please, try again.'), 'default', array('class' => 'alert alert-success'));
            }
          if(Auth::hasRoles(['fbadmin'])) {
						return $this->redirect(array('action' => 'index_institution'));
					}
					else
					{
						return $this->redirect(array('action' => 'index'));
					}
        }
    }


    public function list_feedback_categories(){
    	$this->request->onlyAllow('ajax');
        $id = $this->request->query('id');
        if (!$id) {
          throw new NotFoundException();
        }
	  	$this->disableCache();
	  	$categories = $this->FeedbackCategory->getListByInstitution($id);
        $this->set(compact('categories'));
        $this->set('_serialize', array('categories'));
    }



    public function list_categorywise(){
    	$this->request->onlyAllow('ajax');
        $id = $this->request->query('id');
        if (!$id) {
          throw new NotFoundException();
        }
	  	$this->disableCache();
	  	$categories = $this->FeedbackCategory->getListByCategorywise($id);
        $this->set(compact('categories'));
        $this->set('_serialize', array('categories'));
    }


    public function list_category(){
    	$this->request->onlyAllow('ajax');
        $id = $this->request->query('id');
        if (!$id) {
          throw new NotFoundException();
        }
	  	$this->disableCache();
	  	$feedbackCategories = $this->FeedbackCategory->getListByDepartment($id);
        $this->set(compact('feedbackCategories'));
        $this->set('_serialize', array('feedbackCategories'));
    }
}