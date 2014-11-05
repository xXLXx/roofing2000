<h2 class="text-center"><?= Date::forge($updated_at)->format('%B %Y'); ?></h2>
<table class="table mono-table" id="table-logs-monthly">
	<thead>
		<tr>
			<th></th>
			<th class="text-center">TIME IN</th>
			<th class="text-center">TIME OUT</th>
			<!-- <th class="text-center">JOB</th> -->
		</tr>
	</thead>
	<tbody>
<?php foreach ($logs as $item): ?>
		<tr>
			<td class="text-right"><?php echo $item['username']; ?> <?= strtoupper($item['first_name'] . ' ' . $item['last_name']) ?><br><?php echo $item['email']; ?></td>
			<td class="text-center">
				<?= Date::forge($item['updated_at'])->format('%b ' . (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' ? '%#d' : '%e') . ', %Y'); ?>
				<br>
				<?= Date::forge($item['updated_at'])->format('%I:%M%p'); ?>
			</td>
			<td class="text-center">
				<?php if (isset($item['timeout'])): ?>
				<?= Date::forge($item['timeout'])->format('%b ' . (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' ? '%#d' : '%e') . ', %Y'); ?>
				<br>
				<?= Date::forge($item['timeout'])->format('%I:%M%p'); ?>
				<?php else : ?>
					<?= Model_Log::LABEL_NOT_YET_TIMEOUT ?>
				<?php endif; ?>
			</td>
			<!-- <td class="text-center">
				<?php //$item['job_no'] ?>
			</td> -->
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php echo Html::anchor('admin/logs/get_xls/' . $updated_at, 'Download as Excel', ['class' => 'btn mono-button btn-lg btn-block btn-success', 'target' => '_blank']); ?> 
<br>
<div class="text-right">
	<?php echo Html::anchor('admin/logs', '<span class="glyphicon glyphicon-share-alt"></span>'); ?> 
</div>