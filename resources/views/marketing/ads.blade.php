@extends('layouts.master')

@section('css')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="{{asset('plugins/iCheck/all.css')}}">
  <script src="{{asset('plugins/jQuery/jquery-2.2.3.min.js')}}"></script>

  <link rel="stylesheet" href="https://unpkg.com/angular-toastr/dist/angular-toastr.css" />  
  <style>
    .flat-red {
      padding: 5px;
    }
  </style>
@endsection

@section('breadcrumb')
    Dashboard
@endsection

@section('menu')
    <li class="treeview">
        <a href="../marketing">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a>
    </li>
    <li class="treeview">
      <a href="{{ route("import_fb") }}">
        <i class="fa fa-upload"></i> <span>Tạo collection SP Dropshing</span>
      </a>
    </li>
    <li class="treeview">
      <a href="{{ route("ads") }}">
        <i class="fa fa-gear"></i> <span>Tạp Campaign Dropship</span>
      </a>
    </li>
    <li class="treeview">
      <a href="{{ route("pages") }}">
        <i class="fa fa-gear"></i> <span>Quản lý page</span>
      </a>
    </li>
    @if ( Auth::user()->department->slug == "admin")
      <li>
        <li class="header">Admin</li>
      </li>    
      <li class="treeview">
        <a href="{{route('admin-home')}}">
          <i class="fa fa-gear"></i> <span>Chuyển qua Admin</span>
        </a>
      </li>    
    @endif    
@stop

@section('content')
<div class="container-fluid" ng-app="ads" ng-controller="adsController">
    <!-- Content Header (Page header) -->
      <section class="content box box-info box-body">
        <div class="overlay" ng-show="loading">
        <i class="fa fa-spinner fa-spin fa-4x fa-fw" style="color: blue;"></i>
      </div>

        <!--Form -->
        <form id="formtaocamp" role="form" ng-submit="createCampaign()" ng-show="!hoanthanh">
          <div class="col-md-6">
                <div class="form-group" id="description">
                  <label>Description: </label>
                  <textarea style="height:135px;" name="description" class="form-control" rows="3" placeholder="Nhập description cho post (lưu ý thêm $link$ vào vị trí muốn chèn link sản phẩm) ..." required="true" ng-model="description" ng-init="description='Order here ➡️️ $link$'"></textarea>
                </div>
                  
                
                <div class="form-group">
                  <div class="row">
                      <div class="col-xs-3" id="minage">
                        <label>Min Age: </label>
                        <input type="text" required="true" class="form-control" ng-model="minage" ng-init="minage=21" placeholder="21">
                      </div>
                      <div class="col-xs-3" id="maxage">
                        <label>Max Age: </label>
                        <input type="text" name="maxage" required="true" class="form-control" ng-init="maxage=65" ng-model="maxage" placeholder="65">
                      </div>
                      <div class="col-xs-6">
                        <label for="">Gender: </label><br>
                        <input type="radio" name="gender" ng-model="gender" value="1" class="flat-red"><label>Nam</label>
                        <input type="radio" name="gender" ng-model="gender" value="2" class="flat-red"><label>Nữ</label>
                        <input type="radio" name="gender" ng-model="gender" value="1,2" class="flat-red" checked><label>All</label>
                      </div>                  
                  </div>
                </div>

                <div class="form-group input-group">
                  <div id="location" class="input-group-btn">
                    <label class="btn bg-olive btn-flat">Country</label>
                  </div>
                  <select name="location" class="form-control" data-placeholder="Select Country" ng-model="country" id="country">
                  </select>
                </div>
                <div class="row">
                  <div class="col-lg-5">
                    <div class="form-group input-group" id="niche">
                      <div class="input-group-btn">
                        <label class="btn bg-olive btn-flat">Niche</label>
                      </div>
                      <select name="niche" class="form-control" required="true" ng-options="niche as niche.name for niche in niches" ng-model="selectedNiche" ng-change="changeNiche(selectedNiche)"></select>
                    </div>
                  </div>
                  <div class="col-lg-7">
                    <div class="form-group input-group" id="loaisanpham">
                      <div class="input-group-btn">
                        <label class="btn bg-olive btn-flat">Loại Sản Phẩm: </label>
                      </div>
                      <input type="text" class="form-control" ng-model="selectedType">
                    </div>
                  </div>                    
                </div>               
          </div>
          <div class="col-md-6">
            <div class="row">

              <div class="col-lg-12">
                <div class="form-group input-group" id="pageid">
                  <div class="input-group-btn">
                    <label class="btn bg-olive btn-flat">Pages: </label>
                  </div>
                  <select name="pageid" class="form-control select2" ng-options="page as page.name for page in pages" ng-model="selectedPage"></select>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="form-group input-group">
                  <div class="input-group-btn">
                    <label class="btn bg-olive btn-flat">Ad Accounts: </label>
                  </div>
                  <select class="form-control" ng-options="adaccount as adaccount.name for adaccount in adaccounts" ng-model="selectedAdAccount" ng-change="changeAdAccount()"></select>
                </div>
              </div> 
            {{-- End ad accounts --}}
              <div class="col-lg-12">
                <div class="form-group input-group">
                  <div class="input-group-btn">
                    <label class="btn bg-olive btn-flat">Pixels: </label>
                  </div>
                  <select class="form-control" ng-options="pixel.name + '(' + pixel.id +')' for pixel in pixels" ng-model="selectedPixel"></select>
                </div>
              </div> 
              {{-- End Pixel --}}
              <div class="col-lg-12">
                <div class="form-group input-group" id="pageid">
                  <div class="input-group-btn">
                    <label class="btn bg-olive btn-flat">Interests: </label>
                  </div>
                  <select name="interest" class="form-control select2" required="true" multiple="true" ng-options="interest as interest.name for interest in interests" ng-model="selectedInterest"></select>
                </div>
              </div>              
            </div>
            <div class="form-group input-group" id="linksanpham" >
              <div class="input-group-btn">
                <label class="btn bg-olive btn-flat">Product Link</label>
              </div>
              <input type="text" id="linksanpham" class="form-control" ng-model="product_link" placeholder="Nhập link sản phẩm ..." required="true" ng-paste="paste($event.originalEvent)">
            </div>
            <div class="col-lg-6">
              <div class="form-group input-group">
                <label class="radio-inline"><input type="radio" name="optradio" ng-model="optionImage" value="1">Default</label>
                <label class="radio-inline"><input type="radio" name="optradio" ng-model="optionImage" value="2">Custom</label>           
              </div>
            </div>
              <div class="col-lg-6">
                <div class="form-group input-group">

                    <label class="radio-inline">
                      <input type="radio" name="typeAds" ng-model="typeAds" value="POST_ENGAGEMENT">
                      PPE
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="typeAds" ng-model="typeAds" value="CONVERSIONS">
                      WC
                    </label>                    
                  </div>               
              </div>               
            {{-- End option defaut image product          --}}
            <div class="form-group input-group" id="hinhsanpham" ng-if="optionImage == 1">
              <div class="input-group-btn">
                <label class="btn bg-olive btn-flat">Product Image</label>
              </div>
              <input type="text" name="hinhsanpham" class="form-control" placeholder="Link Image"  ng-model="image_link">
            <span class="input-group-btn">
              <a class="btn btn-default" ng-href="@{{image_link}}" target="_blank"><span class="fa fa-hand-o-right"></span></a>
            </span>
            </div>
            <div class="form-group input-group" id="hinhsanpham" ng-if="optionImage == 2">
                <input type="file" ngf-select="uploadFiles($file, $invalidFiles)"
                          accept="image/*" ngf-max-size="2MB" ng-model="thumb">
            </div>
          <span class="progress" ng-show="f.progress >= 0">
              <div style="width:@{{f.progress}}%"  
                   ng-bind="f.progress + '%'"></div>
          </span>                                 
          </div>
          <div class="clearfix"></div>
          <div class="col-xs-12" style="margin-top: 20px">
            <div>
                <button type="submit" id="btn_taocamp" class="btn btn-primary btn-block">Submit</button>
            </div>
          </div>           
        </form>

        <!--Ket Qua -->
        <div class="col-md-12" id="ketqua"  style="margin-top: 10px;" ng-show="hoanthanh">
          <div class="box box-solid box-success">
            <div class="box-header with-border">
              <i class="fa fa-industry"></i>

              <h3 class="box-title">Results</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body" id="kq_results">
              <p>Post tạo thành công:  <a ng-href="@{{linkPost}}" target="_blank">@{{post}}</a></p>
              <p>Campaign đã tạo: @{{campaign}}</p>
              <p>Adset đã tạo 
                <span ng-repeat="adset in adsets">@{{adset}} ,</span>
              </p>
              <p>Ad đã tạo : 
                <span ng-repeat="ad in ads">@{{ad}} ,</span>
              </p>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </section>
</div>

@stop

@section('script')
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
  <script src="https://unpkg.com/angular-toastr/dist/angular-toastr.tpls.js"></script>
  <script src="{{ asset('js/ng-file-upload.js') }}"></script>
  <script src="{{ asset('js/ng-file-upload-shim.js') }}"></script>
<script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
<script src="{{asset("js/angular/ads.js")}}"></script>
<script>
  $(document).ready(function() {
    $(".select2").select2();
    $("#country").select2({
      ajax : {
        url : "http://toanvo.com/fbtooltmz/public/api/marketing/country",
        placeholder : "Select country",
        dataType : 'json',
        delay : 250,
        data : function(params){
          return {
            q : params.term
          }
        },
        processResults : function(data){
          return {
            results : $.map(data, function(val, l) {
              return {id:val.country_code,text:val.name};
            })
          }
        },
      }
    });
  });
</script>
@stop
