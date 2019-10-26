@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Cloth Rolls Page</h4>
                <h6 class="card-subtitle">Show All Available Rolls {{(isset($pageDesc)) ? $pageDesc : ''}}</h6>
                @if(!$transPage)
                <div class=row>
                    <div class=col-lg-4>
                        <h5>Model</h5>
                        <p>{{$model->RAW_NAME}} - {{$model->TYPS_NAME}} - {{$model->MODL_NAME}}</p>
                    </div>
                    <div class=col-lg-2>
                        <h5>Supplier</h5>
                        <p>{{$model->SUPP_NAME}}</p>
                    </div>
                    <div class=col-lg-2>
                        <h5>Price</h5>
                        <p>{{$model->MODL_PRCE}}</p>
                    </div>
                    <div class=col-lg-2>
                        <h5>Color</h5>
                        <p>{{$model->COLR_NAME}} - {{$model->COLR_CODE}}</p>
                    </div>
                </div>
                @endif
                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
                        <thead>
                            <tr>
                                @if($transPage)
                                <th>Photo</th>
                                <th>Raw</th>
                                <th>Type</th>
                                <th>Model</th>
                                <th>Supplier</th>
                                <th>Price</th>
                                @endif
                                <th>Transaction Number</th>
                                <th>Amount</th>
                                <th>in Prod.</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($raws as $raw)
                            <tr>
                                @if($transPage)
                                <td>
                                    @if(isset($raw->MODL_IMGE))
                                        <img src="{{ asset( 'storage/'. $raw->MODL_IMGE ) }}" width=50 height=50>
                                    @endif
                                </td>
                                <td>{{$raw->RAW_NAME}}</td>
                                <td>{{$raw->TYPS_NAME}}</td>
                                <td>{{$raw->MODL_NAME}}</td>
                                <td>{{$raw->SUPP_NAME}}</td>
                                <td>{{$raw->MODL_PRCE}}</td>
                                @endif
                                <td>
                                    <a href="{{url('rawinventory/bytrans/' . $raw->RINV_TRNS)}}"> 
                                        {{$raw->RINV_TRNS}}
                                    <a>
                                </td>
                                <td>{{$raw->RINV_METR}}</td>
                                <td>{{$raw->RINV_PROD_AMNT}}</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Action
                                        </button>
                                        <div class="dropdown-menu">
                                        @if(!$isProd)
                                            <button class="dropdown-item" onclick=goto('{{url("raw/prod/full/insert/{$raw->id}")}}')>Send to Production</button>
                                            <button class="dropdown-item" data-toggle="modal" data-target="#responsive-modal{{$raw->id}}" >Send Cut to Prod</button>
                                        @else
                                            <button class="dropdown-item" data-toggle="modal" data-target="#responsive-modal{{$raw->id}}" >Send Cut Back to Raw</button>
                                                <button class="dropdown-item" data-toggle="modal" 
                                                data-target="#to-finished{{$raw->id}}" >Send Cut to Finished</button>
                                            </div>
                                        @endif
                                    </div> 
                                </td>
                            </tr> 

                            <div id="responsive-modal{{$raw->id}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Send Cut to {{ ($isProd) ? 'Raw Inventory' : 'Production'}}</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        </div>
                                        <form action="{{ ($isProd) ? url('raw/prod/revert') : url('raw/prod/insert')}}" method=post>
                                        @csrf
                                        <div class="modal-body">
                                            <input type=hidden name=raw value={{$raw->id}} >
                                                <div class="form-group col-md-12 m-t-0">
                                                <h5>Amount</h5>
                                                    <input type="number" step=0.01 class="form-control form-control-line" name=in required >
                                                </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-warning waves-effect waves-light">Submit</button>
                                        </div>
                                    </form>
                                    </div>
                                </div>
                            </div>

                            @if($isProd)
                            <div id="to-finished{{$raw->id}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Send Cut to Finished</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        </div>
                                        <form action="{{ url('finished/prod/insert') }}" method=post>
                                        @csrf
                                        <div class="modal-body">
                                            <input type=hidden name=raw value={{$raw->id}} >
                                                <div class="form-group col-md-12 m-t-0">
                                                <h5>Amount</h5>
                                                    <input type="number" step=0.01 class="form-control form-control-line" name=in value="{{ $raw->RINV_PROD_AMNT }}" required >
                                                </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-warning waves-effect waves-light">Submit</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>


function goto($url){
    window.location.href = $url;
}
</script>
@endsection
