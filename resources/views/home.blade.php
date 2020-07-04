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
                            @foreach($all['months'] as $month)
                            <tr>
                                <td>{{$month['monthName']}}</td>
                                <td><strong>{{number_format($month['totalBalance'] - $month['totals']->totalPurch + $month['totals']->totalCash + $month['totals']->totalNotes + $month['totals']->totalDisc + $month['totals']->totalReturn , 2)}}</strong>
                                </td>
                                <td>{{number_format($month['totals']->totalPurch, 2)}}</td>
                                <td>{{number_format($month['totals']->totalCash, 2)}}</td>
                                <td>{{number_format($month['totals']->totalNotes, 2)}}</td>
                                <td>{{number_format($month['totals']->totalDisc, 2)}}</td>
                                <td>{{number_format($month['totals']->totalReturn, 2)}}</td>
                                <td><strong>{{number_format($month['totalBalance'], 2)}}</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <td></td>
                            <td><strong>Totals:</strong></td>
                            <td>{{number_format($fullYearAll['totals']->totalPurch, 2)}}</td>
                            <td>{{number_format($fullYearAll['totals']->totalCash, 2).'('. number_format($fullYearAll['totals']->totalCash/$fullYearAll['totals']->totalPurch*100, 2,'.','').'%)'}}</td>
                            <td>{{number_format($fullYearAll['totals']->totalNotes, 2).'('. number_format($fullYearAll['totals']->totalNotes/$fullYearAll['totals']->totalPurch*100, 2,'.','').'%)'}}</td>
                            <td>{{number_format($fullYearAll['totals']->totalDisc, 2) . '('. number_format($fullYearAll['totals']->totalDisc/$fullYearAll['totals']->totalPurch*100, 2,'.','').'%)'}}
                            </td>
                            <td>{{number_format($fullYearAll['totals']->totalReturn, 2).'('. number_format($fullYearAll['totals']->totalReturn/$fullYearAll['totals']->totalPurch*100, 2,'.', ''). '%)'}}
                            </td>
                            <td></td>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Total Veneto</div>

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
                            @foreach($veneto['months'] as $month)
                            <tr>
                                <td>{{$month['monthName']}}</td>
                                <td><strong>{{number_format($month['totalBalance'] - $month['totals']->totalPurch + $month['totals']->totalCash + $month['totals']->totalNotes + $month['totals']->totalDisc + $month['totals']->totalReturn , 2)}}</strong>
                                </td>
                                <td>{{number_format($month['totals']->totalPurch, 2)}}</td>
                                <td>{{number_format($month['totals']->totalCash, 2)}}</td>
                                <td>{{number_format($month['totals']->totalNotes, 2)}}</td>
                                <td>{{number_format($month['totals']->totalDisc, 2)}}</td>
                                <td>{{number_format($month['totals']->totalReturn, 2)}}</td>
                                <td><strong>{{number_format($month['totalBalance'], 2)}}</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <td></td>
                            <td><strong>Totals:</strong></td>
                            <td>{{number_format($fullYearVeneto['totals']->totalPurch, 2)}}</td>
                            <td>{{number_format($fullYearVeneto['totals']->totalCash, 2).'('. number_format($fullYearVeneto['totals']->totalCash/$fullYearVeneto['totals']->totalPurch*100, 2,'.','').'%)'}}</td>
                            <td>{{number_format($fullYearVeneto['totals']->totalNotes, 2).'('. number_format($fullYearVeneto['totals']->totalNotes/$fullYearVeneto['totals']->totalPurch*100, 2,'.','').'%)'}}</td>
                            <td>{{number_format($fullYearVeneto['totals']->totalDisc, 2) . '('. number_format($fullYearVeneto['totals']->totalDisc/$fullYearVeneto['totals']->totalPurch*100, 2,'.','').'%)'}}
                            </td>
                            <td>{{number_format($fullYearVeneto['totals']->totalReturn, 2).'('. number_format($fullYearVeneto['totals']->totalReturn/$fullYearVeneto['totals']->totalPurch*100, 2,'.', ''). '%)'}}
                            </td>
                            <td></td>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Total Online</div>

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
                            @foreach($online['months'] as $month)
                            <tr>
                                <td>{{$month['monthName']}}</td>
                                <td><strong>{{number_format($month['totalBalance'] - $month['totals']->totalPurch + $month['totals']->totalCash + $month['totals']->totalNotes + $month['totals']->totalDisc + $month['totals']->totalReturn , 2)}}</strong>
                                </td>
                                <td>{{number_format($month['totals']->totalPurch, 2)}}</td>
                                <td>{{number_format($month['totals']->totalCash, 2)}}</td>
                                <td>{{number_format($month['totals']->totalNotes, 2)}}</td>
                                <td>{{number_format($month['totals']->totalDisc, 2)}}</td>
                                <td>{{number_format($month['totals']->totalReturn, 2)}}</td>
                                <td><strong>{{number_format($month['totalBalance'], 2)}}</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <td></td>
                            <td><strong>Totals:</strong></td>
                            <td>{{number_format($fullYearOnline['totals']->totalPurch, 2)}}</td>
                            <td>{{number_format($fullYearOnline['totals']->totalCash, 2).'('. number_format($fullYearOnline['totals']->totalCash/$fullYearOnline['totals']->totalPurch*100, 2,'.','').'%)'}}</td>
                            <td>{{number_format($fullYearOnline['totals']->totalNotes, 2).'('. number_format($fullYearOnline['totals']->totalNotes/$fullYearOnline['totals']->totalPurch*100, 2,'.','').'%)'}}</td>
                            <td>{{number_format($fullYearOnline['totals']->totalDisc, 2) . '('. number_format($fullYearOnline['totals']->totalDisc/$fullYearOnline['totals']->totalPurch*100, 2,'.','').'%)'}}
                            </td>
                            <td>{{number_format($fullYearOnline['totals']->totalReturn, 2).'('. number_format($fullYearOnline['totals']->totalReturn/$fullYearOnline['totals']->totalPurch*100, 2,'.', ''). '%)'}}
                            </td>
                            <td></td>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Total Via Veneto</div>

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
                            @foreach($via['months'] as $month)
                            <tr>
                                <td>{{$month['monthName']}}</td>
                                <td><strong>{{number_format($month['totalBalance'] - $month['totals']->totalPurch + $month['totals']->totalCash + $month['totals']->totalNotes + $month['totals']->totalDisc + $month['totals']->totalReturn , 2)}}</strong>
                                </td>
                                <td>{{number_format($month['totals']->totalPurch, 2)}}</td>
                                <td>{{number_format($month['totals']->totalCash, 2)}}</td>
                                <td>{{number_format($month['totals']->totalNotes, 2)}}</td>
                                <td>{{number_format($month['totals']->totalDisc, 2)}}</td>
                                <td>{{number_format($month['totals']->totalReturn, 2)}}</td>
                                <td><strong>{{number_format($month['totalBalance'], 2)}}</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <td></td>
                            <td><strong>Totals:</strong></td>
                            <td>{{number_format($fullYearVia['totals']->totalPurch, 2)}}</td>
                            <td>{{number_format($fullYearVia['totals']->totalCash, 2).'('. number_format($fullYearVia['totals']->totalCash/$fullYearVia['totals']->totalPurch*100, 2,'.','').'%)'}}</td>
                            <td>{{number_format($fullYearVia['totals']->totalNotes, 2).'('. number_format($fullYearVia['totals']->totalNotes/$fullYearVia['totals']->totalPurch*100, 2,'.','').'%)'}}</td>
                            <td>{{number_format($fullYearVia['totals']->totalDisc, 2) . '('. number_format($fullYearVia['totals']->totalDisc/$fullYearVia['totals']->totalPurch*100, 2,'.','').'%)'}}
                            </td>
                            <td>{{number_format($fullYearVia['totals']->totalReturn, 2).'('. number_format($fullYearVia['totals']->totalReturn/$fullYearVia['totals']->totalPurch*100, 2,'.', ''). '%)'}}
                            </td>
                            <td></td>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection