<div class="text-center">
	<h2>Editing User #<?= $user->id ?></h2>
	<br>

	<?php echo render('admin\users/_form'); ?>
	<div class="text-right">
		<?php echo Html::anchor('admin/users/view/'.$user->id, '<span class="glyphicon glyphicon-eye-open"></span>'); ?> | 
		<?php echo Html::anchor('admin/users', '<span class="glyphicon glyphicon-share-alt"></span>'); ?> 
	</div>
</div>
