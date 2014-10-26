<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <?php echo Form::open(array('class' => 'mono-form', 'id' => 'google-form', 'target' => '_self')); ?>

            <div class="error text-center text-danger" id="prompt-text"><?php echo '' ?></div>

            <div class="actions">
                <?php echo Form::submit(array('value'=>'', 'name'=>'submit', 'class' => 'btn btn-lg btn-primary btn-block', 'disabled' => 'true')); ?>
            </div>

            <br><div class="error text-center text-danger" id="jobno-text"><?php echo '' ?></div>

            <?php if (isset($change_status)): ?>
                <div class="error text-center text-danger"><?php echo $change_status; ?></div>
            <?php endif; ?>

        <?php echo Form::close(); ?>
    </div>
</div>

<div class="modal fade" id="modal-jobno">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Job No.</h4>
            </div>
            <div class="modal-body mono-form row">
                <div class="col-xs-8">
                    <?php echo Form::input('jobno', Input::post('jobno'), array('class' => 'form-control', 'placeholder' => 'Job No', 'autofocus')); ?>
                </div>
                <div class="col-xs-4 actions">
                    <?php echo Form::submit(array('value'=>'OK', 'name'=>'submit', 'class' => 'btn btn-lg btn-default btn-block')); ?>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php echo Asset::js(array(
    'geoPosition.js',
    'dashboard.js',
    'bootstrap.min.js'
)); ?>