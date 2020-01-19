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

                    <div class="form-group">
                        <label>عميل*</label>
                        <div class="input-group mb-3">
                            <select name=client class="select2 form-control custom-select" style="width: 100%; height:36px;" required>
                                <option disabled hidden selected value="">Pick From Clients</option>
                                @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{$client->CLNT_NAME}}</option>
                                @endforeach
                            </select>
                        </div>
                        <small class="text-danger">{{$errors->first('client')}}</small>
                    </div>

                    <div class="form-group">
                        <label>مبيعات</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-arrow-up"></i></span>
                            </div>
                            <input type="number" step=0.01 class="form-control" name=sales value="0" required>
                        </div>
                        <small class="text-danger">{{$errors->first('sales')}}</small>
                    </div>

                    <div class="form-group">
                        <label>نقدي</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-arrow-down"></i></span>
                            </div>
                            <input type="number" step=0.01 class="form-control" name=cash value="0" required>
                        </div>
                        <small class="text-danger">{{$errors->first('cash')}}</small>
                    </div>

                    <div class="form-group">
                        <label>اوراق دفع</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-arrow-down"></i></span>
                            </div>
                            <input type="number" step=0.01 class="form-control" name=notes  value="0" required>
                        </div>
                        <small class="text-danger">{{$errors->first('notes')}}</small>
                    </div>

                    <div class="form-group">
                        <label>خصم</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-arrow-down"></i></span>
                            </div>
                            <input type="number" step=0.01 class="form-control" name=disc  value="0" required>
                        </div>
                        <small class="text-danger">{{$errors->first('disc')}}</small>
                    </div>

                    <div class="form-group">
                        <label>مرتجع</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-arrow-down"></i></span>
                            </div>
                            <input type="number" step=0.01 class="form-control" name=return value="0" required>
                        </div>
                        <small class="text-danger">{{$errors->first('return')}}</small>
                    </div>

                    <div class="form-group">
                        <label>Comment</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-comment-alt"></i></span>
                            </div>
                            <input type="text" class="form-control" name=comment >
                        </div>
                    </div>

                    
                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                    <a href="{{url('clients/report') }}" class="btn btn-dark">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
