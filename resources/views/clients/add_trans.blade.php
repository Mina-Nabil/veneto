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
                            <label>عميل*</label>
                            <div class="input-group mb-3">
                                <select name=client[] class="select2 form-control custom-select" style="width: 100%; height:36px;" required>
                                    <option disabled hidden selected value="">Pick From Clients</option>
                                    @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{($client->CLNT_SRNO ) ? $client->CLNT_SRNO.' - '  : ''}}{{$client->CLNT_NAME}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <small class="text-danger">{{$errors->first('client')}}</small>
                        </div>

                        <div class="form-group col-2">
                            <label>وصف العمليه</label>
                            <div class="input-group mb-3">
                               
                                <input type="text" class="form-control" name=desc[]>
                            </div>
                            <small class="text-danger">{{$errors->first('desc')}}</small>
                        </div>

                        <div class="form-group col-1">
                            <label>مبيعات</label>
                            <div class="input-group mb-3">
                                <input type="number" step=0.01 class="form-control" name=sales[] value="0" required>
                            </div>
                            <small class="text-danger">{{$errors->first('sales')}}</small>
                        </div>

                        <div class="form-group col-1">
                            <label>نقدي</label>
                            <div class="input-group mb-3">
                                <input type="number" step=0.01 class="form-control" name=cash[] value="0" required>
                            </div>
                            <small class="text-danger">{{$errors->first('cash')}}</small>
                        </div>

                        <div class="form-group col-1">
                            <label>اوراق دفع</label>
                            <div class="input-group mb-3">
                                <input type="number" step=0.01 class="form-control" name=notes[] value="0" required>
                            </div>
                            <small class="text-danger">{{$errors->first('notes')}}</small>
                        </div>

                        <div class="form-group col-1">
                            <label>خصم</label>
                            <div class="input-group mb-3">
                                <input type="number" step=0.01 class="form-control" name=disc[] value="0" required>
                            </div>
                            <small class="text-danger">{{$errors->first('disc')}}</small>
                        </div>

                        <div class="form-group col-1">
                            <label>مرتجع</label>
                            <div class="input-group mb-3">
                                <input type="number" step=0.01 class="form-control" name=return[] value="0" required>
                            </div>
                            <small class="text-danger">{{$errors->first('return')}}</small>
                        </div>

                        <div class="form-group col-2">
                            <label>Comment</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name=comment[] >
                                <div class="input-group-append">
                                    <button class="btn btn-success" id="dynamicAddButton" type="button" onclick="addClient();"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="dynamicContainer">
                    </div>


                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                    <a href="{{url('clients/report') }}" class="btn btn-dark">Cancel</a>
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

    function addClient() {

        room++;
        var objTo = document.getElementById('dynamicContainer')
        var divtest = document.createElement("div");
        divtest.setAttribute("class", " row col-lg-12 removeclass" + room);
        var rdiv = 'removeclass' + room;
        var concatString = "";
        concatString += "<div class=row>\
                        <div class='form-group col-3'>\
                            <label>عميل*</label>\
                            <div class='input-group mb-3'>\
                                <select name=client[] class='select2 form-control custom-select' style='width: 100%; height:36px;' required>\
                                    <option disabled hidden selected value=''>Pick From Clients</option>\
                                    @foreach($clients as $client)\
                                    <option value='{{ $client->id }}'>{{($client->CLNT_SRNO ) ? $client->CLNT_SRNO.' - '  : ''}}{{$client->CLNT_NAME}}</option>\
                                    @endforeach\
                                </select>\
                            </div>\
                            <small class='text-danger'>{{$errors->first('client')}}</small>\
                        </div>"
        concatString += "<div class='form-group col-2'>\
                            <label>وصف العمليه</label>\
                            <div class='input-group mb-3'>\
                                <input type='text' class='form-control' name=desc[]>\
                            </div>\
                            <small class='text-danger'>{{$errors->first('desc')}}</small>\
                        </div>\
                        <div class='form-group col-1'>\
                            <label>مبيعات</label>\
                            <div class='input-group mb-3'>\
                                <input type='number' step=0.01 class='form-control' name=sales[] value='0' required>\
                            </div>\
                            <small class='text-danger'>{{$errors->first('sales')}}</small>\
                        </div>"
        concatString += "<div class='form-group col-1'>\
                            <label>نقدي</label>\
                            <div class='input-group mb-3'>\
                                <input type='number' step=0.01 class='form-control' name=cash[] value='0' required>\
                            </div>\
                            <small class='text-danger'>{{$errors->first('cash')}}</small>\
                        </div>\
                        <div class='form-group col-1'>\
                            <label>اوراق دفع</label>\
                            <div class='input-group mb-3'>\
                                <input type='number' step=0.01 class='form-control' name=notes[] value='0' required>\
                            </div>\
                            <small class='text-danger'>{{$errors->first('notes')}}</small>\
                        </div>"
        concatString += "<div class='form-group col-1'>\
                            <label>خصم</label>\
                            <div class='input-group mb-3'>\
                                <input type='number' step=0.01 class='form-control' name=disc[] value='0' required>\
                            </div>\
                            <small class='text-danger'>{{$errors->first('disc')}}</small>\
                        </div>\
                        <div class='form-group col-1'>\
                            <label>مرتجع</label>\
                            <div class='input-group mb-3'>\
                                <input type='number' step=0.01 class='form-control' name=return[] value='0' required>\
                            </div>\
                            <small class='text-danger'>{{$errors->first('return')}}</small>\
                        </div>\
                        <div class='form-group col-2'>\
                            <label>Comment</label>\
                            <div class='input-group mb-3'>\
                                <input type='text' class='form-control' name=comment[]>\
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