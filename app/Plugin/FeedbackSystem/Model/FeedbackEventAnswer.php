<?php
App::uses('FeedbackSystemAppModel', 'FeedbackSystem.Model');
/**
 * FeedbackAnswer Model
 *
 */
class FeedbackEventAnswer extends FeedbackSystemAppModel {

	/**
 * Validation rules
 *
 * @var array
 */

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = ['FeedbackSystem.FeedbackEventQuestion','User'];

}
