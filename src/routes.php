<?php

//deprecated
function detect_component($filename){

	$base_path = base_path();
	$path_to_assets = Config::get('boots::boots.path_assets');
	//dd($path_to_assets);
	$name = str_replace('.blade.php', '', $filename);

    return array(
    	'name' 		=> $name,
    	//'type' 		=> '',
    	/*
    	'controls' 	=> array(
    		'js' 	=> file_exists("{$base_path}/public/{$path_to_assets}js/boots/{$name}-controls.js"),
    		'php' 	=> file_exists("{$base_path}/app/views/boots/controls/{$name}.blade.php")
    	),
    	*/
    	'page' 		=> array(
    		'js' 	=> file_exists("{$base_path}/public/{$path_to_assets}js/boots/{$name}-page.js"),
    		'php' 	=> file_exists("{$base_path}/app/views/boots/pages/{$name}.blade.php")
    	),
    	'doc' 		=> file_exists("{$base_path}/app/views/boots/docs/{$name}.md"),
    	'view' 		=> "boots.{$name}",		            	
    	'js' 		=> file_exists("{$base_path}/public/{$path_to_assets}js/boots/{$name}.js")
    );
}

function detectComponent($name){

	$base_path = base_path();
	$path_to_assets = Config::get('boots::boots.path_assets');

	return array(
		'name' 		=> $name,
		'base' 		=> file_exists("{$base_path}/app/views/boots/{$name}.blade.php"),
    	'page' 		=> array(
    		'js' 	=> file_exists("{$base_path}/public/{$path_to_assets}js/boots/{$name}-page.js"),
    		'php' 	=> file_exists("{$base_path}/app/views/boots/pages/{$name}.blade.php")
    	),
    	'doc' 		=> file_exists("{$base_path}/app/views/boots/docs/{$name}.md"),
    	'view' 		=> "boots.{$name}",		            	
    	'js' 		=> file_exists("{$base_path}/public/{$path_to_assets}js/boots/{$name}.js")
	);
}

function detectComponents($components_name){
		
	$components = array();

	foreach($components_name as $name){

		$components[] = detectComponent($name);
	}

	return $components;
}

// Allow to sort designs and components
function sort_by_name($a, $b){

	if($a['name'] == $b['name']){
		return 0;
	}
	return ($a['name'] < $b['name']) ? -1 : 1;
}

//deprecated
function load_components(){

	//List components

	$components = array();
	//$base_path = base_path();

	if($handle = opendir(base_path().'/app/views/boots/')){

		$invalid_files = array('.', '..', '.DS_Store', '._.DS_Store', 'controls', 'pages', 'docs', 'layouts');
	    
	    while(false !== ($entry = readdir($handle))){

	    	if(!in_array($entry, $invalid_files)){

	    		if(strpos($entry, '._') !== 0){ //File starting with ."_"

	    			$name = str_replace('.blade.php', '', $entry);

	            	$components[] = detect_component($entry);
	    		}	        	
	        }
	    }
	    closedir($handle);
	}
	//dd($components);

	$settings = new Setting();

	$components = $settings->apply('components', $components);

	usort($components, 'sort_by_name');

	return $components;
}

function scanComponents($path){

	// List components

	$components = array();

	if($handle = opendir($path)){

		$invalid_files = array('.', '..', '.DS_Store', '._.DS_Store', 'controls', 'pages', 'docs', 'layouts');
	    
	    while(false !== ($entry = readdir($handle))){

	    	if(!in_array($entry, $invalid_files)){

	    		if(strpos($entry, '._') !== 0){ //File starting with ."_"

	    			$name = str_replace('.blade.php', '', $entry);

	            	//$components[] = detect_component($entry);
	            	$components[] = $name;
	    		}	        	
	        }
	    }
	    closedir($handle);
	}
	//dd($components);

	return $components;
}

function load_designs(){

	$designs = array();

	if($handle = opendir(base_path().'/public/'.Config::get('boots::boots.path_designs'))){

		$invalid_files = array('.', '..', '.DS_Store', '._.DS_Store');
	    
	    while(false !== ($entry = readdir($handle))){

	    	//dd(strpos($entry, '._'));

	    	if(!in_array($entry, $invalid_files) && strpos($entry, '._') !== 0){
	            //echo "$entry\n";

	        	$name = str_replace('.jpg', '', strtolower($entry));

	            $designs[] = array('name' => $name);
	        }
	    }
	    closedir($handle);
	}

	$settings = new Setting();

	$designs = $settings->apply('designs', $designs);

	usort($designs, 'sort_by_name');
	//dd($designs);

	return $designs;
}

//todo Authentification
//'before' => 'authentification'
Route::group(array('before' => 'lazyauth', 'prefix' => 'boots'), function(){

	Route::get('/', function(){

		//$components = load_components();
		$componentsBase = scanComponents(base_path().'/app/views/boots/');
		$componentsPage = scanComponents(base_path().'/app/views/boots/pages');

		$components = array_merge($componentsBase, $componentsPage);
		//dd($components);

		// Detect components

		$components = detectComponents($components);
		//dd($components);

		// Apply settings

		$settings = new Setting();
		$components = $settings->apply('components', $components);
		usort($components, 'sort_by_name');
		//dd($components);		

		//

		$components2 = $components;

		// Components into groups

		$groups = array();

		foreach(Config::get('boots::boots.order') as $groupname => $item){

			$groups[$groupname] = array();

			foreach($item as $componentname){

				foreach($components2 as $key => $c){

					if($c['name'] == $componentname){

						$groups[$groupname][] = $c;
						unset($components2[$key]);

						break;
					}

					//dd($c);
				}
			}

			//dd($item);
		}

		// Components not in the config file

		foreach($components2 as $c){
			$groups[''][] = $c;
		}

		//

		$designs = load_designs();

		// Package info

		$packagejson_filename = storage_path().'/../../package.json';
		$packagejson = array();

		if(file_exists($packagejson_filename)){
			$packagejson = json_decode(file_get_contents($packagejson_filename), true);
		}

		// Version info

		$versionjson_filename = storage_path().'/version.json';
		$versionjson = array();

		if(file_exists($versionjson_filename)){
			$versionjson = json_decode(file_get_contents($versionjson_filename), true);
		}
		
		return View::make('boots::index', compact('components', 'groups', 'designs', 'packagejson', 'versionjson'));
	});

	Route::get('designs', function(){

		$components = load_components();
		$designs 	= load_designs();

		return View::make('boots::designs', compact('designs', 'components'));
	});

	Route::get('designs/{item}', function($item){

		//$designs = load_designs();
		$filename = "{$item}.jpg";

		if(!file_exists(base_path()."/public/".Config::get('boots::boots.path_designs')."/{$filename}")){
			
			App::abort(404);

		}else{
				
			return View::make('boots::design-item')->with('design', $item);
		}
	});

	Route::controller('admin', 'AdminController');

	/*
	Route::get('admin', function(){

		$components = load_components();
		$designs 	= load_designs();

		return View::make('boots::admin', compact('components', 'designs'));
	});
	*/

	Route::get('{item}', function($item){

		$component = detectComponent($item);
		//dd($component);

		if($component['base'] || $component['page']['php']){

			// View overwriten?

			if($component['page']['php']){
				return View::make("boots.pages.{$item}", compact('component'));
			}else{
				return View::make('boots::page', compact('component'));			
			}	

		} else {
			App::abort(404);
		}
	});
});