<?php defined('SYSPATH') OR die('No direct script access.');

return array(
	'version' => '1.1.0',
	'prev_version' => '1.0',
	'queries' => array(
		array(
			'if' => 'field-exists:users.theme,type:varchar',
			'then' => '',
			'else' => 'ALTER TABLE `users` ADD `theme` VARCHAR(255) NOT NULL;'
		),
		array(
			'if' => 'field-exists:users.theme_options,type:text',
			'then' => '',
			'else' => 'ALTER TABLE `users` ADD `theme_options` TEXT NOT NULL;'
		),
		array(
			'if' => 'table-exists:widgets',
			'then' => '',
			'else' => 'CREATE TABLE IF NOT EXISTS `widgets` (`id` int(11) unsigned NOT NULL AUTO_INCREMENT, `widget` varchar(255) DEFAULT NULL, `user_id` int(11) unsigned NOT NULL, `config` text NOT NULL, `sorting` int(2) NOT NULL DEFAULT "99", PRIMARY KEY (`id`), KEY `user_id` (`user_id`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;' .
				'ALTER TABLE `widgets` ADD CONSTRAINT `widgets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;'
		)
	),
	'changelog' => array(
		'1.2.0' => array(
			'[Release] 1.2.0 - This version adds more features to customize your Faktura installation.',
			'[Feature] Implementing a "modal popup" plugin.',
			'[Feature] Implemented a customizable dashboard.',
			'[Bugfix] Some minor updates and fixes.',
		),
		'1.1.0' => array(
			'[Release] 1.1.0 - The second major version release which brings some new features and a few bugfixes.',
			'[Feature] Implemented a "theme selector" function. This can be found at the user profiles.',
			'[Feature] Creating a basic "update" wizard.',
			'[Feature] Creating a basic "setup" wizard.',
			'[Change] Updating to FontAwesome 4.0.3.',
			'[Change] Changing the font-size of the widget headlines.',
			'[Change] Adding "number" filters for some properties (engineer hour price, ...).',
			'[Change] Adding the "setup.sql" file for manual and automated installation.',
			'[Change] Removing some unnecessary HTML from the invoice, customer and supplier tables.',
			'[Change] Updating various views with the standard Faktura branding (from base configuration).',
			'[Bugfix] JS Bugfix for the "searchable" popup (inside invoice form, when searching a customer).',
			'[Bugfix] Bugfixing the exception handling, when a user is not logged in.',
			'[Bugfix] JS Bugfix, when tables have no data.'
		),
		'1.0' => array(
			'[Release] The first major "Faktura" system release with all its standard features.'
		)
	)
);