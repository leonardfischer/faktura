<div class="row">
	<div class="col-xs-12">
		<h2>2. Changes since version <?=$update['prev_version'] ?></h2>
		<p>At this point you can see all changes and/or new features the new Faktura version will bring to you. The next step will initialize the update procedure!</p>

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
</script>