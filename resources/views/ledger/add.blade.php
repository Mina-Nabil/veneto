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
                        <label>Transaction Description*</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="fas fa-list"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Transaction Example" name=name list=transTypes required>
                            <datalist id=transTypes>
                                <option value="مرتبات"></option>
                                <option value="مصاريف عرض"></option>
                            </datalist>
                        </div>
                        <small class="text-danger">{{$errors->first('name')}}</small>
                    </div>
                    <div class="form-group">
                        <div class="input-group mb-3">
                            <select name=typeID class="select2 form-control custom-select" required>
                                <option disabled hidden selected value="">الانواع</option>
                                @foreach($ledgerSubTypes as $ledgerSubType)
                                <option value="{{ $ledgerSubType->id }}">
                                    {{$ledgerSubType->LDTP_NAME}}-{{$ledgerSubType->LDST_NAME}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <small class="text-danger">{{$errors->first('typeID')}}</small>
                    </div>
                    <div class="form-group">
                        <label>Debit</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-money"></i></span>
                            </div>
                            <input type="number" class="form-control" placeholder="Debit Amout" name=out value=0 required>
                        </div>
                        <small class="text-danger">{{$errors->first('out')}}</small>
                    </div>
                    <div class="form-group">
                        <label>Credit</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-money"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Credit Amount" name=in value=0 required>
                        </div>
                        <small class="text-danger">{{$errors->first('in')}}</small>
                    </div>
                    <div class="form-group">
                        <label>Comment</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-user"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Transaction Comment" name=comment >
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                    <a href="{{url('ledger/show') }}" class="btn btn-dark">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
