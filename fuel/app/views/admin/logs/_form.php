<?php echo Form::open(array("class"=>"form-horizontal")); ?>

	<fieldset>
		<div class="form-group">
			<?php echo Form::label('User id', 'user_id', array('class'=>'control-label')); ?>

				<?php echo Form::input('user_id', Input::post('user_id', isset($log) ? $log->user_id : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'User id')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Status id', 'status_id', array('class'=>'control-label')); ?>

				<?php echo Form::input('status_id', Input::post('status_id', isset($log) ? $log->status_id : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Status id')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Location', 'location', array('class'=>'control-label')); ?>

				<?php echo Form::input('location', Input::post('location', isset($log) ? $log->location : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Location')); ?>

		</div>
		<div class="form-group">
			<label class='control-label'>&nbsp;</label>
			<?php echo Form::submit('submit', 'Save', array('class' => 'btn btn-primary')); ?>		</div>
	</fieldset>
<?php echo Form::close(); ?>