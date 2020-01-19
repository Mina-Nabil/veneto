@extends('layouts.app')

@section('content')
<form id=myForm class="form pt-3" method="post" action="{{ $formURL }}" enctype="multipart/form-data" >
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ $pageTitle }}</h4>
                <h5 class="card-subtitle">{{ $pageDescription }}</h5>
                @csrf

                    <div class="col-lg-12">
                    <label>Client</label>
                        <div class="input-group mb-3">
                            <select name=clientID class="select2 form-control custom-select" style="width: 100%; height:50px;" required>
                                <option disabled selected hidden value="">Clients</option>
                                @foreach($clients as $client)
                                <option value="{{ $client->id }}">
                                {{$client->CLNT_NAME}} </option>
                                @endforeach 
                            </select>
                        </div>
                    </div>

                    <div class="row p-l-10 p-r-10 p-b-10">   
                        <h5 class="card-title p-l-10">Items Added</h5>                                    
                        <ul class="col-lg-12  list-group" id=itemsList>
              
        
                        </ul>
                    </div>
                    <hr>
                    <div class="row p-l-10 p-r-10">  
                        <h5 class="col-lg-12 card-title p-l-10">Sales Summary</h5>
                    </div>
                    <div class="row p-l-20 p-r-20">  
                        <div class="col-lg-4">
                            <strong>Totals</strong>
                        </div>
                        
                        <div class="col-lg-4">
                            <strong>Number of Items</strong>
                            <p id=numberOfInv >0</p>
                        </div>
                        
                        <div class="col-lg-4 p-r-20">
                            <strong>Price</strong>
                            <p id=totalPrice >0</p>
                        </div>     
                    </div>
                <hr>

                    <label class="nopadding" for="input-file-now-custom-1"><strong>Entry Details</strong></label>
                    <div class="row ">
         
                        <div id="dynamicContainer">
                        </div>

                        <div class="nopadding row col-lg-12">
                            <div class="col-lg-2">
                                        <div class="input-group mb-2">
                                            <select name=finished[] id=finished[] class="form-control select2  custom-select" required>
                                                <option disabled hidden selected value="">Finished Inventory</option>
                                                @foreach($items['data'] as $item)
                                                <option value="{{ $item->id }}">
                                                {{$item->BRND_NAME}} - {{$item->MODL_UNID}} </option>
                                                @endforeach 
                                            </select>
                                        </div>
                            </div>


                            <div class="col-lg-1">
                                    <input type="number"  step=1  min=0  class="form-control amount" placeholder="36" name=amount36[] aria-label="Total Amount in Meters" aria-describedby="basic-addon11" >
                            </div>
                            <div class="col-lg-1">
                                    <input type="number"  step=1  min=0  class="form-control amount" placeholder="38" name=amount38[] aria-label="Total Amount in Meters" aria-describedby="basic-addon11" >
                            </div>
                            <div class="col-lg-1">
                                    <input type="number"  step=1  min=0  class="form-control amount" placeholder="40" name=amount40[] aria-label="Total Amount in Meters" aria-describedby="basic-addon11" >
                            </div>
                                
                            <div class="col-lg-1">
                                    <input type="number"  step=1  min=0  class="form-control amount" placeholder="42" name=amount42[] aria-label="Total Amount in Meters" aria-describedby="basic-addon11" >
                                </div>
                                <div class="col-lg-1">

                                    <input type="number"  step=1  min=0  class="form-control amount" placeholder="44" name=amount44[] aria-label="Total Amount in Meters" aria-describedby="basic-addon11" >
                                </div>
                                <div class="col-lg-1">
                                    <input type="number"  step=1  min=0  class="form-control amount" placeholder="46" name=amount46[] aria-label="Total Amount in Meters" aria-describedby="basic-addon11"  >
                                </div>
                                <div class="col-lg-1">
                                    <input type="number"  step=1  min=0  class="form-control amount" placeholder="48" name=amount48[] aria-label="Total Amount in Meters" aria-describedby="basic-addon11"  >
                                </div>
                                <div class="col-lg-1">
                                    <input type="number"  step=1  min=0  class="form-control amount" placeholder="50" name=amount50[] aria-label="Total Amount in Meters" aria-describedby="basic-addon11"  >
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group mb-3">
                                        <input type="number" step=0.01   class="form-control amount" placeholder="Price" name=price[] aria-label="Total Amount in Meters" aria-describedby="basic-addon11"  required>
                                        <div class="input-group-append">
                                            <button class="btn btn-success" id="dynamicAddButton" type="button" onclick="addToab();"><i class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                       
                        </div>
                 

                    
                    <button type="button" onclick="calculateTotals()" class="btn btn-info mr-2">Calculate</button>
                    <a href="{{url('rawinventory/cancel') }}" class="btn btn-dark">Cancel</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">More Sales Details</h4>
                <h5 class="card-subtitle">Values Set by default, change if neccessary</h5>
                <div class="col-lg-12">
                    <label>Paid</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon11"><i class="ti-money"></i></span>
                        </div>
                        <input type="number" step=0.01 class="form-control" placeholder="Example: 1234.56" name=paid value="{{ old('paid') ?? 0}}" required >
                    </div>
                    <small class="text-danger">{{$errors->first('paid')}}</small>
                </div>

                <div class="col-lg-12">
                    <h5>Payment Type</h5>
                    <div class="input-group mb-3">
                        <select class="select form-control form-control-line" name=type  required >
                            <option selected value=0>Cash</option>
                            <option value=1>Cheque</option>
                        </select>
                    </div>
                </div>


                <div class="col-lg-12">
                    <label>Comment</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon11"><i class="ti-list"></i></span>
                        </div>
                        <input type="text" class="form-control"  name=comment  value="{{  old('comment')}}" >
                    </div>
                </div>
                <div class=p-10>
                    <button type="submit" id=checker class="btn btn-success mr-2">&emsp;Submit&emsp;</button>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
<script>


var room = 1;
var brand;

function setDefaultBrand(){
    brand = document.getElementById('defbrand').options[e.selectedIndex].value;

}

function addToab() {

    room++;
    var objTo = document.getElementById('dynamicContainer')
    var divtest = document.createElement("div");
    divtest.setAttribute("class", "removeclass row col-lg-12 " + room);
    var rdiv = 'removeclass' + room;
    var concatString = "";
    concatString +=   " <div class='col-lg-2'>\
                                        <div class='input-group mb-2'>\
                                            <select name=finished[] class='select2 form-control custom-select' style='width: 100%; height:50px;' required>\
                                                <option disabled selected hidden value='' >Finished Inventory</option>\
                                                @foreach($items['data'] as $item)\
                                                <option value='{{ $item->id }}'>\
                                                {{$item->BRND_NAME}} - {{$item->MODL_UNID}} </option>\
                                                @endforeach \
                                            </select>\
                                        </div>\
                                    </div>\
                                <div class='col-lg-1'>\
                                    <input type='number'  step=1  min=0 class='form-control amount' placeholder='36' name=amount36[] aria-label='Total Amount in Meters' aria-describedby='basic-addon11'  >\
                                </div>\
                                <div class='col-lg-1'>\
                                    <input type='number'  step=1  min=0 class='form-control amount' placeholder='38' name=amount38[] aria-label='Total Amount in Meters' aria-describedby='basic-addon11'  >\
                                    </div> ";
            concatString +=    "<div class='col-lg-1'>\
                                    <input type='number'  step=1  min=0 class='form-control amount' placeholder='40' name=amount40[] aria-label='Total Amount in Meters' aria-describedby='basic-addon11'  >\
                            </div>\
                            <div class='col-lg-1'>\
                                    <input type='number'  step=1  min=0 class='form-control amount' placeholder='42' name=amount42[] aria-label='Total Amount in Meters' aria-describedby='basic-addon11'  >\
                                </div>\
                                <div class='col-lg-1'>\
                                    <input type='number'  step=1  min=0 class='form-control amount' placeholder='44' name=amount44[] aria-label='Total Amount in Meters' aria-describedby='basic-addon11'  >\
                                </div>";
            concatString +=      "<div class='col-lg-1'>\
                                    <input type='number'  step=1  min=0  class='form-control amount' placeholder='46' name=amount46[] aria-label='Total Amount in Meters' aria-describedby='basic-addon11'  >\
                                </div>\
                                <div class='col-lg-1'>\
                                    <input type='number'  step=1  min=0 class='form-control amount' placeholder='48' name=amount48[] aria-label='Total Amount in Meters' aria-describedby='basic-addon11'  >\
                                </div>\
                                <div class='col-lg-1'>\
                                <input type='number'  step=1  min=0  class='form-control amount' placeholder='50' name=amount50[]    >\
                                </div>\
                                <div class='col-lg-2'>\
                                <div class='input-group mb-3'>\
                                    <input type='number' step=0.01 class='form-control amount' placeholder='Price' name=price[] aria-label='Each Item Price' aria-describedby='basic-addon11'  required>\
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

function calculateTotals(){
    var numberOfInv = 0;
    var price = 0;
    var i = 0;
    
    amount36 = document.forms[1].elements['amount36[]'];
    amount38 = document.forms[1].elements['amount38[]'];
    amount40 = document.forms[1].elements['amount40[]'];
    amount42 = document.forms[1].elements['amount42[]'];
    amount44 = document.forms[1].elements['amount44[]'];
    amount46 = document.forms[1].elements['amount46[]'];
    amount48 = document.forms[1].elements['amount48[]'];
    amount50 = document.forms[1].elements['amount50[]'];
    prices   = document.forms[1].elements['price[]'];
    finished = document.forms[1].elements['finished[]'];

    document.getElementById('itemsList').innerHTML = "";

    if (typeof prices.length === "undefined" && finished.selectedIndex!=0) {
    console.log("1 row")
        numberOfInv =  Number(amount36.value) + Number(amount38.value)  + Number(amount40.value) + Number(amount42.value) + Number(amount44.value) + Number(amount46.value)  + Number(amount48.value)  + Number(amount50.value ) ;
        price = numberOfInv * prices.value;
        
        document.getElementById('itemsList').innerHTML = "<li class='list-group-item' >\
                        <div class='row'>\
                            <div class='col-lg-4'> " + finished.options[finished.selectedIndex].innerHTML + " </div>\
                            <div class='col-lg-4'> " + numberOfInv + " </div>\
                            <div class='col-lg-4 p-l-10'> " + price.toFixed(1).replace(/\d(?=(\d{3})+\.)/g, '$&,') + "</div>\
                        </div>\
                        </li>";
    } else for(i ; i < amount36.length ; i++){
        if(finished[i].selectedIndex!=0){

            let row = Number(amount36[i].value) + Number(amount38[i].value)  + Number(amount40[i].value) + Number(amount42[i].value) + Number(amount44[i].value) + Number(amount46[i].value)  + Number(amount48[i].value)  + Number(amount50[i].value ) ;
            numberOfInv += row;
            price += row * prices[i].value;
            document.getElementById('itemsList').innerHTML += " <li class='list-group-item' >\
                            <div class='row'>\
                                <div class='col-lg-4'> " + finished[i].options[finished[i].selectedIndex].innerHTML + " </div>\
                                <div class='col-lg-4'> " + row + " </div>\
                                <div class='col-lg-4 p-l-10'> " + (prices[i].value * row).toFixed(1).replace(/\d(?=(\d{3})+\.)/g, '$&,'); + "</div>\
                            </div>\
                            </li>";
        }
    }

    document.getElementById('numberOfInv').innerHTML = numberOfInv;
    document.getElementById('totalPrice').innerHTML = price.toFixed(1).replace(/\d(?=(\d{3})+\.)/g, '$&,');
 }
 

</script>
@endsection
