<?php if ($logs): ?>
<table class="table mono-table" id="table-logs">
	<!-- <thead>
		<tr>
			<th>Date</th>
			<th></th>
		</tr>
	</thead> -->
	<tbody>
<?php foreach ($logs as $item): ?>
		<tr>
			<td class="text-right"><?php echo Date::forge($item->updated_at)->format('%B %Y'); ?></td>
			<td>
				<?php echo Html::anchor('admin/logs/view/'.$item->updated_at, '<span class="glyphicon glyphicon-eye-open"></span>'); ?>
			</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php else: ?>
<p>No Logs.</p>

<?php endif; ?>

<?php echo Html::anchor('admin/logs/get_xls', 'Download as Excel', ['class' => 'btn mono-button btn-lg btn-block btn-success', 'target' => '_blank']); ?> 