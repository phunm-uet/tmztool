@extends('admin.layout')

@section('content')

<div class="container-fluid">
	<div class="box box-success">
		<div class="box-header">
			<h3>Quản lý Interests</h3>
		</div>
		<div class="box-body">

			<div class="col-xs-12">
				@if(Session::has('fail'))
					<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<strong>Fail!</strong> Có lỗi xảy ra trong quá trình lưu
					</div>
				@elseif(Session::has('success'))
					<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<strong>Success!</strong> Đã thêm thành công
					</div>				
				@endif
			</div>
			<div class="col-xs-12">
				<div class="pull-right">
					<button type="button" class="btn btn-success" id="btn_add_interest"><i class="fa fa-plus"></i>  Them Interest</button>
				</div>
			</div>
			<div class="col-xs-12" style="margin-top: 20px">
				<table class="table table-hover table-bordered">
					<thead>
						<tr>
							<th>STT</th>
							
							<th>Tên Interest</th>
							<th>Lượng Audience</th>
							<th>Niche</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@foreach($interests as $interest)
							<tr>
								<td>{{$loop->iteration}}</td>
								
								<td>{{$interest->name}}</td>
								<td>{{$interest->num_audience}}</td>
								<td>{{$interest->niche->name}}</td>
								<td>
									<a href="interest/edit?id={{$interest->id}}"><i class="fa fa-pencil-square-o fa-2x" aria-hidden="true" style="color: blue"></i></a>
									<a href="javascript:void(0)" onclick="xoa({{$interest->id}})"><i class="fa fa-trash-o fa-2x" aria-hidden="true" style="color: red"></i></a>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_add_interest">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Thêm Interest</h4>
			</div>
			<div class="modal-body">
				<form action="interest/add" id="form" method="POST">
					{{ csrf_field() }}
					<div class="form-group">
						<label>Tên Interest</label>
						<input type="text" name="name_interest" class="form-control" required="required">
					</div>
					<div class="form">
						<label>Lượng Interest</label>
						<input type="text" name="num_audience" class="form-control" required="required">
					</div>
					<div class="form-group">
						<label>Niches</label>
						<select class="form-control select2" name="niche" style="width: 100%">
							@foreach($niches as $niche)
								<option value="{{$niche->id}}">{{$niche->name}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group">
						<label>Pages</label>
						<select class="form-control select2" name="pages[]" multiple="multiple" style="width: 100%">
							@foreach($pages as $page)
								<option value="{{$page->id}}">{{$page->page_name}}</option>
							@endforeach
						</select>
					</div>					
					<div class="form-group">
						<label>Nhập Interest</label>
						<textarea name="targeting" id="targeting" class="form-control" rows="5" required="required"></textarea>
					</div>
					<button type="submit" class="btn btn-primary btn-block" id="submit_interest">Thêm Interest</button>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
@stop
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
	<script>

		// Handle function xoa
		function xoa(id) {
			var _confirm = confirm("Bạn có chắc muốn xóa");
			if(_confirm)
			{
				$.post('interest/delete', {id: id,_token: "{{csrf_token()}}" }, function(data, textStatus, xhr) {
					location.reload();
				});
			}
		}	

		$(document).ready(function() {

			$(".select2").select2();
			// Handle button add interest
			$("#btn_add_interest").on("click",function(){
				$("#modal_add_interest").modal();
			});
			// Hanle Form add interest submit
			$("#form").on("submit",function(){
				var targeting = $("#targeting").val();
				try{
					targeting =  JSON.parse(targeting);
				}catch(err){
					alert("Không đúng định dạng JSON");
					return false;
				}
			});
		});
	</script>
@stop