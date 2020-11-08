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
                                <th>#</th>
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
                                <td>{{$op->CLNT_SRNO}}</td>
                                <td>
                                    <a href="{{url('clients/trans/quick/' . $op->id)}}">
                                        {{$op->CLNT_NAME}}
                                    </a>
                                </td>
                                <td>{{number_format( (isset($ops['balances'][$op->id])) ? ($ops['balances'][$op->id] - $op->totalPurch + $op->totalCash + $op->totalDisc + $op->totalNotes + $op->totalReturn : $op->CLTR_BLNC), 2)}}</td>
                                <td>{{number_format($op->totalPurch ?? 0, 2)}}</td>
                                <td>{{number_format($op->totalCash ?? 0, 2)}}</td>
                                <td>{{number_format($op->totalNotes ?? 0, 2)}}</td>
                                <td>{{number_format($op->totalDisc ?? 0, 2)}}</td>
                                <td>{{number_format($op->totalReturn ?? 0, 2)}}</td>
                                <td>{{number_format($ops['balances'][$op->id]  ?? $op->CLTR_BLNC, 2)}}</td>
                            </tr>
                            @endforeach
                       

                            @if(isset($ops['totals']))

                            <tr class="table-info">
                                <td></td>
                                <td><strong>Veneto Totals</strong></td>
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

                            @foreach($viaVenetoOps['data'] as $op)
                            <tr>
                                <td>{{$op->CLNT_SRNO}}</td>
                                <td>
                                    <a href="{{url('clients/trans/quick/' . $op->id)}}">
                                        {{$op->CLNT_NAME}}
                                    </a>
                                </td>
                                <td>{{number_format($viaVenetoOps['balances'][$op->id] - $op->totalPurch + $op->totalCash + $op->totalDisc + $op->totalNotes + $op->totalReturn, 2)}}</td>
                                <td>{{number_format($op->totalPurch, 2)}}</td>
                                <td>{{number_format($op->totalCash, 2)}}</td>
                                <td>{{number_format($op->totalNotes, 2)}}</td>
                                <td>{{number_format($op->totalDisc, 2)}}</td>
                                <td>{{number_format($op->totalReturn, 2)}}</td>
                                <td>{{number_format($viaVenetoOps['balances'][$op->id], 2)}}</td>
                            </tr>
                            @endforeach
                            @foreach($viaVenetoOps['others'] as $op)
                            <tr>
                                <td>{{$op->CLNT_SRNO}}</td>
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

                            @if(isset($viaVenetoOps['onlineTotals']))

                            <tr class="table-info">
                                <td></td>
                                <td><strong>Via Veneto Totals</strong></td>
                                <td><strong>{{number_format($viaVenetoOps['totalBalance'] - $viaVenetoOps['onlineTotals']->totalPurch + $viaVenetoOps['onlineTotals']->totalCash + $viaVenetoOps['onlineTotals']->totalDisc + $viaVenetoOps['onlineTotals']->totalNotes + $viaVenetoOps['onlineTotals']->totalReturn, 2)}}</strong>
                                </td>
                                <td><strong>{{number_format($viaVenetoOps['onlineTotals']->totalPurch, 2)}}</strong></td>
                                <td><strong>{{number_format($viaVenetoOps['onlineTotals']->totalCash, 2)}}</strong></td>
                                <td><strong>{{number_format($viaVenetoOps['onlineTotals']->totalNotes, 2)}}</strong></td>
                                <td><strong>{{number_format($viaVenetoOps['onlineTotals']->totalDisc, 2)}}</strong></td>
                                <td><strong>{{number_format($viaVenetoOps['onlineTotals']->totalReturn, 2)}}</strong></td>
                                <td><strong>{{number_format($viaVenetoOps['totalBalance'], 2)}}</strong></td>
                            </tr>

                            @endif

                            @foreach($onlineOps['data'] as $op)
                            <tr>
                                <td>{{$op->CLNT_SRNO}}</td>
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
                            @foreach($onlineOps['others'] as $op)
                            <tr>
                                <td>{{$op->CLNT_SRNO}}</td>
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
                                <td></td>
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

                            @foreach($prodOps['data'] as $op)
                            <tr>
                                <td>{{$op->CLNT_SRNO}}</td>
                                <td>
                                    <a href="{{url('clients/trans/quick/' . $op->id)}}">
                                        {{$op->CLNT_NAME}}
                                    </a>
                                </td>
                                <td>{{number_format($prodOps['balances'][$op->id] - $op->totalPurch + $op->totalCash + $op->totalDisc + $op->totalNotes + $op->totalReturn, 2)}}</td>
                                <td>{{number_format($op->totalPurch, 2)}}</td>
                                <td>{{number_format($op->totalCash, 2)}}</td>
                                <td>{{number_format($op->totalNotes, 2)}}</td>
                                <td>{{number_format($op->totalDisc, 2)}}</td>
                                <td>{{number_format($op->totalReturn, 2)}}</td>
                                <td>{{number_format($prodOps['balances'][$op->id], 2)}}</td>
                            </tr>
                            @endforeach
                            @foreach($prodOps['others'] as $op)
                            <tr>
                                <td>{{$op->CLNT_SRNO}}</td>
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

                            @if(isset($prodOps['onlineTotals']))

                            <tr class="table-info">
                                <td></td>
                                <td><strong>Production Totals</strong></td>
                                <td><strong>{{number_format($prodOps['totalBalance'] - $prodOps['onlineTotals']->totalPurch + $prodOps['onlineTotals']->totalCash + $prodOps['onlineTotals']->totalDisc + $prodOps['onlineTotals']->totalNotes + $prodOps['onlineTotals']->totalReturn, 2)}}</strong>
                                </td>
                                <td><strong>{{number_format($prodOps['onlineTotals']->totalPurch, 2)}}</strong></td>
                                <td><strong>{{number_format($prodOps['onlineTotals']->totalCash, 2)}}</strong></td>
                                <td><strong>{{number_format($prodOps['onlineTotals']->totalNotes, 2)}}</strong></td>
                                <td><strong>{{number_format($prodOps['onlineTotals']->totalDisc, 2)}}</strong></td>
                                <td><strong>{{number_format($prodOps['onlineTotals']->totalReturn, 2)}}</strong></td>
                                <td><strong>{{number_format($prodOps['totalBalance'], 2)}}</strong></td>
                            </tr>

                            @endif

                            @foreach($procOps['data'] as $op)
                            <tr>
                                <td>{{$op->CLNT_SRNO}}</td>
                                <td>
                                    <a href="{{url('clients/trans/quick/' . $op->id)}}">
                                        {{$op->CLNT_NAME}}
                                    </a>
                                </td>
                                <td>{{number_format($procOps['balances'][$op->id] - $op->totalPurch + $op->totalCash + $op->totalDisc + $op->totalNotes + $op->totalReturn, 2)}}</td>
                                <td>{{number_format($op->totalPurch, 2)}}</td>
                                <td>{{number_format($op->totalCash, 2)}}</td>
                                <td>{{number_format($op->totalNotes, 2)}}</td>
                                <td>{{number_format($op->totalDisc, 2)}}</td>
                                <td>{{number_format($op->totalReturn, 2)}}</td>
                                <td>{{number_format($procOps['balances'][$op->id], 2)}}</td>
                            </tr>
                            @endforeach
                            @foreach($procOps['others'] as $op)
                            <tr>
                                <td>{{$op->CLNT_SRNO}}</td>
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

                            @if(isset($procOps['onlineTotals']))

                            <tr class="table-info">
                                <td></td>
                                <td><strong>Procurment Totals</strong></td>
                                <td><strong>{{number_format($procOps['totalBalance'] - $procOps['onlineTotals']->totalPurch + $procOps['onlineTotals']->totalCash + $procOps['onlineTotals']->totalDisc + $procOps['onlineTotals']->totalNotes + $procOps['onlineTotals']->totalReturn, 2)}}</strong>
                                </td>
                                <td><strong>{{number_format($procOps['onlineTotals']->totalPurch, 2)}}</strong></td>
                                <td><strong>{{number_format($procOps['onlineTotals']->totalCash, 2)}}</strong></td>
                                <td><strong>{{number_format($procOps['onlineTotals']->totalNotes, 2)}}</strong></td>
                                <td><strong>{{number_format($procOps['onlineTotals']->totalDisc, 2)}}</strong></td>
                                <td><strong>{{number_format($procOps['onlineTotals']->totalReturn, 2)}}</strong></td>
                                <td><strong>{{number_format($procOps['totalBalance'], 2)}}</strong></td>
                            </tr>

                            @endif





                        </tbody>
                        <tfoot>
                            @if(isset($koloTotals))

                            <tr class="table-info">
                                <td><strong>General Totals</strong></td>
                                <td><strong>{{number_format($koloTotals['totalBalance'] - $koloTotals['totals']->totalPurch + $koloTotals['totals']->totalCash + $koloTotals['totals']->totalDisc + $koloTotals['totals']->totalNotes + $koloTotals['totals']->totalReturn, 2)}}</strong>
                                </td>
                                <td><strong>{{number_format($koloTotals['totals']->totalPurch, 2)}}</strong></td>
                                <td><strong>{{number_format($koloTotals['totals']->totalCash, 2)}}</strong></td>
                                <td><strong>{{number_format($koloTotals['totals']->totalNotes, 2)}}</strong></td>
                                <td><strong>{{number_format($koloTotals['totals']->totalDisc, 2)}}</strong></td>
                                <td><strong>{{number_format($koloTotals['totals']->totalReturn, 2)}}</strong></td>
                                <td><strong>{{number_format($koloTotals['totalBalance'], 2)}}</strong></td>
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