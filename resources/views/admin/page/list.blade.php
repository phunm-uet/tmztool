@extends('admin.layout')
@section('css')
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css"/>
@endsection
@section('content')
<div class="container-fluid">
	<div class="col-xs-12">
		<div class="box box-info">
			<div class="overlay" hidden="true">
				<i class="fa fa-refresh fa-spin"></i>
			</div>
			<div class="box-header">
				<h4>Quản lý Pages</h4>
			</div>
			<div class="box-body">
			<div class="col-xs-12" style="margin-bottom: 20px">
				<div class="pull-right">
					<a href="javascript:void(0)" class="btn btn-success" id="sync">Sync</a>
				</div>
			</div>
				<table class="table table-hover table-bordered" id="pages">
					<thead>
						<tr>
							<th hidden="true"></th>
							<th>STT</th>
							<th>Page</th>
							<th>Store ID</th>
							<th>Niche</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@foreach($pages as $page)
							<tr>
								<th hidden="true" class="id">{{$page->id}}</th>
								<td>{{$loop->iteration	}}</td>
								<td><a href="{{$page->url}}" target="_blank">{{$page->page_name}}</a></td>
								<td>{{$page->store_id}}</td>
								<td>
									@foreach($page->niches as $niche)
										<span class="niche_id" hidden="true">{{$niche->id}}</span>
										<span class="label label-success">{{$niche->name}}</span>
									@endforeach
								</td>
								<td>
									<a href="javascript:void(0)" class="btn btn-xs btn-warning edit">Edit</a>
									<a href="javascript:void(0)" class="btn btn-xs btn-danger delete">Delete</a>
									<a href="javascript:void(0)" class="btn btn-xs btn-info" onclick="updateStore('{{$page->url}}',{{$page->id}})">Update Store_id</a>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div>
		<div class="modal fade" id="modal-edit">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Sửa Page</h4>
					</div>
					<div class="modal-body">
					<form action="page/edit" method="POST">
						{{ csrf_field() }}
						<div class="form-group">
							<label>Chọn Page</label>
							<select name="editID" id="editID" class="form-control select2" style="width: 100%">
								@foreach ($pages as $page)
									<option value="{{$page->id}}">{{$page->page_name}}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group">
							<label>Chọn Niches</label>
							<select name="selNiche[]" id="selNiche" class="form-control select2" multiple="multiple" style="width: 100%">
								@foreach($niches as $niche)
									<option value="{{$niche->id}}">{{$niche->name}}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-primary btn-block">Submit</button>
						</div>
										
					</form>
					</div>	
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script src="http://tmztool.com/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="http://tmztool.com/plugins/datatables/dataTables.bootstrap.min.js"></script>
	<script>
	
	var cookie = "{{Session::get('cookie_fb')}}";
	var updateStore = function(url,id)
	{
		if(cookie!= "")
		{
			$('.overlay').show();
			$.post('http://toanvo.com/fbtooltmz/public/admin/page/get_store', {_token: window.Laravel.csrfToken,url: url,id : id}, function(data, textStatus, xhr) {
					if(data.status == 1)
					{
						$('.overlay').hide();
						location.reload();
					} else {
						$('.overlay').hide();
						alert('Page chưa có shop section');
					}
				});			
		} else {
			alert("Bạn cần config trưóc");
			window.location.href = "./config";
			return;
		}

	};
		$(document).ready(function() {
			$("#pages").DataTable();
			$(".select2").select2();

			$("#sync").on("click",function(){
				$('.overlay').show();
				$.post('page/sync', {
					_token: window.Laravel.csrfToken
				}).done(function(data){
					$('.overlay').hide();
					location.reload();
				}).fail(function(err){
					alert("Error");
				});
			});

			$('.edit').on('click',function(){
				var page_id = $(this).parents('tr').children('.id').text();
				var niches = $(this).parents('tr').find('.niche_id');
				var niche_arr = [];
				$.each(niches, function(index, val) {
					 niche_arr.push($(val).text());
				});
				$("#editID").val(page_id).trigger('change');
				$("#selNiche").val(niche_arr).trigger('change');
				$("#modal-edit").modal();
			});

			$('.delete').on('click',function(){
				var page_id = $(this).parents('tr').children('.id').text();
				$.post('page/delete', {_token: window.Laravel.csrfToken,page_id: page_id}, function(data, textStatus, xhr) {
					location.reload();
				});
			});

		});
	</script>
@stop