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
                                <th>وصف</th>
                                <th>مبيعات</th>
                                <th>نقديه</th>
                                <th>اوراق دفع</th>
                                <th>خصم</th>
                                <th>مرتجع</th>
                                <th>رصيد</th>
                                <th>Comment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ops as $op)
                            <tr >
                                <td>                                
                                    {{date_format(date_create($op->CLTR_DATE), "d-m-Y")}}
                                </td>
                                <td>
                                    <?php 
                                    $salesArr = explode(' ', $op->CLTR_CMNT) ;
                                    if(isset($op->CLTR_DESC)){
                                        $descArr = explode(' ', $op->CLTR_DESC) ;
                                    }
                                ?>  
                                    @if(isset($descArr) && $descArr[0]=='Sales' && is_numeric($descArr[1]))
                                        <a href="{{url('/sales/items/' . $descArr[1]) }}">
                                            مبيعات {{$descArr[1]}}
                                        </a>
                                    @elseif($salesArr[0]=='Sales' && is_numeric($salesArr[1]) && (!isset($salesArr[2]) || $salesArr[2]=='Comment:')  )
                                        <a href="{{url('/sales/items/' . $salesArr[1]) }}">
                                            مبيعات {{$salesArr[1]}}
                                        </a>

                                    @else
                                    {{ (strlen($op->CLTR_DESC)>25) ?  mb_substr($op->CLTR_DESC,0,22, "utf-8") . '...' : $op->CLTR_DESC}}
                                    @endif
                                </td>                              
                                <td>{{number_format($op->CLTR_SALS_AMNT, 2)}}</td>
                                <td>{{number_format($op->CLTR_CASH_AMNT, 2)}}</td>
                                <td>{{number_format($op->CLTR_NTPY_AMNT, 2)}}</td>
                                <td>{{number_format($op->CLTR_DISC_AMNT, 2)}}</td>
                                <td>{{number_format($op->CLTR_RTRN_AMNT, 2)}}</td>
                                <td>{{number_format($op->CLTR_BLNC, 2)}}</td>
                                <td>
                                    @if(isset($op->CLTR_CMNT) && strcmp($op->CLTR_CMNT, '')!=0 )
                                        <button type="button"  style="padding:.1rem"  class="btn btn-secondary" data-container="body" title="" data-toggle="popover" data-placement="bottom" data-content="{{$op->CLTR_CMNT}}" data-original-title="Comment:">
                                    @endif
                                    <i class="far fa-list-alt" ></i>
                                    </button>
                                </td>
                            </tr> 
                            @endforeach
                            
                        </tbody>
                        @if(isset($totals))
                        <tfoot>
                            <tr>
                                <td colspan=3><strong>Start Balance: {{number_format($startBalance, 1)}}</strong></td>
                                <td><strong>{{number_format($totals->totalPurch, 2)}} </strong></td>
                                <td><strong>{{number_format($totals->totalCash, 2)}}</strong></td>
                                <td><strong>{{number_format($totals->totalNotes, 2)}}</strong></td>
                                <td><strong>{{number_format($totals->totalDisc, 2)}}</strong></td>
                                <td><strong>{{number_format($totals->totalReturn, 2)}}</strong></td>
                                <td><strong>End: {{number_format($balance, 2)}}</strong></td>
                                <td></td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
