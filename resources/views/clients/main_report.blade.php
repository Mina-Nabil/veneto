@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Main Clients Report</h4>
                <h6 class="card-subtitle">Show Clients totals during the selected period</h6>
                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Start Balance</th>
                                <th>Sales</th>
                                <th>Cash</th>
                                <th>Notes Payables</th>
                                <th>Discount</th>
                                <th>Return</th>
                                <th>End Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ops as $op)
                            <tr>
                                <td>
                                    <a href="{{url('clients/trans/quick/' . $op->id)}}">
                                        {{$op->CLNT_NAME}}
                                    </a>
                                </td>
                                <td>{{number_format($op->CLNT_BLNC - $op->totalPurch + $op->totalCash + $op->totalDisc + $op->totalNotes + $op->totalReturn, 2)}}</td>
                                <td>{{number_format($op->totalPurch, 2)}}</td>
                                <td>{{number_format($op->totalCash, 2)}}</td>
                                <td>{{number_format($op->totalNotes, 2)}}</td>
                                <td>{{number_format($op->totalDisc, 2)}}</td>
                                <td>{{number_format($op->totalReturn, 2)}}</td>
                                <td>{{number_format($op->CLNT_BLNC, 2)}}</td>
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
