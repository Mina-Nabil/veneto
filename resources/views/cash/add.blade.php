@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ $pageTitle }}</h4>
                <h5 class="card-subtitle">{{ $pageDescription }}</h5>
                <form class="form pt-3" method="post" action="{{ $formURL }}" enctype="multipart/form-data">
                    @csrf
                    <div class=row>
                        <div class="form-group col-3">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon11"><i class="fas fa-list"></i></span>
                                </div>
                                <input type="text" class="form-control" placeholder="Transaction Example" name=name[] list=transTypes required>
                                <datalist id=transTypes>
                                    <option value="مرتبات"></option>
                                    <option value="مصاريف عرض"></option>
                                </datalist>
                            </div>
                            <small class="text-danger">{{$errors->first('name')}}</small>
                        </div>
                        <div class="form-group col-2">
                            <div class="input-group mb-3">
                                <select name=typeID[] class="select2 form-control custom-select">
                                    <option disabled hidden selected value="">الانواع</option>
                                    @foreach($transSubTypes as $transSubType)
                                    <option value="{{ $transSubType->id }}">
                                        {{$transSubType->TRTP_NAME}}-{{$transSubType->TRST_NAME}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <small class="text-danger">{{$errors->first('typeID')}}</small>
                        </div>
                        <div class="form-group col-2">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon11"><i class="ti-money"></i></span>
                                </div>
                                <input type="number" step=0.01 class="form-control" placeholder="Debit Amout" name=out[] >
                            </div>
                            <small class="text-danger">{{$errors->first('out')}}</small>
                        </div>
                        <div class="form-group col-2">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon11"><i class="ti-money"></i></span>
                                </div>
                                <input type="number" step=0.01 class="form-control" placeholder="Credit Amount" name=in[] >
                            </div>
                            <small class="text-danger">{{$errors->first('in')}}</small>
                        </div>
                        <div class="form-group col-3">
                            <div class="input-group mb-3">

                                <input type="text" class="form-control" placeholder="Transaction Comment" name=comment[]>
                                <div class="input-group-append">
                                    <button class="btn btn-success" id="dynamicAddButton" type="button" onclick="addCash();"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="dynamicContainer">
                    </div>

                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                    <a href="{{url('cash/show') }}" class="btn btn-dark">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    var room = 1;

    function setDefaultBrand() {
        brand = document.getElementById('defbrand').options[e.selectedIndex].value;

    }

    function addCash() {

        room++;
        var objTo = document.getElementById('dynamicContainer')
        var divtest = document.createElement("div");
        divtest.setAttribute("class", " row col-lg-12 removeclass" + room);
        var rdiv = 'removeclass' + room;
        var concatString = "";
        concatString += "<div class=row>\
                        <div class='form-group col-3'>\
                            <div class='input-group mb-3'>\
                                <div class='input-group-prepend'>\
                                    <span class='input-group-text' id='basic-addon11'><i class='fas fa-list'></i></span>\
                                </div>\
                                <input type='text' class='form-control' placeholder='Transaction Example' name=name[] list=transTypes required>\
                                <datalist id=transTypes>\
                                    <option value='مرتبات'></option>\
                                    <option value='مصاريف عرض'></option>\
                                </datalist>\
                            </div>\
                            <small class='text-danger'>{{$errors->first('name')}}</small>\
                        </div>";

        concatString += "<div class='form-group col-2'>\
                            <div class='input-group mb-3'>\
                                <select name=typeID[] class='select2 form-control custom-select'>\
                                    <option disabled hidden selected value=''>الانواع</option>\
                                    @foreach($transSubTypes as $transSubType)\
                                    <option value='{{ $transSubType->id }}'>\
                                        {{$transSubType->TRTP_NAME}}-{{$transSubType->TRST_NAME}}\
                                    </option>\
                                    @endforeach\
                                </select>\
                            </div>\
                            <small class='text-danger'>{{$errors->first('typeID')}}</small>\
                        </div>\
                        <div class='form-group col-2'>\
                            <div class='input-group mb-3'>\
                                <div class='input-group-prepend'>\
                                    <span class='input-group-text' id='basic-addon11'><i class='ti-money'></i></span>\
                                </div>\
                                <input type='number' step=0.01 class='form-control' placeholder='Debit Amout' name=out[] >\
                            </div>\
                            <small class='text-danger'>{{$errors->first('out')}}</small>\
                        </div>";

        concatString += " <div class='form-group col-2'>\
                            <div class='input-group mb-3'>\
                                <div class='input-group-prepend'>\
                                    <span class='input-group-text' id='basic-addon11'><i class='ti-money'></i></span>\
                                </div>\
                                <input type='number' step=0.01 class='form-control' placeholder='Credit Amount' name=in[] >\
                            </div>\
                            <small class='text-danger'>{{$errors->first('in')}}</small>\
                        </div>\
                        <div class='form-group col-3'>\
                            <div class='input-group mb-3'>\
                                <input type='text' class='form-control' placeholder='Transaction Comment' name=comment[]>\
                                <div class='input-group-append'>\
                                        <button class='btn btn-danger' type='button' onclick='removeToab(" + room + ");'><i class='fa fa-minus'></i></button>\
                                    </div>\
                            </div>\
                        </div>\
                    </div>";

        divtest.innerHTML = concatString;

        objTo.appendChild(divtest);
        $(".select2").select2();

    }

    function removeToab(rid) {
        $('.removeclass' + rid).remove();

    }

</script>
@endsection