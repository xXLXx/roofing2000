<div id="student-view" class="text-center">
	<h2>Viewing #<?php echo $user->id; ?></h2>

	<p>
		<strong>Username:</strong>
		<?php echo $user->username; ?></p>
	<p>
		<strong>First name:</strong>
		<?php echo ucwords($user->first_name); ?></p>
	<p>
		<strong>Last name:</strong>
		<?php echo ucwords($user->last_name); ?></p>
	<p>
		<strong>Email:</strong>
		<?php echo $user->email; ?></p>
	<p>
		<strong>Group:</strong>
		<?php echo Config::get('simpleauth')['groups'][$user->group]['name']; ?></p>
	<p>
		<strong>Last login:</strong>
		<?php echo Date::forge($user->last_login); ?></p>
</div>
<div class="text-right">
	<?php echo Html::anchor('admin/users/edit/'.$user->id, '<span class="glyphicon glyphicon-pencil"></span>'); ?> | 
	<?php echo Html::anchor('admin/users', '<span class="glyphicon glyphicon-share-alt"></span>'); ?> 
</div>