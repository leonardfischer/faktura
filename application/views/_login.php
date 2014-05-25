<!DOCTYPE html>
<html lang="de">
<head>
	<meta charset="utf-8">
	<title>Login - <?php echo $config->get('title') ?></title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">

	<link href="<?=$basedir; ?>assets/css/themes/default/bootstrap.min.css" rel="stylesheet">
	<link href="<?=$basedir; ?>assets/css/themes/default/addition.css" rel="stylesheet">
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

		// Define "german" as default JS language.
		Locale.use('de-DE');
	</script>

	<link rel="shortcut icon" href="<?=$basedir; ?>assets/ico/favicon.png">
</head>

<body>
	<div class="container">
		<div class="row">
			<div class="col-md-offset-3 col-md-6">

				<h2><?=__('Welcome, please login to proceed') ?></h2>

				<div class="well well-sm">
					<div id="login-form">
						<form method="post" action="<?php echo Route::url('user', array('action' => 'login')) ?>">
							<input type="text" class="form-control form-group" placeholder="<?=__('Username') ?>" name="username" required autofocus>
							<input type="password" class="form-control form-group" placeholder="<?=__('Password') ?>" name="password" required>

							<label class="checkbox">
								<input type="checkbox" value="remember-me" name="remember"> <?=__('Remember me') ?>
							</label>

							<?php if (isset($error)): ?>
							<div class="alert alert-danger"><?=$error ?></div>
							<?php endif; ?>

							<button class="btn btn-lg btn-primary btn-block mb20" type="submit"><?=__('Sign in') ?></button>
							<a href="#" id="forgot-password-toggle"><i class="fa fa-frown-o"></i> <?=__('I forgot my password') ?></a>
						</form>
					</div>

					<div id="forgot-password-form" style="display:none;">
						<form method="post" action="<?php echo Route::url('user', array('action' => 'reset_password')) ?>">
							<input type="text" class="form-control form-group" placeholder="<?=__('Email') ?>" name="email" required autofocus>
							<p><?=__('By clicking the "Reset my password" button your password will be changed by the system and sent to you by the given email address.') ?></p>
							<button class="btn btn-lg btn-primary btn-block" type="submit"><?=__('Reset my password') ?></button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script>
		$('forgot-password-toggle').addEvent('click', function () {
			$('login-form').dissolve();
			$('forgot-password-form').reveal();
		});
	</script>
</body>
</html>