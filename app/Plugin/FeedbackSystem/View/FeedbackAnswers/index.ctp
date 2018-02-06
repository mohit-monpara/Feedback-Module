<?php echo $this->Html->script('FeedbackSystem.jquery.form');?>
<?php echo $this->Html->script('FeedbackSystem.jquery.MetaData');?>
<?php echo $this->Html->script('FeedbackSystem.jquery.rating');?>
<?php echo $this->Html->script('FeedbackSystem.jquery.rating.pack');?>
<?php echo $this->Html->css('FeedbackSystem.jquery.rating');?>
<?php echo $this->Form->create('FeedbackAnswer', array(
	'inputDefaults' => array(
		'div' => 'form-group',
		'wrapInput' => false,
		'class' => 'form-control'
	),
	'class' => 'well form-horizontal'
)); ?>
<div id='wrapper'>
<div class="feedbackAnswers form">
<h2><?php echo __('Feedback Form '); ?></h2>
<div class="row">
<div class="page-header">
<table cellpadding="0" cellspacing="0" class="table table-striped">
	<tr>

			<th><?php echo 'No.' ?></th>
			<th><?php echo 'Question' ?></th>	
			<th><?php echo __('Rating'); ?></th>	
	</tr>
	<?php $counter  = 1; ?>	
	<?php 
	$i = 0;

	foreach ( $feedbackAnswers as $feedbackAnswer ): ?>	
	<tr>
	
			<td><?php echo $counter++; ?>&nbsp;</td>	
	
	<td>
			 <?php echo h($feedbackAnswer['FeedbackQuestion']['text']); ?>&nbsp;
	</td>
	<td class="actions">
		<?php
    	echo $this->Form->input($i.".feedback_question_id",['type' => 'hidden','value' => $feedbackAnswer['FeedbackQuestion']['id']]);

    	echo $this->Form->input($i.".user_id",['type' => 'hidden','value' => Auth::User('id')]);
    	
		echo $this->Form->input($i.".answer",['type' => 'radio','class' => 'star','options' => [1=>'',2=>'',3=>'',4=>'',5=>'']]);
    	
    	?>
		
    </td>

</tr>
<?php $i++; endforeach; ?>		
			

		</table>
		</div>
		</div>
		</div>
		</div>
<?php echo $this->Form->submit('Submit', array(
				'div' => false,
				'class' => 'btn btn-primary'
			)); ?>
<?php echo $this->Form->end(); ?>