<!DOCTYPE html>
<html lang="de">
<head>
	<meta charset="utf-8">
	<title>Print - <?=$config->get('title') ?></title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">

	<script src="<?=$basedir; ?>assets/js/mootools-core-1.4.5.js"></script>
	<script src="<?=$basedir; ?>assets/js/mootools-more-1.4.0.1.js"></script>

	<script>
		// Create a global register.
		var Factura = new Hash;

		// Define "german" as default JS language.
		Locale.use('de-DE');
	</script>

	<link href="<?=$basedir; ?>assets/css/print.css" rel="stylesheet">
	<link href="<?=$basedir; ?>assets/css/print.css" rel="stylesheet" media="print">
</head>

<body>
	<div id="container"><?=$content ?></div>
</body>
</html>