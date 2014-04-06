<div class="row">
	<div class="col-xs-12">
		<h2>Step 2. Input your database configuration</h2>
		<p>Please input your database configuration, so the system can install the necessary data.</p>
		<p><strong>Attention!</strong> The given MySQL user needs permission to create databases and tables!</p>

		<form class="form-horizontal" method="post" id="database-form">
			<fieldset>
				<legend>Database configuration</legend>

				<div class="row">
					<div class="col-sm-6">

						<div class="form-group">
							<label class="col-md-4 control-label" for="inputHost">Host<span class="text-danger">*</span></label>
							<div class="col-md-8"><?=Form::input('inputHost', '', array('type' => 'text', 'placeholder' => 'Host', 'class' => 'form-control')) ?></div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label" for="inputUsername">Username</label>
							<div class="col-md-8"><?=Form::input('inputUsername', '', array('type' => 'text', 'placeholder' => 'Username', 'class' => 'form-control')) ?></div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label" for="inputPassword">Password</label>
							<div class="col-md-8"><?=Form::input('inputPassword', '', array('type' => 'text', 'placeholder' => 'Password', 'class' => 'form-control')) ?></div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label" for="inputDatabase">Database<span class="text-danger">*</span></label>
							<div class="col-md-8"><?=Form::input('inputDatabase', '', array('type' => 'text', 'placeholder' => 'Database name', 'class' => 'form-control')) ?></div>
						</div>
					</div>

					<div class="col-sm-6">
						<button id="check-database-connection" type="button" class="btn btn-default btn-block">Check connection</button>

						<div id="check-database-result" class="alert hide mt15"></div>
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

		$('check-database-connection').addEvent('click', function () {
			var host = $('inputHost'),
				username = $('inputUsername'),
				password = $('inputPassword'),
				database = $('inputDatabase');

			if (host.value.clean() === '') {
				host.getParent('div.form-group').addClass('has-error');
				return;
			} else {
				host.getParent('div.form-group').removeClass('has-error');
			}

			if (database.value.clean() === '') {
				database.getParent('div.form-group').addClass('has-error');
				return;
			} else {
				database.getParent('div.form-group').removeClass('has-error');
			}

			new Request.JSON({
				url: '?',
				data: {
					special_step:'test_database_connection',
					hostname:host.value,
					username:username.value,
					password:password.value,
					database:database.value
				},
				onSuccess: function(json){
					console.log(json);

					if (json.success) {
						Faktura.set('db.config', json.data);
						$('check-database-result')
							.addClass('alert-success')
							.removeClass('alert-danger')
							.removeClass('hide')
							.set('html', json.message);
						window.enable_next_button();
					} else {
						$('check-database-result')
							.addClass('alert-danger')
							.removeClass('alert-success')
							.removeClass('hide')
							.set('html', json.message);
					}
				}}).send();
		});
	});
</script>
