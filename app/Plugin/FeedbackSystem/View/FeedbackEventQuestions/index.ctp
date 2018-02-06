
<?php echo $this->Html->script('FeedbackSystem.index');?>
<?php echo $this->Form->create('FeedbackEventQuestion', array(
	'inputDefaults' => array(
		'div' => 'form-group',
		'wrapInput' => false,
		'class' => 'form-control',
		
	),
	'class' => 'well form-horizontal',
	'action'=> 'index'
)); ?>
<div id='wrapper'>
   <div id='origin1' class='fquestion'>
	<h3><?php echo __('Event Questions'); ?></h3>
	<table cellpadding="0" cellspacing="0" class="table table-striped">
	<tr>
		
			<th><?php echo $this->Paginator->sort('Add'); ?></th>
			<th><?php echo $this->Paginator->sort('event_id'); ?></th>
			<th><?php echo $this->Paginator->sort('Question'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ( $feedbackEventQuestions as $feedbackEventQuestion ): ?>	
	<tr>
    	<td>
    	<?php
    	 echo "<input type='checkbox' name='question[]' value={$feedbackEventQuestion['FeedbackEventQuestion']['id']} >";

        ?>

   		</td>
       <td>
			<?php echo $this->Html->link($feedbackEventQuestion['FeedbackEvent']['name'], array('plugin'=>'feedback_system','controller' => 'feedback_events', 'action' => 'view',$feedbackEventQuestion['FeedbackEvent']['id'] )); ?>
		</td>
        <td ><?php echo h($feedbackEventQuestion['FeedbackEventQuestion']['text']); ?>&nbsp;</td>

		<td class="actions">
			
			<?php echo $this->Html->link(__('',true), array('action' => 'view', $feedbackEventQuestion['FeedbackEventQuestion']['id']),array('class' => 'glyphicon glyphicon-eye-open','data-toggle' => 'tooltip','Title'=>'View')); ?>
			<?php echo $this->Html->link(__('',true), array('action' => 'edit', $feedbackEventQuestion['FeedbackEventQuestion']['id']),array('class' => 'glyphicon glyphicon-edit','data-toggle' => 'tooltip','Title'=>'Edit')); ?>
			

			<?php
            if($feedbackEventQuestion['FeedbackEventQuestion']['recstatus'] == 1){

                 echo $this->Form->postLink(__('',true), array('action' => 'deactivate', $feedbackEventQuestion['FeedbackEventQuestion']['id']),array('class' => 'glyphicon glyphicon-remove','data-toggle' => 'tooltip','Title'=>'Deactivate', 'escape' => false),null, __('Are you sure you want to deactivate # %s?', $feedbackEventQuestion['FeedbackEventQuestion']['id']));
            }
        ?>
        <?php
            if($feedbackEventQuestion['FeedbackEventQuestion']['recstatus'] == 0){

                 echo $this->Form->postLink(__('',true), array('action' => 'activate', $feedbackEventQuestion['FeedbackEventQuestion']['id']),array('class' => 'glyphicon glyphicon-ok','data-toggle' => 'tooltip','Title'=>'Activate', 'escape' => false),null, __('Are you sure you want to activate # %s?', $feedbackEventQuestion['FeedbackEventQuestion']['id']));
            }
        ?>
		</td>
	</tr>
	

<?php endforeach; ?>
	</table>

		<?php echo $this->Form->submit('Add', array(
				'div' => false,
				'class' => 'btn btn-primary'
			)); ?>
	</div>
</div>
<?php echo $this->Form->end(); ?>


<?php echo $this->Form->create('FeedbackEventQuestion', array(
	'inputDefaults' => array(
		'div' => 'form-group',
		'wrapInput' => false,
		'class' => 'form-control',
		
	),
	'class' => 'well form-horizontal',
	'action'=> 'indexquestionform'
)); ?>

<h3><?php echo __('Feedback Questions Form'); ?></h3>
		<table cellpadding="0" cellspacing="0" class="table table-striped">
	<tr>
		
			<th><?php echo $this->Paginator->sort('Add'); ?></th>
			<th><?php echo $this->Paginator->sort('event_id'); ?></th>
			<th><?php echo $this->Paginator->sort('Question'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ( $feedbackEventQuestionform as $feedbackEventQuestion ): ?>	
	<tr>
    	<td>
    	<?php
    	 echo "<input type='checkbox' name='formquestion[]' value={$feedbackEventQuestion['FeedbackEventQuestion']['id']} >";

        ?>

   		</td>
       <td>
			<?php echo $this->Html->link($feedbackEventQuestion['FeedbackEvent']['name'], array('plugin'=>'feedback_system','controller' => 'feedback_events', 'action' => 'view',$feedbackEventQuestion['FeedbackEvent']['id'] )); ?>
		</td>
        <td ><?php echo h($feedbackEventQuestion['FeedbackEventQuestion']['text']); ?>&nbsp;</td>

		<td class="actions">


			<?php echo $this->Html->link(__('',true), array('action' => 'view', $feedbackEventQuestion['FeedbackEventQuestion']['id']),array('class' => 'glyphicon glyphicon-eye-open','data-toggle' => 'tooltip','Title'=>'View')); ?>
			<?php echo $this->Html->link(__('',true), array('action' => 'edit', $feedbackEventQuestion['FeedbackEventQuestion']['id']),array('class' => 'glyphicon glyphicon-edit','data-toggle' => 'tooltip','Title'=>'Edit')); ?>
			
			<?php
            if($feedbackEventQuestion['FeedbackEventQuestion']['recstatus'] == 1){

                 echo $this->Form->postLink(__('',true), array('action' => 'deactivate', $feedbackEventQuestion['FeedbackEventQuestion']['id']),array('class' => 'glyphicon glyphicon-remove','data-toggle' => 'tooltip','Title'=>'Deactivate', 'escape' => false),null, __('Are you sure you want to deactivate # %s?', $feedbackEventQuestion['FeedbackEventQuestion']['id']));
            }
        	?>
        	<?php
            if($feedbackEventQuestion['FeedbackEventQuestion']['recstatus'] == 0){

                 echo $this->Form->postLink(__('',true), array('action' => 'activate', $feedbackEventQuestion['FeedbackEventQuestion']['id']),array('class' => 'glyphicon glyphicon-ok','data-toggle' => 'tooltip','Title'=>'Activate', 'escape' => false),null, __('Are you sure you want to activate # %s?', $feedbackEventQuestion['FeedbackEventQuestion']['id']));
            }
        	?>
		</td>
	</tr>
	

<?php endforeach; ?>
	</table>


	<?php echo $this->Form->submit('Update', array(
				'div' => false,
				'class' => 'btn btn-primary'
			)); ?>
	</div>
</div>
<?php echo $this->Form->end(); ?>