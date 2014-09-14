<!-- <h4 class="mt0"><?=$self->get_name() ?></h4>

<? if($invoices->count()): ?>
	<div class="alert alert-danger mb0"><?=$invoices->count() ?> <?= __('Open reminders') ?>!</div>

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
	<div class="alert alert-success mb0"><?= __('No open reminders')?>!</div>
<? endif; ?> -->

<? if($invoices->count()): ?><i class="pull-right fa fa-plus-square mouse-pointer toggle-button"></i><? endif; ?>
<h4 class="h1 mt0"><?=$invoices->count() ?></h4>

<p><?=$self->get_name() ?></p>

<? if($invoices->count()): ?>
	<table class="table table-striped opacity hide">
		<thead>
		<tr>
			<th><?= __('Invoice no.') ?></th>
			<th><?= __('Created at') ?></th>
		</tr>
		</thead>
		<tbody>
		<? foreach ($invoices as $invoice): ?>
			<tr>
				<td><a class="inherit-font-color" href="<?= Route::url('invoice', array('action' => 'edit', 'id' => $invoice->id)) ?>"><?= $invoice->invoice_no ?></a></td>
				<td><?= $invoice->invoice_date() ?></td>
			</tr>
		<? endforeach; ?>
		</tbody>
	</table>

	<script type="text/javascript">
		(function() {
			"use strict";

			var $widget = $('<?=$id ?>'),
				$toggle_button = $widget.getElement('.toggle-button'),
				$table = $widget.getElement('table');

			$toggle_button.addEvent('click', function() {
				$toggle_button.toggleClass('fa-plus-square').toggleClass('fa-minus-square');

				$table.toggleClass('hide');
			});
		})();
	</script>
<? endif; ?>