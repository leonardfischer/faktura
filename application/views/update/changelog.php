<div class="row">
	<div class="col-xs-12">
		<h2>2. Changes since version <?=$update['prev_version'] ?></h2>
		<p>At this point you can see all changes and/or new features the new Faktura version will bring to you. The next step will initialize the update procedure!</p>
		<p><strong>Attention!</strong> By clicking the "Next" button you trigger the update procedure which will perform various actions (including database updates).</p>

		<?php foreach ($update['changelog'] as $version => $changes): ?>
		<h3 class="mouse-pointer"><?=$version ?></h3>
		<table class="table table-striped">
			<?php foreach ($changes as $change): ?>
				<tr>
					<td><?=$change ?></td>
				</tr>
			<?php endforeach; ?>
		</table>
		<hr />
		<?php endforeach; ?>

		<label class="mt20"><input type="checkbox" id="confirmationCheckbox" /> I understand that clicking the "Next" button will trigger the Faktura update, which will change some system-files and parts of the database. I have done a file and database backup!</label>
	</div>
</div>

<script>
	$$('h3').each(function (el, i) {
		if (i > 0) {
			el.getNext('table').addClass('hide');
		}
	}).addEvent('click', function () {
		this.getNext('table').toggleClass('hide');
	});

	$('next-button').set('disabled', 'disabled');

	$('confirmationCheckbox').addEvent('change', function () {
		if (this.checked) {
			$('next-button').set('disabled', false);
		} else {
			$('next-button').set('disabled', 'disabled');
		}
	});
</script>