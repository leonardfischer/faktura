<div class="row">
	<div class="col-xs-12">
		<h2>Step 1. Introduction</h2>
		<p>Welcome to the Faktura update wizard. Here we will update your Faktura version from your current <?=$config->get('version') ?> to <?=$update['version'] ?>!</p>
		<p><strong>Attention!</strong> Please do not reload your browser tab during the complete update procedure or your installation may corrupt.</p>

		<table class="table table-striped">
			<?php foreach ($requirements as $name => $message): ?>
			<tr>
				<td><?=$name ?></td>
				<td><?=$message ?></td>
			</tr>
			<?php endforeach; ?>
		</table>
	</div>
</div>

<script>
	window.addEvent('domready', function() {
		<?php if ($errors): ?>
		window.disable_next_button();
		<?php else: ?>
		window.enable_next_button();
		<?php endif; ?>
	});
</script>
