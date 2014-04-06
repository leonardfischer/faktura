<div class="well">
	<form class="form-horizontal" method="post" id="invoice-form">
		<fieldset>
			<legend><?=$title ?></legend>

			<div class="row">
				<div class="col-sm-6">

					<div class="form-group" data-property="username">
						<label class="col-md-4 control-label" for="inputUsername">
							<?=$properties['username']['label'] ?><span class="text-danger">*</span>
						</label>
						<div class="col-md-8">
							<?=Form::input('inputUsername', $user_model->username, array('type' => 'text', 'placeholder' => $properties['username']['label'], 'class' => 'form-control')) ?>
							<span class="help-inline text-danger hidden"></span>
						</div>
					</div>

					<div class="form-group" data-property="email">
						<label class="col-md-4 control-label" for="inputEmail">
							<?=$properties['email']['label'] ?><span class="text-danger">*</span>
						</label>
						<div class="col-md-8">
							<?=Form::input('inputEmail', $user_model->email, array('type' => 'text', 'placeholder' => $properties['email']['label'], 'class' => 'form-control')) ?>
							<span class="help-inline text-danger hidden"></span>
						</div>
					</div>

				</div>
				<div class="col-sm-6">

					<div class="form-group" data-property="password">
						<label class="col-md-4 control-label" for="inputPassword">
							<?=$properties['password']['label'] ?>
						</label>
						<div class="col-md-8">
							<?=Form::input('inputPassword', '', array('type' => 'password', 'placeholder' => __('Password'), 'class' => 'form-control')) ?>
							<span class="help-inline text-danger hidden"></span>
						</div>
					</div>

					<div class="form-group" data-property="password_confirm">
						<label class="col-md-4 control-label" for="inputPassword_confirm">
							<?=__('Confirm password') ?>
						</label>
						<div class="col-md-8">
							<?=Form::input('inputPassword_confirm', '', array('type' => 'password', 'placeholder' => __('Confirm password'), 'class' => 'form-control')) ?>
							<span class="help-inline text-danger hidden"></span>
						</div>
					</div>
				</div>
			</div>
		</fieldset>

		<fieldset>
			<legend><?=__('Additional options') ?></legend>

			<div class="row">
				<div class="col-sm-6">

					<div class="form-group" data-property="theme">
						<label class="col-md-4 control-label" for="inputTheme">
							<?=$properties['theme']['label'] ?><span class="text-danger">*</span>
						</label>
						<div class="col-md-8">
							<?=Form::select('inputTheme', $themes, $theme, array('type' => 'text', 'class' => 'form-control')) ?>
							<span class="help-inline text-danger hidden"></span>
						</div>
					</div>

				</div>
				<div class="col-sm-6">

				</div>
			</div>
		</fieldset>

		<?php if ($is_admin): ?>
			<fieldset>
				<legend><?= __('User role assignment') ?></legend>

				<?php foreach ($roles as $role): ?>
					<?php if ($role->name == 'login') { continue; } ?>
					<div class="form-group">
						<label class="col-xs-2 control-label"><?=__($role->name) ?></label>
						<div class="col-xs-10">
							<div class="checkbox" data-property="<?=$role->name ?>">
								<label>
									<?=Form::checkbox('inputRole' . ucfirst($role->name), null, $user_model->has('roles', $role), array('type' => 'checkbox', 'class' => 'role-checkbox')) ?>
									<?=__($role->description) ?>
									<span class="help-inline text-danger hidden"></span>
								</label>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</fieldset>
		<?php endif; ?>

		<div id="form-message" class="hidden alert"></div>

		<div class="form-group">
			<div class="col-md-offset-2 col-md-10">
				<button type="button" id="save-button" class="btn btn-success"><?=__('Save') ?></button>
				<a href="<?=Route::url('invoice')?>" class="btn btn-default"><?=__('Cancel') ?></a>
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">
	<?php if ($copy_n_paste): ?>
	$('inputPassword').addEvent('keydown', function (ev) {
		if (ev.control && ev.key == 'c') {
			alert('<?=__('Please do not simply copy and paste!') ?>');
			ev.preventDefault();
		}
	}).addEvent('copy', function (ev) {
		alert('<?=__('Please do not simply copy and paste!') ?>');
		ev.preventDefault();
	});

	$('inputPassword_confirm').addEvent('keydown', function (ev) {
		if (ev.control && ev.key == 'v') {
			alert('<?=__('Please do not simply copy and paste!') ?>');
			ev.preventDefault();
		}
	}).addEvent('paste', function (ev) {
		alert('<?=__('Please do not simply copy and paste!') ?>');
		ev.preventDefault();
	});
	<?php endif; ?>

	<?php if ($is_admin): ?>
	$('inputRoleAdmin').addEvent('click', function () {
		var is_admin = this.checked;

		$$('input.role-checkbox').each(function(el) {
			if (el.id != 'inputRoleAdmin') {
				el.set('disabled', is_admin);
			}
		});
	}).fireEvent('click');
	<?php endif; ?>

	$('inputTheme').addEvent('change', function (ev) {
		var theme = ev.target.get('value');

		$('theme-bootstrap').set('href', '<?=$basedir; ?>assets/css/themes/' + theme + '/bootstrap.min.css');
		$('theme-addition').set('href', '<?=$basedir; ?>assets/css/themes/' + theme + '/addition.css');
	});

	// Adding some general data to our dynamic save-logic.
	Faktura
		.set('form.name', 'invoice-form')
		.set('form.ajax_url', '<?=$ajax_url ?>')
		.set('form.loading-message', '<img src="<?=URL::base() ?>assets/img/loading-on-grey.gif" /> <?=__('Loading, please wait...') ?>')
		.set('form.success-message', '<?=__('The data has been saved') ?>');
</script>