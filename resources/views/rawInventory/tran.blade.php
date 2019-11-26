@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Raw Inventory Transactions</h4>
                <h6 class="card-subtitle">Show All Transactions for Raw Materials Inventory</h6>
                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
                        <thead>
                            <tr>
                                <th>Trans #</th>
                                <th>خامه</th>
                                <th>صنف</th>
                                <th>موديل</th>
                                <th>مورد</th>
                                <th>كميه داخل</th>
                                <th>من</th>
                                <th>كميه خارج</th>
                                <th>الي</th>
                                <th>متاح</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($trans as $tran)
                            <tr>
                                <td>
                                    <a href="{{url('rawinventory/bytrans/' . $tran->RINV_TRNS)}}"> 
                                        {{$tran->RINV_TRNS}}
                                    <a>
                                </td>
                                <td>{{$tran->RAW_NAME}}</td>
                                <td>{{$tran->TYPS_NAME}}</td>
                                <td>{{$tran->MODL_NAME}}</td>
                                <td>{{$tran->SUPP_NAME}}</td>
                                <td>{{number_format($tran->RWTR_AMNT_IN, 2)}}</td>
                                <td>{{$tran->RWTR_FROM}}</td>
                                <td>{{number_format($tran->RWTR_AMNT_OUT, 2)}}</td>
                                <td>{{$tran->RWTR_TO}}</td>
                                <td>{{number_format($tran->RINV_METR, 2)}}</td>
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
