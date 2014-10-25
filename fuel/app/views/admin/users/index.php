<h2>Listing Users</h2>
<br>
<?php if ($users): ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Username</th>
			<th>Password</th>
			<th>First name</th>
			<th>Last name</th>
			<th>Email</th>
			<th>Group</th>
			<th>Profile fields</th>
			<th>Last login</th>
			<th>Login hash</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($users as $item): ?>		<tr>

			<td><?php echo $item->username; ?></td>
			<td><?php echo $item->password; ?></td>
			<td><?php echo $item->first_name; ?></td>
			<td><?php echo $item->last_name; ?></td>
			<td><?php echo $item->email; ?></td>
			<td><?php echo $item->group; ?></td>
			<td><?php echo $item->profile_fields; ?></td>
			<td><?php echo $item->last_login; ?></td>
			<td><?php echo $item->login_hash; ?></td>
			<td>
				<?php echo Html::anchor('admin/users/view/'.$item->id, 'View'); ?> |
				<?php echo Html::anchor('admin/users/edit/'.$item->id, 'Edit'); ?> |
				<?php echo Html::anchor('admin/users/delete/'.$item->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>

			</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php else: ?>
<p>No Users.</p>

<?php endif; ?><p>
	<?php echo Html::anchor('admin/users/create', 'Add new User', array('class' => 'btn btn-success')); ?>

</p>
