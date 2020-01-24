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
                                <th>عمليه شراء</th>
                                <th>مشتريات</th>
                                <th>نقديه</th>
                                <th>اوراق دفع</th>
                                <th>خصم</th>
                                <th>مرتجع</th>
                                <th>رصيد</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ops as $op)
                            <tr>
                                <td>
                                
                                {{date_format(date_create($op->SPTR_DATE), "d-m-Y")}}
                                
                                </td>
                                <td>{{$op->SPTR_DESC}}</td>
                                <td>
                                    <?php 
                                        $commentArr = explode(' ', $op->SPTR_CMNT) ;
                                    ?>
                                    @if( $commentArr[0] == "Entry"  && $commentArr[1] == "Serial" && is_numeric($commentArr[2]) )
                                    <a href="{{url('rawinventory/bytrans/' . $commentArr[2])}}"> 
                                        {{$commentArr[2]}}
                                    <a>
                                    @endif

                                </td>
                                <td>{{number_format($op->SPTR_PRCH_AMNT, 2)}}</td>
                                <td>{{number_format($op->SPTR_CASH_AMNT, 2)}}</td>
                                <td>{{number_format($op->SPTR_NTPY_AMNT, 2)}}</td>
                                <td>{{number_format($op->SPTR_DISC_AMNT, 2)}}</td>
                                <td>{{number_format($op->SPTR_RTRN_AMNT, 2)}}</td>
                                <td>{{number_format($op->SPTR_BLNC, 2)}}</td>
                                <td>
                                    @if(isset($op->SPTR_CMNT) && strcmp($op->SPTR_CMNT, '')!=0 )
                                        <button type="button"  style="padding:.1rem"  class="btn btn-secondary" data-container="body" title="" data-toggle="popover" data-placement="bottom" data-content="{{$op->SPTR_CMNT}}" data-original-title="Comment:">
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
