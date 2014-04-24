<div class="row">
	<div class="col-xs-6">
		<a class="btn btn-success" href="<?=Route::url('user', array('action' => 'new')) ?>"><i class="fa fa-plus"></i> <?=__('Create new user') ?></a>
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

		<div class="table-responsive clearfix">
			<table id="user-list" class="table table-striped mt20 searchable">
				<thead>
				<tr>
					<th><?=__('ID'); ?></th>
					<th><?=__('Username'); ?></th>
					<th><?=__('Email'); ?></th>
					<th><?=__('Last login'); ?></th>
					<th style="width:130px;"><?=__('Action'); ?></th>
				</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>

		<ul class="pagination pagination-sm pull-right"></ul>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<a class="btn btn-success" href="<?=Route::url('user', array('action' => 'new')) ?>"><i class="fa fa-plus"></i> <?=__('Create new user') ?></a>
	</div>
</div>

<script type="text/javascript">
	var pager_items = parseInt('<?=$count ?>'),
		items_per_page = parseInt('<?=$config->get('rows_per_page', 40) ?>'),
		user_pager = new Pager({
			items: pager_items,
			max_page: Math.ceil(pager_items / items_per_page),
			pager: $$('ul.pagination'),
			target: $('user-list')
		}),
		user_table_search = new AjaxTableSearch($('form-ajax-search'), $('user-list'), {
			minlength: parseInt('<?=$config->get('search_minlength', 3); ?>'),
			pager: user_pager,
			loading_label: '<img src="<?=URL::base() ?>assets/img/loading.gif" /> <?=__('Loading, please wait...') ?>',
			nothing_found_label: '<i class="mr5 fa fa-info-circle"></i><?=__('Sorry - no users were found!') ?>',
			url: '<?=Route::url('user', array('action' => 'search')) ?>',
			filter: null
		});

	<?php if ($theme_options['table_transparency']): ?>
	$('user-list').addClass('table-hide-buttons');
	<?php endif; ?>
</script>