<div class="row">
	<div class="col-xs-6">
		<a class="btn btn-success" href="<?=Route::url('invoice', array('action' => 'new')) ?>"><i class="fa fa-plus"></i> <?=__('Create new invoice') ?></a>

		<div class="btn-group ">
			<button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button">
				<span class="button-text mr5"><?=$selected_filter ?></span>
				<span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				<li><a href="<?=Route::url('invoice') ?>"><?=__('All') ?></a></li>
				<li><a href="<?=Route::url('invoice', array('action' => 'filter', 'id' => INVOICE_FILTER_OPEN)) ?>"><span class="badge badge-primary pull-right"><?=$open_invoices ?></span><?=__('Open') ?></a></li>
				<li><a href="<?=Route::url('invoice', array('action' => 'filter', 'id' => INVOICE_FILTER_REMINDER)) ?>"><span class="badge badge-danger pull-right"><?=$reminder_invoices ?></span><?=__('Reminder') ?></a></li>
			</ul>
		</div>
	</div>
	<div class="col-xs-6">
		<form>
			<input id="form-ajax-search" type="text" class="form-control" placeholder="<?=__('Search - insert at least :minlength characters', array(':minlength' => $config->get('search_minlength', 3))) ?>">
		</form>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<ul class="pagination pagination-sm pull-right"></ul>
		<table id="invoice-list" class="table table-striped table-hide-buttons mt20">
			<thead>
			<tr>
				<th><?=__('Invoice no.') ?></th>
				<th><?=__('Customer / Contact person') ?></th>
				<th><?=__('Created at') ?></th>
				<th><?=__('Paid at') ?></th>
				<th><?=__('Total') ?></th>
				<th style="width:140px;"><?=__('Action') ?></th>
			</tr>
			</thead>
			<tbody></tbody>
		</table>
		<ul class="pagination pagination-sm pull-right"></ul>
	</div>
</div>

<script>
	var pager_items = parseInt('<?=$count ?>'),
		items_per_page = parseInt('<?=$config->get('rows_per_page', 40) ?>'),
		invoice_pager = new Pager({
			items: pager_items,
			max_page: Math.ceil(pager_items / items_per_page),
			pager: $$('ul.pagination'),
			target: $('invoice-list')
		}),
		invoice_table_search = new AjaxTableSearch($('form-ajax-search'), $('invoice-list'), {
			minlength: parseInt('<?=$config->get('search_minlength', 3); ?>'),
			pager: invoice_pager,
			loading_label: '<img src="<?=URL::base() ?>assets/img/loading.gif" /> <?=__('Loading, please wait...') ?>',
			nothing_found_label: '<i class="mr5 fa fa-info-circle"></i><?=__('Sorry - no invoices were found!') ?>',
			url: '<?=Route::url('invoice', array('action' => 'search')) ?>',
			filter: '<?=$filter ?>'
		});
</script>