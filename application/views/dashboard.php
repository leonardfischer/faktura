<div class="row">
	<div class="col-xs-12 mt20">
		<button type="button" class="btn btn-primary pull-right" title="<?=__('Configure the dashboard') ?>">
			<i class="fa fa-cog mouse-pointer"></i>
		</button>

		<h2 class="mt0">Willkommen auf dem Dashboard!
			<small>Ihr letzter Login war am <?= $last_login ?></small>
		</h2>
	</div>
</div>

<div id="dashboard" class="row">
	<?php foreach ($widgets as $widget): ?>
		<div class="col-sm-<?=($widget['instance']->get_width() * 6) ?> col-md-<?=($widget['instance']->get_width() * 4) ?> widget-container">
			<? if ($widget['instance']->is_configurable()): ?>
			<div class="controls">
				<button class="btn btn-sm btn-primary" type="button" title="<?=__('Configure this widget') ?>">
					<i class="fa fa-cog mouse-pointer"></i>
				</button>
			</div>
			<? endif; ?>

			<div class="well well-sm widget" data-widget="<?= $widget['data']->widget ?>">
				<img src="<?=$basedir; ?>assets/img/loading.gif" /> <?=__('Loading, please wait...') ?>
			</div>
		</div>
	<?php endforeach; ?>
</div>

<script type="text/javascript">
	var $widgets = $('dashboard').getElements('.widget').each(function ($widget) {
		new Request.JSON({
			url:'<?=Route::url('default', array('action' => 'ajax'))?>',
			data:{
				identifier:$widget.get('data-widget')
			},
			onComplete:function(json) {
				if (json.success) {
					$widget.set('html', json.data);
				} else {
					$widget.set('html', new Element('p.alert.alert-danger').set('html', json.message));
				}
			}
		}).send();
	});
</script>