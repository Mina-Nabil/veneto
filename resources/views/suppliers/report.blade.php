@extends('layouts.app')

@section('content')

<script>
    function confirmAndGoTo(url, action){
         Swal.fire({
             text: "Are you sure you want to " + action + "?",
             icon: "warning",
             showCancelButton: true,
         }).then((isConfirm) => {
     if(isConfirm.value){
         window.location.href = url;
         }
     });
 }
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
                    var url  = '{{url("suppliers/trans/error")}}';
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
                    var url  = '{{url("suppliers/trans/unmark")}}';
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
                    <div class=col-9>
                        @if($isSupplier)
                        <h4 class="card-title">'{{$supplier->SUPP_NAME}}' Quick Report</h4>
                        @else
                        <h4 class="card-title">Suppliers Report</h4>
                        @endif
                        <h6 class="card-subtitle">Show Suppliers transactions</h6>
                    </div>
                    @if($isSupplier)
                    <div class=col-3>
                        <div class=row>
                            <div class="col-lg-6 align-self-center text-right">
                                <div class="d-flex justify-content-end align-items-center">
                                    <button style="font-family: 'Allerta Stencil'" data-toggle="modal" data-target="#hideTrans" class="btn btn-success d-none d-lg-block m-l-15">Hide</button>
                                </div>
                            </div>
                            <div class="col-lg-6 align-self-center text-right">
                                <div class="d-flex justify-content-start align-items-center">
                                    <button style="font-family: 'Allerta Stencil'" onclick="confirmAndGoTo('{{url('suppliers/trans/show/'.$supplier->id)}}','Show the Suppliers Transactions')"
                                        class="btn btn-success d-none d-lg-block m-l-15">Show</button>
                                </div>
                            </div>
                        </div>
                        <div id="hideTrans" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Hide Transaction</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    </div>
                                    <form action="{{ url('suppliers/trans/hide') }}" method=post>
                                        @csrf
                                        <div class="modal-body">
                                            <input type=hidden name=supplierID value="{{$supplier->id}}">

                                            <div class="form-group col-md-12 m-t-0">
                                                <h5>Date</h5>
                                                <input type="date" class="form-control form-control-line" name=date required>
                                                <small>Hide Transactions till the mentioned date</small>
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-warning waves-effect waves-light">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="table-responsive m-t-40">
                <table id="myTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]">
                    <thead>
                        <tr>
                            <th>تاريخ</th>
                            <th>وصف</th>
                            @if(!$isSupplier)
                            <th>الاسم</th>
                            @endif
                            <th>عمليه شراء</th>
                            <th>مشتريات</th>
                            <th>نقديه</th>
                            <th>اوراق دفع</th>
                            <th>خصم</th>
                            <th>مرتجع</th>
                            <th>رصيد</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php 
                        if(isset($totals)){
                        $totals->SPTR_PRCH_BLNC =0 ;
                        $totals->SPTR_CASH_BLNC =0 ;
                        $totals->SPTR_NTPY_BLNC =0 ;
                        $totals->SPTR_DISC_BLNC =0 ;
                        $totals->SPTR_RTRN_BLNC =0 ;
                        }
                        ?>
                        @foreach($ops as $key => $op)
                        <?php  
                         if(isset($totals)){
                        $totals->SPTR_PRCH_BLNC  += $op->SPTR_PRCH_AMNT ;
                        $totals->SPTR_CASH_BLNC  += $op->SPTR_CASH_AMNT ;
                        $totals->SPTR_NTPY_BLNC  += $op->SPTR_NTPY_AMNT ;
                        $totals->SPTR_DISC_BLNC  += $op->SPTR_DISC_AMNT ;
                        $totals->SPTR_RTRN_BLNC  += $op->SPTR_RTRN_AMNT ;
                         }
                        ?>
                        <tr @if($op->SPTR_EROR==1)

                            style="background-color: #ffbdbd;"
                            title="Wrong Transaction"

                            @endif
                            >
                            <td>
                                {{date_format(date_create($op->SPTR_DATE), "d-m-Y")}}
                            </td>
                            <td title="{{$op->SPTR_DESC}}">{{ (strlen($op->SPTR_DESC)>18) ?  mb_substr($op->SPTR_DESC,0,15, "utf-8") . '...' : $op->SPTR_DESC}}</td>
                            @if(!$isSupplier)
                            <td>
                                <a href="{{url('suppliers/trans/quick/' . $op->SPTR_SUPP_ID)}}" title="{{$op->SUPP_NAME}}">
                                    {{ (strlen($op->SUPP_NAME)>18) ?  mb_substr($op->SUPP_NAME,0,18, "utf-8") . '...' : $op->SUPP_NAME}}
                                </a>
                            </td>
                            @endif
                            <td>
                                <?php 
                                        $commentArr = explode(' ', $op->SPTR_CMNT) ;
                                        if(isset($op->SPTR_DESC))
                                            $descArr = explode(' ', $op->SPTR_DESC) ;
                                    ?>
                                @if( isset($descArr) && $descArr[0] == "Entry" && $descArr[1] == "Serial" && is_numeric($descArr[2]) )
                                <a href="{{url('rawinventory/bytrans/' . $descArr[2])}}" title="{{$descArr[2]}}">
                                    {{mb_substr($descArr[2],0,4, "utf-8") . '..' . mb_substr($descArr[2], strlen($descArr[2])-4, strlen($descArr[2])-1 , "utf-8") }}
                                    <a>
                                        @elseif( $commentArr[0] == "Entry" && $commentArr[1] == "Serial" && is_numeric($commentArr[2]) )
                                        <a href="{{url('rawinventory/bytrans/' . $commentArr[2])}}" title="{{$commentArr[2]}}">
                                            {{mb_substr($commentArr[2],0,4, "utf-8") . '..' . mb_substr($commentArr[2], strlen($commentArr[2])-4, strlen($commentArr[2])-1, "utf-8") }}
                                            <a>
                                                @endif

                            </td>
                            <td>{{number_format($op->SPTR_PRCH_AMNT, 2)}}</td>
                            <td>{{number_format($op->SPTR_CASH_AMNT, 2)}}</td>
                            <td>{{number_format($op->SPTR_NTPY_AMNT, 2)}}</td>
                            <td>{{number_format($op->SPTR_DISC_AMNT, 2)}}</td>
                            <td>{{number_format($op->SPTR_RTRN_AMNT, 2)}}</td>
                            <td>{{number_format($op->SPTR_BLNC, 2)}}</td>
                            <td>
                                @if(isset($op->SPTR_CMNT) && strcmp($op->SPTR_CMNT, '')!=0 )
                                <button type="button" style="padding:.1rem" class="btn btn-secondary" data-container="body" title="" data-toggle="popover" data-placement="bottom"
                                    data-content="{{$op->SPTR_CMNT}}" data-original-title="Comment:">
                                    @endif
                                    <i class="far fa-list-alt"></i>
                                </button>
                                @if($op->SPTR_EROR==0)
                                <button style="padding:.1rem" class="btn btn-success">
                                    <i class="fas fa-exclamation-triangle" onclick="confirmError({{$op->id}}, {{$op->SPTR_EROR}})"></i>
                                </button>
                                @else
                                <button style="padding:.1rem" class="btn btn-danger">
                                    <i class="fas fa-exclamation-triangle" onclick="unmarkError({{$op->id}}, {{$op->SPTR_EROR}})"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    @if($isSupplier && isset($totals))
                    <tfoot>
                        <tr>
                            <td></td>
                            <td><strong>Totals</strong></td>
                            <td></td>
                            <td><strong>{{number_format($totals->SPTR_PRCH_BLNC, 2)}}</strong></td>
                            <td><strong>{{number_format($totals->SPTR_CASH_BLNC, 2)}}</strong></td>
                            <td><strong>{{number_format($totals->SPTR_NTPY_BLNC, 2)}}</strong></td>
                            <td><strong>{{number_format($totals->SPTR_DISC_BLNC, 2)}}</strong></td>
                            <td><strong>{{number_format($totals->SPTR_RTRN_BLNC, 2)}}</strong></td>
                            <td><strong>{{number_format($totals->SPTR_BLNC, 2)}}</strong></td>
                            <td></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@endsection