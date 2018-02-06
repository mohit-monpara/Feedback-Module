<div class="row">
          <div class="col-lg-6">
<?php echo $this->Html->script('FeedbackSystem.chain_dropdown');?>
<div class="Feedbackevents form">
<?php echo $this->Form->create('FeedbackEvent', array(
	'inputDefaults' => array(
		'div' => 'form-group',
		'wrapInput' => false,
		'class' => 'form-control'
	),
	'class' => 'well form-horizontal'
)); ?>

	<fieldset>
		<legend><?php echo __('Add Event(Department-Wise)'); ?></legend>
	<?php


	$url             = $this->Html->url(array('controller' => 'departments','plugin'=>false,
		'action' => 'list_departments',
		'ext' => 'json'
		));
		$emptyDepartment = count($departments) > 0 ? Configure::read('Select.defaultAfter') : array('0' => Configure::read('Select.naBefore') . __('Select Institution First') . Configure::read('Select.naAfter')
		);


echo $this->Form->input('institution_id', array('id' => 'institutions','empty' => 'Please Select First','rel' => $url));
		echo $this->Form->input('department_id',array('id' => 'departments','empty' => $emptyDepartment));
/*
		echo $this->Form->input('year_id', array(
    'options' => $years
));*/

echo $this->Form->input('name',['autocomplete' => 'off','label'=>'New Event']);
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
