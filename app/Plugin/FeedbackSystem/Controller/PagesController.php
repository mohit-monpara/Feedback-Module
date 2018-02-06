<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('FeedbackSystemAppController', 'FeedbackSystem.Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class PagesController extends FeedbackSystemAppController {

/**
 * This controller does not use a model
 *
 * @var array
 */

    public function dashboard() {
        $this->loadModel('FeedbackCategory');
        $this->loadModel('FeedbackEvent');

		$devcategories = $this->FeedbackCategory->find('count');
        $devactivecategories = $this->FeedbackCategory->find('count',['conditions'=>['FeedbackCategory.recstatus'=>1,'FeedbackCategory.flag'=>1]]);
		       
        $categories = $this->FeedbackCategory->find('count');
        $activecategories = $this->FeedbackCategory->find('count',['conditions'=>['FeedbackCategory.institution_id'=>$this->Session->read('institution_id'),'FeedbackCategory.recstatus'=>1,'FeedbackCategory.flag'=>1]]);

        $devevents = $this->FeedbackEvent->find('count');
        $devactiveevents = $this->FeedbackEvent->find('count',['conditions'=>['FeedbackEvent.recstatus'=>1,'FeedbackEvent.flag'=>1]]);
       
        $events = $this->FeedbackEvent->find('count');
        $activeevents = $this->FeedbackEvent->find('count',['conditions'=>['FeedbackEvent.institution_id'=>$this->Session->read('institution_id'),'FeedbackEvent.recstatus'=>1,'FeedbackEvent.flag'=>1]]);
       
        $this->set(compact('categories','activecategories','events','activeevents','institutions','devcategories','devactivecategories','devevents','devactiveevents'));

    }
}
