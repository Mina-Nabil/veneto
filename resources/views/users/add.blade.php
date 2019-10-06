@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ $pageTitle }}</h4>
                <h5 class="card-subtitle">{{ $pageDescription }}</h5>
                <form class="form pt-3" method="post" action="{{ $formURL }}" enctype="multipart/form-data" >
                @csrf
                <input type=hidden name=id value="{{(isset($user)) ? $user->id : ''}}">
                @if(isset($user->image))
                <input type=hidden name=oldPath value="{{$user->image}}">
                @endif
                    <div class="form-group">
                        <label>User Name*</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-user"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Username" name=name aria-label="Username" aria-describedby="basic-addon11" value="{{ (isset($user)) ? $user->username : old('username')}}" required>
                        </div>
                        <small class="text-danger">{{$errors->first('name')}}</small>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Full Name</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon22"><i class="ti-user"></i></span>
                            </div>
                            <input type="text" class="form-control" name=fullname placeholder="Full Name" aria-label="Full Name" aria-describedby="basic-addon22" value="{{ (isset($user)) ? $user->fullname : old('fullname')}}" >
                        </div>
                        <small class="text-danger">{{$errors->first('fullname')}}</small>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Mobile Number</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon22"><i class="mdi mdi-cellphone-iphone"></i></span>
                            </div>
                            <input type="text" class="form-control" name=mobNumber placeholder="Mobile Number" aria-label="Mobile Number" aria-describedby="basic-addon22" value="{{ (isset($user)) ? $user->mobNumber : old('mobNumber')}}" >
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="input-file-now-custom-1">User Photo</label>
                        <div class="input-group mb-3">
                            <input type="file" id="input-file-now-custom-1" name=photo class="dropify" data-default-file="{{ (isset($user->image)) ? asset( 'storage/'. $user->image ) : old('photo') }}" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Password*</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon33"><i class="ti-lock"></i></span>
                            </div>
                            <input type="text" class="form-control" name=password placeholder="Password" aria-label="Password" aria-describedby="basic-addon33"
                            @if($isPassNeeded)
                            required
                            @endif
                            >
                            <small class="text-danger">{{$errors->first('password')}}</small>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                    <a href="{{url('users/show') }}" class="btn btn-dark">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
