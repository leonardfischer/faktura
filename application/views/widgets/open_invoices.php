<? if($invoices->count()): ?>
	<div class="alert alert-danger mb0"><?=$invoices->count() ?> <?= __('Open invoices') ?>!</div>

	<table class="table table-striped">
		<thead>
		<tr>
			<th><?= __('Invoice no.') ?></th>
			<th><?= __('Created at') ?></th>
		</tr>
		</thead>
		<tbody>
		<? foreach ($invoices as $invoice): ?>
		<tr>
			<td><a href="<?= Route::url('invoice', array('action' => 'edit', 'id' => $invoice->id)) ?>"><?= $invoice->invoice_no ?></a></td>
			<td><?= $invoice->invoice_date() ?></td>
		</tr>
		<? endforeach; ?>
		</tbody>
	</table>
<? else: ?>
	<div class="alert alert-success mb0"><?= __('No open invoices')?>!</div>
<? endif; ?>