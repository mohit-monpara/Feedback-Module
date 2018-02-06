<div class="row">
          <div class="col-lg-6">	
<div class="feedbackEventAnswers form">
<?php echo $this->Form->create('FeedbackEventAnswer', array(
	'inputDefaults' => array(
		'div' => 'form-group',
		'wrapInput' => false,
		'class' => 'form-control'
	),
	'class' => 'well form-horizontal'
)); ?>

	<fieldset>
		<legend><?php echo __('Select Feedback Event'); ?></legend>
	
	<?php
	

	echo $this->Form->input('feedback_event_id', array(
    'id' => 'feedback_events'
));
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