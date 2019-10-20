@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Available Raw Materials</h4>
                <h6 class="card-subtitle">Show All Available Raw Materials</h6>
                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Raw</th>
                                <th>Type</th>
                                <th>Model</th>
                                <th>Supplier</th>
                                @if(!$isProd)
                                <th>Available</th>
                                @endif
                                <th>in Prod.</th>
                                @if(!$isProd)
                                <th>Price</th>
                                <th>Total Price</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($raws as $raw)
                            <tr>
                                <td>{{$raw->id}}</td>
                                <td>{{$raw->RAW_NAME}}</td>
                                <td>{{$raw->TYPS_NAME}}</td>
                                <td>{{$raw->MODL_NAME}}</td>
                                <td>{{$raw->SUPP_NAME}}</td>
                                @if(!$isProd)
                                <td>{{number_format($raw->RINV_METR, 2)}}</td>
                                @endif
                                <td>{{number_format($raw->RINV_PROD_AMNT, 2)}}</td>
                                @if(!$isProd)
                                <td>{{number_format($raw->RINV_PRCE, 2)}}</td>
                                <td>{{number_format($raw->RINV_PRCE * $raw->RINV_METR, 2)}}</td>
                                @endif
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
