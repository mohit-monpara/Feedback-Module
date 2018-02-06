<?php
App::uses('FeedbackSystemAppModel', 'FeedbackSystem.Model');
/**
 * FeedbackEventManage Model
 *
 * @property Creator $Creator
 * @property Modifier $Modifier
 * @property Event $Event
 * @property Staff $Staff
 */
class FeedbackEventManage extends FeedbackSystemAppModel {

/**
 * Validation rules
 *
 * @var array
 */
public $validate = array(
     'category_id' => array(
        'required' => array(
            'rule' => array('notEmpty'),
            'message' => 'You have to select category'
        ),
    ),
);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array('FeedbackSystem.FeedbackEvent','Staff');
	public $hasMany = ['FeedbackSystem.FeedbackEventQuestion','FeedbackSystem.FeedbackEventAnswer'];
}
