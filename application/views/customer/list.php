<div class="row">
	<div class="col-xs-6">
		<a class="btn btn-success" href="<?=Route::url('customer', array('action' => 'new')) ?>"><i class="icon-plus icon-white"></i> <?=__('Create new customer') ?></a>
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
		<table id="customer-list" class="table table-striped table-hide-buttons mt20 searchable">
			<thead>
				<tr>
					<th><?=__('Contact person'); ?></th>
					<th><?=__('Company'); ?></th>
					<th><?=__('Address'); ?></th>
					<th><?=__('Email'); ?></th>
					<th><?=__('Allowance'); ?></th>
					<th style="width:140px;"><?=__('Action'); ?></th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
		<ul class="pagination pagination-sm pull-right"></ul>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<a class="btn btn-success" href="<?=Route::url('customer', array('action' => 'new')) ?>"><i class="icon-plus icon-white"></i> <?=__('Create new customer') ?></a>
	</div>
</div>

<script type="text/javascript">
	var pager_items = parseInt('<?=$count ?>'),
		items_per_page = parseInt('<?=$config->get('rows_per_page', 40) ?>'),
		customer_pager = new Pager({
			items: pager_items,
			max_page: Math.ceil(pager_items / items_per_page),
			pager: $$('ul.pagination'),
			target: $('customer-list')
		}),
		customer_table_search = new AjaxTableSearch($('form-ajax-search'), $('customer-list'), {
			minlength: parseInt('<?=$config->get('search_minlength', 3); ?>'),
			pager: customer_pager,
			loading_label: '<img src="<?=URL::base() ?>assets/img/loading.gif" /> <?=__('Loading, please wait...') ?>',
			nothing_found_label: '<i class="mr5 icon-info-sign"></i><?=__('Sorry - no customers were found!') ?>',
			url: '<?=Route::url('customer', array('action' => 'search')) ?>',
			filter: null
		});
</script>