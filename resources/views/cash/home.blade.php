@extends('layouts.app')

@section('content')

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
                    var url  = '{{url("cash/error")}}';
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
                    var url  = '{{url("cash/unmark")}}';
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
    
    </script>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Cash Account</h4>
                <h6 class="card-subtitle">Show All Cash Account transactions</h6>
                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
                        <thead>
                            <tr>
                                <th>TR#</th>
                                <th>تاريخ</th>
                                <th>وصف</th>
                                <th>مدين</th>
                                <th>دائن</th>
                                <th>رصيد</th>
                                <th></th>
                                @if(isset($report) && !$report)
                                <th><i class="fas fa-times"></i></th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ops as $op)
                            <tr
                            @if($op->CASH_EROR==1)

                                style="background-color: #ffbdbd;"
                                title="Wrong Transaction"
                                
                            @endif
                            >
                                <td>{{$op->id}}</td>
                                <td>
                                    {{date_format(date_create($op->CASH_DATE), "d-m-Y")}}   
                                </td>
                                <td>{{$op->CASH_NAME}}</td>
                                <td>{{number_format($op->CASH_OUT, 2)}}</td>
                                <td>{{number_format($op->CASH_IN, 2)}}</td>
                                <td>{{number_format($op->CASH_BLNC, 2)}}</td>
                                <td>
                                    @if(isset($op->CASH_CMNT) && strcmp($op->CASH_CMNT, '')!=0 )
                                        <button type="button"  style="padding:.1rem"  class="btn btn-secondary" data-container="body" title="" data-toggle="popover" data-placement="bottom" data-content="{{$op->CASH_CMNT}}" data-original-title="Comment:">
                                    @endif
                                    <i class="fas fa-list-alt"></i>
                                    </button>
                                </td>
                                @if(isset($report) && !$report)
                                @if($op->CASH_EROR==0)
                                <td><button style="padding:.1rem" class="btn btn-danger">
                                    <i class="fas fa-exclamation-triangle" onclick="confirmError({{$op->id}}, {{$op->CASH_EROR}})" ></i>
                                </button></td>
                                @else
                                <td><button style="padding:.1rem" class="btn btn-success">
                                    <i class="fas fa-exclamation-triangle" onclick="unmarkError({{$op->id}}, {{$op->CASH_EROR}})" ></i>
                                </button></td>
                                @endif
                                @endif
                            </tr
                            > 
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
