@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Bank Account</h4>
                <h6 class="card-subtitle">Show All Bank Account transactions</h6>
                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
                        <thead>
                            <tr>
                                <th>تاريخ</th>
                                <th>Transaction</th>
                                <th>مدين</th>
                                <th>دائن</th>
                                <th>رصيد</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ops as $op)
                            <tr>
                                <td>
                                @if(isset($op->BANK_CMNT) && strcmp($op->BANK_CMNT, '')!=0 )
                                    <button type="button" class="btn btn-secondary" data-container="body" title="" data-toggle="popover" data-placement="bottom" data-content="{{$op->BANK_CMNT}}" data-original-title="Comment:">
                                @endif
                                {{$op->BANK_DATE}}
                                    </button>
                                </td>
                                <td>{{$op->BANK_NAME}}</td>
                                <td>{{number_format($op->BANK_OUT, 2)}}</td>
                                <td>{{number_format($op->BANK_IN, 2)}}</td>
                                <td>{{number_format($op->BANK_BLNC, 2)}}</td>
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
