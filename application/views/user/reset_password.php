<!DOCTYPE html>
<html lang="de">
<head>
	<meta charset="utf-8">
	<title>Reset password - <?php echo $config->get('title') ?></title>
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

			<h2><?=$headline ?></h2>

			<div class="well well-sm">
				<?php if ($success): ?>
				<p>Your password has been reset by the system and is currently beeing sent to you by the system, this can take a few minutes.</p>
				<?php else: ?>
				<p>There was an error while resetting your password: <span class="text-danger"><?=$error_message ?></span></p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
</body>
</html>