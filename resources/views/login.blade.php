<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>login</title>
	<!-- Latest compiled and minified CSS & JS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<script src="//code.jquery.com/jquery.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
</head>
<body>
	<div class="container">
	<div class="row">
	<div class="col-xs-6 col-xs-offset-3">
		@if ((Session::get('error')))
		<div class="alert alert-danger">
		  <strong>Danger!</strong> Invalidate Token.
		</div>
		@endif 
		<form action="login" method="POST" role="form">
			{{ csrf_field() }}	
			<div class="form-group">
				<label for="">Input Accesstoken</label>
				<input type="text" name="access_token" class="form-control" required="true" placeholder="Accesstoken">
			</div>
			<button type="submit" class="btn btn-primary">Login</button>
		</form>		
	</div>

	</div>
	</div>
</body>
</html>