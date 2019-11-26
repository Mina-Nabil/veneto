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
                                <th>تاريخ</th>
                                <th>مبيعات</th>
                                <th>نقديه</th>
                                <th>اوراق دفع</th>
                                <th>خصم</th>
                                <th>مرتجع</th>
                                <th>رصيد</th>
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
                                <td>{{number_format($op->CLTR_PRCH_AMNT, 2)}}</td>
                                <td>{{number_format($op->CLTR_CASH_AMNT, 2)}}</td>
                                <td>{{number_format($op->CLTR_NTPY_AMNT, 2)}}</td>
                                <td>{{number_format($op->CLTR_DISC_AMNT, 2)}}</td>
                                <td>{{number_format($op->CLTR_RTRN_AMNT, 2)}}</td>
                                <td>{{number_format($op->CLTR_BLNC, 2)}}</td>
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
                                <td><strong>End: {{number_format($client->CLNT_BLNC, 2)}}</strong></td>
                              
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
