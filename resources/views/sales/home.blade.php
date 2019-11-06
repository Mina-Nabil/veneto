@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ ($isClntPage) ? $client->CLNT_NAME : ''  }} Sales Operations</h4>
                <h6 class="card-subtitle">Show All Sales Operations {{ ($isClntPage) ? "for " . $client->CLNT_NAME : ''  }}</h6>
                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                            @if(!$isClntPage)
                                <th>Client</th>
                            @endif
                                <th>Total</th>
                                <th>Paid</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sales as $row)
                            <tr>
                                <td>{{$row->id}}</td>
                                <td>
                                @if(isset($row->SALS_CMNT) && strcmp($row->SALS_CMNT, '')!=0 )
                                    <button type="button" class="btn btn-secondary" data-container="body" title="" data-toggle="popover" data-placement="bottom" 
                                    data-content="{{$row->SALS_CMNT}}" data-original-title="Comment:">
                                @endif
                                {{$row->SALS_DATE}}
                                </button>
                                </td>
                            @if(!($isClntPage))
                                <td>
                                    <a href="{{url('sales/show/' . $row->SALS_CLNT_ID)}}">
                                        {{$row->CLNT_NAME}}
                                    </a>
                                </td>
                            @endif
                                <td>{{$row->SALS_TOTL_PRCE}}</td>
                                <td>{{$row->SALS_PAID}}</td>
                                <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action
                                    </button>
                                    <div class="dropdown-menu">
                                        <button class="dropdown-item" data-toggle="modal" data-target="#addPay{{$row->id}}" >Add Payment</button>
                                        <button class="dropdown-item" onclick="goto('{{url("sales/items/$row->id")}}')" >Show details</button>

                                    </div> 
                            
                                </td>
                            </tr> 

                            <div id="addPay{{$row->id}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Add Client Payment</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        </div>
                                        <form action="{{ url('sales/add/payment') }}" method=post>
                                        @csrf
                                        <div class="modal-body">
                                            <input type=hidden name=salesID value="{{$row->id}}" >

                                                <div class="form-group col-md-12 m-t-0">
                                                <h5>Payment Type</h5>
                                                    <select class="select form-control form-control-line" name=type  required >
                                                        <option selected value=0>Cash</option>
                                                        <option value=1>Cheque</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-12 m-t-0">
                                                <h5>Amount</h5>
                                                    <input type="number" step=0.01 max="{{$row->SALS_TOTL_PRCE - $row->SALS_PAID}}" class="form-control form-control-line" name=payment  required >
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


                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

function goto(url){
    window.location.href = url;
}

</script>
@endsection
