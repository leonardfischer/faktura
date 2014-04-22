<h4 class="mt0"><?=$self->get_name() ?></h4>

<table class="table table-striped">
	<thead>
	<tr>
		<th><?=__('Customer / Contact person') ?></th>
		<th><?=__('Created at') ?></th>
		<th><?=__('Paid at') ?></th>
		<th><?=__('Total') ?></th>
		<th><?=__('Action') ?></th>
	</tr>
	</thead>
	<tbody>
	<? foreach ($invoices as $invoice): ?>
		<tr>
			<td><?=implode('</td><td>', $invoice->get_table_data(array('invoice_no'))); ?></td>
		</tr>
	<? endforeach; ?>
	</tbody>
</table>