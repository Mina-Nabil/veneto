@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{$pageTitle}}</h4>
                <h5 class="card-subtitle">Add/Edit Raw Materials</h5>
                <form class="form pt-3" method="post" action="{{ $formURL }}" enctype="multipart/form-data" >
                @csrf

                @if(isset($raw))
                    <input name=id type=hidden value="{{$raw->id}}">
                @endif

                    <div class="form-group">
                        <label>Raw Material</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-eraser"></i></span>
                            </div>
                            <input type="text" placeholder="Enter Raw Material Name, Example: Cotton" class="form-control" name=name  
                            value="{{ (isset($raw)) ? $raw->RAW_NAME : old('name') }}" required>
                        </div>
                        <small class="text-danger">{{$errors->first('name')}}</small>
                    </div>

                    <button type="submit" class="btn btn-success mr-2">Submit</button>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
            <h4 class="card-title">Raw Materials</h4>
                <h6 class="card-subtitle">Show Available Raw Materials</h6>
                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($raws as $raw)
                            <tr>
                                <td>{{$raw->RAW_NAME}}</td>
                                <td><a href="{{ url('raw/edit/' . $raw->id) }}"><img src="{{ asset('images/edit.png') }}" width=25 height=25></a></td>                               
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