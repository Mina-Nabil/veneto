@extends('layouts.app')

@section('content')
<div class="row">

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
            <h4 class="card-title">Models</h4>
                <h6 class="card-subtitle">Show Available Models</h6>
                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>صوره</th>
                                <th>خامه</th>
                                <th>صنف</th>
                                <th>اسم</th>
                                <th>مورد</th>
                                <th>سعر</th>
                                <th>لون</th>
                                <th>تعديل</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($models as $mod)
                            <tr>
                                <td>{{$mod->MODL_UNID}}</td>
                                <td>
                                @if(isset($mod->MODL_IMGE))
                                <img src="{{ asset( 'storage/'. $mod->MODL_IMGE ) }}" width=50 height=50>
                                @endif
                                </td>
                                <td>{{$mod->RAW_NAME}}</td>
                                <td>{{$mod->TYPS_NAME}}</td>
                                <td>
                                @if(isset($mod->MODL_CMNT) && strcmp($mod->MODL_CMNT, '')!=0 )
                                    <button type="button" class="btn btn-secondary" data-container="body" title="" data-toggle="popover" data-placement="bottom" data-content="{{$mod->MODL_CMNT}}" data-original-title="{{$mod->MODL_NAME}}">
                                @endif
                                        {{$mod->MODL_NAME}}
                                </button>
                                </td>
                                <td>{{$mod->SUPP_NAME}}</td>
                                <td>{{$mod->MODL_PRCE}}</td>
                                <td>{{$mod->COLR_NAME}}</td>
                                <td><a href="{{ url('models/edit/' . $mod->id) }}"><img src="{{ asset('images/edit.png') }}" width=25 height=25></a></td>                               
                            </tr> 
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @if(isset($model))
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{$pageTitle}}</h4>
                <h5 class="card-subtitle">Add/Edit Models</h5>
                <form class="form pt-3" method="post" action="{{ $formURL }}" enctype="multipart/form-data" >
                @csrf

                
                    <input name=id type=hidden value="{{$model->id}}">
                    @if(isset($model->MODL_IMGE))
                    <input name=oldPath type=hidden value="{{$model->MODL_IMGE}}">
                    @endif
               

                <div class="form-group">
                    <label>Type</label>
                    <div class="input-group mb-3">
                        <select name=type class="select2 form-control custom-select" style="width: 100%; height:36px;" required>
                            <option disabled>Pick From Raw Material Types</option>
                            @foreach($types as $type)
                            <option value="{{ $type->id }}"
                            @if(isset($model) && $model->MODL_TYPS_ID == $type->id)
                                selected
                            @endif
                            >
                            {{$type->RAW_NAME}} : {{$type->TYPS_NAME}}</option>
                            @endforeach
                        </select>
                    </div>
                    <small class="text-danger">{{$errors->first('type')}}</small>
                </div>

                <div class="form-group">
                    <label>Supplier</label>
                    <div class="input-group mb-3">
                        <select name=supplier class="select2 form-control custom-select" style="width: 100%; height:36px;" required>
                            <option disabled>Pick From Suppliers</option>
                            @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}"
                            @if(isset($model) && $model->MODL_SUPP_ID == $sup->id)
                                selected
                            @endif
                            
                            >{{$sup->SUPP_NAME}}</option>
                            @endforeach
                        </select>
                    </div>
                    <small class="text-danger">{{$errors->first('supplier')}}</small>
                </div>

                <div class="form-group">
                    <label>Color</label>
                    <div class="input-group mb-3">
                        <select name=color class="select2 form-control custom-select" style="width: 100%; height:36px;" required>
                            <option disabled>Pick From Raw Material Types</option>
                            @foreach($colors as $color)
                            <option value="{{ $color->id }}"
                            @if(isset($model) && $model->MODL_COLR_ID == $color->id)
                                selected
                            @endif
                            
                            >{{$color->COLR_NAME}} {{$color->COLR_CODE}}</option>
                            @endforeach
                        </select>
                    </div>
                    <small class="text-danger">{{$errors->first('color')}}</small>
                </div>

                <div class="form-group">
                    <label>Model Name</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon11"><i class="ti-receipt"></i></span>
                        </div>
                        <input type="text" placeholder="Enter Model Name, Example: Cotton1 " class="form-control" name=name  
                        value="{{ (isset($model)) ? $model->MODL_NAME : old('name') }}" >
                    </div>
                    <small class="text-danger">{{$errors->first('name')}}</small>
                </div>
  

                <div class="form-group">
                    <label>Model Price</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon11"><i class="ti-receipt"></i></span>
                        </div>
                        <input type="text" placeholder="Enter Model Meter Price, Example: 350 " class="form-control" name=price  
                        value="{{ (isset($model)) ? $model->MODL_PRCE : old('price') }}" >
                    </div>
                    <small class="text-danger">{{$errors->first('price')}}</small>
                </div>

                <div class="form-group">
                    <label>Model Serial Number</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon11"><i class="fas fa-barcode"></i></span>
                        </div>
                        <input type="text" placeholder="Enter Model Serial Number, Example: 19 " class="form-control" name=serial  
                        value="{{ (isset($model)) ? $model->MODL_UNID : old('serial') }}" >
                    </div>
                    <small class="text-danger">{{$errors->first('serial')}}</small>
                </div>

                <div class="form-group">
                      <label for="input-file-now-custom-1">Model Image</label>
                        <div class="input-group mb-3">
                            <input type="file" id="input-file-now-custom-1" name=photo class="dropify" data-default-file="{{ (isset($model->MODL_IMGE)) ? asset( 'storage/'. $model->MODL_IMGE ) : old('photo') }}" />
                        </div>
                    </div>

                <div class="form-group">
                    <label>Model Comment</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon11"><i class="ti-text"></i></span>
                        </div>
                        <input type="text" placeholder="Enter Comment" class="form-control" name=comment  
                        value="{{ (isset($model)) ? $model->MODL_CMNT : old('comment') }}" >
                    </div>
                </div>

                    <button type="submit" class="btn btn-success mr-2">Submit</button>

                </form>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection