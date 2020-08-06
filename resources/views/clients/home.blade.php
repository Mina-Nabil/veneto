@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Clients</h4>
                <h6 class="card-subtitle">Show All Clients data</h6>
                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Client Name</th>
                                <th>Arabic Name</th>
                                <th>Current Balance</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($veneto as $client)
                            <tr>
                                <td>{{$client->CLNT_SRNO}}</td>
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

                            <tr>
                                <td></td>
                                <td><strong>Veneto Balance: </strong></td>
                                <td></td>
                                <td><strong>{{number_format($totalVeneto, 2)}}</strong></td>
                                <td></td>

                            </tr>
                            @foreach($online as $client)
                            <tr>
                                <td>{{$client->CLNT_SRNO}}</td>
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

                            <tr>
                                <td></td>
                                <td><strong>Online Balance: </strong></td>
                                <td></td>
                                <td><strong>{{number_format($totalOnline, 2)}}</strong></td>
                                <td></td>

                            </tr>
                            @foreach($via as $client)
                            <tr>
                                <td>{{$client->CLNT_SRNO}}</td>
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

                            <tr>
                                <td></td>
                                <td><strong>Via Veneto Balance: </strong></td>
                                <td></td>
                                <td><strong>{{number_format($totalVia, 2)}}</strong></td>
                                <td></td>

                            </tr>
                            @foreach($prod as $client)
                            <tr>
                                <td>{{$client->CLNT_SRNO}}</td>
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

                            <tr>
                                <td></td>
                                <td><strong>Production Balance: </strong></td>
                                <td></td>
                                <td><strong>{{number_format($totalProd, 2)}}</strong></td>
                                <td></td>

                            </tr>
                            @foreach($proc as $client)
                            <tr>
                                <td>{{$client->CLNT_SRNO}}</td>
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
                            <tr>
                                <td></td>
                                <td><strong>Procurement Balance: </strong></td>
                                <td></td>
                                <td><strong>{{number_format($totalProc, 2)}}</strong></td>
                                <td></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td><strong>Total Balance: </strong></td>
                                <td></td>
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