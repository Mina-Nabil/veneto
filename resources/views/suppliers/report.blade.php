@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
            @if($isSupplier)
                <h4 class="card-title">'{{$supplier->SUPP_NAME}}' Quick Report</h4>
                @else
                <h4 class="card-title">Suppliers Report</h4>
                @endif
                <h6 class="card-subtitle">Show Suppliers transactions</h6>
                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
                        <thead>
                            <tr>
                                <th>تاريخ</th>
                                @if(!$isSupplier)
                                <th>الاسم</th>
                                @endif
                                <th>عمليه شراء</th>
                                <th>مشتريات</th>
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
                            <tr>
                                <td>
                                    {{date_format(date_create($op->SPTR_DATE), "d-m-Y")}}
                                </td>
                                @if(!$isSupplier)
                                <td>
                                    <a href="{{url('suppliers/trans/quick/' . $op->SPTR_SUPP_ID)}}">
                                        {{$op->SUPP_NAME}}
                                    </a>
                                </td>
                                @endif
                                <td>
                                    <?php 
                                        $commentArr = explode(' ', $op->SPTR_CMNT) ;
                                        if(isset($op->SPTR_DESC))
                                            $descArr = explode(' ', $op->SPTR_DESC) ;
                                    ?>
                                    @if( isset($descArr) && $descArr[0] == "Entry"  && $descArr[1] == "Serial" && is_numeric($descArr[2]) )
                                        <a href="{{url('rawinventory/bytrans/' . $descArr[2])}}"> 
                                            {{$descArr[2]}}
                                        <a>
                                    @elseif( $commentArr[0] == "Entry"  && $commentArr[1] == "Serial" && is_numeric($commentArr[2]) )
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
                        @if($isSupplier)
                        <tfoot>
                            <tr>
                                <td colspan=2><strong>Totals</strong></td>
                                <td><strong>{{number_format($totals->SPTR_PRCH_BLNC, 2)}}</strong></td>
                                <td><strong>{{number_format($totals->SPTR_CASH_BLNC, 2)}}</strong></td>
                                <td><strong>{{number_format($totals->SPTR_NTPY_BLNC, 2)}}</strong></td>
                                <td><strong>{{number_format($totals->SPTR_DISC_BLNC, 2)}}</strong></td>
                                <td><strong>{{number_format($totals->SPTR_RTRN_BLNC, 2)}}</strong></td>
                                <td><strong>{{number_format($totals->SPTR_BLNC, 2)}}</strong></td>
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
