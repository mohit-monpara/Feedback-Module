
<?php echo $this->Html->script('FeedbackSystem.index_adm');?>
<?php echo $this->Form->create('FeedbackQuestion', array(
	'inputDefaults' => array(
		'div' => 'form-group',
		'wrapInput' => false,
		'class' => 'form-control',
		
	),
	'class' => 'well form-horizontal',
	'action'=> 'index_adm'
)); ?>

<h2><?php echo __('Feedback Categories: '); ?></h2>
	
<div id='wrapper'>
   <div id='origin1' class='fquestion'>
	<h3><?php echo __('Feedback Questions'); ?></h3>
	<table cellpadding="0" cellspacing="0" class="table table-striped">
	<tr>
		
			<th><?php echo $this->Paginator->sort('Add'); ?></th>
			<th><?php echo $this->Paginator->sort('Question'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ( $feedbackQuestions as $feedbackQuestion ): ?>	
	<tr>
    	<td>
    	<?php
    	 echo "<input type='checkbox' name='question[]' value={$feedbackQuestion['FeedbackQuestion']['id']} >";
        ?>

   		</td>
        
        <td ><?php echo h($feedbackQuestion['FeedbackQuestion']['text']); ?>&nbsp;</td>

		<td class="actions">
			

			<?php echo $this->Html->link(__('',true), array('action' => 'view', $feedbackQuestion['FeedbackQuestion']['id']),array('class' => 'glyphicon glyphicon-eye-open','data-toggle' => 'tooltip','Title'=>'View')); ?>
			<?php echo $this->Html->link(__('',true), array('action' => 'edit', $feedbackQuestion['FeedbackQuestion']['id']),array('class' => 'glyphicon glyphicon-edit','data-toggle' => 'tooltip','Title'=>'Edit')); ?>
			<?php
            if($feedbackQuestion['FeedbackQuestion']['recstatus'] == 1){

                 echo $this->Form->postLink(__('',true), array('action' => 'deactivate', $feedbackQuestion['FeedbackQuestion']['id']),array('class' => 'glyphicon glyphicon-remove','data-toggle' => 'tooltip','Title'=>'Deactivate', 'escape' => false),null, __('Are you sure you want to deactivate # %s?', $feedbackQuestion['FeedbackQuestion']['id']));
            }
        ?>
        <?php
            if($feedbackQuestion['FeedbackQuestion']['recstatus'] == 0){
                 echo $this->Form->postLink(__('',true), array('action' => 'activate', $feedbackQuestion['FeedbackQuestion']['id']),array('class' => 'glyphicon glyphicon-ok','data-toggle' => 'tooltip','Title'=>'Activate', 'escape' => false),null, __('Are you sure you want to activate # %s?', $feedbackQuestion['FeedbackQuestion']['id']));
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


<?php echo $this->Form->create('FeedbackQuestion', array(
	'inputDefaults' => array(
		'div' => 'form-group',
		'wrapInput' => false,
		'class' => 'form-control',
		
	),
	'class' => 'well form-horizontal',
	'action'=> 'indexquestionform1'
)); ?>

<h3><?php echo __('Feedback Questions Form'); ?></h3>
		<table cellpadding="0" cellspacing="0" class="table table-striped">
	<tr>
		
			<th><?php echo $this->Paginator->sort('Add'); ?></th>
			<th><?php echo $this->Paginator->sort('Question'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ( $feedbackQuestionform as $feedbackQuestion ): ?>	
	<tr>
    	<td>
    	<?php
    	 echo "<input type='checkbox' name='formquestion[]' value={$feedbackQuestion['FeedbackQuestion']['id']} >";

        ?>

   		</td>

        <td ><?php echo h($feedbackQuestion['FeedbackQuestion']['text']); ?>&nbsp;</td>

		<td class="actions">
			
			<?php echo $this->Html->link(__('',true), array('action' => 'view', $feedbackQuestion['FeedbackQuestion']['id']),array('class' => 'glyphicon glyphicon-eye-open','data-toggle' => 'tooltip','Title'=>'View')); ?>
			<?php echo $this->Html->link(__('',true), array('action' => 'edit', $feedbackQuestion['FeedbackQuestion']['id']),array('class' => 'glyphicon glyphicon-edit','data-toggle' => 'tooltip','Title'=>'Edit')); ?>

				<?php
            if($feedbackQuestion['FeedbackQuestion']['recstatus'] == 1){
                 echo $this->Form->postLink(__('',true), array('action' => 'deactivate', $feedbackQuestion['FeedbackQuestion']['id']),array('class' => 'glyphicon glyphicon-remove','data-toggle' => 'tooltip','Title'=>'Deactivate', 'escape' => false),null, __('Are you sure you want to deactivate # %s?', $feedbackQuestion['FeedbackQuestion']['id']));
            }
        ?>
        <?php
            if($feedbackQuestion['FeedbackQuestion']['recstatus'] == 0){
                echo $this->Form->postLink(__('',true), array('action' => 'activate', $feedbackQuestion['FeedbackQuestion']['id']),array('class' => 'glyphicon glyphicon-ok','data-toggle' => 'tooltip','Title'=>'Activate', 'escape' => false),null, __('Are you sure you want to activate # %s?', $feedbackQuestion['FeedbackQuestion']['id']));
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

