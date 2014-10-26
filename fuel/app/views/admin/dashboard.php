<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <?php echo Form::open(array('class' => 'mono-form', 'id' => 'google-form', 'target' => '_self')); ?>

            <div class="error text-center text-danger" id="prompt-text"><?php echo '' ?></div>

            <div class="actions">
                <?php echo Form::submit(array('value'=>'', 'name'=>'submit', 'class' => 'btn btn-lg btn-primary btn-block', 'disabled' => 'true')); ?>
            </div>

            <?php if (isset($change_status)): ?>
                <div class="error text-center text-danger"><?php echo $change_status; ?></div>
            <?php endif; ?>

        <?php echo Form::close(); ?>
    </div>
</div>

<?php echo Asset::js(array(
    'geoPosition.js',
    'dashboard.js'
)); ?>