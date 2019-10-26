@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{$reportTitle}}</h4>
                <h6 class="card-subtitle">{{$reportDesc}}</h6>
                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Purchase</th>
                                <th>Cash</th>
                                <th>Notes Payables</th>
                                <th>Discount</th>
                                <th>Return</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ops as $op)
                            <tr>
                                <td>
                                @if(isset($op->SPTR_CMNT) && strcmp($op->SPTR_CMNT, '')!=0 )
                                    <button type="button" class="btn btn-secondary" data-container="body" title="" data-toggle="popover" data-placement="bottom" data-content="{{$op->SPTR_CMNT}}" data-original-title="Comment:">
                                @endif 
                                {{$op->SPTR_DATE}}
                                </button>
                                </td>
                                <td>{{number_format($op->SPTR_PRCH_AMNT, 2)}}</td>
                                <td>{{number_format($op->SPTR_CASH_AMNT, 2)}}</td>
                                <td>{{number_format($op->SPTR_NTPY_AMNT, 2)}}</td>
                                <td>{{number_format($op->SPTR_DISC_AMNT, 2)}}</td>
                                <td>{{number_format($op->SPTR_RTRN_AMNT, 2)}}</td>
                                <td>{{number_format($op->SPTR_BLNC, 2)}}</td>
                            </tr> 
                            @endforeach
                            
                        </tbody>
                        <tfoot>
                            <tr>
                                <td><strong>Start Balance: {{number_format($startBalance, 1)}}</strong></td>
                                <td><strong>{{number_format($totals->totalPurch, 2)}} </strong></td>
                                <td><strong>{{number_format($totals->totalCash, 2)}}</strong></td>
                                <td><strong>{{number_format($totals->totalNotes, 2)}}</strong></td>
                                <td><strong>{{number_format($totals->totalDisc, 2)}}</strong></td>
                                <td><strong>{{number_format($totals->totalReturn, 2)}}</strong></td>
                                <td><strong>End: {{number_format($supplier->SUPP_BLNC, 2)}}</strong></td>
                              
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
