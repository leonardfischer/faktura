<!DOCTYPE html>
<html lang="de">
<head>
	<meta charset="utf-8">
	<title>Faktura setup</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">

	<link href="<?php echo $basedir; ?>assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo $basedir; ?>assets/css/font-awesome.min.css" rel="stylesheet">
	<link href="<?php echo $basedir; ?>assets/css/style.css" rel="stylesheet">
	<link href="<?php echo $basedir; ?>assets/css/datepicker.css" rel="stylesheet">
	<script src="<?php echo $basedir; ?>assets/js/mootools-core-1.4.5.js"></script>
	<script src="<?php echo $basedir; ?>assets/js/mootools-more-1.4.0.1.js"></script>
	<script src="<?php echo $basedir; ?>assets/js/respond.js"></script>
	<script>
		// Create a global register.
		var Factura = new Hash;

		// Define "german" as default JS language.
		Locale.use('<?=$config->get('language'); ?>');
	</script>

	<link rel="shortcut icon" href="<?= $basedir; ?>assets/ico/favicon.png">
</head>

<body>
<div id="main-container" class="container">
	<div class="row">
		<div class="col-xs-12">
			<h1><a href="<?= URL::base() ?>">Faktura setup</a></h1>
		</div>
	</div>

	<hr/>

	<div id="content-area">
		<?php echo $content; ?>
	</div>

	<hr />

	<div class="row">
		<div class="col-xs-12">
			<button type="button" id="next-button" class="btn btn-success pull-right">Next &raquo;</button>
			<button type="button" id="reload-button" class="btn btn-default"><i class="icon-refresh mr10"></i> Try again</button>
		</div>
	</div>
</div>

<script>
	Factura.set('current-step', 1);

	window.disable_next_button = function () {
		$('next-button')
			.removeClass('btn-success')
			.addClass('btn-danger')
			.set('disabled', 'disabled');
	};

	window.enable_next_button = function () {
		$('next-button')
			.removeClass('btn-danger')
			.addClass('btn-success')
			.set('disabled', false);
	};

	$('reload-button').addEvent('click', function () {
		new Request.HTML({
			url: '?',
			data: {step:Factura.get('current-step'), factura_data:Factura.toQueryString()},
			update: $('content-area')
		}).send();
	});

	$('next-button').addEvent('click', function () {
		new Request.HTML({
			url: '?',
			data: {step:(Factura.get('current-step') + 1), factura_data:Factura.toQueryString()},
			update: $('content-area'),
			onSuccess: function () {
				Factura.set('current-step', (Factura.get('current-step') + 1));
			}
		}).send();
	});
</script>

</body>
</html>