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
                @if(isset($client))
                <input type=hidden name=id value={{$client->id}} >
                @endif
                <div class="form-group ">
                    <label>Type</label>
                    <div class="input-group mb-3 " >
                        <select class="select2 form-control "  name="isOnline" required>
                            <option value=0 {{ ( isset($client) && $client->CLNT_ONLN==0 ) ? 'selected' : ''}} >Veneto</option>
                            <option value=1 {{ ( isset($client) && $client->CLNT_ONLN==1 ) ? 'selected' : ''}} >Online</option>
                            <option value=2 {{ ( isset($client) && $client->CLNT_ONLN==2 ) ? 'selected' : ''}} >Via Veneto</option>
                            <option value=3 {{ ( isset($client) && $client->CLNT_ONLN==3 ) ? 'selected' : ''}} >Production</option>
                            <option value=4 {{ ( isset($client) && $client->CLNT_ONLN==4 ) ? 'selected' : ''}} >Procurement</option>

                        </select>
                    </div>
                    <small class="text-danger">{{$errors->first('paid')}}</small>
                </div>

                    <div class="form-group">
                        <label>Name*</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-user"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Example: Mohamed Saad Factory" name=name aria-label="Client Name" aria-describedby="basic-addon11" value="{{ (isset($client)) ? $client->CLNT_NAME : old('name')}}" required>
                        </div>
                        <small class="text-danger">{{$errors->first('name')}}</small>
                    </div>
                    <div class="form-group">
                        <label>Serial*</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class=" fas fa-barcode"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Example: 101" name=serial aria-label="Client Serial Number" aria-describedby="basic-addon11" value="{{ (isset($client)) ? $client->CLNT_SRNO : old('serial')}}" >
                        </div>
                        <small class="text-danger">{{$errors->first('serial')}}</small>
                    </div>

                    <div class="form-group">
                        <label>Balance*</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-bar-chart"></i></span>
                            </div>
                            <input type="number" step=0.01 class="form-control" placeholder="Example: 1234.56" name=balance aria-label="Client Balance" aria-describedby="basic-addon11" value="{{ (isset($client)) ? $client->CLNT_BLNC : old('balance')}}" 
                            @if(isset($client))
                            readonly
                            @endif
                            required
                            >
                        </div>
                        <small class="text-danger">{{$errors->first('balance')}}</small>
                    </div>

                    <div class="form-group">
                        <label>Arabic Name</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-user"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="اسم العميل بالعربي" name=arbcName aria-label="Client Name" aria-describedby="basic-addon11" value="{{ (isset($client)) ? $client->CLNT_ARBC_NAME : old('arbcName')}}" >
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Address</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-map"></i></span>
                            </div>
                            <input type="text" class="form-control" name=address  value="{{ (isset($client)) ? $client->CLNT_ADRS : old('address')}}" >
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Telephone</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-mobile"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="0123456778" name=tele value="{{ (isset($client)) ? $client->CLNT_TELE : old('tele')}}" >
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Comment</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-list"></i></span>
                            </div>
                            <input type="text" class="form-control"  name=comment  value="{{ (isset($client)) ? $client->CLNT_CMNT : old('comment')}}" >
                        </div>
                    </div>

                    
                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                    <a href="{{url('clients/show') }}" class="btn btn-dark">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
