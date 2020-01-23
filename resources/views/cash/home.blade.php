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
        } else if(errorState==2){
            Swal.fire({
                title: 'Transaction already is marked as an Error Correction',
                text: "You won't be able to mark this!",
                icon: 'warning',
            })
        } else {
            Swal.fire({
                title: 'Transaction Error, Are you sure?',
                text: "You won't be able to revert this!",
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
                                text: 'New record added to counter the error, please refresh!',
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
                                <th>رقم المعاملات</th>
                                <th>مدين</th>
                                <th>دائن</th>
                                <th>رصيد</th>
                                <th>Comment</th>
                                @if(isset($report) && !$report)
                                <th>Error</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ops as $op)
                            <tr
                            @if($op->CASH_EROR==1)

                                style="background-color: lightcoral;"
                                title="Wrong Transaction"

                            @elseif($op->CASH_EROR==2)

                                style="background-color: lightgoldenrodyellow; color:#02587e"
                                title="Error Correction Transaction"
                                
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
                                <td><button style="padding:.1rem" class="btn btn-danger">
                                    <i class="fas fa-exclamation-triangle" onclick="confirmError({{$op->id}}, {{$op->CASH_EROR}})" ></i>
                                </button></td>
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
