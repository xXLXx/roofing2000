<?php if ($users): ?>
<table class="table mono-table" id="table-users">
	<tbody>
<?php foreach ($users as $item): ?>		<tr>

			<td class="text-right"><?php echo $item->username; ?> <?= strtoupper($item->getFullName()) ?><br><?php echo $item->email; ?></td>
			<td>
				<?php echo Html::anchor('admin/users/view/'.$item->id, '<span class="glyphicon glyphicon-eye-open"></span>'); ?> |
				<?php echo Html::anchor('admin/users/edit/'.$item->id, '<span class="glyphicon glyphicon-pencil"></span>'); ?> |
				<?php echo Html::anchor('admin/users/delete/'.$item->id, '<span class="glyphicon glyphicon-trash"></span>', array('onclick' => "return confirm('Are you sure?')")); ?>
			</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php else: ?>
<p>No Users.</p>

<?php endif; ?><p>
	<div class="mono-form">
		<?php echo Html::anchor('admin/users/create', 'Add new User', array('class' => 'btn btn-success mono-button btn-block')); ?>
	</div>
</p>

<div class="modal fade" id="modal-user">
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
    'dashboard.js',
    'bootstrap.min.js'
)); ?>