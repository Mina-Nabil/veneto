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


                        <div class="nopadding row col-lg-12">
                            <div class="col-lg-2">
                                <div class="input-group mb-2">
                                    <select name=finished[] id=finished[] class="form-control select2  custom-select" required>
                                        <option disabled hidden selected value="">Finished Inventory</option>
                                        @foreach($finished['data'] as $item)
                                        <option value="{{ $item->id }}">
                                            {{$item->BRND_NAME}} - {{$item->MODL_UNID}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <input type="number" step=1 class="form-control amount" placeholder="36" name=amount36[] aria-label="Total Amount in Meters" aria-describedby="basic-addon11">
                            </div>
                            <div class="col-lg-1">
                                <input type="number" step=1 class="form-control amount" placeholder="38" name=amount38[] aria-label="Total Amount in Meters" aria-describedby="basic-addon11">
                            </div>
                            <div class="col-lg-1">
                                <input type="number" step=1 class="form-control amount" placeholder="40" name=amount40[] aria-label="Total Amount in Meters" aria-describedby="basic-addon11">
                            </div>

                            <div class="col-lg-1">
                                <input type="number" step=1 class="form-control amount" placeholder="42" name=amount42[] aria-label="Total Amount in Meters" aria-describedby="basic-addon11">
                            </div>
                            <div class="col-lg-1">

                                <input type="number" step=1 class="form-control amount" placeholder="44" name=amount44[] aria-label="Total Amount in Meters" aria-describedby="basic-addon11">
                            </div>
                            <div class="col-lg-1">
                                <input type="number" step=1 class="form-control amount" placeholder="46" name=amount46[] aria-label="Total Amount in Meters" aria-describedby="basic-addon11">
                            </div>
                            <div class="col-lg-1">
                                <input type="number" step=1 class="form-control amount" placeholder="48" name=amount48[] aria-label="Total Amount in Meters" aria-describedby="basic-addon11">
                            </div>
                            <div class="col-lg-1">
                                <input type="number" step=1 class="form-control amount" placeholder="50" name=amount50[] aria-label="Total Amount in Meters" aria-describedby="basic-addon11">
                            </div>
                            <div class="col-lg-2">
                                <div class="input-group mb-3">
                                    <input type="number" step=0.01 class="form-control amount" placeholder="Price" name=price[] required>
                                    <div class="input-group-append">
                                        <button class="btn btn-success" id="dynamicAddButton" type="button" onclick="addToab();"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div id="dynamicContainer">
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
        divtest.setAttribute("class", " row col-lg-12 removeclass" + room);
        var rdiv = 'removeclass' + room;
        var concatString = "";
        concatString += " <div class='col-lg-2'>\
                                <div class='input-group mb-2'>\
                                    <select name=finished[] id=finished[] class='form-control select2  custom-select' required>\
                                        <option disabled hidden selected value=''>Finished Inventory</option>\
                                        @foreach($finished['data'] as $item)\
                                        <option value='{{ $item->id }}'>\
                                            {{$item->BRND_NAME}} - {{$item->MODL_UNID}} </option>\
                                        @endforeach\
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
                                    <input type='number' step=0.01 class='form-control amount' placeholder='Price' name=price[]  >\
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

    var bar = ''
 var selecteds = [];
    document.onkeyup = function (evt) {
     try {
        if (evt.keyCode == 13)// Enter key pressed
        {
        selectat = document.forms[1].elements['finished[]'];
       

        lastSelect = selectat[selectat.length-1]
        var $selectat = $('select[name ="finished[]"]') 
        var $lastSelect = $selectat.eq($selectat.length-1)
        let noOfRows = $selectat.length-1
        console.log(bar)
        entries = bar.split(' ');    
        let selectedID = selectByText($lastSelect, entries[0])
        if(selectedID){ //found
        var isFound = isFoundBefore(selectedID);
        console.log(isFound)
        if(isFound === false){
            row = noOfRows
            $lastSelect.val(selectedID)
            $lastSelect.trigger('change')
            addSize(row, entries[1], true)
            addToab() 
        } else if (Number.isInteger(isFound)){
            addSize(isFound, entries[1], false)
        }

        }
        bar=''
        } else {
            bar += evt.key;
        };
        }  catch(e){
            bar=''
        }
    }
 
        function selectByText ($el, term) {
            doma = $el.get(0)
            opts = doma.options
            let ret;
            let found=false;
            let i = 0;
            opts = Array.from(opts);
            for(i=0 ; i < opts.length ; i++) {
                if(opts[i].innerHTML.toLowerCase().includes(term.toLowerCase())){
                    console.log(opts[i].innerHTML)
                    return opts[i].value
                }
            }

            return  false;
        } 

        function isFoundBefore(val){
            selectatGodad = document.getElementsByName('finished[]');
            console.log(selectatGodad)
            for(i=0 ; i < selectatGodad.length ; i++) {
                if(selectatGodad[i].options[selectatGodad[i].selectedIndex].value == val){
                    return i
                }
            } 
            return false;
        } 

        function addSize(row, size){
            amount = document.getElementsByName('amount' + size + '[]');
            console.log('amount' + size + '[] : ' + row)
            if(isNaN(amount[row].value) || amount[row].value == ''){
                amount[row].value = 1 ;
            } else {
                amount[row].value = parseInt(amount[row].value) + 1
            }
            
        }



</script>
@endsection