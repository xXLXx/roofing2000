<h2>Editing Status</h2>
<br>

<?php echo render('admin\statuses/_form'); ?>
<p>
	<?php echo Html::anchor('admin/statuses/view/'.$status->id, 'View'); ?> |
	<?php echo Html::anchor('admin/statuses', 'Back'); ?></p>
