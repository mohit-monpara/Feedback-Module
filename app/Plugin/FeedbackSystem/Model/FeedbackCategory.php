<?php
App::uses('FeedbackSystemAppModel', 'FeedbackSystem.Model');
/**
 * FeedbackCategory Model
 *
 * @property Creator $Creator
 * @property Modifier $Modifier
 */
class FeedbackCategory extends FeedbackSystemAppModel {

/**
 * Validation rules
 *
 * @var array
 */
/*public $displayField = 'name';
	public $validate = array(
    'name' => array(
        'required' => array(
            'rule' => array('notEmpty'),
            'message' => 'You must enter a category.'
        ),
        'unique' => array(
            'rule'    => 'isUnique',
            'message' => 'This category already exists'
        ),
    ),
);*/
	//The Associations below have been created with all possible keys, those that are not needed can be removed
/**
     * Checks a record, if it is unique - depending on other fields in this table (transfered as array)
     * example in model: 'rule' => array ('validateUnique', array('belongs_to_table_id','some_id','user_id')),
     * if all keys (of the array transferred) match a record, return false, otherwise true
     *
     * @param array $fields Other fields to depend on
     * TODO: add possibity of deep nested validation (User -> Comment -> CommentCategory: UNIQUE comment_id, Comment.user_id)
     * @param array $options
     * - requireDependentFields Require all dependent fields for the validation rule to return true
     * @return boolean Success
     */
/*public $validate = [
                'name' => [
                    'rule' => ['validateUnique', ['institution_id','name']],
                    'message' => 'This category already exists'    
            ]
            ];*/

public $validate = array
(
'name' => array
(
'unique' => array
(
'rule' => array('checkUnique', array('name', 'institution_id')),
'message' => 'Category already exists for given Institution.',
)
),
);
	public $hasMany = ['FeedbackSystem.FeedbackManage','FeedbackSystem.FeedbackQuestion','FeedbackSystem.FeedbackAnswer'];

    public $belongsTo = array('Staff','Institution');


    public function getListByInstitution($cid = null) {
        if (empty($cid)) {
            return array();
        }
        
        return $this->find('list', array(
            'conditions' =>  array($this->alias . '.institution_id' => $cid ,$this->alias . '.recstatus' => 1,
                 $this->alias . '.flag' => 0)
            
        ));
    }


       public function getListByDepartment($cid = null) {
        if (empty($cid)) {
            return array();
        }
        
        return $this->find('list', array(
            'conditions' =>  array($this->alias . '.institution_id' => $cid ,$this->alias . '.recstatus' => 1,
                $this->alias . '.flag' => 0)
            
        ));
    }


     public function getListByCategorywise($cid = null) {
        if (empty($cid)) {
            return array();
        }
        
        return $this->find('list', array(
            'conditions' =>  array($this->alias . '.feedback_category_id' => $cid ,$this->alias . '.recstatus' => 1,
                 $this->alias . '.flag' => 0)
            
        ));
    }


}
