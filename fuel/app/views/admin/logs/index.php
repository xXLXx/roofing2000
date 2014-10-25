<h2>Listing Logs</h2>
<br>
<?php if ($logs): ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>User id</th>
			<th>Status id</th>
			<th>Location</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($logs as $item): ?>		<tr>

			<td><?php echo $item->user_id; ?></td>
			<td><?php echo $item->status_id; ?></td>
			<td><?php echo $item->location; ?></td>
			<td>
				<?php echo Html::anchor('admin/logs/view/'.$item->id, 'View'); ?> |
				<?php echo Html::anchor('admin/logs/edit/'.$item->id, 'Edit'); ?> |
				<?php echo Html::anchor('admin/logs/delete/'.$item->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>

			</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php else: ?>
<p>No Logs.</p>

<?php endif; ?><p>
	<?php echo Html::anchor('admin/logs/create', 'Add new Log', array('class' => 'btn btn-success')); ?>

</p>
