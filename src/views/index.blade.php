@extends('boots::layouts.main')

<?php
$base_path = base_path();

$path_to_assets = Config::get('boots::boots.path_assets');
$path_to_css_file = $path_to_assets.Config::get('boots::boots.file_css');
$path_to_js_file = $path_to_assets.Config::get('boots::boots.file_js');
//dd($path_to_assets);

$status = Config::get('boots::boots.tags_status');
$colors = Config::get('boots::boots.tags_colors');
?>

@section('js')

	@foreach($components as $c)

		@if($c['js'])
			{{ HTML::script("{$path_to_assets}js/boots/{$c['name']}.js") }}
		@endif

	@endforeach
@stop

@section('body')

<div id="boots" class="">
	<div class="row">
		
		<div class="sidebar col-md-2">

			@include('boots::components/sidebar-head')
			@include('boots::components/sidebar-nav', array('active' => 'components'))
				
			@foreach($groups as $groupname => $gr)
				<div class="panel panel-default">
					<div class="panel-heading">
						<h2>{{ $groupname }}</h2>
					</div>
					<div class="list-group">
						@foreach($gr as $c)
							@include('boots::components/sidebar-item', array('item' => $c, 'status' => $status, 'colors' => $colors))
						@endforeach
					</div>
				</div>
			@endforeach

			<?php /*
			<ul>
			@foreach($components as $c)		
				<li>{{ $c['name'] }}</li>
			@endforeach
			</ul>
			*/?>
		</div>
		<div class="main col-md-10 container">

			<?php
			#
			# Intro
			#
			?>
			<div class="page-header"><h1 id="introduction">Introduction</h1></div>
			<p>This microsite regroups all of the components for the {{{ Config::get('boots::boots.title') }}} Styleguide.</p>
			<!--<p>The <a href="/styles">styles</a> section regroup all of the generic styling for the site.</p>
			<p>The <a href="/components">components</a> section regroup all of the components with functionnal demo when needed.</p>
			-->

			<?php
			//dd($packagejson->version);
			?>
			<!--
			<ul>
				@if(isset($packagejson['version']))
					<li>Version: {{$packagejson['version']}}</li>
				@endif
				@if(isset($versionjson['date']))
					<li>Date: {{$versionjson['date']}}</li>
				@endif
			</ul>

			<h3>Compiled files</h3>
			<p>Compiled files can be found here:</p>
			<ul>
				<?php /*<li><a href="{{{ URL::asset('js/main.js') }}}">main.js</a></li>*/ ?>
				<li><a href="{{{ $path_to_js_file }}}">{{{ Config::get('boots::boots.file_js') }}}</a></li>
				<li><a href="{{{ $path_to_css_file }}}">{{{ Config::get('boots::boots.file_css') }}}</a></li>
			</ul>			

			<h3>Source files</h3>
			<p>Complete source files can be found here:</p>
			<ul>
				<li><a href="{{{ URL::asset('files/sources.zip') }}}">sources.zip</a></li>	
			</ul>
			-->

		 	<?php
		 	#
		 	# Components
		 	#
		 	?>
			@foreach($groups as $groupname => $gr)
				
				@foreach($gr as $c)
					@if($c['base'])
						<div class="component">
							<a name="{{ $c['name'] }}"></a>
							<div class="page-header">
								<h1>
									{{ $c['name'] }}
									@if($c['page']['php'])
										<small><a href="{{ URL::to("boots/{$c['name']}") }}">Standalone page</a></small>
									@endif							
								</h1>
								@if($c['doc'])
									<div class="well well-sm">
										<pre>
											<?php 
											//note Strangely if I dont put a \n it will have tabs in the first row...
											echo "\n".file_get_contents(app_path()."/views/boots/docs/{$c['name']}.md"); 
											?>
										</pre>
									</div>
								@endif
							</div>						
							<div class="content" style="position: relative; min-height:100px; overflow:hidden;">
								@include($c['view'])
							</div>
							<?php /*
							<div class="controls">
								@if($c['controls']['php'])
									@include("boots.controls.{$c['name']}")
								@endif
							</div> */ ?>					
						</div>
					@endif
				@endforeach
				
			@endforeach
		</div>

	</div>
</div>

@stop