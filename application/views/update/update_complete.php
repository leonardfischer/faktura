<div class="row">
	<div class="col-xs-12">
		<h2>Step 3. Update complete!</h2>
		<p>Please check the beneath, if everything went well. If not, try to re-run the setup!</p>

		<?php if (count($errors) == 0): ?>
			<div class="alert alert-success">No errors occured, during the update!</div>
			<p><a href="<?php echo $basedir; ?>">Please empty your browser cache and click here to start up the Faktura.</a><br />If you are experiencing style or javascript errors, try to reload a few times and clear your cache (CTRL+F5)</p>
		<?php else: ?>
			<table class="table table-striped">
				<?php foreach ($errors as $error): ?>
					<tr class="danger">
						<td>
							<?php echo $error; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		<?php endif; ?>
	</div>
</div>

<script>
	window.addEvent('domready', function() {
		$('next-button').addClass('hide');

		$('reload-button').set('disabled', 'disabled');
	});
</script>
