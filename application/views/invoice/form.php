<div class="well">
	<form class="form-horizontal" method="post" id="invoice-form">
		<fieldset>
			<legend><?=$title ?></legend>

			<div class="row">
				<div class="col-sm-6">

					<div class="form-group" data-property="invoice_no">
						<label class="col-md-4 control-label" for="inputInvoice_no">
							<?=$properties['invoice_no']['label'] ?>
							<?php if ($invoice->is_mandatory('invoice_no')): ?><span class="text-danger">*</span><?php endif; ?>
						</label>
						<div class="col-md-8">
							<?=Form::input('inputInvoice_no', $invoice->invoice_no, array('type' => 'text', 'placeholder' => $properties['invoice_no']['label'], 'class' => 'form-control')) ?>
							<span class="help-inline text-danger hidden"></span>
						</div>
					</div>

					<div class="form-group" data-property="customer_id">
						<label class="col-md-4 control-label" for="inputCustomer_id">
							<?=$properties['customer_id']['label'] ?>
							<?php if ($invoice->is_mandatory('customer_id')): ?><span class="text-danger">*</span><?php endif; ?>
						</label>
						<div class="col-md-8">
							<div class="input-group">
								<?= Form::select('inputCustomer_id', $customers, $invoice->customer_id, array('class' => 'form-control')) ?>
								<span class="input-group-btn">
									<button type="button" class="btn btn-default" id="customer-browser">
										<i class="fa fa-search"></i>
									</button>
								</span>
								<span class="help-inline text-danger hidden"></span>
							</div>
						</div>
					</div>

				</div>
				<div class="col-sm-6">

					<div class="form-group" data-property="invoice_date">
						<label class="col-md-4 control-label" for="inputInvoice_date">
							<?=$properties['invoice_date']['label'] ?>
							<?php if ($invoice->is_mandatory('invoice_date')): ?><span class="text-danger">*</span><?php endif; ?>
						</label>
						<div class="col-md-8">
							<?php
							echo Form::input('inputInvoice_date_VIEW', $invoice->invoice_date, array('type' => 'text', 'placeholder' => $properties['invoice_date']['label'], 'class' => 'form-control date-selector'));
							echo Form::hidden('inputInvoice_date', $invoice->invoice_date);
							?>
							<span class="help-inline text-danger hidden"></span>
						</div>
					</div>

					<div class="form-group" data-property="paid_on_date">
						<label class="col-md-4 control-label" for="inputPaid_on_date_VIEW">
							<?=$properties['paid_on_date']['label'] ?>
							<?php if ($invoice->is_mandatory('paid_on_date')): ?><span class="text-danger">*</span><?php endif; ?>
						</label>
						<div class="col-md-8">
							<?php
							echo Form::input('inputPaid_on_date_VIEW', $invoice->paid_on_date, array('type' => 'text', 'placeholder' => $properties['paid_on_date']['label'], 'class' => 'form-control date-selector'));
							echo Form::hidden('inputPaid_on_date', $invoice->paid_on_date);
							?>
							<span class="help-inline text-danger hidden"></span>
						</div>
					</div>

				</div>
			</div>
			<div class="row">

				<div class="col-sm-12">
					<div class="form-group" data-property="shipping_address">
						<label class="col-md-2 control-label" for="inputShipping_address">
							<?=$properties['shipping_address']['label'] ?>
							<?php if ($invoice->is_mandatory('shipping_address')): ?><span class="text-danger">*</span><?php endif; ?>
						</label>
						<div class="col-md-10">
							<?=Form::textarea('inputShipping_address', $invoice->shipping_address, array('class' => 'form-control')) ?>
							<span class="help-inline text-danger hidden"></span>
						</div>
					</div>
				</div>

			</div>
		</fieldset>

		<fieldset class="mt20">
			<legend><?=__('Positions') ?></legend>

			<ul id="invoice-position-list" class="list-group mt10">
				<?php $i = 1; foreach ($invoice->positions->find_all() as $position): ?>
					<li data-position="<?=$i ?>" class="list-group-item clearfix">
						<div class="col-sm-8">
							<div class="form-group">
								<textarea rows="3" name="inputPositionText-<?=$i ?>" class="form-control"><?=$position->description ?></textarea>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="row">
								<div class="col-sm-6 mb10">
									<div class="input-group">
										<span class="input-group-addon"><strong>#</strong></span>
										<input type="text" placeholder="<?=__('Amount') ?>" name="inputPositionAmount-<?=$i ?>" class="form-control" value="<?=$position->amount ?>">
									</div>
								</div>

								<div class="col-sm-6 mb10">
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-euro"></i></span>
										<input type="text" placeholder="<?=__('Cost') ?>" name="inputPositionPrice-<?=$i ?>" class="form-control money" value="<?=number_format($position->price, 2, ',', '') ?>">
									</div>
								</div>
							</div>

							<button type="button" class="btn btn-block btn-danger remove-position"><?=__('Remove') ?></button>
						</div>
					</li>
				<?php $i++; endforeach; ?>
			</ul>

			<p>
				<button type="button" class="btn btn-success btn-sm add-position"><i class="fa fa-plus"></i> <?=__('Add new position') ?></button>
			</p>
		</fieldset>

		<fieldset class="mt20" id="print-actions">
			<legend><?=__('Actions') ?></legend>

			<div class="row">
				<div class="col-sm-4">
					<p><a href="<?=Route::url('print', array('action' => 'invoice', 'id' => $invoice->id)) ?>" target="_blank" class="btn btn-block btn-default"><i class="fa fa-print mr5"></i> <?= __('Print invoice') ?></a></p>
					<p><a href="<?=Route::url('print', array('action' => 'delivery_note', 'id' => $invoice->id)) ?>" target="_blank" class="btn btn-block btn-default"><i class="fa fa-print mr5"></i> <?= __('Print delivery note') ?></a></p>
					<p><a href="<?=Route::url('print', array('action' => 'order_confirmation', 'id' => $invoice->id)) ?>" target="_blank" class="btn btn-block btn-default"><i class="fa fa-print mr5"></i> <?= __('Print order confirmation') ?></a></p>
				</div>
				<div class="col-sm-4">
					<p><a href="<?=Route::url('print', array('action' => 'reminder', 'id' => $invoice->id)) ?>" target="_blank" class="btn btn-block btn-danger"><i class="fa fa-exclamation-circle mr5"></i> <?= __('Print reminder') ?></a></p>
				</div>
				<div class="col-sm-4">
					<p><button type="button" class="btn btn-block btn-success" id="credit-button"><i class="fa fa-check-circle mr5"></i> <?= __('Print credit') ?></button></p>
				</div>
			</div>

			<?php if ($invoice->id === null): ?><p class="text-muted">* <?=__('You can only print invoices, which have been saved.') ?></p><?php endif; ?>
		</fieldset>

		<hr />

		<div id="form-message" class="hidden alert"></div>

		<div class="form-group">
			<div class="col-md-offset-2 col-md-10">
				<button type="button" id="save-button" class="btn btn-success"><?=__('Save') ?></button>
				<a href="<?=Route::url('invoice')?>" class="btn btn-default"><?=__('Cancel') ?></a>
			</div>
		</div>
	</form>
</div>

<div id="credit-popup" class="hidden">
	<div class="row">
		<div class="col-xs-12">
			<h3><?=__('Please choose the positions, you want to credit') ?></h3>
			<table class="mt20 table table-striped">
				<tbody></tbody>
			</table>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12 form-group">
			<a id="credit-print-button" target="_blank" class="btn btn-success" href="<?=Route::url('print', array('action' => 'credit', 'id' => $invoice->id)) ?>"><?=__('Print credit') ?></a>
			<button type="button" class="btn btn-default popup-close"><?=__('Cancel') ?></button>
		</div>
	</div>
</div>

<script type="text/javascript">
	// Define the datepickers.
	var date_format = '<?=$config->get('date_format_form') ?>';
	var pickers = new Picker.Date($$('input.date-selector'), {
			positionOffset: {x: 1, y: 3},
			draggable:false,
			format: date_format,
			blockKeydown:false,
			onSelect:function(date) {
				this.input.getNext().set('value', date.format('%Y-%m-%d'));
			}
		}),
		item = <?=$i ?>; // Retrieving the "position-number" for new entries.

	<?php if ($invoice->id === null): ?>
	// This will come to effect, when we are in "create new" mode.
	$('print-actions').getElements('.btn').invoke('addClass', 'disabled').invoke('addEvent', 'click', function (ev) {
		ev.preventDefault();
	});
	<?php endif; ?>

	// Manually setting the datepickers, after the user typed in a date.
	$$('input.date-selector').invoke('addEvent', 'change', function () {
		if (this.value.clean() == '') {
			// If the input is empty, we also empty the hidden field.
			this.getNext().value = '';
		} else {
			if (! isNaN(Date.parse(this.value).getTime())) {
				this.value = Date.parse(this.value).strftime(date_format);
			}
		}
	}).invoke('fireEvent', 'change');

	// When clicking the "+" button, a new position shall be added.
	$$('button.add-position').invoke('addEvent', 'click', function () {
		item ++;

		var position_text = new Element('div.form-group').grab(new Element('textarea.form-control', {rows:3, name:'inputPositionText-' + item})),
			position_amount = new Element('div.col-sm-6.mb10').grab(new Element('div.input-group')
				.grab(new Element('span.input-group-addon').grab(new Element('strong').set('text', '#')))
				.grab(new Element('input.form-control', {type:'text', placeholder:'<?=__('Amount') ?>', value:'1', name:'inputPositionAmount-' + item}))),
			position_price = new Element('div.col-sm-6.mb10').grab(new Element('div.input-group')
				.grab(new Element('span.input-group-addon').grab(new Element('i.fa.fa-euro')))
				.grab(new Element('input.form-control.money', {type:'text', placeholder:'<?=__('Cost') ?>', value:'0,00', name:'inputPositionPrice-' + item})));

		$('invoice-position-list').grab(
			new Element('li.list-group-item.clearfix', {'data-position':item})
				.grab(new Element('div.col-sm-8').grab(position_text))
				.grab(new Element('div.col-sm-4').grab(new Element('div.row').grab(position_amount).grab(position_price)).grab(new Element('button.btn.btn-block.btn-danger.remove-position', {type:'button'}).set('text', '<?=__('Remove') ?>'))))
			.getLast('.list-group-item').getElement('textarea').focus();
	});

	$('invoice-position-list').addEvent('click:relay(button.remove-position)', function () {
		this.getParent('li').dispose();
	});

	$('credit-button').addEvent('click', function () {
		new Request.JSON({
			url: '<?=$credit_popup_ajax_url ?>',
			onSuccess: function(json){
				var credit_popup = $('credit-popup'),
					i;

				credit_popup.getElement('tbody').set('html', '')

				if (json.success) {
					for (i in json.data) {
						if (json.data.hasOwnProperty(i)) {
							credit_popup
								.getElement('tbody')
								.grab(new Element('tr')
									.grab(new Element('td').grab(new Element('input', {type:'checkbox', class:'position', value:json.data[i].id})))
									.grab(new Element('td').grab(new Element('span', {html:json.data[i].description})))
									.grab(new Element('td').grab(new Element('span', {text:json.data[i].amount + 'x ' + json.data[i].price})))
							);
						}
					}
				} else {
					credit_popup
						.getElement('tbody')
						.grab(new Element('tr.danger')
							.grab(new Element('td').grab(new Element('span', {html:json.message})))
						);
				}

				ModalPopup.grab(credit_popup.removeClass('hidden').clone()).open();
			}
		}).send();
	});

	$('credit-print-button').addEvent('click', function () {
		this.set('href', '<?=Route::url('print', array('action' => 'credit', 'id' => $invoice->id)) ?>/' + $('credit-popup').getElements('input.position:checked').invoke('get', 'value').join())
	});

	// Also, add a single empty position if there is none.
	if ($('invoice-position-list').getElements('li').length == 0) {
		$$('button.add-position').fireEvent('click');
	}

	// Process the customer browser
	$('customer-browser').addEvent('click', function () {
		ModalPopup.load_html('<?=Route::url('customer', array('action' => 'browser')) ?>', {receiver:'inputCustomer_id', selection:$('inputCustomer_id').getSelected()[0].get('value')}, function (js) {
			ModalPopup.open();

			Browser.exec(js);
		});
	});

	// Adding some general data to our dynamic save-logic.
	Faktura
		.set('form.name', 'invoice-form')
		.set('form.ajax_url', '<?=$ajax_url ?>')
		.set('form.loading-message', '<img src="<?=URL::base() ?>assets/img/loading-on-grey.gif" /> <?=__('Loading, please wait...') ?>')
		.set('form.success-message', '<?=__('The data has been saved') ?>')
		.set('searchable.min_length', '<?=$config->get('search_minlength', 3); ?>')
		.set('searchable.placeholder', '<?=__('Search - insert at least :minlength characters'); ?>')
		.set('searchable.no-data-message', '<?=__('No customer data'); ?>');
</script>