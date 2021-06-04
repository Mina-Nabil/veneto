@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-8 col-md-12">
        <div class="card">
            <div class="card-header bg-dark">
                <h4 class="m-b-0 text-white">All Accounts Transactions</h4>
            </div>
            <div class="card-body">

                <ul class="nav nav-tabs profile-tab" role="tablist">
                    <?php $i=0 ?>
                    @foreach($accounts as $account)
                    <li class="nav-item"> <a class="nav-link {{($i++==0) ? 'active' : ''}}" data-toggle="tab" href="#account{{$account->id}}-tab" role="tab">{{$account->GNAC_NAME}}</a> </li>
                    @endforeach
                </ul>

                <div class="tab-content">
                    <!--second tab-->
                    <?php $i=0 ?>
                    @foreach($accounts as $account)
                    <div class="tab-pane  {{($i++==0) ? 'active' : ''}}" id="account{{$account->id}}-tab" role="tabpanel">
                        <div class="row">
                            <div class="card-body">
                                <h3 class="card-title">{{$account->GNAC_NAME}}</h3>
                                <table id="myTable-{{$account->id}}" class="table color-bordered-table table-striped full-color-table full-dark-table hover-table" data-display-length='-1'
                                    data-order="[]">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Title</th>
                                            <th>Credit</th>
                                            <th>Debit</th>
                                            <th>Balance</th>
                                            <th>Actions</th>
                                    
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($trans[$account->id] as $op)
                                        <tr @if($op->GNTR_EROR==1)

                                            style="background-color: #ffbdbd;"
                                            title="Wrong Transaction"

                                            @endif
                                            >
                                            <td>
                                                {{date_format(date_create($op->GNTR_DATE), "d-m-Y")}}
                                            </td>
                                            <td>
                                                {{$op->GNTR_TTLE}}
                                            </td>
                                            <td>{{number_format($op->GNTR_CRDT, 2)}}</td>
                                            <td>{{number_format($op->GNTR_DEBT, 2)}}</td>
                                            <td>{{number_format($op->GNTR_GNAC_BLNC, 2)}}</td>
                                            <td>
                                                @if(isset($op->GNTR_CMNT) && strcmp($op->GNTR_CMNT, '')!=0 )
                                                <button type="button" style="padding:.1rem" class="btn btn-secondary" data-container="body" title="" data-toggle="popover" data-placement="bottom"
                                                    data-content="{{$op->GNTR_CMNT}}" data-original-title="Comment:">
                                                    @endif
                                                    <i class="fas fa-list-alt"></i>
                                                </button>
                                      
                                            @if($op->GNTR_EROR==0)
                                            <button style="padding:.1rem" class="btn btn-success">
                                                    <i class="fas fa-exclamation-triangle" onclick="confirmError({{$op->id}}, {{$op->GNTR_EROR}})"></i>
                                                </button>
                                            @else
                                            <button style="padding:.1rem" class="btn btn-danger">
                                                    <i class="fas fa-exclamation-triangle" onclick="unmarkError({{$op->id}}, {{$op->GNTR_EROR}})"></i>
                                                </button>
                                            @endif
                                        </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    @endforeach
                </div>
                <div class="card-body">
                </div>
            </div>
            <div class="card border-dark">
                <div class="card-header bg-dark">
                    <h4 class="m-b-0 text-white">All Accounts</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive m-t-40">
                        <table id="myTable" class="table color-bordered-table table-striped full-color-table full-dark-table hover-table" data-display-length='-1' data-order="[]">
                            <thead>
                                <tr>
                                    <th>Account</th>
                                    <th>Type</th>
                                    <th>Nature</th>
                                    <th>Balance</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($accounts as $account)
                                <tr>
                                    <td id="acnt{{$account->id}}-name">{{$account->GNAC_NAME}}</td>
                                    <td>{{$account->GNTL_NAME}}</td>
                                    <td>{{($account->GNAC_NATR==1) ? "Credit" : "Debit"}}</td>
                                    <td>{{number_format($account->GNAC_BLNC)}}</td>
                                    <td>
                                        @if(isset($account->GNAC_DESC) && strcmp($account->GNAC_DESC, '')!=0 )
                                        <button type="button" style="padding:.1rem" class="btn btn-secondary" data-container="body" title="" data-toggle="popover" data-placement="bottom"
                                            data-content="{{$account->GNAC_DESC}}" data-original-title="Description:">
                                            <i class="fas fa-list-alt"></i>
                                        </button>
                                        @endif
                                        <a href="javascript:void(0)" onclick="loadAccount({{$account->id}})"><img src="{{ asset('images/edit.png') }}" width=25 height=25></a>
                                        <a href="javascript:void(0)" onclick="deleteAccount({{$account->id}})"><img src="{{ asset('images/del.png') }}" width=25 height=25></a>
                                    </td>
                                </tr>
                                <div style="display: none" id="acnt{{$account->id}}-type">{{$account->GNAC_GNTL_ID}}</div>
                                <div style="display: none" id="acnt{{$account->id}}-nature">{{$account->GNAC_NATR}}</div>
                                <div style="display: none" id="acnt{{$account->id}}-arbcName">{{$account->GNAC_ARBC_NAME}}</div>
                                <div style="display: none" id="acnt{{$account->id}}-desc">{{$account->GNAC_DESC}}</div>
                                <div style="display: none" id="acnt{{$account->id}}-balance">{{$account->GNAC_BLNC}}</div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-12">
        <div class="card">
            <div class="card-header bg-dark">
                <h4 class="m-b-0 text-white">Add Transaction</h4>
            </div>
            <div class="card-body">
                <form class="form pt-3" method="post" action="{{url($insertTransURL)}}">
                    @csrf
                    <div class="form-group ">
                        <label>Account*</label>
                        <div class="input-group mb-3 ">
                            <select class="select2 form-control" style="width: 100%" name="accountID" required>
                                @foreach($accounts as $account)
                                <option value="{{$account->id}}" @if(old('accountID')==$account->id) selected @endif >{{$account->GNAC_NAME}}</option>
                                @endforeach
                            </select>
                        </div>
                        <small class="text-danger">{{$errors->first('accountID')}}</small>
                    </div>

                    <div class="form-group">
                        <label>Title*</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-list"></i></span>
                            </div>
                            <input type="text" class="form-control" id=title placeholder="Monthly Payment" name=title value="{{ old('title')}}" required>
                        </div>
                        <small class="text-danger">{{$errors->first('title')}}</small>
                    </div>
                    <div class="form-group">
                        <label>Value</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-money"></i></span>
                            </div>
                            <input type="number" step="0.01" id=value class="form-control" placeholder="Example: 150.25" name=value value="{{ old('value')}}" required>
                        </div>
                        <small class="text-danger">{{$errors->first('value')}}</small>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Credit/Debit</label>
                            <div class=row>
                                <div class="col-md-6">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="customRadio11" name="type" value="1" class="custom-control-input">
                                        <label class="custom-control-label" for="customRadio11">Credit</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="customRadio22" name="type" value="2" class="custom-control-input">
                                        <label class="custom-control-label" for="customRadio22">Debit</label>
                                    </div>
                                </div>
                            </div>
                            <small class="text-danger">{{$errors->first('type')}}</small>
                        </div>
                    </div>
                    <div class="col-lg-12 bt-switch">
                        <label>Reflect on Cash?</label>
                        <div class="input-group mb-3 ">
                            <input type="checkbox" data-size="large" data-on-color="success" data-off-color="danger" data-on-text="Yes" data-off-text="No" name="isCash">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Comment</label>
                        <div class="input-group mb-3">
                            <textarea class="form-control" id=comment name=comment>{{ old('comment')??''}}</textarea>
                        </div>
                        <small class="text-danger">{{$errors->first('comment')}}</small>
                    </div>
                    <button type="submit" class="btn btn-success mr-2">Submit</button>

                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-dark">
                <h4 class="m-b-0 text-white">Add Account</h4>
            </div>
            <div class="card-body">
                <form id="addAccountForm" class="form pt-3" method="post" action="{{url($insertAccountURL)}}">
                    @csrf
                    <input type="hidden" id="acntID" name="id" />
                    <div class="form-group ">
                        <label>Type*</label>
                        <div class="input-group mb-3 ">
                            <select class="select2 form-control" style="width: 100%" id=titleID name="titleID" required>
                                @foreach($titles as $title)
                                <option value="{{$title->id}}" @if(old('titleID')==$title->id) selected @endif >{{$title->GNTL_NAME}}</option>
                                @endforeach
                            </select>
                        </div>
                        <small class="text-danger">{{$errors->first('titleID')}}</small>
                    </div>

                    <div class="form-group">
                        <label>Name*</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-list"></i></span>
                            </div>
                            <input type="text" class="form-control" id=name placeholder="Example: Machines, Custody Mr. Guirguis" name=name value="{{ old('name')}}" required>
                        </div>
                        <small class="text-danger">{{$errors->first('name')}}</small>
                    </div>

                    <div class="form-group ">
                        <label>Account Nature*</label>
                        <div class="input-group mb-3 ">
                            <select class="select form-control" id=nature name="nature" required>
                                <option value="1" @if(old('nature')==1) selected @endif>Credit</option>
                                <option value="2" @if(old('nature')==2) selected @endif>Debit</option>
                            </select>
                        </div>
                        <small class="text-danger">{{$errors->first('nature')}}</small>
                    </div>

                    <div class="form-group">
                        <label>Current Balance*</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-money"></i></span>
                            </div>
                            <input type="number" step="0.01" id=balance class="form-control" placeholder="Example: 2000" name=balance value="{{ old('balance')}}" required>
                        </div>
                        <small class="text-danger">{{$errors->first('balance')}}</small>
                    </div>

                    <div class="form-group">
                        <label>Arabic Name</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-list"></i></span>
                            </div>
                            <input type="text" class="form-control" id=arabicName placeholder="Example: سلف شهرية, عهدة الاستاذ جرجس " name=arabicName value="{{ old('arabicName')}}">
                        </div>
                        <small class="text-danger">{{$errors->first('arabicName')}}</small>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <div class="input-group mb-3">
                            <textarea class="form-control" id=desc name=desc>{{ old('desc')??''}}</textarea>
                        </div>
                        <small class="text-danger">{{$errors->first('desc')}}</small>
                    </div>
                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                    <button type="button" onclick="resetAddAccount()" class="btn btn-dark mr-2">Reset</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function loadAccount(id){
        typeID = $('#acnt' + id + "-type").html()
        nature = $('#acnt' + id + "-nature").html()
        name = $('#acnt' + id + "-name").html()
        arbcName = $('#acnt' + id + "-arbcName").html()
        desc = $('#acnt' + id + "-desc").html()
        balance = $('#acnt' + id + "-balance").html()
  

        $('#titleID').select2('val', typeID);
        $('#name').val(name)
        $('#nature').val(nature)
        $('#balance').val(balance)
        $('#arabicName').val(arbcName)
        $('#desc').val(desc)
        $('#acntID').val(id)

        $('#nature').attr("disabled","true")
        $('#balance').attr("readonly","true")

        $('#addAccountForm').attr("action", "{{$updateAccount}}");

        $.toast({
            heading: 'Account Loaded',
            text: 'Reset form has been cleared.',
            position: 'top-right',
            loaderBg:'blue',
            icon: 'success',
            hideAfter: 2000, 
            stack: 6,
            type: 'success'
        });
    }

    function resetAddAccount(){
        $('#titleID').select2('val', '{{$titles->first()->id}}');
        $('#name').val("")
        $('#nature').val("1")
        $('#balance').val("")
        $('#arabicName').val("")
        $('#desc').val("")
        $('#acntID').val("")
        $('#addAccountForm').attr("action", "{{$insertAccountURL}}");

        $('#nature').removeAttr("disabled")
        $('#balance').removeAttr("readonly")

        $.toast({
            heading: 'Reset Done',
            text: 'Reset form has been cleared.',
            position: 'top-right',
            loaderBg:'blue',
            icon: 'success',
            hideAfter: 2000, 
            stack: 6,
            type: 'success'
        });
    }

    function deleteAccount(id){
        Swal.fire({
        text: "Are you sure you want to delete this account and all the related transactions?",
        icon: "warning",
        showCancelButton: true,
        }).then((isConfirm) => {
            if(isConfirm.value){
                var http = new XMLHttpRequest();
                var url = "{{$deleteAccount}}" ;
                http.open('POST', url, true);
                var formdata = new FormData();

                formdata.append('_token','{{ csrf_token() }}');
                formdata.append('id',id);
  
                http.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200 && this.responseText=="1") {

                    location.reload();
                    return;
                } else {
                    Swal.fire({
                        text: "Delete failed!",
                        icon: "warning",
                    });
                    } 
                }    
                http.send(formdata)
        } } ) 
       
        
        }
    

</script>

@endsection

@section('js_content')



<script>
    function IsNumeric(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }
    
    function getMeta(metaName) {
      const metas = document.getElementsByTagName('meta');
    
      for (let i = 0; i < metas.length; i++) {
        if (metas[i].getAttribute('name') === metaName) {
          return metas[i].getAttribute('content');
        }
      }
    
      return '';
    }
    
    function confirmError(id, errorState){
    
    if(errorState==1){
        Swal.fire({
            title: 'Transaction already is marked as an Error',
            text: "You won't be able to mark this!",
            icon: 'warning',
        });
    } else {
        Swal.fire({
            title: 'Transaction Error, Are you sure?',
            text: "You can revert this in the future",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, mark it!'
        }).then((result) => {
            if (result.value) {
                const csrf = getMeta('csrf-token');
                var http = new XMLHttpRequest();
                var url  = '{{url("accounts/error")}}';
                var params = '_token=' + csrf + '&tranId=' + id;
                http.open('POST', url, true);
                //Send the proper header information along with the request
                http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                http.onreadystatechange = function() {
                    console.log(this.responseText=='1')
                    if (IsNumeric(this.responseText) && this.responseText=='1' && this.readyState == 4 && this.status == 200) {
                        Swal.fire({
                            title: 'Marked!',
                            text: 'Please refresh for an updated view!',
                            icon: 'success',
                            confirmButtonText: 'Refresh'
                            }).then((refresh) => {
                                document.location.reload();
                            })
                        
                    } else if(this.readyState == 4 && this.status == 200 && IsNumeric(this.responseText) && this.responseText=='0') {
                        Swal.fire("Error", "Process Failed, please try again", 'error');
                    } 
                    };
                http.send(params);
            }
        })
    }

    
}

function unmarkError(id, errorState){

    if(errorState==0){
        Swal.fire({
            title: 'Transaction already is unmarked',
            text: "You won't be able to unmark this!",
            icon: 'warning',
        });
    } else {
        Swal.fire({
            title: 'Transaction not Error, Are you sure?',
            text: "You can revert this in the future",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, unmark it!'
        }).then((result) => {
            if (result.value) {
                const csrf = getMeta('csrf-token');
                var http = new XMLHttpRequest();
                var url  = '{{url("accounts/unmark")}}';
                var params = '_token=' + csrf + '&tranId=' + id;
                http.open('POST', url, true);
                //Send the proper header information along with the request
                http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                http.onreadystatechange = function() {
                    console.log(this.responseText=='1')
                    if (IsNumeric(this.responseText) && this.responseText=='1' && this.readyState == 4 && this.status == 200) {
                        Swal.fire({
                            title: 'Unmarked!',
                            text: 'please refresh for updated view!',
                            icon: 'success',
                            confirmButtonText: 'Refresh'
                            }).then((refresh) => {
                                document.location.reload();
                            })
                        
                    } else if(this.readyState == 4 && this.status == 200 && IsNumeric(this.responseText) && this.responseText=='0') {
                        Swal.fire("Error", "Process Failed, please try again", 'error');
                    } 
                    };
                http.send(params);
            }
        })
    }

    
}

    @foreach ($accounts as $account)
    var table = $('#myTable-{{$account->id}}').DataTable({
    "displayLength": 25,
    dom: 'Bfrtip',
    buttons: [
        {
            extend: 'print',
            text: 'Print',
            title: 'Veneto',
            footer: true,
            messageTop: "Date: " + formatted,
            customize: function (win) {
                $(win.document.body)
                    .prepend('<center><img src="{{asset('images / dark - logo.png')}}" style="position:absolute; margin: auto; ; margin-top: 460px ; left: 0; right: 0; opacity:0.2" /></center>')
                    .css('font-size', '24px')

                //$('#stampHeader' ).addClass( 'stampHeader' );
                $(win.document.body).find('table')
                    .css('border', 'solid')
                    .css('margin-top', '20px')
                    .css('font-size', 'inherit');
                $(win.document.body).find('th')
                    .css('border', 'solid')
                    .css('border', '!important')
                    .css('border-width', '1px')
                    .css('font-size', 'inherit')
                $(win.document.body).find('td')
                    .css('border', 'solid')
                    .css('border', '!important')
                    .css('border-width', '1px');
                $(win.document.body).find('tr')
                    .css('border', 'solid')
                    .css('border', '!important')
                    .css('border-width', '1px')
            }
        }, {
            extend: 'excel',
            title: 'Veneto',
            footer: true,

        }
    ]
});
@endforeach
</script>

@endsection