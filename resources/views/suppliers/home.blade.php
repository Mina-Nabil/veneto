@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Suppliers</h4>
                <h6 class="card-subtitle">Show All Suppliers data</h6>
                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]">
                        <thead>
                            <tr>
                                <th>Supplier Name</th>
                                <th>Arabic Name</th>
                                <th>Type</th>
                                <th>Current Balance</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($types as $type)
                            @foreach($suppliers[$type->id] as $supplier)
                            <tr>
                                <td>
                                    <a href="{{url('suppliers/trans/quick/' . $supplier->id)}}">
                                        {{$supplier->SUPP_NAME}}
                                    </a>
                                </td>
                                <td>{{$supplier->SUPP_ARBC_NAME}}</td>
                                <td>{{$supplier->SPTP_NAME}}</td>
                                <td>{{number_format($supplier->SUPP_BLNC, 2)}}</td>
                                <td><a href="{{ url('suppliers/edit/' . $supplier->id) }}"><img src="{{ asset('images/edit.png') }}" width=25 height=25></a></td>
                            </tr>
                            @endforeach

                     
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan=3><strong>Total Balance: </strong></td>
                                <td><strong>{{number_format($total[0], 2)}}</strong></td>
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