<div class="row">
	<div class="col-xs-12">
		<h2>Step 4. Define your admin-user</h2>
		<p>Nearly there! Now you need to input the login, password and email information for your admin-user.</p>

		<form class="form-horizontal" method="post" id="database-form">
			<fieldset>
				<legend>User data</legend>

				<div class="row">
					<div class="col-sm-6">

						<div class="form-group">
							<label class="col-md-4 control-label" for="inputUsername">Username<span class="text-danger">*</span></label>
							<div class="col-md-8"><?=Form::input('inputUsername', '', array('type' => 'text', 'placeholder' => 'Username', 'class' => 'form-control')) ?></div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label" for="inputPassword">Password<span class="text-danger">*</span></label>
							<div class="col-md-8"><?=Form::input('inputPassword', '', array('type' => 'text', 'placeholder' => 'Password', 'class' => 'form-control')) ?></div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label" for="inputEmail">Email<span class="text-danger">*</span></label>
							<div class="col-md-8"><?=Form::input('inputEmail', '', array('type' => 'text', 'placeholder' => 'Email', 'class' => 'form-control')) ?></div>
						</div>

					</div>
					<div class="col-sm-6">
						<p>Aftere clicking "Next" the installer will perform the following tasks:</p>
						<ul>
							<li>Create configuration files (to be found in <code><?php echo APPPATH ?>config</code>)</li>
							<li>Create a database, according to your input</li>
							<li>Import the necessary tables and define some standard user-roles</li>
							<li>Create a user, according to your input on the left side</li>
						</ul>

						<div class="checkbox">
							<label class="control-label"><input id="confirmationCheckbox" type="checkbox" value="" /> Okay, understood!</label>
						</div>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
</div>

<script>
	window.addEvent('domready', function() {
		// At first we disable the "next" button. This will be activated, as soon as the database connection is there.
		window.disable_next_button();

		$('next-button').set('html', 'Setup &raquo;');

		$$('#inputUsername,#inputPassword,#confirmationCheckbox').invoke('addEvent', 'change', function () {
			var username = $('inputUsername'),
				password = $('inputPassword'),
				email = $('inputEmail'),
				confirmation = $('confirmationCheckbox');

			if (username.value.clean() === '') {
				username.getParent('div.form-group').addClass('has-error');
				return;
			} else {
				username.getParent('div.form-group').removeClass('has-error');
			}

			if (password.value.clean() === '') {
				password.getParent('div.form-group').addClass('has-error');
				return;
			} else {
				password.getParent('div.form-group').removeClass('has-error');
			}

			if (email.value.clean() === '') {
				email.getParent('div.form-group').addClass('has-error');
				return;
			} else {
				email.getParent('div.form-group').removeClass('has-error');
			}

			if (! confirmation.checked) {
				confirmation.getParent('div.checkbox').addClass('has-error');
				return;
			}

			Faktura.set('adminuser', {
				username:username.value,
				password:password.value,
				email:email.value
			});

			window.enable_next_button();
		});
	});
</script>
