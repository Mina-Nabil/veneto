@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class=row>
                    <div class="col-7">
                        <h4 class="card-title">Client Collection Target</h4>
                        <h6 class="card-subtitle">Manage Clients targets for {{$month}} - {{$year}}</h6>
                    </div>
                    @if (!$isHistory)
                    <div class="col-lg-5 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                            <a style="font-family: 'Allerta Stencil'" href="{{url('clients/generate/targets')}}" class="btn btn-success d-none d-lg-block m-l-15">Generate New Clients Targets</a>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]">
                        <thead>
                            <tr>
                                <th>Client Name</th>
                                @if(!$isHistory)
                                <th>Balance</th>
                                @endif
                                <th>Cash Target</th>
                                <th>Bank Target</th>
                                <th>Cash Paid</th>
                                <th>%</th>
                                <th>Bank Paid</th>
                                <th>%</th>
                                @if(!$isHistory)
                                <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($targets as $target)
                            <tr>
                                <td>
                                    <a href="{{url('clients/trans/quick/' . $target->TRGT_CLNT_ID)}}">
                                        {{($target->CLNT_SRNO ) ? $target->CLNT_SRNO.' - '  : ''}}{{$target->CLNT_NAME}}
                                    </a>
                                </td>
                                @if(!$isHistory)
                                <td>{{number_format($target->CLNT_BLNC, 2)}}</td>
                                @endif
                                <td id="moneyCell{{$target->id}}">{{$target->TRGT_MONY}}</td>
                                <td id="bankCell{{$target->id}}">{{$target->TRGT_BANK}}</td>
                                <td>{{number_format($target->cashPaid,2) }}</td>
                                <td>{{($target->TRGT_BANK==0) ? '0%':number_format($target->bankPaid/$target->TRGT_BANK*100,2,'.','').'%'}}</td>
                                <td>{{number_format($target->cashPaid,2) }}</td>
                                <td>{{($target->TRGT_BANK==0) ? '0%':number_format($target->bankPaid/$target->TRGT_BANK*100,2,'.','').'%'}}</td>
                                @if(!$isHistory)
                                <td>
                                    <div class="btn-group">
                                        <button style="padding:.1rem .2rem" type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Action
                                        </button>
                                        <div class="dropdown-menu">
                                            <button class="dropdown-item" data-toggle="modal" data-target="#setTarget{{$target->id}}">Set Targets</button>
                                        </div>
                                    </div>
                                </td>
                                @endif
                            </tr>
                            <div id="setTarget{{$target->id}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Set Target</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="form-group col-md-12 m-t-0">
                                                <h5>Cash Target</h5>
                                                <input type="number" class="form-control form-control-line" id="money{{$target->id}}" value="{{$target->TRGT_MONY}}" required>
                                            </div>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group col-md-12 m-t-0">
                                                <h5>Bank Target</h5>
                                                <input type="number" class="form-control form-control-line" id="bank{{$target->id}}" value="{{$target->TRGT_BANK}}" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                                            <button onclick="setTarget({{$target->id}})" class="btn btn-warning waves-effect waves-light">Submit</button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td><strong>Totals: </strong></td>
                                @if(!$isHistory)
                                <td><strong>{{number_format($totals->balanceTotal, 2)}}</strong></td>
                                @endif
                                <td><strong id=moneyTotal>{{$totals->cashTarget}}</strong></td>
                                <td><strong id=bankTotal>{{$totals->bankTarget}}</strong></td>
                                <td><strong>{{number_format($totals->cashPaid, 2)}}({{($totals->cashTarget==0) ? $totals->cashTarget.'%':number_format($totals->cashPaid/$totals->cashTarget*100,2,'.','').'%'}})</strong>
                                </td>
                                <td><strong>{{number_format($totals->bankPaid, 2)}}({{($totals->bankTarget==0) ? $totals->bankTarget.'%':number_format($totals->bankPaid/$totals->bankTarget*100,2,'.','').'%'}})</strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function IsNumeric(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
    }
       
    function setTarget(id){
        moneyCell = document.getElementById("moneyCell"+id);
        bankCell = document.getElementById("bankCell"+id);

        moneyTarget = document.getElementById('money'+id).value
        bankTarget = document.getElementById('bank'+id).value

        var http = new XMLHttpRequest();  

        var formdata = new FormData();
        formdata.append('id', id);
        formdata.append('money', moneyTarget);
        formdata.append('bank', bankTarget);
        formdata.append('_token', '{{csrf_token()}}');


        http.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                
                if (IsNumeric(this.responseText) ) {

                    moneyCell.innerHTML = moneyTarget
                    bankCell.innerHTML = bankTarget
                    calculateTotals();
                    Swal.fire({
                        text: "Success",
                        icon: "success",
                    });
                } else if (!IsNumeric(this.responseText)) {
                    Swal.fire({
                        text: "ERROR - Please refresh and try again",
                        icon: "error",
                    });
                }
            }
        }
        
    http.open('POST', '{{url("clients/set/targets")}}')
    http.send(formdata, true);
        
        
    } 

    function calculateTotals(){
            let moneyTotal = 0;
            let bankTotal = 0;
 
            moneyCells = document.querySelectorAll('[id^="moneyCell"]');
            bankCells = document.querySelectorAll('[id^="bankCell"]');
            moneyCells.forEach(element => {
                moneyTotal += parseInt(element.innerHTML);
                console.log(element.innerHTML)
            });
            bankCells.forEach(element => {
                bankTotal += parseInt(element.innerHTML);
            });
            document.getElementById('moneyTotal').innerHTML = moneyTotal
            document.getElementById('bankTotal').innerHTML = bankTotal
           
        }
</script>

@endsection