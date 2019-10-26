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
                                <th>Image</th>
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
                                <td>
                                    @if(isset($raw->MODL_IMGE))
                                    <img src="{{ asset( 'storage/'. $raw->MODL_IMGE ) }}" width=50 height=50>
                                    @endif
                                </td>
                                <td>{{$raw->RAW_NAME}}</td>
                                <td>{{$raw->TYPS_NAME}}</td>
                                <td>
                                @if(isset($raw->MODL_CMNT) && strcmp($raw->MODL_CMNT, '')!=0 )
                                    <button type="button" class="btn btn-secondary" data-container="body" title="" data-toggle="popover" data-placement="bottom" data-content="{{$raw->MODL_CMNT}}" data-original-title="{{$raw->MODL_NAME}}">
                                @endif
                                        {{$raw->MODL_NAME}}
                                </button>
                                </td>
                                <td>{{$raw->SUPP_NAME}}</td>
                                @if(!$isProd)
                                <td>
                                    <a href={{url("rawinventory/model/$raw->RINV_MODL_ID")}}>
                                        {{number_format($raw->rolls)}}
                                    </a>                           
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
