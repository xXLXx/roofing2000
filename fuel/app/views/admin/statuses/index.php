<h2>Listing Statuses</h2>
<br>
<?php if ($statuses): ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Name</th>
			<th>Prompt time</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($statuses as $item): ?>		<tr>

			<td><?php echo $item->name; ?></td>
			<td><?php echo $item->prompt_time; ?></td>
			<td>
				<?php echo Html::anchor('admin/statuses/view/'.$item->id, 'View'); ?> |
				<?php echo Html::anchor('admin/statuses/edit/'.$item->id, 'Edit'); ?> |
				<?php echo Html::anchor('admin/statuses/delete/'.$item->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>

			</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php else: ?>
<p>No Statuses.</p>

<?php endif; ?><p>
	<?php echo Html::anchor('admin/statuses/create', 'Add new Status', array('class' => 'btn btn-success')); ?>

</p>
