@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Account Query</h4>
                <h6 class="card-subtitle">Show All transactions for {{$account}} from {{$from}} to {{$to}}</h6>
                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Title</th>
                                <th>Credit</th>
                                <th>Debit</th>
                                <th>Balance</th>
                                <th>Comment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($trans as $op)
                            <tr>
                                <td>
                                    {{date_format(date_create($op->GNTR_DATE), "d-m-Y")}}
                                </td>
                                <td>
                                    {{$op->GNTR_TTLE}}
                                </td>
                                <td>{{number_format($op->GNTR_CRDT, 2)}}</td>
                                <td>{{number_format($op->GNTR_DEBT, 2)}}</td>
                                <td>{{number_format($op->GNTR_GNAC_BLNC, 2)}}</td>
                                <td>
                                    @if(isset($op->GNTR_CMNT) && strcmp($op->GNTR_CMNT, '')!=0 )
                                    <button type="button" style="padding:.1rem" class="btn btn-secondary" data-container="body" title="" data-toggle="popover" data-placement="bottom"
                                        data-content="{{$op->GNTR_CMNT}}" data-original-title="Comment:">
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