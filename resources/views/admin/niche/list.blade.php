@extends('admin.layout')

@section('content')

<div class="container-fluid">
	<div class="box box-success">
		<div class="box-header">
			<h3>Quản lý Niches</h3>
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
						<strong>Success!</strong> Đã cập nhật thành công
					</div>				
				@endif
			</div>
			<div class="col-xs-12">
				<div class="pull-right">
					<button type="button" class="btn btn-success" id="btn_add_interest" onclick="add()"><i class="fa fa-plus"></i>  Thêm Niche</button>
				</div>
			</div>
			<div class="col-xs-12" style="margin-top: 20px">
				<table class="table table-hover table-bordered">
					<thead>
						<tr>
							<th>STT</th>
							<th>Tên Niche</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@foreach($niches as $niche)
							<tr>
								<td>{{$loop->iteration}}</td>
								
								<td>{{$niche->name}}</td>
								<td>
									<a href="javascript:void(0)" onclick="edit({{$niche->id}},'{{$niche->name}}')"><i class="fa fa-pencil-square-o fa-2x" aria-hidden="true" style="color: blue"></i></a>
									<a href="javascript:void(0)" onclick="xoa({{$niche->id}})"><i class="fa fa-trash-o fa-2x" aria-hidden="true" style="color: red"></i></a>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_edit_interest">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Thêm Interest</h4>
			</div>
			<div class="modal-body">
				<form action="niche/edit" id="form" method="POST">
					{{ csrf_field() }}
					<input type="text" hidden="true" name="id">
					<div class="form-group">
						<label>Tên Niche</label>
						<input type="text" name="niche_name" class="form-control" required="required">
					</div>
					<button type="submit" class="btn btn-primary btn-block" id="submit_interest">Thêm Niche</button>
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
	<script>

		// Handle function xoa
		function xoa(id) {
			var _confirm = confirm("Bạn có chắc muốn xóa");
			if(_confirm)
			{
				$.post('niche/delete', {id: id,_token: "{{csrf_token()}}" }, function(data, textStatus, xhr) {
					location.reload();
				});
			}
		}	

		function edit(id,content)
		{
			$("#modal_edit_interest").modal();
			$("#submit_interest").text("Sửa Niche");
			$("input[name='niche_name']").val(content);
			$("input[name='id']").val(id);
		}

		function add()
		{
			$("#submit_interest").text("Thêm Niche");
			$("#modal_edit_interest").modal();
		}
	</script>
@stop