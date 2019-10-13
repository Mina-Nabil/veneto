@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Bank Account</h4>
                <h6 class="card-subtitle">Show All Bank Account transactions</h6>
                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Transaction</th>
                                <th>Debit</th>
                                <th>Credit</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ops as $op)
                            <tr 
                            @if(isset($op->BANK_CMNT) && strcmp($op->BANK_CMNT, '')!=0 )
                            style="font-style: italic"
                            @endif
                            title="{{$op->BANK_CMNT}}"
                            >
                                <td>{{$op->BANK_DATE}}</td>
                                <td>{{$op->BANK_NAME}}</td>
                                <td>{{number_format($op->BANK_OUT, 2)}}</td>
                                <td>{{number_format($op->BANK_IN, 2)}}</td>
                                <td>{{number_format($op->BANK_BLNC, 2)}}</td>
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
