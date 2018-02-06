<?php
App::uses('FeedbackSystemAppController', 'FeedbackSystem.Controller');
/**
 * FeedbackAnswers Controller
 *
 * @property FeedbackAnswer $FeedbackAnswer
 * @property PaginatorComponent $Paginator
 */
class FeedbackEventAnswersController extends FeedbackSystemAppController {

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
public function index($event = null) {
		if (!$this->FeedbackEventAnswer->FeedbackEventQuestion->FeedbackEvent->exists($event)) {
			throw new NotFoundException(__('Invalid feedback Event'));
			return $this->redirect(array('controller'=>'feedback_event_questions','action'=>'event_show'));
		}
		if ($this->request->is('post')) {
			$this->FeedbackEventAnswer->create();		
			if ($this->FeedbackEventAnswer->saveMany($this->request->data['FeedbackEventAnswer'])) {			    	
				$this->Session->setFlash(__('The feedback answer has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('controller'=>'feedback_event_questions','action'=>'event_show'));
			} else {
				$this->Session->setFlash(__('The feedback answer could not be saved. Please, try again.'));
			}
		}
		$data = $this->FeedbackEventAnswer->FeedbackEventQuestion->find('all',['conditions' => [
																'FeedbackEventQuestion.inform_id'=>1,
																'FeedbackEventQuestion.recstatus'=>1,
																'FeedbackEventQuestion.feedback_event_id'=>$event
															]
													]);
		$this->set('feedbackAnswers', $data);
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->FeedbackEventAnswer->exists($id)) {
			throw new NotFoundException(__('Invalid feedback answer'));
		}
		$options = array('conditions' => array('FeedbackEventAnswer.' . $this->FeedbackEventAnswer->primaryKey => $feedback_event_question_id));
		$this->set('feedbackEventAnswer', $this->FeedbackEventAnswer->find('first', $options));
	}


	public function export_all() {     
  		$this->set('feedbackEventAnswers', $this->FeedbackEventAnswer->find('all',
  			[ 
				'fields' => ['FeedbackEventAnswer.created','FeedbackEventAnswer.feedback_event_question_id','FeedbackEventAnswer.answer','FeedbackEventAnswer.user_id'],
				'contain' => [
					'FeedbackEventQuestion' => [
						'fields' => ['FeedbackEventQuestion.text']
										],
					'User' =>[
						'fields' => ['User.username']
					]
				]

  			]));
    	$this->layout = null;
   		$this->autoLayout = false;
	}


/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->FeedbackEventAnswer->exists($id)) {
			throw new NotFoundException(__('Invalid feedback answer'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->FeedbackEventAnswer->save($this->request->data)) {
				$this->Session->setFlash(__('The feedback answer has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The feedback answer could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('FeedbackEventAnswer.' . $this->FeedbackEventAnswer->primaryKey => $id));
			$this->request->data = $this->FeedbackEventAnswer->find('first', $options);
		}
		$createdBies = $this->FeedbackEventAnswer->CreatedBy->find('list');
		$modifiedBies = $this->FeedbackEventAnswer->ModifiedBy->find('list');
		$this->set(compact('createdBies', 'modifiedBies'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->FeedbackEventAnswer->id = $id;
		if (!$this->FeedbackEventAnswer->exists()) {
			throw new NotFoundException(__('Invalid feedback answer'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->FeedbackEventAnswer->delete()) {
			$this->Session->setFlash(__('The feedback answer has been deleted.'), 'default', array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash(__('The feedback answer could not be deleted. Please, try again.'), 'default', array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}


	public function event_feedback() {
	if ($this->request->is('post')) {
		$this->redirect(array('action' => 'column_feedbacks',$this->request->data['FeedbackEventAnswer']['feedback_event_id']));
		}
		$feedbackEvents = $this->FeedbackEventAnswer->FeedbackEventQuestion->FeedbackEventManage->FeedbackEvent->find('list',['conditions' => ['FeedbackEvent.recstatus' => 1,
																					'FeedbackEvent.flag'=>1]]);
		$this->set(compact('feedbackEvents'));
	}

	public function event_feedback_admin() {
		if ($this->request->is('post')) {
			$this->redirect(array('action' => 'column_feedbacks',$this->request->data['FeedbackEventAnswer']['feedback_event_id']));
		}	
		$feedbackEvents = $this->FeedbackEventAnswer->FeedbackEventQuestion->FeedbackEventManage->FeedbackEvent->find('list',['conditions' => ['FeedbackEvent.recstatus' => 1,
																					'FeedbackEvent.flag'=>1,'FeedbackEvent.institution_id'=>$this->Session->read('institution_id')]]);
		$this->set(compact('feedbackEvents'));
	}

	public function event_feedback_coordinator() {
		if ($this->request->is('post')) {
			$this->redirect(array('action' => 'column_feedbacks',$this->request->data['FeedbackEventAnswer']['feedback_event_id']));
		}
		$staffid = $this->Auth->user('staff_id'); 
		$feedback_manage = $this->FeedbackEventAnswer->FeedbackEventQuestion->FeedbackEvent->FeedbackEventManage->find('first',['conditions'=>['FeedbackEventManage.staff_id'=>$staffid,'FeedbackEventManage.recstatus'=>1],'fields'=>['FeedbackEventManage.feedback_event_id']]);
		$counter = 0;
		foreach($feedback_manage as $key=>$value) {
			foreach ($value as $key => $value1) {
				$data[$counter] = $value1;
					$counter++;
			}					
		}		
		$feedbackEvents = $this->FeedbackEventAnswer->FeedbackEventQuestion->FeedbackEventManage->FeedbackEvent->find('list',['conditions' => ['FeedbackEvent.recstatus' => 1,
																					'FeedbackEvent.flag'=>1,'FeedbackEvent.id'=>$data,'FeedbackEvent.institution_id'=>$this->Session->read('institution_id')]]);
		$this->set(compact('feedbackEvents'));
	}

	public function column_feedbacks($feedback_event_id) {

		$question_id = $this->FeedbackEventAnswer->FeedbackEventQuestion->find('list',['conditions' =>['FeedbackEventQuestion.feedback_event_id' => $feedback_event_id, 
																							'FeedbackEventQuestion.inform_id'=>1],
																							'fields'=>['id']]);		
		foreach ($question_id as $key => $value) {
			$count = $this->FeedbackEventAnswer->find('all',['conditions' =>['FeedbackEventAnswer.feedback_event_question_id' => $value],
														'recursive' => 1, 
														'fields' => ['AVG(FeedbackEventAnswer.answer) AS average']]);
		$answer[$key]= $count;
		}
		$counter = 0;
		foreach($answer as $key=>$value) {
			foreach ($value as $key => $value1) {
				foreach ($value1 as $key => $value2) {
					foreach ($value2 as $key => $value3) {
						$data[$counter] = $value3;
						$counter++;
					}	
				}
			}
		}
		$counter = 0;
		foreach ($data as $key => $value) {
			$ans[$counter]=(float)$value;
			$counter++;
		}
		$questions = $this->FeedbackEventAnswer->FeedbackEventQuestion->find('list',[
			'conditions' =>[
				'FeedbackEventQuestion.feedback_event_id' => $feedback_event_id, 
				'FeedbackEventQuestion.inform_id'=>1
				],
			'fields'=>['FeedbackEventQuestion.text']]);

		$content = [];
		$counter = 0;

		foreach ($questions as $key => $value) {
			$content[$counter] = $value;
			$counter++;
		}


        $chartName = 'Total Feedbacks';

        $mychart = $this->HighCharts->create( $chartName, 'column');

        $this->HighCharts->setChartParams (
                        $chartName,
                        array
                        (
                            'renderTo'                                  => 'columnwrapper',
                            'chartWidth'				=> 800,
                            'chartHeight'				=> 500,
                            'title'					=> 'Question VS Average',
                            'subtitle'                                  => 'FeedbackSystem',
                            'xAxisLabelsEnabled' 			=> FALSE,
                            'xAxisCategories'       	=> $content,
                            'yAxisTitleText' 		=> 'Average',
                            'enableAutoStep' 		=> TRUE,
                            'creditsEnabled'		=> FALSE,
                            'chartTheme'                => 'highroller',

                        )
                );

        $series = $this->HighCharts->addChartSeries();

        $series->addName('Total Feedback')->addData($ans);

        $mychart->addSeries($series);
    }


   	public function event_feedback_pie() {
	if ($this->request->is('post')) {
		$this->redirect(array('action' => 'pie_feedback',$this->request->data['FeedbackEventAnswer']['feedback_event_id']));
		}
		$feedbackEvents = $this->FeedbackEventAnswer->FeedbackEventQuestion->FeedbackEventManage->FeedbackEvent->find('list',['conditions' => ['FeedbackEvent.recstatus' => 1,
																					'FeedbackEvent.flag'=>1]]);
		$this->set(compact('feedbackEvents'));
	}

	public function event_feedback_pie_admin() {
	if ($this->request->is('post')) {
		$this->redirect(array('action' => 'pie_feedback',$this->request->data['FeedbackEventAnswer']['feedback_event_id']));
		}
		$feedbackEvents = $this->FeedbackEventAnswer->FeedbackEventQuestion->FeedbackEventManage->FeedbackEvent->find('list',['conditions' => ['FeedbackEvent.recstatus' => 1,
																					'FeedbackEvent.flag'=>1,'FeedbackEvent.institution_id'=>$this->Session->read('institution_id')]]);
		$this->set(compact('feedbackEvents'));
	}

	public function event_feedback_pie_coordinator() {
		if ($this->request->is('post')) {
			$this->redirect(array('action' => 'pie_feedback',$this->request->data['FeedbackEventAnswer']['feedback_event_id']));
		}
		$staffid = $this->Auth->user('staff_id'); 
		$feedback_manage = $this->FeedbackEventAnswer->FeedbackEventQuestion->FeedbackEvent->FeedbackEventManage->find('first',['conditions'=>['FeedbackEventManage.staff_id'=>$staffid,'FeedbackEventManage.recstatus'=>1],'fields'=>['FeedbackEventManage.feedback_event_id']]);
		$counter = 0;
		foreach($feedback_manage as $key=>$value) {
			foreach ($value as $key => $value1) {
				$data[$counter] = $value1;
					$counter++;
			}					
		}
		
		$feedbackEvents = $this->FeedbackEventAnswer->FeedbackEventQuestion->FeedbackEventManage->FeedbackEvent->find('list',['conditions' => ['FeedbackEvent.recstatus' => 1,
																					'FeedbackEvent.flag'=>1,'FeedbackEvent.id'=>$data,'FeedbackEvent.institution_id'=>$this->Session->read('institution_id')]]);
		
		$this->set(compact('feedbackEvents'));
	}


	public function pie_feedback($feedback_event_id) {			
		$question_id = $this->FeedbackEventAnswer->FeedbackEventQuestion->find('list',['conditions' =>['FeedbackEventQuestion.feedback_event_id' => $feedback_event_id, 
																							'FeedbackEventQuestion.inform_id'=>1],
																							'fields'=>['id']]);
		
		foreach ($question_id as $key => $value) {
			$count = $this->FeedbackEventAnswer->find('all',['conditions' =>['FeedbackEventAnswer.feedback_event_question_id' => $value],
														'recursive' => 1, 
														'fields' => ['AVG(FeedbackEventAnswer.answer) AS average']]);
		$answer[$key]= $count;
		}

		$counter = 0;

		foreach($answer as $key=>$value) {
			foreach ($value as $key => $value1) {
				foreach ($value1 as $key => $value2) {
					foreach ($value2 as $key => $value3) {
						$data[$counter] = $value3;
						$counter++;
					}	
				}
			}
		}

		$counter = 0;
		
		foreach ($data as $key => $value) {
			$ans[$counter]=(float)$value;
			$counter++;
		}
		
		$questions = $this->FeedbackEventAnswer->FeedbackEventQuestion->find('list',[
			'conditions' =>[
				'FeedbackEventQuestion.feedback_event_id' => $feedback_event_id, 
				'FeedbackEventQuestion.inform_id'=>1
				],
			'fields'=>['FeedbackEventQuestion.text']]);



		$content = [];
		$counter = 0;

		foreach ($questions as $key => $value) {
			$content[$counter] = $value;
			$counter++;
		}

		$content1 = [];
        $counter = 0;

       foreach ($content as $key => $value) {
        	$temp = [];
			array_push($temp, $value,(float)$ans[$counter]);
			$content1[$counter] = $temp;
			$counter++;
       }
		
		$chartData = $content1;
        $chartName = 'Total Feedbacks';

        $pieChart = $this->HighCharts->create( $chartName, 'pie' );

        $this->HighCharts->setChartParams(
                                            $chartName,
                                            array
                                            (
                                                'renderTo'				=> 'piewrapper',  // div to display chart inside
                                                'chartWidth'				=> 800,
                                                'chartHeight'				=> 500,
                                                'chartTheme'                            => 'grid',
                                                'title'					=> 'Total Feedbacks',
                                                'plotOptionsShowInLegend'		=> TRUE,
                                                'creditsEnabled' 			=> FALSE
                                            )
        );

        $series = $this->HighCharts->addChartSeries();

        $series->addName('Tickets')
            ->addData($chartData);

        $pieChart->addSeries($series);
    }
    
}