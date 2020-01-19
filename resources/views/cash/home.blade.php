@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Cash Account</h4>
                <h6 class="card-subtitle">Show All Cash Account transactions</h6>
                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
                        <thead>
                            <tr>
                                <th>تاريخ</th>
                                <th>رقم المعاملات</th>
                                <th>مدين</th>
                                <th>دائن</th>
                                <th>رصيد</th>
                                <th>Comment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ops as $op)
                            <tr>
                                <td>
                                    {{date_format(date_create($op->CASH_DATE), "d-m-Y")}}   
                                </td>
                                <td>{{$op->CASH_NAME}}</td>
                                <td>{{number_format($op->CASH_OUT, 2)}}</td>
                                <td>{{number_format($op->CASH_IN, 2)}}</td>
                                <td>{{number_format($op->CASH_BLNC, 2)}}</td>
                                <td>
                                    @if(isset($op->CASH_CMNT) && strcmp($op->CASH_CMNT, '')!=0 )
                                        <button type="button"  style="padding:.1rem"  class="btn btn-secondary" data-container="body" title="" data-toggle="popover" data-placement="bottom" data-content="{{$op->CASH_CMNT}}" data-original-title="Comment:">
                                    @endif
                                    <i class="fas fa-list-alt"></i>
                                    </button>
                                </td>
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
