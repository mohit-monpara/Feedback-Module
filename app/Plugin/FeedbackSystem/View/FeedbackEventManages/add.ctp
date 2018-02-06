<div class="row">
          <div class="col-lg-6">
<?php echo $this->Html->script('FeedbackSystem.chain_coordinator1');?>
<div class="feedbackEventManages form">
<?php echo $this->Form->create('FeedbackEventManage', array(
	'inputDefaults' => array(
		'div' => 'form-group',
		'wrapInput' => false,
		'class' => 'form-control'
	),
	'class' => 'well form-horizontal'
)); ?>
	<fieldset>
		<legend><?php echo __('Add Event Coordinators'); ?></legend>
	<?php
	$url             = $this->Html->url(array('controller' => 'departments','plugin'=>false,
'action' => 'list_departments',
'ext' => 'json'
));
$urla            = $this->Html->url(array('controller' => 'staffs', 'plugin'=>false,
'action' => 'list_staff',
'ext' => 'json'
));

$emptyDepartment = count($departments) > 0 ? Configure::read('Select.defaultAfter') : array('0' => Configure::read('Select.naBefore') . __('Select Institution First') . Configure::read('Select.naAfter')
);
$emptyStaff     = count($staffs) > 0 ? Configure::read('Select.defaultAfter') : array('0' => Configure::read('Select.naBefore') . __('Select Department First') . Configure::read('Select.naAfter')
);

$emptyEvent = count($feedbackEvents) > 0 ? Configure::read('Select.defaultAfter') : array('0' => Configure::read('Select.naBefore') . __('Select Institution First') . Configure::read('Select.naAfter')
);

$urlb            = $this->Html->url(array('controller' => 'feedback_events', 'plugin'=>'feedback_system',
'action' => 'list_event',
'ext' => 'json'
));


echo $this->Form->input('UserRole.institution_id', array('id' => 'institutions','empty' => 'Please Select First',
'rel' => $url,
	'data-rel' => $urlb
));

echo $this->Form->input('UserRole.department_id', array('id' => 'departments','empty' => $emptyDepartment,'rel' => $urla));

echo $this->Form->input('feedback_event_id', array(
    'id' => 'feedback_events',
    'options' => $feedbackEvents,'empty' => $emptyEvent
));

echo $this->Form->input('staff_id', array('id' => 'staffs','empty' => $emptyStaff));

	?>
	<?php echo $this->Form->submit('Submit', array(
				'div' => false,
				'class' => 'btn btn-primary'
			)); ?>
	</fieldset>
<?php echo $this->Form->end(); ?>
</div>
</div>
</div>