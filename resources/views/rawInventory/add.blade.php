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
                        <label>Model*</label>
                        <div class="input-group mb-3">
                            <select name=model class="select2 form-control custom-select" style="width: 100%; height:36px;">
                                <option disabled>Pick From Models</option>
                                @foreach($models as $model)
                                <option value="{{ $model->id }}">
                                    {{$model->MODL_UNID}} {{$model->RAW_NAME}}-{{$model->TYPS_NAME}}-{{$model->MODL_NAME}}  ({{$model->COLR_NAME}})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <small class="text-danger">{{$errors->first('model')}}</small>
                    </div>

                    <div class="form-group">
                        <label>Supplier*</label>
                        <div class="input-group mb-3">
                            <select name=supplier class="select2 form-control custom-select" style="width: 100%; height:36px;">
                                <option disabled>Pick From Suppliers</option>
                                @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">
                                    {{$supplier->SUPP_NAME}} 
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <small class="text-danger">{{$errors->first('supplier')}}</small>
                    </div>

                    <div class="form-group">
                        <label>Amount*</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-server"></i></span>
                            </div>
                            <input type="number" step=0.01 class="form-control" placeholder="Example: 2.5" name=amount aria-label="Total Amount in Meters" aria-describedby="basic-addon11" value="{{old('amount')}}" required>
                        </div>
                        <small class="text-danger">{{$errors->first('amount')}}</small>
                    </div>

                    <div class="form-group">
                        <label>Meter Price*</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-money"></i></span>
                            </div>
                            <input type="number" step=0.01 class="form-control" placeholder="Example: 180" name=price aria-label="Price per Meter" aria-describedby="basic-addon11" value="{{ old('price')}}" required>
                        </div>
                        <small class="text-danger">{{$errors->first('price')}}</small>
                    </div>

                    <div class="form-group">
                        <label>Discount on Total Price</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-money"></i></span>
                            </div>
                            <input type="number" step=0.01 class="form-control" placeholder="Example: 5000" name=discount aria-label="Discount on total price" aria-describedby="basic-addon11" value="{{ old('discount')}}" >
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                    <a href="{{url('rawinventory/show') }}" class="btn btn-dark">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
