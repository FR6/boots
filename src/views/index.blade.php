@extends('boots::layouts.main')

@section('js')

	@foreach($components as $c)

		@if($c['js'])
			<script type="text/javascript" src="{{ URL::asset('js/boots/'.$c['name'].'.js') }}"></script>
		@endif

		@if($c['controls']['js'])
			<script type="text/javascript" src="{{ URL::asset('js/boots/'.$c['name'].'-controls.js') }}"></script>
		@endif

	@endforeach
@stop

@section('body')

<div id="boots" class="">
	<div class="row">
		
		<div class="sidebar col-md-2">

			<div class="navbar navbar-default">
				<div class="navbar-header">
					<a href="#" class="navbar-brand">{{ Config::get('boots::boots.title') }}</a>
				</div>
			</div>
				
			@foreach($groups as $groupname => $gr)
				<div class="panel panel-default">
					<div class="panel-heading">
						<h2>{{ $groupname }}</h2>
					</div>
					<div class="list-group">
						@foreach($gr as $c)
							<a class="list-group-item" href="#{{ $c['name'] }}">{{ $c['name'] }}</a>
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
			@foreach($groups as $groupname => $gr)
				
				@foreach($gr as $c)
					<div class="component">
						<a name="{{ $c['name'] }}"></a>
						<div class="page-header">
							<h1>
								{{ $c['name'] }}
								@if($c['page']['php'])
									<small><a href="{{ URL::to("boots/{$c['name']}") }}">Standalone page</a></small>
								@endif							
							</h1>
						</div>						
						<div class="content">
							@include($c['view'])
						</div>
						<?php /*
						<div class="controls">
							@if($c['controls']['php'])
								@include("boots.controls.{$c['name']}")
							@endif
						</div> */ ?>					
					</div>
				@endforeach
				
			@endforeach
		</div>

	</div>
</div>

@stop