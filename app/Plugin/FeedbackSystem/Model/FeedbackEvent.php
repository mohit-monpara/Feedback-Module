<?php
App::uses('FeedbackSystemAppModel', 'FeedbackSystem.Model');
/**
 * FeedbackEvent Model
 *
 * @property Created $Created
 * @property Modifier $Modifier
 * @property Event $Event
 */
class FeedbackEvent extends FeedbackSystemAppModel {

/**
 * Validation rules
 *
 * @var array
 */

	public $displayField = 'name';
	public $validate = array(
    'name' => array(
        'required' => array(
            'rule' => array('checkUnique', array('name', 'institution_id','department_id')),
'message' => 'Event already exists for given Institution.',
        )
    ),
);

    public $belongsTo = ['Institution','Department','Staff','FeedbackSystem.AcademicYear'];
    public $hasMany = ['FeedbackSystem.FeedbackEventManage','FeedbackSystem.FeedbackEventQuestion'];
 

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


     public function getListByEventwise($cid = null) {
        if (empty($cid)) {
            return array();
        }
        
        return $this->find('list', array(
            'conditions' =>  array($this->alias . '.feedback_event_id' => $cid ,$this->alias . '.recstatus' => 1,
                 $this->alias . '.flag' => 0)
            
        ));
    }

}
