<div class="row">
	<div class="col-xs-12">
		<h2>Step 1. Introduction</h2>
		<p>Welcome to the Faktura setup wizard. At first we will try to check, if your environment fits the systems requirements!</p>
		<p><strong>Attention!</strong> Please do not reload your browser tab during the complete installation or your installation may corrupt.</p>

		<table class="table table-striped">
			<tr>
				<td>PHP short tags</td>
				<td><?php echo $short_tags ?></td>
			</tr>
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
