@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Total Sales</div>

                <div class="card-body">
                    <table id="myTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]">
                        <thead>
                            <tr>
                                <th>شهر</th>
                                <th>رصيد مبدئي</th>
                                <th>مبيعات</th>
                                <th>نقديه</th>
                                <th>اوراق دفع</th>
                                <th>خصم</th>
                                <th>مرتجع</th>
                                <th>رصيد نهائي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($months as $month)
                            <tr>
                                <td>{{$month['monthName']}}</td>
                                <td><strong>{{number_format($month['totalBalance'] - $month['totals']->totalPurch + $month['totals']->totalCash + $month['totals']->totalNotes + $month['totals']->totalDisc + $month['totals']->totalReturn , 1)}}</strong></td>
                                <td>{{number_format($month['totals']->totalPurch, 2)}}</td>
                                <td>{{number_format($month['totals']->totalCash, 2)}}</td>
                                <td>{{number_format($month['totals']->totalNotes, 2)}}</td>
                                <td>{{number_format($month['totals']->totalDisc, 2)}}</td>
                                <td>{{number_format($month['totals']->totalReturn, 2)}}</td>
                                <td><strong>{{number_format($month['totalBalance'], 2)}}</strong></td>
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