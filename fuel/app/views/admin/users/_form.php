<div class="container" id="student-form">
	<?php echo Form::open(array("class"=>"form-horizontal mono-form")); ?>

		<fieldset>
			<div class="form-group">
				<?php echo Form::input('username', Input::post('username', isset($user) ? $user->username : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Username')); ?>

			</div>
			<div class="form-group">
				<?php echo Form::input('first_name', Input::post('first_name', isset($user) ? $user->first_name : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'First name')); ?>

			</div>
			<div class="form-group">
				<?php echo Form::input('last_name', Input::post('last_name', isset($user) ? $user->last_name : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Last name')); ?>

			</div>
			<div class="form-group">
				<?php echo Form::input('email', Input::post('email', isset($user) ? $user->email : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Email')); ?>

			</div>
			<div class="form-group">
				<?php
					$userTypes = array();
					foreach (Config::get('simpleauth.groups') as $key => $value) {
						if($key < Auth::get('group')) $userTypes[$key] = $value['name'];
					}
					echo Form::select('group', Input::post('group', isset($user) ? $user->group : 1), $userTypes, array('class' => 'col-md-4 form-control', 'placeholder'=>'Group'));
				?>
			</div>
			<div class="form-group actions">
				<label class='control-label'>&nbsp;</label>
				<?php echo Form::submit('submit', 'Save', array('class' => 'btn btn-primary btn-block')); ?>		</div>
		</fieldset>
	<?php echo Form::close(); ?>
</div>