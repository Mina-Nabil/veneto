@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Saved Entry</h4>
                <div class="row">
                    <div class="col-lg-4">
                        <strong>Number of rolls:</strong>
                        <p id=numberOfInv >{{$totals['numberOfInv']}}</p>
                    </div>
                    <div class="col-lg-4">
                        <strong>Meters:</strong>
                        <p id=meter >{{$totals['meter']}}</p>
                    </div>
                    <div class="col-lg-4">
                        <strong>Total Price:</strong>
                        <p id=totalPrice >{{$totals['totalPrice']}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ $pageTitle }}</h4>
                <h5 class="card-subtitle">{{ $pageDescription }}</h5>
                <form id=myForm class="form pt-3" method="post" action="{{ $formURL }}" enctype="multipart/form-data" >
                @csrf
                    
                    <div class="form-group">
                        <label>Type</label>
                        <div class="input-group mb-3">
                            <select name=type class="select2 form-control custom-select" style="width: 100%; height:36px;" required>
                                <option value="" selected disabled hidden>Pick From Raw Material Types</option>
                                @foreach($types as $type)
                                <option value="{{ $type->id }}%{{$type->RAW_NAME}} : {{$type->TYPS_NAME}}" 
                                @if($model !== null && isset($model[0]['MODL_TYPS_ID']) && $model[0]['MODL_TYPS_ID'] == $type->id)
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
                            <select name=supplier class="select2 form-control custom-select" style="width: 100%; height:36px;" required >   
                                <option disabled selected hidden>Pick From Suppliers</option>
                                @foreach($suppliers as $sup)
                                    @if($model !== null && isset($model[0]['MODL_SUPP_ID']))
                                        @if ($model[0]['MODL_SUPP_ID'] == $sup->id)
                                            <option value="{{ $sup->id }}" selected>{{$sup->SUPP_NAME}}</option>
                                        @endif
                                    @else
                                        <option value="{{ $sup->id }}">{{$sup->SUPP_NAME}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <small class="text-danger">{{$errors->first('supplier')}}</small>
                    </div>

                    <div class="form-group">
                        <label>Color</label>
                        <div class="input-group mb-3">
                            <select name=color class="select2 form-control custom-select" style="width: 100%; height:36px;" required>
                                <option disabled selected hidden>Pick From Colors</option>
                                @foreach($colors as $color)
                                <option value="{{ $color->id }}%{{$color->COLR_NAME}}">
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
                            <input type="text" placeholder="Enter Model Name, Example: Cotton1 " class="form-control" name=name value="{{ ($model !== null && isset($model[0]['MODL_NAME']) ) ? $model[0]['MODL_NAME'] : '' }}" required>
                        </div>
                        <small class="text-danger">{{$errors->first('name')}}</small>
                    </div>  

                    <div class="form-group">
                        <label>Model Price</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-money"></i></span>
                            </div>
                            <input type="text" placeholder="Enter Model Meter Price, Example: 350 " class="form-control" id=price name=price  
                            value="{{ ($model !== null && isset($model[0]['MODL_PRCE']) ) ? $model[0]['MODL_PRCE'] : 0 }}"
                            required  >
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
                             value="{{ ($model !== null && isset($model[0]['MODL_UNID'])) ? $model[0]['MODL_UNID'] : '' }}" >
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
                            <input type="text" placeholder="Enter Comment" class="form-control" name=comment  
                            value="{{ ($model !== null && isset($model[0]['MODL_CMNT'])) ? $model[0]['MODL_CMNT'] : old('comment') }}" >
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

                    <button type="submit" id=checker class="btn btn-success mr-2">Submit</button>
                    <button type="button" onclick="addNewModel()" class="btn btn-warning mr-2">Add Another Model</button>
                    <button type="button" onclick="calculateTotals()" class="btn btn-info mr-2">Calculate</button>
                    <a href="{{url('rawinventory/cancel') }}" class="btn btn-dark">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Entry Summary</h4>
                <div class="row">
                    <div class="col-lg-4">
                        <strong>Number of rolls:</strong>
                        <p id=numberOfInv2 >{{$totals['numberOfInv']}}</p>
                    </div>
                    <div class="col-lg-4">
                        <strong>Meters:</strong>
                        <p id=meter2 >{{$totals['meter']}}</p>
                    </div>
                    <div class="col-lg-4">
                        <strong>Total Price:</strong>
                        <p id=totalPrice2 >{{$totals['totalPrice']}}</p>
                    </div>
                </div>
                <h4 class="card-title">Added Items</h4>
                <div class=row>
                    <div class=col-lg-12>
                        <ul class="list-group">
                            @foreach($entries as $ent)
                                <li class="list-group-item">{{$ent['serial']}} &nbsp&nbsp - &nbsp&nbsp{{$ent['typeName']}} : {{$ent['name']}}  &nbsp&nbsp  -  &nbsp&nbsp  {{$ent['colorName']}} </li>
                                @foreach($ent['items'] as $key => $item)
                                    <li class="list-group-item">{{$key+1}}# Meters: {{$item}}  &nbsp&nbsp&nbsp&nbsp Price: {{number_format($item*$ent['price'], 2)}}EGP
                                @endforeach
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>

function addNewModel() {
    if(document.getElementById('myForm').checkValidity()){
        document.getElementById('myForm').action = "{{$addNewURL}}";
        document.getElementById('myForm').submit();
    } else
    document.getElementById('checker').click();
}

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

function calculateTotals(){
    var numberOfInv = document.getElementsByClassName('amount').length;
    
    var totalMeters = 0 ;

    var inputs = document.getElementsByClassName('amount'),
    names = [].map.call(inputs, function( input ) {
        totalMeters += Number(input.value);
    });

    var newPrice =  totalMeters * Number(document.getElementById('price').value)

    // document.getElementById('numberOfInv').innerHTML = numberOfInv;
    // document.getElementById('meter').innerHTML = totalMeters;
    // document.getElementById('totalPrice').innerHTML = {{ $totals['numberOfInv'] }} + totalMeters * Number(document.getElementById('price').value);

    document.getElementById('numberOfInv2').innerHTML = "{{ $totals['numberOfInv'] }} + " + numberOfInv;
    document.getElementById('meter2').innerHTML = "{{ $totals['meter'] }} + "  + totalMeters + " = " + Number({{ $totals['meter'] }} + totalMeters);
    document.getElementById('totalPrice2').innerHTML = "{{ $totals['totalPrice'] }} + "  + newPrice + " = " + Number({{ $totals['totalPrice'] }} + newPrice);

}

</script>
@endsection
