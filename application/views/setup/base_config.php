<div class="row">
	<div class="col-xs-12">
		<h2>Step 2. Input your base configuration</h2>
		<p>Please input your base configuration, for example the faktura name, the default theme, the starting invoice number, ...</p>

		<form class="form-horizontal" method="post" id="database-form">
			<fieldset>
				<legend>Base configuration</legend>

				<div class="row">
					<div class="col-sm-6">

						<div class="form-group">
							<label class="col-md-4 control-label" for="inputName">Faktura name</label>
							<div class="col-md-8"><?=Form::input('inputName', 'Faktura', array('type' => 'text', 'placeholder' => 'Faktura name', 'class' => 'form-control')) ?></div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label" for="inputTheme">Default theme</label>
							<div class="col-md-8"><?=Form::select('inputTheme', $themes, 'default', array('class' => 'form-control')) ?></div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label" for="inputTimezone">Timezone<span class="text-danger">*</span></label>
							<div class="col-md-8">
								<?=Form::input('inputTimezone', '', array('type' => 'text', 'placeholder' => 'America/Chicago', 'class' => 'form-control')) ?>
								<p class="text-muted">Please check the <a target="_blank" href="https://php.net/manual/en/timezones.php">PHP documentation</a>.</p>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label" for="inputLocale">Locale<span class="text-danger">*</span></label>
							<div class="col-md-8">
								<?=Form::input('inputLocale', '', array('type' => 'text', 'placeholder' => 'en_US.utf-8', 'class' => 'form-control')) ?>
								<p class="text-muted">Please check the <a target="_blank" href="http://www.w3.org/WAI/ER/IG/ert/iso639.htm">ISO 639 Specification</a> for more information.</p>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label" for="inputLanguage">Language</label>
							<div class="col-md-8"><?=Form::select('inputLanguage', $languages, 'en_US', array('class' => 'form-control')) ?></div>
						</div>
					</div>

					<div class="col-sm-6">
						<div class="form-group">
							<label class="col-md-4 control-label" for="inputRowsPerPage">Rows per page</label>
							<div class="col-md-8">
								<?=Form::input('inputRowsPerPage', 40, array('type' => 'text', 'placeholder' => '40', 'class' => 'form-control')) ?>
								<p class="text-muted">This configuration defines, how many rows will be displayed on one table page - this needs to be a number.</p>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label" for="inputInvoiceStartNo">Invoice start number</label>
							<div class="col-md-8">
								<?=Form::input('inputInvoiceStartNo', 1, array('type' => 'text', 'placeholder' => '1', 'class' => 'form-control')) ?>
								<p class="text-muted">You can define with which number your invoices shall be prefixed - this needs to be a number.</p>
							</div>
						</div>

						<button id="check-base-config" type="button" class="btn btn-primary btn-block">Check your input</button>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
</div>

<script>
	window.addEvent('domready', function () {
		// At first we disable the "next" button. This will be activated, as soon as the database connection is there.
		window.disable_next_button();

		$('check-base-config').addEvent('click', function () {
			var timezone = $('inputTimezone'),
				locale = $('inputLocale'),
				rows_per_page = $('inputRowsPerPage'),
				invoice_start_no = $('inputInvoiceStartNo');

			if (timezone.value.clean() === '') {
				timezone.getParent('div.form-group').addClass('has-error');
				return;
			} else {
				timezone.getParent('div.form-group').removeClass('has-error');
			}

			if (locale.value.clean() === '') {
				locale.getParent('div.form-group').addClass('has-error');
				return;
			} else {
				locale.getParent('div.form-group').removeClass('has-error');
			}

			if (rows_per_page.value.clean() === '' || isNaN(rows_per_page.value)) {
				rows_per_page.getParent('div.form-group').addClass('has-error');
				return;
			} else {
				rows_per_page.getParent('div.form-group').removeClass('has-error');
			}

			if (invoice_start_no.value.clean() === '' || isNaN(invoice_start_no.value)) {
				invoice_start_no.getParent('div.form-group').addClass('has-error');
				return;
			} else {
				invoice_start_no.getParent('div.form-group').removeClass('has-error');
			}

			Faktura.set('base', $('database-form').toQueryString().parseQueryString());

			window.enable_next_button();
		});
	});
</script>