<h2>Viewing #<?php echo $log->id; ?></h2>

<p>
	<strong>User id:</strong>
	<?php echo $log->user_id; ?></p>
<p>
	<strong>Status id:</strong>
	<?php echo $log->status_id; ?></p>
<p>
	<strong>Location:</strong>
	<?php echo $log->location; ?></p>

<?php echo Html::anchor('admin/logs/edit/'.$log->id, 'Edit'); ?> |
<?php echo Html::anchor('admin/logs', 'Back'); ?>