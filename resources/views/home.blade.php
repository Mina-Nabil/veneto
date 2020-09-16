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
                            <td>{{number_format($fullYearAll['totals']->totalNotes, 2).'('. number_format($fullYearAll['totals']->totalNotes/$fullYearAll['totals']->totalPurch*100, 2,'.','').'%)'}}
                            </td>
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
                    <table id="myTable2" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]">
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
                            <td>{{number_format($fullYearVeneto['totals']->totalCash, 2).'('. number_format($fullYearVeneto['totals']->totalCash/$fullYearVeneto['totals']->totalPurch*100, 2,'.','').'%)'}}
                            </td>
                            <td>{{number_format($fullYearVeneto['totals']->totalNotes, 2).'('. number_format($fullYearVeneto['totals']->totalNotes/$fullYearVeneto['totals']->totalPurch*100, 2,'.','').'%)'}}
                            </td>
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
                    <table id="myTable3" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]">
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
                            <td>{{number_format($fullYearOnline['totals']->totalCash, 2).'('. number_format($fullYearOnline['totals']->totalCash/$fullYearOnline['totals']->totalPurch*100, 2,'.','').'%)'}}
                            </td>
                            <td>{{number_format($fullYearOnline['totals']->totalNotes, 2).'('. number_format($fullYearOnline['totals']->totalNotes/$fullYearOnline['totals']->totalPurch*100, 2,'.','').'%)'}}
                            </td>
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
                    <table id="myTable4" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]">
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
                            <td>{{number_format($fullYearVia['totals']->totalNotes, 2).'('. number_format($fullYearVia['totals']->totalNotes/$fullYearVia['totals']->totalPurch*100, 2,'.','').'%)'}}
                            </td>
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

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Total Production</div>

                <div class="card-body">
                    <table id="myTable5" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]">
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
                            @foreach($prod['months'] as $month)
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
                            <td>{{number_format($fullYearProd['totals']->totalPurch, 2)}}</td>
                            <td>{{number_format($fullYearProd['totals']->totalCash, 2)}}
                                @if($fullYearProd['totals']->totalPurch > 0)
                                {{'('. number_format($fullYearProd['totals']->totalCash/$fullYearProd['totals']->totalPurch*100, 2,'.','').'%)'}}
                            @endif</td>
                            <td>{{number_format($fullYearProd['totals']->totalNotes, 2)}}
                                @if($fullYearProd['totals']->totalPurch > 0)
                                {{'('. number_format($fullYearProd['totals']->totalNotes/$fullYearProd['totals']->totalPurch*100, 2,'.','').'%)'}}
                                @endif
                            </td>
                            <td>{{number_format($fullYearProd['totals']->totalDisc, 2)}}
                                @if($fullYearProd['totals']->totalPurch > 0)
                                {{ '('. number_format($fullYearProd['totals']->totalDisc/$fullYearProd['totals']->totalPurch*100, 2,'.','').'%)'}}
                                @endif
                            </td>
                            <td>{{number_format($fullYearProd['totals']->totalReturn, 2)}}
                            @if($fullYearProd['totals']->totalPurch > 0)
                            {{'('. number_format($fullYearProd['totals']->totalReturn/$fullYearProd['totals']->totalPurch*100, 2,'.', ''). '%)'}}
                            @endif
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
                <div class="card-header">Total Procurement</div>

                <div class="card-body">
                    <table id="myTable6" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]">
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
                            @foreach($proc['months'] as $month)
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
                            <td>{{number_format($fullYearProc['totals']->totalPurch, 2)}}</td>
                            <td>{{number_format($fullYearProc['totals']->totalCash, 2)}}
                                @if($fullYearProc['totals']->totalPurch > 0)
                            {{'('.
                             number_format($fullYearProc['totals']
                             ->totalCash/$fullYearProc['totals']->totalPurch*100, 2,'.','').'%)'}}
                             @endif
                            </td>
                            <td>{{number_format($fullYearProc['totals']->totalNotes, 2)}} 
                                @if($fullYearProc['totals']->totalPurch > 0)
                                {{'('. number_format($fullYearProc['totals']->totalNotes/$fullYearProc['totals']->totalPurch*100, 2,'.','').'%)'  }}
                                @endif
                            </td>
                            <td>{{number_format($fullYearProc['totals']->totalDisc, 2)}}
                                @if($fullYearProc['totals']->totalPurch > 0)
                                {{'('. number_format($fullYearProc['totals']->totalDisc/$fullYearProc['totals']->totalPurch*100, 2,'.','').'%)' }}
                                @endif
                            </td>
                            <td>{{number_format($fullYearProc['totals']->totalReturn, 2)}}
                                @if($fullYearProc['totals']->totalPurch > 0)
                                {{'('.  number_format($fullYearProc['totals']->totalReturn/$fullYearProc['totals']->totalPurch*100, 2,'.', '') . '%)'}}
                                @endif
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
                <div class="card-header">مصاريف مجمعه</div>

                <div class="card-body">
                    <table id="myTable7" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]">
                        <thead>
                            <tr>
                                <th></th>
                                <th>بيان</th>
                                <th>يناير</th>
                                <th>فبراير</th>
                                <th>مارس</th>
                                <th>ابريل</th>
                                <th>مايو</th>
                                <th>يونيو</th>
                                <th>يوليو</th>
                                <th>اغسطس</th>
                                <th>سبتمبر</th>
                                <th>اكتوبر</th>
                                <th>نوفمبر</th>
                                <th>ديسمبر</th>
                                <th>اجمالي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($masareef as $masroof)
                            <tr>
                                <td><strong>{{$masroof['typeName']}}</strong></td>
                                <td><strong>{{$masroof['subTypeName']}}</strong></td>

                                <td>{{number_format($masroof[1] ->totalIn - $masroof[1] ->totalOut, 2)}}</td>
                                <td>{{number_format($masroof[2] ->totalIn - $masroof[2] ->totalOut, 2)}}</td>
                                <td>{{number_format($masroof[3] ->totalIn - $masroof[3] ->totalOut, 2)}}</td>
                                <td>{{number_format($masroof[4] ->totalIn - $masroof[4] ->totalOut, 2)}}</td>
                                <td>{{number_format($masroof[5] ->totalIn - $masroof[5] ->totalOut, 2)}}</td>
                                <td>{{number_format($masroof[6] ->totalIn - $masroof[6] ->totalOut, 2)}}</td>
                                <td>{{number_format($masroof[7] ->totalIn - $masroof[7] ->totalOut, 2)}}</td>
                                <td>{{number_format($masroof[8] ->totalIn - $masroof[8] ->totalOut, 2)}}</td>
                                <td>{{number_format($masroof[9] ->totalIn - $masroof[9] ->totalOut, 2)}}</td>
                                <td>{{number_format($masroof[10]->totalIn - $masroof[10]->totalOut, 2)}}</td>
                                <td>{{number_format($masroof[11]->totalIn - $masroof[11]->totalOut, 2)}}</td>
                                <td>{{number_format($masroof[12]->totalIn - $masroof[12]->totalOut, 2)}}</td>
                                <td><strong>{{number_format($masroof[13]->totalIn - $masroof[13]->totalOut, 2)}}</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <td></td>
                            <td><strong>Totals</strong></td>

                            <td>{{number_format($totals['masareef'][1], 2)}}</td>
                            <td>{{number_format($totals['masareef'][2], 2)}}</td>
                            <td>{{number_format($totals['masareef'][3], 2)}}</td>
                            <td>{{number_format($totals['masareef'][4], 2)}}</td>
                            <td>{{number_format($totals['masareef'][5], 2)}}</td>
                            <td>{{number_format($totals['masareef'][6], 2)}}</td>
                            <td>{{number_format($totals['masareef'][7], 2)}}</td>
                            <td>{{number_format($totals['masareef'][8], 2)}}</td>
                            <td>{{number_format($totals['masareef'][9], 2)}}</td>
                            <td>{{number_format($totals['masareef'][10], 2)}}</td>
                            <td>{{number_format($totals['masareef'][11], 2)}}</td>
                            <td>{{number_format($totals['masareef'][12], 2)}}</td>
                            <td><strong>{{number_format($totals['masareef'][13], 2)}}</strong></td>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js_content')
    <script>

        $(function () {

            var table = $('#myTable3').DataTable({
                "displayLength": 25,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'print',
                        text: 'Print',
                        title: 'Veneto',
                        footer: true,
                        messageTop: "Date: " + formatted,
                        customize: function (win) {
                            $(win.document.body)
                                .prepend('<center><img src="{{asset('images / dark - logo.png')}}" style="position:absolute; margin: auto; ; margin-top: 460px ; left: 0; right: 0; opacity:0.2" /></center>')
                                .css('font-size', '24px')

                            //$('#stampHeader' ).addClass( 'stampHeader' );
                            $(win.document.body).find('table')
                                .css('border', 'solid')
                                .css('margin-top', '20px')
                                .css('font-size', 'inherit');
                            $(win.document.body).find('th')
                                .css('border', 'solid')
                                .css('border', '!important')
                                .css('border-width', '1px')
                                .css('font-size', 'inherit')
                            $(win.document.body).find('td')
                                .css('border', 'solid')
                                .css('border', '!important')
                                .css('border-width', '1px');
                            $(win.document.body).find('tr')
                                .css('border', 'solid')
                                .css('border', '!important')
                                .css('border-width', '1px')
                        }
                    }, {
                        extend: 'excel',
                        title: 'Veneto',
                        footer: true,

                    }
                ]
            }); 
            var table = $('#myTable4').DataTable({
                "displayLength": 25,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'print',
                        text: 'Print',
                        title: 'Veneto',
                        footer: true,
                        messageTop: "Date: " + formatted,
                        customize: function (win) {
                            $(win.document.body)
                                .prepend('<center><img src="{{asset('images / dark - logo.png')}}" style="position:absolute; margin: auto; ; margin-top: 460px ; left: 0; right: 0; opacity:0.2" /></center>')
                                .css('font-size', '24px')

                            //$('#stampHeader' ).addClass( 'stampHeader' );
                            $(win.document.body).find('table')
                                .css('border', 'solid')
                                .css('margin-top', '20px')
                                .css('font-size', 'inherit');
                            $(win.document.body).find('th')
                                .css('border', 'solid')
                                .css('border', '!important')
                                .css('border-width', '1px')
                                .css('font-size', 'inherit')
                            $(win.document.body).find('td')
                                .css('border', 'solid')
                                .css('border', '!important')
                                .css('border-width', '1px');
                            $(win.document.body).find('tr')
                                .css('border', 'solid')
                                .css('border', '!important')
                                .css('border-width', '1px')
                        }
                    }, {
                        extend: 'excel',
                        title: 'Veneto',
                        footer: true,

                    }
                ]
            }); 
            var table = $('#myTable5').DataTable({
                "displayLength": 25,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'print',
                        text: 'Print',
                        title: 'Veneto',
                        footer: true,
                        messageTop: "Date: " + formatted,
                        customize: function (win) {
                            $(win.document.body)
                                .prepend('<center><img src="{{asset('images / dark - logo.png')}}" style="position:absolute; margin: auto; ; margin-top: 460px ; left: 0; right: 0; opacity:0.2" /></center>')
                                .css('font-size', '24px')

                            //$('#stampHeader' ).addClass( 'stampHeader' );
                            $(win.document.body).find('table')
                                .css('border', 'solid')
                                .css('margin-top', '20px')
                                .css('font-size', 'inherit');
                            $(win.document.body).find('th')
                                .css('border', 'solid')
                                .css('border', '!important')
                                .css('border-width', '1px')
                                .css('font-size', 'inherit')
                            $(win.document.body).find('td')
                                .css('border', 'solid')
                                .css('border', '!important')
                                .css('border-width', '1px');
                            $(win.document.body).find('tr')
                                .css('border', 'solid')
                                .css('border', '!important')
                                .css('border-width', '1px')
                        }
                    }, {
                        extend: 'excel',
                        title: 'Veneto',
                        footer: true,

                    }
                ]
            }); 
            var table = $('#myTable6').DataTable({
                "displayLength": 25,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'print',
                        text: 'Print',
                        title: 'Veneto',
                        footer: true,
                        messageTop: "Date: " + formatted,
                        customize: function (win) {
                            $(win.document.body)
                                .prepend('<center><img src="{{asset('images / dark - logo.png')}}" style="position:absolute; margin: auto; ; margin-top: 460px ; left: 0; right: 0; opacity:0.2" /></center>')
                                .css('font-size', '24px')

                            //$('#stampHeader' ).addClass( 'stampHeader' );
                            $(win.document.body).find('table')
                                .css('border', 'solid')
                                .css('margin-top', '20px')
                                .css('font-size', 'inherit');
                            $(win.document.body).find('th')
                                .css('border', 'solid')
                                .css('border', '!important')
                                .css('border-width', '1px')
                                .css('font-size', 'inherit')
                            $(win.document.body).find('td')
                                .css('border', 'solid')
                                .css('border', '!important')
                                .css('border-width', '1px');
                            $(win.document.body).find('tr')
                                .css('border', 'solid')
                                .css('border', '!important')
                                .css('border-width', '1px')
                        }
                    }, {
                        extend: 'excel',
                        title: 'Veneto',
                        footer: true,

                    }
                ]
            }); 
            var table = $('#myTable7').DataTable({
                "displayLength": 25,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'print',
                        text: 'Print',
                        title: 'Veneto',
                        footer: true,
                        messageTop: "Date: " + formatted,
                        customize: function (win) {
                            $(win.document.body)
                                .prepend('<center><img src="{{asset('images / dark - logo.png')}}" style="position:absolute; margin: auto; ; margin-top: 460px ; left: 0; right: 0; opacity:0.2" /></center>')
                                .css('font-size', '24px')

                            //$('#stampHeader' ).addClass( 'stampHeader' );
                            $(win.document.body).find('table')
                                .css('border', 'solid')
                                .css('margin-top', '20px')
                                .css('font-size', 'inherit');
                            $(win.document.body).find('th')
                                .css('border', 'solid')
                                .css('border', '!important')
                                .css('border-width', '1px')
                                .css('font-size', 'inherit')
                            $(win.document.body).find('td')
                                .css('border', 'solid')
                                .css('border', '!important')
                                .css('border-width', '1px');
                            $(win.document.body).find('tr')
                                .css('border', 'solid')
                                .css('border', '!important')
                                .css('border-width', '1px')
                        }
                    }, {
                        extend: 'excel',
                        title: 'Veneto',
                        footer: true,

                    }
                ]
            }); 
       
        });
    </script>
@endsection