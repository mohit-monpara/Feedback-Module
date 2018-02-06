<div class="table-responsive">
<div class="questions view">
<h2><?php echo __('FeedbackQuestion'); ?></h2>
	<table class="table table-striped">
		<tr>
		<th><?php echo __('Question'); ?></th>
		<td>
			<?php echo h($feedbackQuestion['FeedbackQuestion']['text']); ?>
			&nbsp;
		</td>
		</tr>
		<tr>
		<th><?php echo __('Recstatus'); ?></th>
		<td>
			<?php if($feedbackQuestion['FeedbackQuestion']['recstatus'] ==1 ){
				echo "Active";
				} else {
					echo "Deactive";
				} ?>
			&nbsp;
		</td>
		</tr>
	</table>
</div>