<div class="form-horizontal">
	<div class="row">
		<div class="col-sm-6">
			<h3><?=$title ?></h3>
		</div>
		<div class="col-sm-6">
			<input id="browser-ajax-search" type="text" class="form-control" placeholder="<?=__('Search - insert at least :minlength characters', array(':minlength' => $config->get('search_minlength', 3))) ?>">
		</div>
	</div>

	<div class="row">
		<ul class="pagination pagination-sm pull-right"></ul>

		<div class="col-xs-12 table-responsive">
			<div id="popup-data-table-container" class="mt10 mb10">
			<table class="table table-striped mouse-pointer" id="popup-data-table">
				<? if (isset($table_header) && is_array($table_header)): ?>
					<thead>
					<tr>
					<? foreach ($table_header as $header): ?>
						<th><?=$header ?></th>
					<? endforeach ?>
					</tr>
					</thead>
				<? endif; ?>

				<tbody>
				<? foreach ($data as $row): ?>
					<?
					$row_data = $row->get_table_data($exclude);
					unset($row_data['_id']);
					?>

					<tr data-id="<?=$row->id ?>" class="<?=(($row->id == $selection) ? 'success' : '') ?>">
						<td><?=implode('</td><td>', $row_data) ?></td>
					</tr>
				<? endforeach ?>
				</tbody>
			</table>
			</div>
		</div>

		<ul class="pagination pagination-sm pull-right"></ul>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<button id="browser-save-button" type="button" class="btn btn-success"><?=__('Accept') ?></button>
			<button type="button" class="btn btn-default popup-close"><?=__('Cancel') ?></button>
		</div>
	</div>
</div>

<script type="text/javascript">
	var browser_table_search = new AjaxTableSearch($('browser-ajax-search'), $('popup-data-table'), {
			minlength: parseInt('<?=$config->get('search_minlength', 3); ?>'),
			pager: false,
			loading_label: '<img src="<?=URL::base() ?>assets/img/loading.gif" /> <?=__('Loading, please wait...') ?>',
			nothing_found_label: '<i class="mr5 icon-info-sign"></i><?=__('Sorry - no customers were found!') ?>',
			url: '<?=$search_url ?>',
			filter: null,
			selection: '<?=$selection ?>',
			exclude: <?=json_encode($exclude) ?>
		});

	$('browser-save-button').addEvent('click', function () {
		var selection = $('popup-data-table').getElement('tr.success');
		<? if ($receiver): ?>
		if (selection) {
			$('<?=$receiver ?>').set('value', selection.get('data-id'));
		}
		<? endif ?>
		ModalPopup.close();
	});

	$('popup-data-table').addEvent('click:relay(td)', function (ev) {
		$('popup-data-table').getElements('tr.success').invoke('removeClass', 'success');

		browser_table_search.options.selection = ev.target.getParent('tr').addClass('success').get('data-id');
	});

	$('browser-ajax-search').focus();
</script>