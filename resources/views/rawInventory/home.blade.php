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
                                <th>Raw</th>
                                <th>Type</th>
                                <th>Model</th>
                                <th>Supplier</th>
                                @if(!$isProd)
                                <th>Rolls</th>
                                <th>Amount</th>
                                @endif
                                <th>in Prod.</th>
                                @if(!$isProd)
         
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($raws as $raw)
                            <tr>
                                <td>{{$raw->RAW_NAME}}</td>
                                <td>{{$raw->TYPS_NAME}}</td>
                                <td> {{$raw->MODL_NAME}}</td>
                                <td>
                                    <a href="{{url('suppliers/trans/quick/' . $supplier->id)}}">
                                        {{$raw->SUPP_NAME}}
                                    </a>
                                </td>
                                @if(!$isProd)
                                <td>
                                    <a href="{{url('rawinventory/model/' . $raw->MODL_NAME . '/' . $raw->RAW_ID . '/' . $raw->TYPS_ID . '/' . $raw->MODL_SUPP_ID )}}" >
                                        {{number_format($raw->rolls)}}              
                                </td>
                                <td>{{number_format($raw->meters, 2)}}</td>
                                @endif
                                <td>                               
                                    {{number_format($raw->amount, 2)}}
                                </td>
                                @if(!$isProd)
                   
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
