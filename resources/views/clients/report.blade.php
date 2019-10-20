@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Clients Report</h4>
                <h6 class="card-subtitle">Show Clients transactions</h6>
                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Name</th>
                                <th>Sales</th>
                                <th>Cash</th>
                                <th>Notes Payables</th>
                                <th>Discount</th>
                                <th>Return</th>
                                <th>Client Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ops as $op)
                            <tr 
                            @if(isset($op->CLTR_CMNT) && strcmp($op->CLTR_CMNT, '')!=0 )
                            style="font-style: italic"
                            @endif
                            title="{{$op->CLTR_CMNT}}"
                            >
                                <td>{{$op->CLTR_DATE}}</td>
                                <td>
                                    <a href="{{url('clients/trans/quick/' . $op->CLTR_CLNT_ID)}}">
                                        {{$op->CLNT_NAME}}
                                    </a>
                                </td>
                                <td>{{number_format($op->CLTR_SALS_AMNT, 2)}}</td>
                                <td>{{number_format($op->CLTR_CASH_AMNT, 2)}}</td>
                                <td>{{number_format($op->CLTR_NTPY_AMNT, 2)}}</td>
                                <td>{{number_format($op->CLTR_DISC_AMNT, 2)}}</td>
                                <td>{{number_format($op->CLTR_RTRN_AMNT, 2)}}</td>
                                <td>{{number_format($op->CLTR_BLNC, 2)}}</td>
                            </tr> 
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
