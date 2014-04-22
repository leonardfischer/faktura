<!DOCTYPE html>
<html lang="de">
<head>
	<meta charset="utf-8">
	<title><?=$config->get('title') ?></title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">

	<link href="<?=$basedir; ?>assets/css/themes/<?=$theme; ?>/bootstrap.min.css" rel="stylesheet" id="theme-bootstrap">
	<link href="<?=$basedir; ?>assets/css/themes/<?=$theme; ?>/addition.css" rel="stylesheet" id="theme-addition">
	<link href="<?=$basedir; ?>assets/css/font-awesome.min.css" rel="stylesheet">
	<link href="<?=$basedir; ?>assets/css/style.css" rel="stylesheet">
	<link href="<?=$basedir; ?>assets/css/datepicker.css" rel="stylesheet">

	<script src="<?=$basedir; ?>assets/js/mootools-core-1.4.5.js"></script>
	<script src="<?=$basedir; ?>assets/js/mootools-more-1.4.0.1.js"></script>
	<script src="<?=$basedir; ?>assets/js/respond.js"></script>

	<script src="<?=$basedir; ?>assets/js/datepicker/Locale.de-DE.DatePicker.js"></script>
	<script src="<?=$basedir; ?>assets/js/datepicker/Picker.js"></script>
	<script src="<?=$basedir; ?>assets/js/datepicker/Picker.Attach.js"></script>
	<script src="<?=$basedir; ?>assets/js/datepicker/Picker.Date.js"></script>
	<script src="<?=$basedir; ?>assets/js/scripts.js"></script>
	<script>
		// Create a global register.
		var Faktura = new Hash;

		Faktura.set('modal.blur', <?php echo ($theme_options['popup_blur'] ? 'true' : 'false') ?>);

		// Define the default JS language.
		Locale.use('<?=$config->get('language'); ?>');
	</script>

	<link rel="shortcut icon" href="<?=$basedir; ?>assets/ico/favicon.png">
</head>

<body>
	<div id="main-container" class="container">
		<div class="row">
			<div class="col-xs-12">

				<div class="pull-right mt5">
					<p>Eingeloggt als <strong><?=$user->username ?></strong></p>
					<div class="dropdown pull-right">
						<button class="btn btn-success dropdown-toggle btn-sm">Optionen <span class="caret"></span></button>
						<ul class="dropdown-menu">
							<li><a href="<?=Route::url('user', array('action' => 'edit/' . $user->id)) ?>" title="<?=__('Profile') ?>"><i class="fa fa-user"></i> <?=__('Profile') ?></a></li>
							<?php if (in_array('admin', $user_roles)): ?>
							<li><a href="<?=Route::url('user', array('action' => 'list')) ?>" title="<?=__('Profile') ?>"><i class="fa fa-group"></i> <?=__('User administration') ?></a></li>
							<?php endif; ?>
							<li class="divider"></li>
							<li><a href="<?=Route::url('user', array('action' => 'logout')) ?>" title="<?=__('Logout') ?>"><i class="fa fa-sign-out"></i> <?=__('Logout') ?></a></li>
						</ul>
					</div>
				</div>
				<h1><a href="<?=URL::base() ?>"><?=$config->get('title') ?></a></h1>

			</div>
		</div>

		<div class="row menu">
			<div class="col-sm-4">
				<?php if (in_array('admin', $user_roles) || in_array('invoices', $user_roles)): ?>
				<h3><a href="<?=Route::url('invoice') ?>" class="btn btn-lg btn-block<?=((strpos($_SERVER['REQUEST_URI'], Route::url('invoice')) === 0) ? ' btn-primary' : ' btn-default') ?>"><i class="fa fa-file-text-o"></i> <?=__('Invoices') ?></a></h3>
				<?php endif; ?>
			</div>

			<div class="col-sm-4">
				<?php if (in_array('admin', $user_roles) || in_array('customers', $user_roles)): ?>
				<h3><a href="<?=Route::url('customer') ?>" class="btn btn-lg btn-block<?=((strpos($_SERVER['REQUEST_URI'], Route::url('customer')) === 0) ? ' btn-primary' : ' btn-default') ?>"><i class="fa fa-group"></i> <?=__('Customers') ?></a></h3>
				<?php endif; ?>
			</div>

			<div class="col-sm-4">
				<?php if (in_array('admin', $user_roles) || in_array('suppliers', $user_roles)): ?>
				<h3><a href="<?=Route::url('supplier') ?>" class="btn btn-lg btn-block<?=((strpos($_SERVER['REQUEST_URI'], Route::url('supplier')) === 0) ? ' btn-primary' : ' btn-default') ?>"><i class="fa fa-truck"></i> <?=__('Supplier') ?></a></h3>
				<?php endif; ?>
			</div>
		</div>

		<hr />

		<?=$content; ?>

		<?php if (Kohana::$environment == Kohana::DEVELOPMENT): ?>
		<hr />

		<?=View::factory('profiler/stats') ?>
		<?php endif; ?>
	</div>
	<div id="overlay" class="hidden"></div>
	<div id="popup" class="hidden container well">
		<i class="fa fa-times popup-close pull-right mouse-pointer"></i>
		<div id="popup-content"></div>
	</div>
</body>
</html>