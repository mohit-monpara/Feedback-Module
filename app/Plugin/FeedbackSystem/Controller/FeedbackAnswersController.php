<?php
App::uses('FeedbackSystemAppController', 'FeedbackSystem.Controller');
/**
 * FeedbackAnswers Controller
 *
 * @property FeedbackAnswer $FeedbackAnswer
 * @property PaginatorComponent $Paginator
 */
class FeedbackAnswersController extends FeedbackSystemAppController {

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
	public function index($category = null) {
		if (!$this->FeedbackAnswer->FeedbackQuestion->FeedbackCategory->exists($category)) {
			throw new NotFoundException(__('Invalid Feedback Category'));
			return $this->redirect(array('controller'=>'feedback_questions','action'=>'category_show'));
		}
		if ($this->request->is('post')) {
			$this->FeedbackAnswer->create();			
			if ($this->FeedbackAnswer->saveMany($this->request->data['FeedbackAnswer'])) {			    	
				$this->Session->setFlash(__('The feedback answer has been saved.'), 'default', array('class' => 'alert alert-success'));
				return $this->redirect(array('controller'=>'feedback_questions','action' => 'category_show'));
			} else {
				$this->Session->setFlash(__('The feedback answer could not be saved. Please, try again.'));
			}
		}
			$data = $this->FeedbackAnswer->FeedbackQuestion->find('all',['conditions' => [
																'FeedbackQuestion.inform_id'=>1,
																'FeedbackQuestion.recstatus'=>1,
																'FeedbackQuestion.feedback_category_id'=>$category
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
		if (!$this->FeedbackAnswer->exists($id)) {
			throw new NotFoundException(__('Invalid feedback answer'));
		}
		$options = array('conditions' => array('FeedbackAnswer.' . $this->FeedbackAnswer->primaryKey => $feedback_question_id));
		$this->set('feedbackAnswer', $this->FeedbackAnswer->find('first', $options));
	}


	public function export_all() {     
  		$this->set('feedbackAnswers', $this->FeedbackAnswer->find('all',
  			[ 
				'fields' => ['FeedbackAnswer.created','FeedbackAnswer.feedback_question_id','FeedbackAnswer.answer','FeedbackAnswer.user_id'],
				'contain' => [
					'FeedbackQuestion' => [
						'fields' => ['FeedbackQuestion.text']
										],
					'User' =>[
						'fields' => ['User.username']
					]
				]

  			]));
    	$this->layout = null;
   		$this->autoLayout = false;
	}


	public function category_feedback() {
		if ($this->request->is('post')) {
			$this->redirect(array('action' => 'column_feedbacks',$this->request->data['FeedbackAnswer']['feedback_category_id']));
		}
		$feedbackCategories = $this->FeedbackAnswer->FeedbackQuestion->FeedbackManage->FeedbackCategory->find('list',['conditions' => ['FeedbackCategory.recstatus' => 1,
																					'FeedbackCategory.flag'=>1]]);
		$this->set(compact('feedbackCategories'));
	}


	public function category_feedback_admin() {
		if ($this->request->is('post')) {
			$this->redirect(array('action' => 'column_feedbacks',$this->request->data['FeedbackAnswer']['feedback_category_id']));
		}
		$feedbackCategories = $this->FeedbackAnswer->FeedbackQuestion->FeedbackManage->FeedbackCategory->find('list',['conditions' => ['FeedbackCategory.recstatus' => 1,
																					'FeedbackCategory.flag'=>1,'FeedbackCategory.institution_id'=>$this->Session->read('institution_id')]]);
				
		$this->set(compact('feedbackCategories'));
	}


	public function category_feedback_coordinator() {
		if ($this->request->is('post')) {

			$this->redirect(array('action' => 'column_feedbacks',$this->request->data['FeedbackAnswer']['feedback_category_id']));
		}
		$staffid = $this->Auth->user('staff_id'); 
		
		$feedback_manage = $this->FeedbackAnswer->FeedbackQuestion->FeedbackCategory->FeedbackManage->find('first',['conditions'=>['FeedbackManage.staff_id'=>$staffid,'FeedbackManage.recstatus'=>1],'fields'=>['FeedbackManage.feedback_category_id']]);
		
		$counter = 0;
		foreach($feedback_manage as $key=>$value) {
			foreach ($value as $key => $value1) {
				$data[$counter] = $value1;
					$counter++;
			}
					
		}
		
		$feedbackCategories = $this->FeedbackAnswer->FeedbackQuestion->FeedbackManage->FeedbackCategory->find('list',['conditions' => ['FeedbackCategory.recstatus' => 1,
																					'FeedbackCategory.flag'=>1,'FeedbackCategory.id'=>$data,'FeedbackCategory.institution_id'=>$this->Session->read('institution_id')]]);
		
		$this->set(compact('feedbackCategories'));
	}

	
	public function column_feedbacks($feedback_category_id) {
	
		$question_id = $this->FeedbackAnswer->FeedbackQuestion->find('list',['conditions' =>['FeedbackQuestion.feedback_category_id' => $feedback_category_id, 
																							'FeedbackQuestion.inform_id'=>1],
																							'fields'=>['id']]);
		
		foreach ($question_id as $key => $value) {
			$count = $this->FeedbackAnswer->find('all',['conditions' =>['FeedbackAnswer.feedback_question_id' => $value],
														'recursive' => 1, 
														'fields' => ['AVG(FeedbackAnswer.answer) AS average']]);
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

		
		$questions = $this->FeedbackAnswer->FeedbackQuestion->find('list',[
			'conditions' =>[
				'FeedbackQuestion.feedback_category_id' => $feedback_category_id, 
				'FeedbackQuestion.inform_id'=>1
				],
			'fields'=>['FeedbackQuestion.text']]);

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


    public function category_feedback_pie() {
		if ($this->request->is('post')) {
			$this->redirect(array('action' => 'pie_feedback',$this->request->data['FeedbackAnswer']['feedback_category_id']));
		}
		$feedbackCategories = $this->FeedbackAnswer->FeedbackQuestion->FeedbackManage->FeedbackCategory->find('list',['conditions' => ['FeedbackCategory.recstatus' => 1,
																					'FeedbackCategory.flag'=>1]]);
		$this->set(compact('feedbackCategories'));
	}


   public function category_feedback_pie_admin() {
		if ($this->request->is('post')) {
			$this->redirect(array('action' => 'pie_feedback',$this->request->data['FeedbackAnswer']['feedback_category_id']));
		}
		$feedbackCategories = $this->FeedbackAnswer->FeedbackQuestion->FeedbackManage->FeedbackCategory->find('list',['conditions' => ['FeedbackCategory.recstatus' => 1,
																					'FeedbackCategory.flag'=>1,'FeedbackCategory.institution_id'=>$this->Session->read('institution_id')]]);
		$this->set(compact('feedbackCategories'));
	}

	
	public function category_feedback_pie_coordinator() {
		if ($this->request->is('post')) {

			$this->redirect(array('action' => 'pie_feedback',$this->request->data['FeedbackAnswer']['feedback_category_id']));
		}
		$staffid = $this->Auth->user('staff_id'); 		
		$feedback_manage = $this->FeedbackAnswer->FeedbackQuestion->FeedbackCategory->FeedbackManage->find('first',['conditions'=>['FeedbackManage.staff_id'=>$staffid,'FeedbackManage.recstatus'=>1],'fields'=>['FeedbackManage.feedback_category_id']]);
		$counter = 0;
		foreach($feedback_manage as $key=>$value) {
			foreach ($value as $key => $value1) {
				$data[$counter] = $value1;
					$counter++;
			}			
		}
		$feedbackCategories = $this->FeedbackAnswer->FeedbackQuestion->FeedbackManage->FeedbackCategory->find('list',['conditions' => ['FeedbackCategory.recstatus' => 1,
																					'FeedbackCategory.flag'=>1,'FeedbackCategory.id'=>$data,'FeedbackCategory.institution_id'=>$this->Session->read('institution_id')]]);
		
		$this->set(compact('feedbackCategories'));
	}


	public function pie_feedback($feedback_category_id) {
			
		$question_id = $this->FeedbackAnswer->FeedbackQuestion->find('list',['conditions' =>['FeedbackQuestion.feedback_category_id' => $feedback_category_id, 
																							'FeedbackQuestion.inform_id'=>1],
																							'fields'=>['id']]);
		
		foreach ($question_id as $key => $value) {
			$count = $this->FeedbackAnswer->find('all',['conditions' =>['FeedbackAnswer.feedback_question_id' => $value],
														'recursive' => 1, 
														'fields' => ['AVG(FeedbackAnswer.answer) AS average']]);
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
		
		$questions = $this->FeedbackAnswer->FeedbackQuestion->find('list',[
			'conditions' =>[
				'FeedbackQuestion.feedback_category_id' => $feedback_category_id, 
				'FeedbackQuestion.inform_id'=>1
				],
			'fields'=>['FeedbackQuestion.text']]);

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
        $series->addName('Feedbacks')->addData($chartData);
        $pieChart->addSeries($series);

    }
}