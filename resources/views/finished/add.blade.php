@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ $pageTitle }}</h4>
                <h5 class="card-subtitle">{{ $pageDescription }}</h5>
                <form id=myForm class="form pt-3" method="post" action="{{ $formURL }}" enctype="multipart/form-data">
                    @csrf

                    <label class="nopadding" for="input-file-now-custom-1"><strong>Entry Details</strong></label>
                    <div class="row ">

                        <div id="dynamicContainer">
                        </div>

                        <div class="nopadding row col-lg-12">
                            <div class="col-lg-1">
                                <div class="input-group mb-3">
                                    <select name=model[] class="select2 form-control custom-select" required>
                                        <option disabled hidden selected value="">Models</option>
                                        @foreach($models as $model)
                                        <option value="{{ $model->id }}">
                                            {{$model->MODL_UNID}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <div class="input-group mb-3">
                                    <select name=brand[] class="select2 form-control custom-select"
                                        style="width: 100%; height:50px;" required>
                                        <option disabled selected hidden value="">Brands</option>
                                        @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}">
                                            {{$brand->BRND_NAME}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <input type="number" step=1 class="form-control amount" placeholder="36" name=amount36[]
                                    aria-label="Total Amount in Meters" aria-describedby="basic-addon11">
                            </div>
                            <div class="col-lg-1">
                                <input type="number" step=1 class="form-control amount" placeholder="38" name=amount38[]
                                    aria-label="Total Amount in Meters" aria-describedby="basic-addon11">
                            </div>
                            <div class="col-lg-1">
                                <input type="number" step=1 class="form-control amount" placeholder="40" name=amount40[]
                                    aria-label="Total Amount in Meters" aria-describedby="basic-addon11">
                            </div>

                            <div class="col-lg-1">
                                <input type="number" step=1 class="form-control amount" placeholder="42" name=amount42[]
                                    aria-label="Total Amount in Meters" aria-describedby="basic-addon11">
                            </div>
                            <div class="col-lg-1">

                                <input type="number" step=1 class="form-control amount" placeholder="44" name=amount44[]
                                    aria-label="Total Amount in Meters" aria-describedby="basic-addon11">
                            </div>
                            <div class="col-lg-1">
                                <input type="number" step=1 class="form-control amount" placeholder="46" name=amount46[]
                                    aria-label="Total Amount in Meters" aria-describedby="basic-addon11">
                            </div>
                            <div class="col-lg-1">
                                <input type="number" step=1 class="form-control amount" placeholder="48" name=amount48[]
                                    aria-label="Total Amount in Meters" aria-describedby="basic-addon11">
                            </div>
                            <div class="col-lg-1">
                                <input type="number" step=1 class="form-control amount" placeholder="50" name=amount50[]
                                    aria-label="Total Amount in Meters" aria-describedby="basic-addon11">
                            </div>
                            <div class="col-lg-2">
                                <div class="input-group mb-3">
                                    <input type="number" step=0.01 class="form-control amount" placeholder="Price"
                                        name=price[] required>
                                    <div class="input-group-append">
                                        <button class="btn btn-success" id="dynamicAddButton" type="button"
                                            onclick="addToab();"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <button type="submit" id=checker class="btn btn-success mr-2">Submit</button>
                    <a href="{{url('rawinventory/cancel') }}" class="btn btn-dark">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>


<script>


    var room = 1;
    var brand;

    function setDefaultBrand() {
        brand = document.getElementById('defbrand').options[e.selectedIndex].value;

    }

    function addToab() {

        room++;
        var objTo = document.getElementById('dynamicContainer')
        var divtest = document.createElement("div");
        divtest.setAttribute("class", "removeclass row col-lg-12 " + room);
        var rdiv = 'removeclass' + room;
        var concatString = "";
        concatString += " <div class='col-lg-1'>\
                                        <div class='input-group mb-3'>\
                                            <select name=model[] class='select2 form-control custom-select' style='width: 100%; height:50px;' required>\
                                                <option disabled selected hidden value=''>Models</option>\
                                                @foreach($models as $model)\
                                                <option value='{{ $model->id }}'>\
                                                {{$model->MODL_UNID}} </option>\
                                                @endforeach \
                                            </select>\
                                        </div>\
                                    </div>\
                                    <div class='col-lg-1'>\
                                        <div class='input-group mb-3'>\
                                            <select name=brand[] class='select2 form-control custom-select' style='width: 100%; height:50px;' required>\
                                                <option disabled selected hidden value=''>Brands</option>\
                                                @foreach($brands as $brand)\
                                                <option value='{{ $brand->id }}'>\
                                                {{$brand->BRND_NAME}} </option>\
                                                @endforeach \
                                            </select>\
                                        </div>\
                                    </div>\
                                <div class='col-lg-1'>\
                                    <input type='number'  step=1 class='form-control amount' placeholder='36' name=amount36[] aria-label='Total Amount in Meters' aria-describedby='basic-addon11'  >\
                                </div>\
                                <div class='col-lg-1'>\
                                    <input type='number'  step=1 class='form-control amount' placeholder='38' name=amount38[] aria-label='Total Amount in Meters' aria-describedby='basic-addon11'  >\
                                    </div> ";
        concatString += "<div class='col-lg-1'>\
                                    <input type='number'  step=1  class='form-control amount' placeholder='40' name=amount40[] aria-label='Total Amount in Meters' aria-describedby='basic-addon11'  >\
                            </div>\
                            <div class='col-lg-1'>\
                                    <input type='number'  step=1  class='form-control amount' placeholder='42' name=amount42[] aria-label='Total Amount in Meters' aria-describedby='basic-addon11'  >\
                                </div>\
                                <div class='col-lg-1'>\
                                    <input type='number'  step=1  class='form-control amount' placeholder='44' name=amount44[] aria-label='Total Amount in Meters' aria-describedby='basic-addon11'  >\
                                </div>";
        concatString += "<div class='col-lg-1'>\
                                    <input type='number'  step=1   class='form-control amount' placeholder='46' name=amount46[] aria-label='Total Amount in Meters' aria-describedby='basic-addon11'  >\
                                </div>\
                                <div class='col-lg-1'>\
                                    <input type='number'  step=1  class='form-control amount' placeholder='48' name=amount48[] aria-label='Total Amount in Meters' aria-describedby='basic-addon11'  >\
                                </div>\
                                <div class='col-lg-1'>\
                                <input type='number'  step=1   class='form-control amount' placeholder='50' name=amount50[]    >\
                                </div>\
                                <div class='col-lg-2'>\
                                <div class='input-group mb-3'>\
                                    <input type='number' step=0.01 class='form-control amount' placeholder='Price' name=price[]  required>\
                                    <div class='input-group-append'>\
                                        <button class='btn btn-danger' type='button' onclick='removeToab(" + room + ");'><i class='fa fa-minus'></i></button>\
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