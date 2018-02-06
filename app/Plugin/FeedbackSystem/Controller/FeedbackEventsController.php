<?php
App::uses('FeedbackSystemAppController', 'FeedbackSystem.Controller');
/**
 * FeedbackEvents Controller
 *
 */
class FeedbackEventsController extends FeedbackSystemAppController {

/**
 * Scaffold
 *
 * @var mixed
 */
public $components = array('Paginator');

    public function index() {
		$this->loadModel('Setting');
		$data = $this->Setting->find('first');
		$pagination_value = $data['Setting']['pagination_value'];
		$this->Paginator->settings = array('limit' => $pagination_value,'page' => 1,'recursive'=>-1,'contain'=>['Institution'],'FeedbackEvent.recstatus'=>1);
		$this->set('events', $this->Paginator->paginate());
	}


	public function index_institution() {
		$this->loadModel('Setting');
		$data = $this->Setting->find('first');
		$pagination_value = $data['Setting']['pagination_value'];
		$this->Paginator->settings = array('limit' => $pagination_value,'page' => 1,'recursive'=>-1,'conditions'=>['FeedbackEvent.institution_id'=>$this->Session->read('institution_id')]);
		$this->set('events', $this->Paginator->paginate());
	}


	public function view($id = null) {
		if (!$this->FeedbackEvent->exists($id)) {
			throw new NotFoundException(__('Invalid Feedback Event'));
		}
		$options = ['conditions' => ['FeedbackEvent.' . $this->FeedbackEvent->primaryKey => $id],'contain'=>['Institution'=>['name'],'Department'=>['name']]];
		$this->set('feedbackevent', $this->FeedbackEvent->find('first', $options));
	}


	public function add_dept() {
		if ($this->request->is('post') && $this->request->data['FeedbackEvent']['institution_id'] != 0 
			&& $this->request->data['FeedbackEvent']['department_id'] != 0) {
			$this->FeedbackEvent->create();
			$year = $this->FeedbackEvent->AcademicYear->find('first',['order' => array('AcademicYear.name' => 'desc'),'conditions' => [ 'AcademicYear.institution_id' => $this->request->data['FeedbackEvent']['institution_id'] ] ]);
			$this->request->data['FeedbackEvent']['name'] = ucfirst(strtolower($this->request->data['FeedbackEvent']['name']));
			$this->request->data['FeedbackEvent']['flag'] = 0;
			$this->request->data['FeedbackEvent']['year_id'] = $year['AcademicYear']['id'];			
			if ($this->FeedbackEvent->save($this->request->data)) {
				$this->Session->setFlash(__('The feedback event has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			}
			else 
			{
				$this->Session->setFlash(__('The feedback event could not be saved.'), 'default', array('class' => 'alert alert-success'));
			}
		}
			unset($this->request->data);
			$institutions = $this->FeedbackEvent->Institution->find('list');
			$departments = [];
			$this->set(compact('institutions','departments'));
	}


	public function add_dept_adm() {
		if ($this->request->is('post') && $this->request->data['FeedbackEvent']['department_id'] != 0) {
			$this->FeedbackEvent->create();
			$this->request->data['FeedbackEvent']['name'] = ucfirst(strtolower($this->request->data['FeedbackEvent']['name']));
			$this->request->data['FeedbackEvent']['institution_id'] = $this->Session->read('institution_id');
			$this->request->data['FeedbackEvent']['flag'] = 0;
			$year = $this->FeedbackEvent->AcademicYear->find('first',['order' => array('AcademicYear.name' => 'desc'),'conditions' => [ 'AcademicYear.institution_id' => $this->Session->read('institution_id') ] ]);
			$this->request->data['FeedbackEvent']['year_id'] = $year['AcademicYear']['id'];			
			if ($this->FeedbackEvent->save($this->request->data)) {
				$this->loadModel('AcademicYear');
				$this->FeedbackEvent->AcademicYear->find('first',
						array(
								'order' => array('AcademicYear.name' => 'desc'),
								'conditions' => array('AcademicYear.institution_id' => $this->request->data['FeedbackEvent']['institution_id'])
								)
						);
				$this->Session->setFlash(__('The feedback event has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index_institution'));
			} 
			else 
			{
				$this->Session->setFlash(__('The feedback event could not be saved.'), 'default', array('class' => 'alert alert-success'));
			}
	}
			unset($this->request->data);
			$departments = $this->FeedbackEvent->Department->find('list');
			$this->set(compact('departments'));		
	}


	public function add_ins() {
		if ($this->request->is('post') && $this->request->data['FeedbackEvent']['institution_id'] != 0) {
			$this->FeedbackEvent->create();
			$year = $this->FeedbackEvent->AcademicYear->find('first',['order' => array('AcademicYear.name' => 'desc'),'conditions' => [ 'AcademicYear.institution_id' => $this->request->data['FeedbackEvent']['institution_id'] ] ]);
			$this->request->data['FeedbackEvent']['name'] = ucfirst(strtolower($this->request->data['FeedbackEvent']['name']));
			$this->request->data['FeedbackEvent']['flag'] = 0;
			$this->request->data['FeedbackEvent']['year_id'] = $year['AcademicYear']['id'];					
			if ($this->FeedbackEvent->save($this->request->data)) {	
			 	$this->Session->setFlash(__('The feedback event has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} 
			else 
			{
				$this->Session->setFlash(__('The feedback event could not be saved.'), 'default', array('class' => 'alert alert-success'));
			}
		}	
			unset($this->request->data);
			$institutions = $this->FeedbackEvent->Institution->find('list');
			$this->set(compact('institutions'));		
	}


	public function add_ins_adm() {
		if ($this->request->is('post')) {
			$this->FeedbackEvent->create();
			$this->request->data['FeedbackEvent']['name'] = ucfirst(strtolower($this->request->data['FeedbackEvent']['name']));
			$this->request->data['FeedbackEvent']['institution_id'] = $this->Session->read('institution_id');
			$year = $this->FeedbackEvent->AcademicYear->find('first',['order' => array('AcademicYear.name' => 'desc'),'conditions' => [ 'AcademicYear.institution_id' => $this->Session->read('institution_id') ] ]);
			$this->request->data['FeedbackEvent']['flag'] = 0;
			$this->request->data['FeedbackEvent']['year_id'] = $year['AcademicYear']['id'];			
			if ($this->FeedbackEvent->save($this->request->data)) {				
				$this->Session->setFlash(__('The feedback event has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index_institution'));
			} 
			else 
			{
				$this->Session->setFlash(__('The feedback event could not be saved.'), 'default', array('class' => 'alert alert-success'));
			}
		}
	}

	public function edit($id = null) {
		if (!$this->FeedbackEvent->exists($id)) {
			throw new NotFoundException(__('Invalid feedback event'));
		}
		if ($this->request->is(array('post', 'put'))) {
			$this->request->data['FeedbackEvent']['name'] = ucfirst(strtolower($this->request->data['FeedbackEvent']['name']));
			if ($this->FeedbackEvent->save($this->request->data)) {
				$this->Session->setFlash(__('The feedback event has been saved.'), 'default', array('class' => 'alert alert-success'));
				if(Auth::hasRoles(['fbadmin'])) {
						return $this->redirect(array('action' => 'index_institution'));
					}
					else
					{
						return $this->redirect(array('action' => 'index'));
					}
			} else {
				$this->Session->setFlash(__('The feedback event could not be saved. Please, try again.'), 'default', array('class' => 'alert alert-success'));
			}
		} else {
			$options = array('conditions' => array('FeedbackEvent.' . $this->FeedbackEvent->primaryKey => $id));
			$this->request->data = $this->FeedbackEvent->find('first', $options);
		}
	}


	public function deactivate($id = null) {
        if ($this->request->is(array('post', 'put'))) {
            $this->FeedbackEvent->id = $id;
            if (!$this->FeedbackEvent->exists()) {
                throw new NotFoundException(__('Invalid Event'));
            }
            $this->request->data['FeedbackEvent']['id'] = $id;
            $this->request->data['FeedbackEvent']['flag'] = 0;
            $this->request->data['FeedbackEvent']['recstatus'] = 0;
            if ($this->FeedbackEvent->save($this->request->data,true,array('id','recstatus'))) {
                $this->Session->setFlash(__('The Event has been deactivated.'), 'default', array('class' => 'alert alert-success'));
            } else {
                $this->Session->setFlash(__('The Event cannot be deactivated. Please, try again.'), 'default', array('class' => 'alert alert-success'));
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
            $this->FeedbackEvent->id = $id;
            if (!$this->FeedbackEvent->exists()) {
                throw new NotFoundException(__('Invalid Event'));
            }
            $this->request->data['FeedbackEvent']['id'] = $id;
            $this->request->data['FeedbackEvent']['recstatus'] = 1;
            if ($this->FeedbackEvent->save($this->request->data,true,array('id','recstatus'))) {
                $this->Session->setFlash(__('The Event has been activated.'), 'default', array('class' => 'alert alert-success'));
            } else {
                $this->Session->setFlash(__('The Event cannot be activated. Please, try again.'), 'default', array('class' => 'alert alert-success'));
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

	public function list_feedback_events(){
    	$this->request->onlyAllow('ajax');
        $id = $this->request->query('id');
        if (!$id) {
          throw new NotFoundException();
        }
	  	$this->disableCache();
	  	$events = $this->FeedbackEvent->getListByInstitution($id);
        $this->set(compact('events'));
        $this->set('_serialize', array('events'));
    }

    public function list_eventwise(){
    	$this->request->onlyAllow('ajax');
        $id = $this->request->query('id');
        if (!$id) {
          throw new NotFoundException();
        }
	  	$this->disableCache();
	  	$events = $this->FeedbackEvent->getListByEventwise($id);
        $this->set(compact('events'));
        $this->set('_serialize', array('events'));
    }

    public function list_event(){
    	$this->request->onlyAllow('ajax');
        $id = $this->request->query('id');
        if (!$id) {
          throw new NotFoundException();
        }
	  	$this->disableCache();
	  	$feedbackEvents = $this->FeedbackEvent->getListByDepartment($id);
        $this->set(compact('feedbackEvents'));
        $this->set('_serialize', array('feedbackEvents'));
    }

}