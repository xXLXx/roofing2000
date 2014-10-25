<h2>Editing Log</h2>
<br>

<?php echo render('admin\logs/_form'); ?>
<p>
	<?php echo Html::anchor('admin/logs/view/'.$log->id, 'View'); ?> |
	<?php echo Html::anchor('admin/logs', 'Back'); ?></p>
