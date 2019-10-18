@extends('layouts.app')

@section('content')
<div class="row">

    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
            <h4 class="card-title">Raw Material Types</h4>
                <h6 class="card-subtitle">Show Available Raw Material Types</h6>
                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
                        <thead>
                            <tr>
                                <th>Raw Material</th>
                                <th>Name</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($types as $typ)
                            <tr>
                                <td>{{$typ->RAW_NAME}}</td>
                                <td>{{$typ->TYPS_NAME}}</td>
                                <td><a href="{{ url('types/edit/' . $typ->id) }}"><img src="{{ asset('images/edit.png') }}" width=25 height=25></a></td>                               
                            </tr> 
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{$pageTitle}}</h4>
                <h5 class="card-subtitle">Add/Edit Raw Types</h5>
                <form class="form pt-3" method="post" action="{{ $formURL }}" enctype="multipart/form-data" >
                @csrf

                @if(isset($type))
                    <input name=id type=hidden value="{{$type->id}}">
                @endif

                <div class="form-group">
                    <label>Raw Material</label>
                    <div class="input-group mb-3">
                        <select name=raw class="select2 form-control custom-select" style="width: 100%; height:36px;">
                            <option disabled>Pick From Raw Materials</option>
                            @foreach($raws as $raw)
                            <option value="{{ $raw->id }}"
                            @if(isset($type) && $type->TYPS_RAW_ID == $raw->id)
                                selected
                            @endif
                            
                            >{{$raw->RAW_NAME}}</option>
                            @endforeach
                        </select>
                    </div>
                    <small class="text-danger">{{$errors->first('raw')}}</small>
                </div>

                    <div class="form-group">
                        <label>Raw Material</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-eraser"></i></span>
                            </div>
                            <input type="text" placeholder="Enter Type Name, Example: بوبلين " class="form-control" name=name  
                            value="{{ (isset($type)) ? $type->TYPS_NAME : old('name') }}" required>
                        </div>
                        <small class="text-danger">{{$errors->first('name')}}</small>
                    </div>

                    <button type="submit" class="btn btn-success mr-2">Submit</button>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection