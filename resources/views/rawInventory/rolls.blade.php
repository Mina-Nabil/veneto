@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Cloth Rolls Page</h4>
                <h6 class="card-subtitle">Show All Available Rolls {{(isset($pageDesc)) ? $pageDesc : ''}}</h6>
                @if(!$transPage)
                <div class=row>
                    <div class=col-lg-4>
                        <h5>Model</h5>
                        <p>{{$model->RAW_NAME}} - {{$model->TYPS_NAME}} - {{$model->MODL_NAME}}</p>
                    </div>
                    <div class=col-lg-2>
                        <h5>Supplier</h5>
                        <p>{{$model->SUPP_NAME}}</p>
                    </div>
                    <div class=col-lg-2>
                        <h5>Price</h5>
                        <p>{{$model->MODL_PRCE}}</p>
                    </div>
                    <div class=col-lg-2>
                        <h5>Color</h5>
                        <p>{{$model->COLR_NAME}} - {{$model->COLR_CODE}}</p>
                    </div>
                </div>
                @endif
                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]">
                        <thead>
                            <tr>
                                @if($transPage)
                                <th>Serial#</th>
                                <th>صوره</th>
                                <th>خامه</th>
                                <th>صنف</th>
                                <th>موديل</th>
                                <th>مورد</th>
                                <th>سعر</th>
                                @endif
                                <th>Transaction Number</th>
                                <th>كميه</th>
                                <th>في الانتاج</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($raws as $raw)
                            <tr>
                                <td>{{$raw->MODL_UNID}}</td>
                                @if($transPage)
                                <td>
                                    @if(isset($raw->MODL_IMGE))
                                    <img src="{{ asset( 'storage/'. $raw->MODL_IMGE ) }}" width=50 height=50>
                                    @endif
                                </td>
                                <td>{{$raw->RAW_NAME}}</td>
                                <td>{{$raw->TYPS_NAME}}</td>
                                <td>{{$raw->MODL_NAME}}</td>
                                <td>{{$raw->SUPP_NAME}}</td>
                                <td>{{$raw->MODL_PRCE}}</td>
                                @endif
                                <td>
                                    <a href="{{url('rawinventory/bytrans/' . $raw->RINV_TRNS)}}">
                                        {{$raw->RINV_TRNS}}
                                        <a>
                                </td>
                                <td>{{number_format($raw->RINV_METR, 2)}}</td>
                                <td>{{number_format($raw->RINV_PROD_AMNT, 2)}}</td>
                                <td>
                                    <div class="btn-group">
                                        <button style="padding:.1rem .2rem" type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Action
                                        </button>
                                        <div class="dropdown-menu">
                                            @if(!$isProd)
                                            <button class="dropdown-item" onclick=goto('{{url("raw/prod/full/insert/{$raw->id}")}}')>Send to Production</button>
                                            <button class="dropdown-item" data-toggle="modal" data-target="#responsive-modal{{$raw->id}}">Send Cut to Prod</button>
                                            <button class="dropdown-item" data-toggle="modal" data-target="#adjust{{$raw->id}}">Edit Quantity</button>
                                            @else
                                            <button class="dropdown-item" data-toggle="modal" data-target="#responsive-modal{{$raw->id}}">Send Cut Back to Raw</button>
                                            <button class="dropdown-item" data-toggle="modal" data-target="#to-finished{{$raw->id}}">Send Cut to Finished</button>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <div id="responsive-modal{{$raw->id}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Send Cut to {{ ($isProd) ? 'Raw Inventory' : 'Production'}}</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        </div>
                                        <form action="{{ ($isProd) ? url('raw/from/prod') : url('raw/prod/insert')}}" method=post>
                                            @csrf
                                            <div class="modal-body">
                                                <input type=hidden name=raw value="{{$raw->id}}">
                                                @if($isProd)
                                                <input type=hidden name=toRaw value=1>
                                                @endif


                                                <div class="form-group col-md-12 m-t-0">
                                                    <h5>Amount</h5>
                                                    <input type="number" step=0.01 class="form-control form-control-line" name=in max="{{ ($isProd) ?  $raw->RINV_PROD_AMNT : $raw->RINV_METR }}"
                                                        required>
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-warning waves-effect waves-light">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @if(!$isProd)
                            <div id="adjust{{$raw->id}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Adjust entry quantity</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        </div>
                                        <form action="{{  url('raw/adjust/entry') }}" method=post>
                                            @csrf
                                            <div class="modal-body">
                                                <input type=hidden name=raw value="{{$raw->id}}">

                                                <div class="form-group col-md-12 m-t-0">
                                                    <h5>Amount</h5>
                                                    <input type="number" step=0.01 class="form-control form-control-line" name=in placeholder="5 or -2" required>
                                                    <small>Enter the amount to increase or decrease only.</small>
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-warning waves-effect waves-light">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($isProd)
                            <div id="to-finished{{$raw->id}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Send Cut to Finished</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        </div>
                                        <form action="{{ url('raw/from/prod') }}" method=post>
                                            @csrf
                                            <div class="modal-body">
                                                <input type=hidden name=raw value="{{$raw->id}}">
                                                <input type=hidden name=toRaw value=0>
                                                <div class="form-group col-md-12 m-t-0">
                                                    <h5>Amount</h5>
                                                    <input type="number" step=0.01 class="form-control form-control-line" name=in value="{{ $raw->RINV_PROD_AMNT }}" max="{{ $raw->RINV_PROD_AMNT  }}"
                                                        required>
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-warning waves-effect waves-light">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif

                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@if(isset($isTranEdit) && $isTranEdit)
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Add Entry</h4>
                <h5 class="card-subtitle">Add New Entry for the Same Transaction</h5>
                <form id=myForm class="form " method="post" action="{{ url('rawinventory/tran/addentry') }}" enctype="multipart/form-data">
                    @csrf

                    <input name=tran type=hidden value={{$tran}}>
                    <input name=supp type=hidden value={{$supp}}>

                    <div class="form-group">
                        <label>Type</label>
                        <div class="input-group mb-3">
                            <select name=type class="select2 form-control custom-select" style="width: 100%; height:36px;" required>
                                <option value="" selected disabled hidden>Pick From Raw Material Types</option>
                                @foreach($types as $type)
                                <option value="{{ $type->id }}">
                                    {{$type->RAW_NAME}} : {{$type->TYPS_NAME}}</option>
                                @endforeach
                            </select>
                        </div>
                        <small class="text-danger">{{$errors->first('type')}}</small>
                    </div>

                    <div class="form-group">
                        <label>Color</label>
                        <div class="input-group mb-3">
                            <select name=color class="select2 form-control custom-select" style="width: 100%; height:36px;" required>
                                <option disabled selected hidden>Pick From Colors</option>
                                @foreach($colors as $color)
                                <option value="{{ $color->id }}">
                                    {{$color->COLR_NAME}} {{$color->COLR_CODE}}</option>
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
                            <input type="text" placeholder="Enter Model Name, Example: Cotton1 " class="form-control" name=name value="{{ old('name')}}" required>
                        </div>
                        <small class="text-danger">{{$errors->first('name')}}</small>
                    </div>

                    <div class="form-group">
                        <label>Model Price</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-money"></i></span>
                            </div>
                            <input type="text" placeholder="Enter Model Meter Price, Example: 350 " class="form-control" id=price name=price value="{{ old('price') }}" required>
                        </div>
                        <small class="text-danger">{{$errors->first('price')}}</small>
                    </div>

                    <div class="form-group">
                        <label>Model Serial Number</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="fas fa-barcode"></i></span>
                            </div>
                            <input type="text" placeholder="Enter Model Serial Number, Example: 19 " class="form-control" name=serial value="{{old('serial')}} ">
                        </div>
                        <small class="text-danger">{{$errors->first('serial')}}</small>
                    </div>

                    <div class="form-group">
                        <label for="input-file-now-custom-1">Model Image</label>
                        <div class="input-group mb-3">
                            <input type="file" id="input-file-now-custom-1" name=photo class="dropify" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Model Comment</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-text"></i></span>
                            </div>
                            <input type="text" placeholder="Enter Comment" class="form-control" name=comment value="{{ old('comment') }}">
                        </div>
                    </div>

                    <label class="col-lg-12 nopadding" for="input-file-now-custom-1"><strong>Entry Details</strong></label>
                    <div class="row">
                        <div class="col-lg-12" id="dynamicContainer"></div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon11"><i class="ti-server"></i></span>
                                    </div>
                                    <input type="number" step=0.01 min=0 class="form-control amount" placeholder="Example: 2.5" name=amount[] aria-label="Total Amount in Meters" aria-describedby="basic-addon11" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-success" id="dynamicAddButton" type="button" onclick="addToab();"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>

    var room = 1;
    
    function addToab() {
    
        room++;
        var objTo = document.getElementById('dynamicContainer')
        var divtest = document.createElement("div");
        divtest.setAttribute("class", "form-group removeclass" + room);
        var rdiv = 'removeclass' + room;
        var concatString = "";
        concatString += '<div class="form-group">\
                                    <div class="input-group mb-3">\
                                        <div class="input-group-prepend">\
                                            <span class="input-group-text" id="basic-addon11"><i class="ti-server"></i></span>\
                                        </div>\
                                        <input type="number" step=0.01 min=0 class="form-control amount" placeholder="Example: 2.5" name=amount[] aria-label="Total Amount in Meters" aria-describedby="basic-addon11" required>\
                                        <div class="input-group-append"> <button class="btn btn-danger" type="button" onclick="removeToab(' + room + ');">\
                                        <i class="fa fa-minus"></i> </button>\
                                        </div>\
                                    </div>\
                                </div>';
    
        divtest.innerHTML = concatString;
    
        objTo.appendChild(divtest);
        calculateTotals();
    
    }
    
    function removeToab(rid) {
        $('.removeclass' + rid).remove();
        calculateTotals();
    }

    
    </script>

@endif

<script>
    function goto($url){
    window.location.href = $url;
}
</script>
@endsection