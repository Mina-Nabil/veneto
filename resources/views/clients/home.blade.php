@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Clients</h4>
                <h6 class="card-subtitle">Show All Clients data</h6>
                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
                        <thead>
                            <tr>
                                <th>Client Name</th>
                                <th>Arabic Name</th>
                                <th>Current Balance</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($clients as $client)
                            <tr>
                                <td>
                                    <a href="{{url('clients/trans/quick/' . $client->id)}}">
                                        {{$client->CLNT_NAME}}
                                    </a>
                                </td>
                                <td>{{$client->CLNT_ARBC_NAME}}</td>
                                <td>{{number_format($client->CLNT_BLNC, 2)}}</td>
                                <td><a href="{{ url('clients/edit/' . $client->id) }}"><img src="{{ asset('images/edit.png') }}" width=25 height=25></a></td>                               
                            </tr> 
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                            <td colspan=2><strong>Total Balance: </strong></td>
                            <td><strong>{{number_format($total, 2)}}</strong></td>
                            <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
