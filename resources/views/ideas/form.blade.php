@extends('layouts.master')

@section('css')
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
@endsection


@section('breadcrumb')
    Form Idea
@endsection

@section('menu')
    <li class="treeview">
        <a href="{{ url('/idea') }}">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a>
    </li>
    <li class="active treeview">
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

<div class="row">
    @if (isset($status))
        <div class="alert alert-success">
            {{ $status }}
        </div>
    @endif
    
    <div class="col-md-7">
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Create Idea</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" method="post" action="{{ route('ideaUpload') }}">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="form-group">
                        <label for="nameIdea">Name Idea</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name Idea" required="true">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="description" name="description" placeholder="Enter description" style="width: 100%; height: 150px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; " required="true"></textarea>

                    </div>
                    <div class="form-group">
                        <label for="audience">Audience</label>
                        <input type="text" class="form-control" id="audience" name="audience" placeholder="Enter Audience" required="true">
                    </div>
                    <div class="form-group">
                        <label for="link">Link</label>
                        <input type="text" class="form-control" id="link" name="link" placeholder="Enter link" required="true">
                    </div>
                    <div class="form-group">
                        <label for="reason">Reason</label>
                        <textarea class="form-control" rows="3" id="reason" name="reason" placeholder="Enter Reason" required="true"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="deploy">Deploy Description</label>
                        <textarea class="deploy" name="deploy_description" placeholder="Enter deploy" style="width: 100%; height: 150px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; " required="true"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="field">Fields</label>
                        <select name="field_id" id="field" class="form-control selectpicker" required>
                            <option data-hidden="true" value="">Choose a field</option>
                            @foreach($fields as $field)
                                <option value={{ $field['id'] }}>{{ $field['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="niche">Niches</label>
                    
                        <div class="row" id="niche">
                            <div class="col-xs-6">
                                <select name="niche_id" id="niche1" class="form-control selectpicker" required>
                                    <option data-hidden='true' value=''>Level 1</option>
                                    @foreach($niches as $niche)
                                        @if($niche['level'] == 1)
                                            <option value={{ $niche->id }}>{{ $niche->name }}</option>
                                        @endif
                                    @endforeach
                                    
                                </select>
                            </div>
                            <div class="col-xs-6">
                                <select name="niche_id_2" id="niche2" class="form-control selectpicker">
                                    <option data-hidden="true" value="">Level 2</option>
                                    @foreach($niches as $niche)
                                        @if($niche['level'] == 2)
                                            <option value={{ $niche->id }}>{{ $niche->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                    </div>
                    <div class="form-group">
                        <label for="source">Sources</label>
                        
                        <div class="row" id="source">
                            <div class="col-xs-4">
                                <select name="source_id" id="source1" class="form-control selectpicker" required>
                                    <option data-hidden="true" value="">Level 1</option>
                                    @foreach($sources as $source)
                                        @if($source['level'] == 1)
                                            <option value={{ $source['id'] }}>{{ $source['name'] }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xs-4">
                                <select name="source_id_2" id="source2" class="form-control selectpicker" >
                                    <option data-hidden="true" value="">Level 2</option>
                                    @foreach($sources as $source)
                                        @if($source['level'] == 2)
                                            <option value={{ $source['id'] }}>{{ $source['name'] }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xs-4">
                                <select name="source_id_3" id="source3" class="form-control selectpicker" >
                                    <option data-hidden="true" value="">Level 3</option>
                                    @foreach($sources as $source)
                                        @if($source['level'] == 3)
                                            <option value={{ $source['id'] }}>{{ $source['name'] }}</option>
                                        @endif
                                    @endforeach    
                                </select>
                            </div>
                        </div>
                        
                    </div>
                    
                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
        <!-- /.box -->
    </div>
    <!-- Preview img -->
    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Preview</h3>
            </div>
            <div class="box-body">
                <img src=""  id="preview" style='height: 100%; width: 100%; object-fit: contain'>
            </div>
        </div>
    </div>
    
</div>
@endsection


@section('script')<!-- Bootstrap WYSIHTML5 -->
<script src="{{ asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>
<script>
    $(document).ready(function() {
        
        //bootstrap WYSIHTML5 - text editor
        $(".description").wysihtml5();
        $(".deploy").wysihtml5();

        //thay doi img preview theo link
        $('#link').change(function(event) {
            $('#preview').attr('src', $(this).val());
        });
        
    });
</script>
@endsection