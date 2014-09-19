<i class="widget-icon fa fa-5x fa-cogs"></i>
<h4 class="mt0"><?=$self->get_name() ?></h4>

<table class="table table-striped">
	<tbody>
	<tr>
		<td><?= __('System version') ?></td>
		<td><?= $config->get('version') ?></td>
	</tr>
	<tr>
		<td><?= __('Kohana version') ?></td>
		<td><?= Kohana::VERSION ?></td>
	</tr>
	<tr>
		<td><?= __('Average request time') ?></td>
		<td><?= number_format($request_time, 4, ',', ' ') ?> Sekunden</td>
	</tr>
	<tr>
		<td><?= __('Average memory usage') ?></td>
		<td><?= number_format($request_memory / 1024, 2, ',', ' ') ?> Kilobyte</td>
	</tr>
	</tbody>
</table>