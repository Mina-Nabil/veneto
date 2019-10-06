@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Suppliers</h4>
                <h6 class="card-subtitle">Show All Suppliers data</h6>
                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
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
                            @foreach($suppliers as $supplier)
                            <tr>
                                <td>{{$supplier->SUPP_NAME}}</td>
                                <td>{{$supplier->SUPP_ARBC_NAME}}</td>
                                <td>{{$supplier->SPTP_NAME}}</td>
                                <td>{{number_format($supplier->SUPP_BLNC)}}</td>
                                <td><a href="{{ url('suppliers/edit/' . $supplier->id) }}"><img src="{{ asset('images/edit.png') }}" width=25 height=25></img></a></td>                               
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
