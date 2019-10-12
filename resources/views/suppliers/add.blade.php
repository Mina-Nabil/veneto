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
                @if(isset($supplier))
                <input type=hidden name=id value={{$supplier->id}} >
                @endif
                    <div class="form-group">
                        <label>Supplier Name*</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-user"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Example: Mohamed Saad Factory" name=name aria-label="Supplier Name" aria-describedby="basic-addon11" value="{{ (isset($supplier)) ? $supplier->SUPP_NAME : old('name')}}" required>
                        </div>
                        <small class="text-danger">{{$errors->first('name')}}</small>
                    </div>

                    <div class="form-group">
                        <label>Supplier Type*</label>
                        <div class="input-group mb-3">
                            <select name=type class="select2 form-control custom-select" style="width: 100%; height:36px;">
                                <option disabled>Pick From Supplier Types</option>
                                @foreach($types as $type)
                                <option value="{{ $type->id }}"
                                @if(isset($supplier) && $type->id == $supplier->SUPP_SPTP_ID)
                                    selected
                                @endif
                                >{{$type->SPTP_NAME}}</option>
                                @endforeach
                            </select>
                        </div>
                        <small class="text-danger">{{$errors->first('type')}}</small>
                    </div>

                    <div class="form-group">
                        <label>Supplier Balance*</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-bar-chart"></i></span>
                            </div>
                            <input type="number" step=0.01 class="form-control" placeholder="Example: 1234.56" name=balance aria-label="Supplier Balance" aria-describedby="basic-addon11" value="{{ (isset($supplier)) ? $supplier->SUPP_BLNC : old('balance')}}" required>
                        </div>
                        <small class="text-danger">{{$errors->first('balance')}}</small>
                    </div>

                    <div class="form-group">
                        <label>Supplier Arabic Name</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-user"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="اسم المورد بالعربي" name=arbcName aria-label="Supplier Name" aria-describedby="basic-addon11" value="{{ (isset($supplier)) ? $supplier->SUPP_ARBC_NAME : old('arbcName')}}" >
                        </div>
                    </div>

                    
                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                    <a href="{{url('suppliers/types/show') }}" class="btn btn-dark">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
