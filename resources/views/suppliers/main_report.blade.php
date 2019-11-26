@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Main Suppliers Report</h4>
                <h6 class="card-subtitle">Show Suppliers totals during the selected period</h6>
                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
                        <thead>
                            <tr>
                                <th>تاريخ</th>
                                <th>رصيد مبدئي</th>
                                <th>مشتريات</th>
                                <th>نقديه</th>
                                <th>اوراق دفع</th>
                                <th>خصم</th>
                                <th>مرتجع</th>
                                <th>رصيد</th>
                                <th>رصيد نهائي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ops as $op)
                            <tr>
                                <td>
                                    <a href="{{url('suppliers/trans/quick/' . $op->id)}}">
                                        {{$op->SUPP_NAME}}
                                    </a>
                                </td>
                                <td>{{number_format($op->SUPP_BLNC - $op->totalPurch + $op->totalCash + $op->totalDisc + $op->totalNotes + $op->totalReturn, 2)}}</td>
                                <td>{{number_format($op->totalPurch, 2)}}</td>
                                <td>{{number_format($op->totalCash, 2)}}</td>
                                <td>{{number_format($op->totalNotes, 2)}}</td>
                                <td>{{number_format($op->totalDisc, 2)}}</td>
                                <td>{{number_format($op->totalReturn, 2)}}</td>
                                <td>{{number_format($op->SUPP_BLNC, 2)}}</td>
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
