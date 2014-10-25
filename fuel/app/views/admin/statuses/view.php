<h2>Viewing #<?php echo $status->id; ?></h2>

<p>
	<strong>Name:</strong>
	<?php echo $status->name; ?></p>
<p>
	<strong>Prompt time:</strong>
	<?php echo $status->prompt_time; ?></p>

<?php echo Html::anchor('admin/statuses/edit/'.$status->id, 'Edit'); ?> |
<?php echo Html::anchor('admin/statuses', 'Back'); ?>