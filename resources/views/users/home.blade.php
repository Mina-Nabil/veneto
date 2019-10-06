@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Users</h4>
                <h6 class="card-subtitle">Show Application Users data</h6>
                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
                        <thead>
                            <tr>
                                <th>User Name</th>
                                <th>Full Name</th>
                                <th>Mobile#</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{$user->username}}</td>
                                <td>{{$user->fullname}}</td>
                                <td>{{$user->mobNumber}}</td>
                                <td><a href="{{ url('users/edit/' . $user->id) }}"><img src="{{ asset('images/edit.png') }}" width=25 height=25></img></a></td>                               
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
