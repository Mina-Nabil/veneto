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
                    var url  = '{{url("clients/trans/error")}}';
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
                    var url  = '{{url("clients/trans/unmark")}}';
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
                
                <div class=row>
                    <div class="col-lg-7">
                        @if($isClient)
                        <h4 class="card-title">'{{$client->CLNT_NAME}}' Quick Report</h4>
                        <h6 class="card-subtitle">{{ ($client->CLNT_ADRS) ? ('Client Address: ' . $client->CLNT_ADRS) : ''}} {{($client->CLNT_TELE) ? ' Number: ' . $client->CLNT_TELE : ''}}</h6>
                        @else
                        <h4 class="card-title">Clients Report</h4>
                        <h6 class="card-subtitle">Show Clients transactions</h6>
                        @endif
                    </div>
                    @if($isClient)
                    <div class="col-lg-5 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                            <a style="font-family: 'Allerta Stencil'" href="{{url('sales/show/'.$client->id)}}" class="btn btn-success d-none d-lg-block m-l-15">Client Sales</a>
                        </div>
                    </div>
                    @endif
                </div>
                
                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
                        <thead>
                            <tr>
                                <th>تاريخ</th>
                                <th>وصف</th>
                                @if(!$isClient)
                                <th>اسم</th>
                                @endif
                                <th>عمليه بيع</th>
                                <th>مبيعات</th>
                                <th>نقديه</th>
                                <th>اوراق دفع</th>
                                <th>خصم</th>
                                <th>مرتجع</th>
                                <th>رصيد</th>
                                <th></th>
                                <th><i class="fas fa-times"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ops as $key => $op)
                            <tr
                            @if($op->CLTR_EROR==1)

                                style="background-color: #ffbdbd;"
                                title="Wrong Transaction"
                                
                            @endif
                            
                            >
                                <td>
                                    {{date_format(date_create($op->CLTR_DATE), "d-m-Y")}}
                                </td>
                                <td title="{{$op->CLTR_DESC}}">{{ (strlen($op->CLTR_DESC)>25) ?  substr($op->CLTR_DESC,0,22) . '...' : $op->CLTR_DESC}}</td>
                                @if(!$isClient)
                                <td>
                                    <a href="{{url('clients/trans/quick/' . $op->CLTR_CLNT_ID)}}" title="{{$op->CLNT_NAME}}">
                                        {{ (strlen($op->CLNT_NAME)>12) ?  substr($op->CLNT_NAME,0,12) . '...' : $op->CLNT_NAME}}
                                    </a>
                                </td>
                                @endif
                                <td>
                                    <?php 
                                        $salesArr = explode(' ', $op->CLTR_CMNT) ;
                                        if(isset($op->CLTR_DESC)){
                                            $descArr = explode(' ', $op->CLTR_DESC) ;
                                        }
                                    ?>  
                                        @if(isset($descArr) && $descArr[0]=='Sales' && is_numeric($descArr[1]))
                                            <a href="{{url('/sales/items/' . $descArr[1]) }}">
                                                {{$descArr[1]}}
                                            </a>
                                        @elseif($salesArr[0]=='Sales' && is_numeric($salesArr[1]) && (!isset($salesArr[2]) || $salesArr[2]=='Comment:')  )
                                            <a href="{{url('/sales/items/' . $salesArr[1]) }}">
                                                {{$salesArr[1]}}
                                            </a>
                                        @endif
                                </td>
                                <td>{{number_format($op->CLTR_SALS_AMNT, 1)}}</td>
                                <td>{{number_format($op->CLTR_CASH_AMNT, 1)}}</td>
                                <td>{{number_format($op->CLTR_NTPY_AMNT, 1)}}</td>
                                <td>{{number_format($op->CLTR_DISC_AMNT, 1)}}</td>
                                <td>{{number_format($op->CLTR_RTRN_AMNT, 1)}}</td>
                                <td>{{number_format($op->CLTR_BLNC, 1)}}</td>
                                <td>
                                    @if(isset($op->CLTR_CMNT) && strcmp($op->CLTR_CMNT, '')!=0 )
                                    <button type="button" style="padding:.1rem" class="btn btn-secondary" data-container="body" title="" data-toggle="popover" data-placement="bottom" 
                                        data-content="{{$op->CLTR_CMNT}}" data-original-title="Comment:">
                                    @endif
                                    <i class="far fa-list-alt" ></i>
                                    </button>
                                </td>
                                <td>
                                @if($op->CLTR_EROR==0)
                                    <button style="padding:.1rem" class="btn btn-danger">
                                        <i class="fas fa-exclamation-triangle" onclick="confirmError({{$op->id}}, {{$op->CLTR_EROR}})" ></i>
                                    </button>
                                @else
                                    <button style="padding:.1rem" class="btn btn-success">
                                        <i class="fas fa-exclamation-triangle" onclick="unmarkError({{$op->id}}, {{$op->CLTR_EROR}})" ></i>
                                    </button>
                                @endif
                                </td>
                            </tr
                            > 
                            @endforeach
                        </tbody>
                        @if($isClient && isset($totals))
                        <tfoot>
                        <tr>
                            <td colspan=3><strong>Totals</strong></td>
                            <td><strong>{{number_format($totals->CLTR_SALS_BLNC, 2)}}</strong></td>
                            <td><strong>{{number_format($totals->CLTR_CASH_BLNC, 2)}}</strong></td>
                            <td><strong>{{number_format($totals->CLTR_NTPY_BLNC, 2)}}</strong></td>
                            <td><strong>{{number_format($totals->CLTR_DISC_BLNC, 2)}}</strong></td>
                            <td><strong>{{number_format($totals->CLTR_RTRN_BLNC, 2)}}</strong></td>
                            <td><strong>{{number_format($totals->CLTR_BLNC, 2)}}</strong></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
