<div class="table-responsive">
<div class="events view">
<h2><?php echo __('FeedbackEvent'); ?></h2>
	<table class="table table-striped">
		<tr>
		<th><?php echo __('Name'); ?></th>
		<td>
			<?php echo h($feedbackevent['FeedbackEvent']['name']); ?>
			&nbsp;
		</td>
		</tr>
		<tr>
		<th><?php echo __('Recstatus'); ?></th>
		<td>
			<?php if($feedbackevent['FeedbackEvent']['recstatus'] ==1 ){
				echo "Active";
				} else {
					echo "Deactive";
				} ?>
			&nbsp;
		</td>
		</tr>
		<tr>
		<th><?php echo __('Institution-Id'); ?></th>
		<td>
		<?php echo h($feedbackevent['FeedbackEvent']['institution_id']); ?>
			&nbsp;
		</td>
		</tr>

		<tr>
		<th><?php echo __('Institution'); ?></th>
		<td>
		<?php echo h($feedbackevent['Institution']['name']); ?>
			&nbsp;
		</td>
		</tr>
		
		<?php if(Auth::hasRoles(['developer','fbadmin','superadmin'])) {?>
		<tr>
		
		<th><?php echo __('Department'); ?></th>
		<td>
		
		
		 <?php echo h($feedbackevent['Department']['name']); ?>
			&nbsp;
		
	
		</td>
		</tr>
		<?php } ?>
	</table>
</div>