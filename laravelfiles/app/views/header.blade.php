<!DOCTYPE html>
<html>
	<head>
	    <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
		<title>CPE332 Database Project</title>
		{{ HTML::style('static/css/bootstrap.min.css') }}
		{{ HTML::style('static/css/main.css') }}
		{{ HTML::script('static/js/jquery-2.1.1.min.js') }}
		{{ HTML::script('static/js/bootstrap.min.js') }}
		{{ HTML::script('static/js/main.js') }}
		<script src="//use.typekit.net/hxv3qzn.js"></script>
		<script>try{Typekit.load();}catch(e){}</script>
	</head>
	
	<body>
	@include('modals')
	<div class="container mainbody">
		<div class="formheader container">
			<div class="dropdown">
				<button class="nav_button btn" data-toggle="dropdown">select form <span class="caret"></span></button>
				<ul class="dropdown-menu" role="menu" aria-labelledby="navmenu">
						<li role="presentation"><a role="menuitem" tabindex="-1" href="{{ URL::to('assets') }}">Asset ID with Components</a></li>
						<li role="presentation"><a role="menuitem" tabindex="-1" href="{{ URL::to('assets_depreciation') }}">Asset Depreciation Form</a></li>
						<li role="presentation"><a role="menuitem" tabindex="-1" href="{{ URL::to('assets_purchase') }}">Asset Purchase Form</a></li>
						<li role="presentation"><a role="menuitem" tabindex="-1" href="{{ URL::to('assets_sale') }}">Asset Sales Form</a></li>
						<li role="presentation"><a role="menuitem" tabindex="-1" href="{{ URL::to('assets_movement') }}">Asset Location Movement Form</a></li>
				</ul>
			</div>
			<div class="app_name"><h1 style="clear:both">Asset Management</h1></div>
			<div class="form_name container"><p style="clear:both">{{ $form_name }}</p></div>
			<div class="head_button_section container">
				<form method="post">
					<button type="submit" name="action" value="new" onclick="if(checkDirtyBit()) return true; return false;" class="head_button btn"><span class="glyphicon glyphicon-plus"></span><br/>new</button>
					<button type="button" class="head_button btn" onclick="if(checkDirtyBit()) { addSearchColumn('{{ $table_name }}'); openListOfValue('{{ $table_name }}','edit'); }"><span class="glyphicon glyphicon-pencil"></span><br/>edit</button>
					<button type="button" class="head_button btn" onclick="if(checkDirtyBit()) { addSearchColumn('{{ $table_name }}'); openListOfValue('{{ $table_name }}','copy'); }"><span class="glyphicon glyphicon-file"></span><br/>copy</button>
					<button type="button" name="action" value="save" onclick="if(checkRequiredField()) return save_asset();" class="head_button btn"><span class="glyphicon glyphicon-floppy-disk"></span><br/>save</button>
					<button type="submit" name="action" value="delete" onclick="return delete_asset();" class="head_button btn"><span class="glyphicon glyphicon-trash"></span><br/>delete</button>
					<a href="/print/{{ $table_name }}.php" target="_blank" class="head_button btn"><span class="glyphicon glyphicon-print"></span><br/>print</a>
				</form>
			</div>
		</div>