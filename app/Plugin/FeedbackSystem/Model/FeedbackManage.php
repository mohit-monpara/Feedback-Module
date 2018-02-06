<?php
App::uses('FeedbackSystemAppModel', 'FeedbackSystem.Model');
/**
 * TicketManage Model
 *
 * @property Category $Category
 * @property Staff $Staff
 */
class FeedbackManage extends FeedbackSystemAppModel {

/**
 * Validation rules
 *
 * @var array
 */
public $validate = array(
     'feedback_category_id' => array(
        	
        
       	
    ),
);
	//The Associations below have been created with all possible keys, those that are not needed can be removed
/**
 * belongsTo associations
 *
 * @var array
 */
 //public $belongsTo = ['Institution','Department','Staff'];
	public $belongsTo = array('FeedbackSystem.FeedbackCategory','Staff');//'FeedbackSystem.FeedbackManage'
	public $hasMany = ['FeedbackSystem.FeedbackQuestion','FeedbackSystem.FeedbackAnswer'];
}