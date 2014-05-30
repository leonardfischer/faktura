<div class="row">
	<div class="col-xs-12">
		<h2>3. Update your configuration</h2>
		<p>Please check and update the configuration displayed below. Don't worry, you can still change it later by editing the file "<code><?=APPPATH . 'config' . DS . 'base.php' ?></code>"!</p>
		<p><strong>Attention!</strong> By clicking the "Next" button you trigger the update procedure which will perform various actions (including database updates).</p>
	</div>
</div>

<form class="form-horizontal" method="post" id="config-form">
	<fieldset>
		<legend>Update base configuration</legend>

		<div class="row">
			<div class="col-sm-6">

				<div class="form-group">
					<label class="col-md-4 control-label" for="inputTitle">Title</label>
					<div class="col-md-8"><?=Form::input('inputTitle', $values['title'], array('type' => 'text', 'placeholder' => 'Title', 'class' => 'form-control')) ?></div>
				</div>

				<div class="form-group">
					<label class="col-md-4 control-label" for="inputTheme">Default theme</label>
					<div class="col-md-8"><?=Form::select('inputTheme', $themes, $values['theme'], array('class' => 'form-control')) ?></div>
				</div>

				<div class="form-group">
					<label class="col-md-4 control-label" for="inputTimezone">Timezone</label>
					<div class="col-md-8">
						<?=Form::input('inputTimezone', $values['timezone'], array('type' => 'text', 'placeholder' => 'Timezone', 'class' => 'form-control')) ?><br />
						<p class="text-info"><i class="fa fa-info-circle"></i> Please check the <a target="_blank" href="https://php.net/manual/en/timezones.php">PHP documentation</a>.</p>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-4 control-label" for="inputLocale">Locale</label>
					<div class="col-md-8">
						<?=Form::input('inputLocale', $values['locale'], array('type' => 'text', 'placeholder' => 'Locale', 'class' => 'form-control')) ?><br />
						<p class="text-info"><i class="fa fa-info-circle"></i> Please check the <a target="_blank" href="http://www.w3.org/WAI/ER/IG/ert/iso639.htm">ISO 639 Specification</a> for more information.</p>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-4 control-label" for="inputLanguage">Language</label>
					<div class="col-md-8"><?=Form::select('inputLanguage', $languages, $values['language'], array('class' => 'form-control')) ?></div>
				</div>

				<hr />

				<div class="form-group">
					<label class="col-md-4 control-label" for="inputPassword_minlength">Password min length</label>
					<div class="col-md-8"><?=Form::input('inputPassword_minlength', $values['password_minlength'], array('type' => 'text', 'placeholder' => 'Password minimum length', 'class' => 'form-control')) ?></div>
				</div>

				<div class="form-group">
					<label class="col-md-4 control-label" for="inputPassword_prevent_copynpaste">Prevent copy and paste when confirming a password</label>
					<div class="col-md-8"><?=Form::select('inputPassword_prevent_copynpaste', $boolean, (int) $values['password_prevent_copynpaste'], array('class' => 'form-control')) ?></div>
				</div>
			</div>

			<div class="col-sm-6">
				<div class="form-group">
					<label class="col-md-4 control-label" for="inputMail_faktura">Email address</label>
					<div class="col-md-8">
						<?=Form::input('inputMail_faktura', $values['mail_faktura'], array('type' => 'text', 'placeholder' => 'Faktura Email address', 'class' => 'form-control')) ?><br />
						<p class="text-info"><i class="fa fa-info-circle"></i> This email address will be used if you receive emails from the Faktura system (reset password, reports, ...).</p>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-4 control-label" for="inputMail_transport">Mail transport</label>
					<div class="col-md-8"><?=Form::select('inputMail_transport', $mail_transports, $values['mail_transport'], array('class' => 'form-control')) ?></div>
				</div>

				<div class="form-group smtp hide">
					<label class="col-md-4 control-label" for="inputMail_smtp_host">SMTP host</label>
					<div class="col-md-8"><?=Form::input('inputMail_smtp_host', $values['mail_smtp_host'], array('type' => 'text', 'placeholder' => 'SMTP host', 'class' => 'form-control')) ?></div>
				</div>

				<div class="form-group smtp hide">
					<label class="col-md-4 control-label" for="inputMail_smtp_port">SMTP port</label>
					<div class="col-md-8"><?=Form::input('inputMail_smtp_port', $values['mail_smtp_port'], array('type' => 'text', 'placeholder' => 'SMTP port', 'class' => 'form-control')) ?></div>
				</div>

				<div class="form-group smtp hide">
					<label class="col-md-4 control-label" for="inputMail_smtp_user">SMTP user</label>
					<div class="col-md-8"><?=Form::input('inputMail_smtp_user', $values['mail_smtp_user'], array('type' => 'text', 'placeholder' => 'SMTP user', 'class' => 'form-control')) ?></div>
				</div>

				<div class="form-group smtp hide">
					<label class="col-md-4 control-label" for="inputMail_smtp_pass">SMTP password</label>
					<div class="col-md-8"><?=Form::input('inputMail_smtp_pass', $values['mail_smtp_pass'], array('type' => 'text', 'placeholder' => 'SMTP password', 'class' => 'form-control')) ?></div>
				</div>

				<div class="form-group sendmail hide">
					<label class="col-md-4 control-label" for="inputMail_sendmail_command">Sendmail command</label>
					<div class="col-md-8"><?=Form::input('inputMail_sendmail_command', $values['mail_sendmail_command'], array('type' => 'text', 'placeholder' => 'Sendmail command', 'class' => 'form-control')) ?></div>
				</div>

				<hr />

				<div class="form-group">
					<label class="col-md-4 control-label" for="inputSearch_minlength">Search min length</label>
					<div class="col-md-8"><?=Form::input('inputSearch_minlength', $values['search_minlength'], array('type' => 'text', 'placeholder' => 'Search phrase minimum length', 'class' => 'form-control')) ?></div>
				</div>

				<div class="form-group">
					<label class="col-md-4 control-label" for="inputSearch_wordsplit">Search wordsplit character</label>
					<div class="col-md-8">
						<?=Form::input('inputSearch_wordsplit', $values['search_wordsplit'], array('type' => 'text', 'placeholder' => 'Search wordsplit character', 'class' => 'form-control')) ?><br />
						<p class="text-info"><i class="fa fa-info-circle"></i> The selected character will be used to split a search phrase into single parts for situations where you (for example) search for a company and a city at the same time.</p>
					</div>
				</div>

				<hr />

				<div class="form-group">
					<label class="col-md-4 control-label" for="inputRows_per_page">Rows per page</label>
					<div class="col-md-8"><?=Form::input('inputRows_per_page', $values['rows_per_page'], array('type' => 'text', 'placeholder' => 'Rows per page', 'class' => 'form-control')) ?></div>
				</div>

				<div class="form-group">
					<label class="col-md-4 control-label" for="inputInvoice_start_no">Invoice start number</label>
					<div class="col-md-8"><?=Form::input('inputInvoice_start_no', $values['invoice_start_no'], array('type' => 'text', 'placeholder' => 'Invoice start number', 'class' => 'form-control')) ?></div>
				</div>
			</div>
		</div>
	</fieldset>
</form>

<div class="row">
	<div class="col-xs-12">
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

	$('inputMail_transport').addEvent('change', function () {
		$$('.smtp,.sendmail').invoke('addClass', 'hide');
		$$('.' + this.get('value')).invoke('removeClass', 'hide');
	}).fireEvent('change');
</script>