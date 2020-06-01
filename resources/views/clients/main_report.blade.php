@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Main Clients Report</h4>
                <h6 class="card-subtitle">Show Clients totals during the selected period without online clients</h6>
                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]">
                        <thead>
                            <tr>
                                <th>اسم</th>
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
                            @foreach($ops['data'] as $op)
                            <tr>
                                <td>
                                    <a href="{{url('clients/trans/quick/' . $op->id)}}">
                                        {{$op->CLNT_NAME}}
                                    </a>
                                </td>
                                <td>{{number_format($ops['balances'][$op->id] - $op->totalPurch + $op->totalCash + $op->totalDisc + $op->totalNotes + $op->totalReturn, 2)}}</td>
                                <td>{{number_format($op->totalPurch, 2)}}</td>
                                <td>{{number_format($op->totalCash, 2)}}</td>
                                <td>{{number_format($op->totalNotes, 2)}}</td>
                                <td>{{number_format($op->totalDisc, 2)}}</td>
                                <td>{{number_format($op->totalReturn, 2)}}</td>
                                <td>{{number_format($ops['balances'][$op->id], 2)}}</td>
                            </tr>
                            @endforeach
                            @foreach($ops['others'] as $op)
                            <tr>
                                <td>
                                    <a href="{{url('clients/trans/quick/' . $op->id)}}">
                                        {{$op->CLNT_NAME}}
                                    </a>
                                </td>
                                <td>{{number_format($op->CLTR_BLNC, 2)}}</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>{{number_format($op->CLTR_BLNC, 2)}}</td>
                            </tr>
                            @endforeach

                            @if(isset($ops['totals']))

                            <tr class="table-info">
                                <td><strong>Offline Totals</strong></td>
                                <td><strong>{{number_format($ops['totalBalance'] - $ops['totals']->totalPurch + $ops['totals']->totalCash + $ops['totals']->totalDisc + $ops['totals']->totalNotes + $ops['totals']->totalReturn, 2)}}</strong>
                                </td>
                                <td><strong>{{number_format($ops['totals']->totalPurch, 2)}}</strong></td>
                                <td><strong>{{number_format($ops['totals']->totalCash, 2)}}</strong></td>
                                <td><strong>{{number_format($ops['totals']->totalNotes, 2)}}</strong></td>
                                <td><strong>{{number_format($ops['totals']->totalDisc, 2)}}</strong></td>
                                <td><strong>{{number_format($ops['totals']->totalReturn, 2)}}</strong></td>
                                <td><strong>{{number_format($ops['totalBalance'], 2)}}</strong></td>
                            </tr>

                            @endif


                            @foreach($onlineOps['data'] as $op)
                            <tr>
                                <td>
                                    <a href="{{url('clients/trans/quick/' . $op->id)}}">
                                        {{$op->CLNT_NAME}}
                                    </a>
                                </td>
                                <td>{{number_format($onlineOps['balances'][$op->id] - $op->totalPurch + $op->totalCash + $op->totalDisc + $op->totalNotes + $op->totalReturn, 2)}}</td>
                                <td>{{number_format($op->totalPurch, 2)}}</td>
                                <td>{{number_format($op->totalCash, 2)}}</td>
                                <td>{{number_format($op->totalNotes, 2)}}</td>
                                <td>{{number_format($op->totalDisc, 2)}}</td>
                                <td>{{number_format($op->totalReturn, 2)}}</td>
                                <td>{{number_format($onlineOps['balances'][$op->id], 2)}}</td>
                            </tr>
                            @endforeach
                            @foreach($onlineOps['onlineOthers'] as $op)
                            <tr>
                                <td>
                                    <a href="{{url('clients/trans/quick/' . $op->id)}}">
                                        {{$op->CLNT_NAME}}
                                    </a>
                                </td>
                                <td>{{number_format($op->CLTR_BLNC, 2)}}</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>{{number_format($op->CLTR_BLNC, 2)}}</td>
                            </tr>
                            @endforeach

                            @if(isset($onlineOps['onlineTotals']))

                            <tr class="table-info">
                                <td><strong>Online Totals</strong></td>
                                <td><strong>{{number_format($onlineOps['totalBalance'] - $onlineOps['onlineTotals']->totalPurch + $onlineOps['onlineTotals']->totalCash + $onlineOps['onlineTotals']->totalDisc + $onlineOps['onlineTotals']->totalNotes + $onlineOps['onlineTotals']->totalReturn, 2)}}</strong>
                                </td>
                                <td><strong>{{number_format($onlineOps['onlineTotals']->totalPurch, 2)}}</strong></td>
                                <td><strong>{{number_format($onlineOps['onlineTotals']->totalCash, 2)}}</strong></td>
                                <td><strong>{{number_format($onlineOps['onlineTotals']->totalNotes, 2)}}</strong></td>
                                <td><strong>{{number_format($onlineOps['onlineTotals']->totalDisc, 2)}}</strong></td>
                                <td><strong>{{number_format($onlineOps['onlineTotals']->totalReturn, 2)}}</strong></td>
                                <td><strong>{{number_format($onlineOps['totalBalance'], 2)}}</strong></td>
                            </tr>

                            @endif
                        </tbody>
                        <tfoot>
                            @if(isset($koloTotals))

                            <tr class="table-info">
                                <td><strong>General Totals</strong></td>
                                <td><strong>{{number_format($koloTotals->totalBalance - $koloTotals->totalPurch + $koloTotals->totalCash + $koloTotals->totalDisc + $koloTotals->totalNotes + $koloTotals->totalReturn, 2)}}</strong>
                                </td>
                                <td><strong>{{number_format($koloTotals->totalPurch, 2)}}</strong></td>
                                <td><strong>{{number_format($koloTotals->totalCash, 2)}}</strong></td>
                                <td><strong>{{number_format($koloTotals->totalNotes, 2)}}</strong></td>
                                <td><strong>{{number_format($koloTotals->totalDisc, 2)}}</strong></td>
                                <td><strong>{{number_format($koloTotals->totalReturn, 2)}}</strong></td>
                                <td><strong>{{number_format($koloTotals->totalBalance, 2)}}</strong></td>
                            </tr>

                            @endif
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection