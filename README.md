boots
=====

Laravel 4 package allowing to create a style guide separated in components and allowing to define design standards for a website.

Each component is decoupled from the page layout and is defined by:

- HTML markup
- CSS style
- Javascript

# Benefits

- Code organisation and modularity
- Quickly create a prototype
- Easy to test in different layouts
- Easy to maintain
- Clients and managers approbation
- Workflow in a team
- Shared vocabulary
- Useful reference
- Separation with the backend
- Documenting each component
- Integrates directly into project
- File based

# Features

- Annotation along each component (documentation)
- The components don't need to be used only with the Laravel Blade templating system
- Integration with Grunt
- Tagging system allowing to set a status to a component (ex.: approved, completed, in-progress)
- List visual design

# What can we do with it?

- Separate and encapsulate components
- Create a javascript plugin
- Create a page layout containing components

# Requirements

- PHP
	- Composer
	- Laravel 4+
- Bower
	- Twitter Bootstrap (not required)
- Grunt (not required)
	- LESS (not required)

# Concept

Boots is file based.

### Basic component

- app/views/boots/myComponent.blade.php (HTML markup)
- public/js/boots/myComponent.js
- public/css/boots/myComponent.less

### Layout containing various components

- app/views/boots/myComponent1.blade.php
- app/views/boots/myComponent2.blade.php
- app/views/boots/pages/myComponent1.blade.php

### Document a component

- app/views/boots/myComponent.blade.php
- app/views/boots/docs/myComponent.md

# Installation

- Install this package. In composer.json (of the root of your project) add the package:

		{
			...
			"require": { 
				...
				"CloudRaker/boots": "dev-master"
			},
			"repositories": [{
				"type": "git",
				"url": "git@github.com:fr6/boots.git"		
			}],
			...
		}

	$ composer update CloudRaker/boots

	In app/config/app.php

		'providers' => array(
			...
			'Cloudraker\Boots\BootsServiceProvider',
		)

	$ composer dump-autoload

- Create basic directories

		app/views/boots/
		
		public/css/boots/
		public/js/boots/

		public/img/boots/designs/

		Note: The "css/boots" and "js/boots" could have been anywhere (see the config file).
	
- Install Twitter Bootstrap in public/bower_components/ (not required)

		$ bower install bootstrap#3.3.1 -S

- Publish package assets and configuration files

		$ php artisan asset:publish cloudraker/boots
		$ php artisan config:publish cloudraker/boots

# Getting started

- If necessary, edit the boots config file (app/config/packages/cloudraker/boots/boots.php).

- Create your first component

		app/views/boots/myComponent.blade.php
		public/js/boots/myComponent.js
		public/css/boots/myComponent.less

- Import your component .less file

	In your main LESS file:

		@import "boots/myComponent.less";

- Configure Grunt to build your main less file into	public/css/index.css:

		grunt.initConfig({
			less: {
				production: {
					options: {
						paths: ['public/css'],
						cleancss: true
					},
					files: {
						'public/css/build.min.css': 'public/css/main.less'
					}			
				}
			}
		});

	Note: The CSS filename can be changed in the app/config/packages/cloudraker/boots/boots.php file.

- Visit da boooots: /boots

# Customization

### Component controls

	If you want to have button controls or some functionnality that is not included in your component you need to create the files:

		app/views/boots/controls/myComponent.blade.php
		public/js/boots/myComponent-controls.js

### Component page overwrite

	By default the View used to render a component is:

		vendor/cloudraker/boots/src/views/page.blade.php

	If you want to overwrite the component page to customize it just create the files:

		app/views/boots/pages/myComponent.blade.php
		public/js/boots/myComponent-page.js

# Exemples

### I'm creating a webpage and want to create a footer component

	- Create the file app/views/boots/footer.blade.php
	- Insert your HTML content

		<ul><li>footer</li></ul>
	- Create public/css/boots/footer.less file and import it in your main LESS file

### I want to create a page layout containing components

	- Create an empty file app/views/boots/myLayout.blade.php
	- Create the file app/views/boots/pages/myLayout.blade.php allowing to overwrite the base page of Boots (vendor/cloudraker/boots/src/views/page.blade.php)
	- Include a component in the layout:

		@include('boots.footer')

### I'm creating a plugin and want to test it outside my website

	- Create blade files
	
		page
		...

### I need a javascript tool that will not be in the website but will be usefull

	- Create the blade file in your views
	- Create the js file

### I'm not using Laravel for my project, but I still want to use Boots

	- Install Laravel and Boots at the root of your project:

		www/*Drupal files*
		www/laravel/*Laravel files*

	- Put your asset and component files outside of Laravel:
		
		www/sites/all/themes/MyDrupalTheme/js/boots/*files*
		www/sites/all/themes/MyDrupalTheme/css/boots/*files*

	- In the config file of Boots change the Path to assets:

		'path_assets' => '../../sites/all/themes/MyDrupalTheme/'

### I want to easily see my designs

	- Create JPG files of your PSD file.
	- Put them in public/img/boots/designs/.
	
	Note: The files need to be in JPG format and the extension need to be lowercase (.jpg).
