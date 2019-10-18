@extends('layouts.app')

@section('content')
<div class="row">

    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
            <h4 class="card-title">Colors</h4>
                <h6 class="card-subtitle">Show Available Colors</h6>
                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($colors as $col)
                            <tr>
                                <td>{{$col->COLR_NAME}}</td>
                                <td>{{$col->COLR_CODE}}</td>
                                <td><a href="{{ url('colors/edit/' . $col->id) }}"><img src="{{ asset('images/edit.png') }}" width=25 height=25></a></td>                               
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
                <h5 class="card-subtitle">Add/Edit Colors</h5>
                <form class="form pt-3" method="post" action="{{ $formURL }}" enctype="multipart/form-data" >
                @csrf

                @if(isset($color))
                    <input name=id type=hidden value="{{$color->id}}">
                @endif

                    <div class="form-group">
                        <label>Color Name</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class=" ti-paint-bucket"></i></span>
                            </div>
                            <input type="text" placeholder="Enter Color Name, Example: Red" class="form-control" name=name  
                            value="{{ (isset($color)) ? $color->COLR_NAME : old('name') }}" required>
                        </div>
                        <small class="text-danger">{{$errors->first('name')}}</small>
                    </div>

                    <div class="form-group">
                        <label>Code</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-shortcode"></i></span>
                            </div>
                            <input type="text" placeholder="Enter Color Code, Example: #ff0932" class="form-control" name=code  
                            value="{{ (isset($color)) ? $color->COLR_CODE : old('code')}}" required>
                        </div>
                        <small class="text-danger">{{$errors->first('code')}}</small>
                    </div>

                    <button type="submit" class="btn btn-success mr-2">Submit</button>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection