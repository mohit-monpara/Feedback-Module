<div class="table-responsive"><div class="table-responsive">
<div class="feedbackManages index">
	<h2><?php echo __('Feedback Coordinators'); ?></h2>
	<table cellpadding="0" cellspacing="0" class="table table-striped">
	<tr>
			<th><?php echo $this->Paginator->sort('Sr.No.'); ?></th>
			<th><?php echo $this->Paginator->sort('category_id'); ?></th>
			<th><?php echo $this->Paginator->sort('staff_id'); ?></th>
			<th><?php echo $this->Paginator->sort('department_id'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php $counter  = 1; ?>
	<?php foreach ($feedbackManages as $feedbackManage): ?>
	<tr>
		<td><?php echo $counter++; ?>&nbsp;</td>

		<td>
			<?php echo $this->Html->link($feedbackManage['FeedbackCategory']['name'], array('plugin'=>'feedback_system','controller' => 'feedback_categories', 'action' => 'view', $feedbackManage['FeedbackCategory']['id'])); ?>
		</td>
	
		<td>
			<?php echo $this->Html->link($feedbackManage['Staff']['name'], array('plugin'=>'feedback_system','controller' => 'staffs', 'action' => 'view', $feedbackManage['Staff']['id'])); ?>
		</td>

			<td><?php echo h($feedbackManage['Staff']['Department']['name']); ?>&nbsp;</td>
		
		<td class="actions">
			<?php echo $this->Html->link(__('',true), array('action' => 'view', $feedbackManage['FeedbackManage']['id']),array('class' => 'glyphicon glyphicon-eye-open','data-toggle' => 'tooltip','Title'=>'View')); ?>
		<?php
            if($feedbackManage['FeedbackManage']['recstatus'] == 1){
                 echo $this->Form->postLink(__('',true), array('action' => 'deactivate', $feedbackManage['FeedbackManage']['id']),array('class' => 'glyphicon glyphicon-remove','data-toggle' => 'tooltip','Title'=>'Deactivate', 'escape' => false),null, __('Are you sure you want to deactivate # %s?', $feedbackManage['FeedbackManage']['id']));
            }
        ?>
        <?php
            if($feedbackManage['FeedbackManage']['recstatus'] == 0){
                echo $this->Form->postLink(__('',true), array('action' => 'activate', $feedbackManage['FeedbackManage']['id']),array('class' => 'glyphicon glyphicon-ok','data-toggle' => 'tooltip','Title'=>'Activate', 'escape' => false),null, __('Are you sure you want to activate # %s?', $feedbackManage['FeedbackManage']['id']));

            }
        ?>
       
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<ul class="pagination pagination-large pull-right">
                        <?php
                            echo $this->Paginator->prev(__('prev'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
                            echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1));
                            echo $this->Paginator->next(__('next'), array('tag' => 'li','currentClass' => 'disabled'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a'));
                        ?>
                    </ul>
</div>
</div>
</div>