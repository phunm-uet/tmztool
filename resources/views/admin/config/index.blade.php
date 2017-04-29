@extends('admin.layout')
@section('css')
	
@stop

@section('content')
	<div class="container-fluid">
		<div class="col-xs-6">
			<div class="box">
				<div class="box-body">
					<form action="" method="POST" role="form">
					{{ csrf_field() }}		
						<div class="form-group">
							<label for="">Cookie</label>
							<textarea name="cookie" id="inputCookie" class="form-control" rows="5" required="required"></textarea>
						</div>
						<button type="submit" class="btn btn-primary btn-block">Submit</button>
					</form>					
				</div>
			</div>
		</div>
	</div>
@stop