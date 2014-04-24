<div class="form-horizontal">
	<div class="row">
		<div class="col-xs-12">
			<h3><?=__('Configure the dashboard') ?></h3>
		</div>
	</div>

	<div class="row">

		<div class="col-sm-8 form-group">
			<label class="col-md-4 control-label" for="inputWidget"><?=__('Select a widget') ?></label>
			<div class="col-md-8">
				<?=Form::select('inputWidget', $available_widgets, null, array('class' => 'form-control')) ?>
			</div>
		</div>

		<div class="col-sm-4">
			<button id="dashboard-add-widget-button" type="button" class="btn btn-default btn-block"><i class="fa fa-plus"></i> <?=__('Add this widget') ?></button>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<ul id="widget-sortable" class="list-group mouse-move-vertical no-select">
				<? foreach ($widgets as $widget): ?>
				<li class="list-group-item" data-widget-id="<?=$widget['data']->id ?>" data-widget="<?=$widget['data']->widget ?>">
					<?=$widget['instance']->get_name() ?>
					<button type="button" title="<?=__('Remove') ?>" class="pull-right btn btn-danger btn-xs delete-widget"><i class="fa fa-times"></i></button>
				</li>
				<? endforeach ?>
			</ul>
		</div>
	</div>

	<div id="dashboard-ajax-response" class="row hidden">
		<div class="col-xs-12">
			<p></p>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<button id="dashboard-save-button" type="button" class="btn btn-success"><?=__('Save') ?></button>
			<button type="button" class="btn btn-default popup-close"><?=__('Cancel') ?></button>
		</div>
	</div>
</div>

<script type="text/javascript">
	var sortable,
		deletion = [];

	window.addEvent('domready', function(){
		sortable = new Sortables('#widget-sortable', {clone:true, revert:true, opacity:0.7});

		$('dashboard-save-button').addEvent('click', function (ev) {
			var widgets = $('widget-sortable').getElements('li'),
				selection = {};

			widgets.each(function(el, i) {
				selection['sort-' + i] = {
					id:el.get('data-widget-id'),
					widget:el.get('data-widget')
				};
			});

			// Save the widgets via AJAX.
			new Request.JSON({
				url:'<?=Route::url('default', array('action' => 'dashboard_save'))?>',
				data:{widgets:JSON.encode(selection), deletions:JSON.encode(deletion)},
				onComplete:function(json) {
					if (json.success) {
						// Reload the page.
						window.location.reload();
					} else {
						// Display the error!
						$('dashboard-ajax-response').removeClass('hidden').getElement('p').set('text', json.message);
					}
				}
			}).send();
		});

		$('dashboard-add-widget-button').addEvent('click', function (ev) {
			var option = $('inputWidget').getElement('option:selected'),
				new_option = new Element('li.list-group-item', {'data-widget-id':'-', 'data-widget':option.get('value')})
					.grab(new Element('button.pull-right.btn.btn-danger.btn-xs.delete-widget', {type:'button', title:'<?=__('Remove') ?>'}).grab(new Element('i.fa.fa-times')))
					.appendText(option.get('text'));

			$('widget-sortable').grab(new_option);

			sortable.addItems(new_option)
		});

		$('widget-sortable').addEvent('click:relay(button.delete-widget)', function (ev) {
			var $li = ev.target.getParent('li');

			// Memorize the deleted widgets.
			if ($li.get('data-widget-id') > 0) {
				deletion.push($li.get('data-widget-id'));
			}

			// Remove the element.
			$li.remove();
		});
	});
</script>