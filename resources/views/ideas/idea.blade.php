@extends('layouts.master')

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.3/angular.min.js"></script>
@endsection

@section('breadcrumb')
    Dashboard
@endsection

@section('menu')
    <li class="active treeview">
        <a href="{{ url('/idea') }}">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a>
    </li>
    <li class="treeview">
      <a href="{{ url('/idea/form') }}">
        <i class="fa fa-edit"></i> <span>Form Idea</span>
      </a>
    </li>
    <li class="treeview">
      <a href="{{ url('/idea/pending') }}">
        <i class="fa fa-share"></i> <span>Idea Pending</span>
      </a>
    </li>
@endsection


@section('content')

     
<div>   
    <!-- Info boxes -->
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="ion ion-ios-plus-outline"></i></span>

            <div class="info-box-content">

                <span class="info-box-number">{{ $ideas_approved }}</span>

                <span class="info-box-text">Total Ideas <br> Approved</span>
              
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="ion ion-ios-checkmark-outline"></i></span>

            <div class="info-box-content">

                <span class="info-box-number">{{ $ideas_approved_in_month }}</span>

                <span class="info-box-text">Ideas Approved 
                    <br>
                    In This Month
                </span>
              
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="ion ion-ios-help-outline"></i></span>

            <div class="info-box-content">
                <span class="info-box-number">{{ $ideas_pending }}</span>

                <span class="info-box-text">Ideas Pending</span>
              
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="ion ion-ios-minus-outline"></i></span>

            <div class="info-box-content">

                <span class="info-box-number">{{ $ideas_denied }}</span>

                <span class="info-box-text">Ideas Denied</span>
              
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
    </div>    

    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Total Ideas Sent</h3>
        </div>
        <!-- /.box-header -->

        <div class="box-body" >
          

            <table id="table-ideas" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Niche</th>
                        <th>Status</th>
                        <th>Created at</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ideas as $idea)

                    <tr>
                        <td class="idIdea">{{ $idea->id }}</td>
                        <td class="nameIdea">{{ $idea->name }}</td>
                        <td class="description">{!! $idea->description !!}</td>
                        <td class="niche">{{ $idea->niche1->name }}</td>
                        <td class="status">
                            @if($idea->result_approve == -1)
                                <span class="label label-danger">Denied</span>
                            @elseif($idea->result_approve == 0)
                                <span class="label label-warning">Pending</span> 
                            @else
                                <span class="label label-success">Approved</span>
                            @endif           
                        </td>
                        <td class="date">{{ $idea->created_at }}</td>
                        {{--  Cac truong data hidden --}}
                        @if($idea->niche_id_2 != null)
                            <input type="hidden" class="niche2" value="{{ $idea->niche2->name }}">
                        @else
                            <input type="hidden" class="niche2" value="">
                        @endif    
                        <input type="hidden" class="audience" value="{{ $idea->audience }}">
                        <input type="hidden" class="link" value="{{ $idea->link }}">
                        <input type="hidden" class="reason" value="{{ $idea->reason }}">
                        <input type="hidden" class="deploy" value="{!! $idea->deploy_description !!}">
                        <input type="hidden" class="source" value="{{ $idea->source1->name }}">
                        @if($idea->source_id_2 != null)
                            <input type="hidden" class="source2" value="{{ $idea->source2->name }}">
                        @else
                            <input type="hidden" class="source2" value="">
                        @endif 
                        @if($idea->source_id_3 != null)
                            <input type="hidden" class="source3" value="{{ $idea->source3->name }}">
                        @else
                            <input type="hidden" class="source3" value="">
                        @endif 
                        <td><a href="#" data-toggle="modal" data-target="#modal" class="set-modal">More detail</a></td>
                    </tr>

                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Niche</th>
                        <th>Status</th>
                        <th>Created at</th>
                        <th>Detail</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
    <!-- Modal -->
    {{-- @foreach($ideas['data'] as $idea) --}}
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="word-wrap: break-word;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="nameIdea"></h4>
                </div>
                <div class="modal-body row">
                    <div class="col-md-6">
                        <img src="" alt="preview" style='height: 100%; width: 100%; object-fit: contain' id="preview">
                        <h4><b>Description: </b></h4>
                        <p id="description"></p>
                    </div>
                    <div class="col-md-6">  
                        <h4><b>Audience: </b></h4>
                        <span id="audience"></span>
                        <h4><b>Link: </b></h4>
                        <a id="link" href="" target="_blank"></a>
                        <h4><b>Reason: </b></h4>
                        <span id="reason"></span>
                        <h4><b>Deploy description: </b></h4>
                        <span id="deploy"></span>
                        <h4><b>Niche: </b></h4>
                        <span id="niche"></span>
                        <h4><b>Source: </b></h4>
                        <span id="source"></span>
                        <h4><b>Status: </b></h4>
                        <span id="status"></span>
                        <h4><b>Created at: </b></h4>
                        <span id="date"></span>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- @endforeach --}}
</div>     

@endsection

@section('script')
<!-- DataTables -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>


<script>
    $(function () {
        
        $('#table-ideas').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true
        });
        

        $('.set-modal').click(function(event) {
            //Lay cac gia tri tu bang vao modal
            var idIdea = $(this).parent().siblings('.idIdea').text();
            var nameIdea = $(this).parent().siblings('.nameIdea').text();
            var niche1 = $(this).parent().siblings('.niche').text();
            var niche2 = $(this).parent().siblings('.niche2').val();
            var source1 = $(this).parent().siblings('.source').val();
            var source2 = $(this).parent().siblings('.source2').val();
            var source3 = $(this).parent().siblings('.source3').val();

            $('#preview').attr('src', $(this).parent().siblings('.link').val());
            $('#description').html($(this).parent().siblings('.description').html());
            $('#nameIdea').text(nameIdea);
            $('#audience').text($(this).parent().siblings('.audience').val());
            $('#link').attr("href",($(this).parent().siblings('.link').val()));
            $('#link').text($(this).parent().siblings('.link').val());
            $('#reason').text($(this).parent().siblings('.reason').val());
            $('#deploy').html($(this).parent().siblings('.deploy').val());
            $('#niche').text(niche1+'  |  '+niche2);
            $('#source').text(source1+'  |  '+source2+'  |  '+source3);
            $('#status').text($(this).parent().siblings('.status').text());
            $('#date').text($(this).parent().siblings('.date').text());

        });
    });

</script>
@endsection