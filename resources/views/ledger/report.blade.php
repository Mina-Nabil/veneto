@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">كشف حساب</h4>
                <h5 class="card-subtitle">Select Time Range for the General Account Report</h5>
                <form class="form pt-3" method="post" action="{{ $reportFormURL }}" enctype="multipart/form-data" >
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

                    <div class="form-group">
                        <label>الحساب</label>
                        <div class="input-group mb-3">
                            <select name=typeID class="select2 form-control custom-select">
                                <option selected value="0">All Accounts</option>
                                @foreach($ledgerSubTypes as $ledgerSubType)
                                <option value="{{ $ledgerSubType->id }}">
                                    {{$ledgerSubType->LDTP_NAME}}-{{$ledgerSubType->LDST_NAME}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <small class="text-danger">{{$errors->first('typeID')}}</small>
                    </div>

                    <button type="submit" class="btn btn-success mr-2">Submit</button>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection