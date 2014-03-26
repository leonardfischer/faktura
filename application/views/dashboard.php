<div class="row">
	<div class="col-xs-12">
		<h2>Willkommen auf dem Dashboard!
			<small>Ihr letzter Login war am <?= $last_login ?></small>
		</h2>
	</div>
</div>
<div class="row">
	<div class="col-sm-6 col-md-4">
		<div class="well well-sm">
			<h3 class="mt0"><?= __('New customers and invoices in :month', array(':month' => $last_month)) ?></h3>

			<p><?= __('In the last month <strong>:new_customers new customers</strong> and <strong>:new_invoices new invoices</strong> have been created.', array(':new_customers' => $new_customers, ':new_invoices' => $new_invoices)) ?></p>
		</div>
	</div>

	<div class="col-sm-6 col-md-4">
		<div class="well well-sm">
			<h3 class="mt0"><?= __('Open invoices and reminders') ?></h3>

			<p><?= __('Currently there are <strong>:open_invoices open invoices</strong> and <strong class=":color">:reminder_invoices reminder</strong>!', array(':open_invoices' => $open_invoices, ':color' => ($reminder_invoices > 0) ? 'text-danger' : 'text-success', ':reminder_invoices' => $reminder_invoices)) ?></p>
		</div>
	</div>

	<div class="clearfix visible-sm"></div>

	<div class="col-sm-6 col-md-4">
		<div class="well well-sm">
			<h3 class="mt0"><?= __('Invoice value of :month', array(':month' => $last_month)) ?></h3>

			<p><?= __('The value of the paid invoices created last month is <strong>:money</strong>!', array(':month' => $last_month, ':money' => $money)) ?></p>
		</div>
	</div>

	<div class="clearfix visible-md"></div>

	<div class="col-sm-12 col-md-8">
		<div class="well well-sm">
			<h3 class="mt0"><?= __('The last invoices') ?></h3>
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
		</div>
	</div>

	<div class="col-sm-6 col-md-4">
		<div class="well well-sm">
			<h3 class="mt0"><?= __('System status') ?></h3>
			<table class="table table-striped">
				<tbody>
				<tr>
					<td><?= __('System version') ?></td>
					<td><?= SYSTEM_VERSION ?></td>
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
		</div>
	</div>
</div>