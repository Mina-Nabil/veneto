@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">كشف حساب</h4>
                <h5 class="card-subtitle">Select Client and Time Range for the Account Statement Report</h5>
                <form class="form pt-3" method="post" action="{{ $accountStatFormURL }}" enctype="multipart/form-data" >
                @csrf
                    <div class="form-group">
                        <label>عميل*</label>
                        <div class="input-group mb-3">
                            <select name=client class="select2 form-control custom-select" style="width: 100%; height:36px;">
                                <option disabled>Pick From Clients</option>
                                @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{$client->CLNT_NAME}}</option>
                                @endforeach
                            </select>
                        </div>
                        <small class="text-danger">{{$errors->first('client')}}</small>
                    </div>

                    <div class="form-group">
                        <label>من</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-calendar"></i></span>
                            </div>
                            <input type="date" class="form-control" name=from  required>
                        </div>
                        <small class="text-danger">{{$errors->first('from')}}</small>
                    </div>

                    <div class="form-group">
                        <label>الي</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-calendar"></i></span>
                            </div>
                            <input type="date" class="form-control" name=to value="{{date('Y-m-d')}}" required>
                        </div>
                        <small class="text-danger">{{$errors->first('to')}}</small>
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
                <h4 class="card-title">اجماليات</h4>
                <h5 class="card-subtitle">اجماليات عملاء</h5>
                <form class="form pt-3" method="post" action="{{ $mainReportFormURL }}" enctype="multipart/form-data" >
                @csrf
                    <div class="form-group">
                        <label>من</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-calendar"></i></span>
                            </div>
                            <input type="date" class="form-control" name=from  required>
                        </div>
                        <small class="text-danger">{{$errors->first('from')}}</small>
                    </div>

                    <div class="form-group">
                        <label>الي</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-calendar"></i></span>
                            </div>
                            <input type="date" class="form-control" name=to value="{{date('Y-m-d')}}" required>
                        </div>
                        <small class="text-danger">{{$errors->first('to')}}</small>
                    </div>

                    <button type="submit" class="btn btn-success mr-2">Submit</button>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection