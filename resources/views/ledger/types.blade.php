@extends('layouts.app')

@section('content')
<div class="row">
   
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{$pageTitle}}</h4>
                <h4 class="card-subtitle">اضف نوع جديد</h4>
                <br>
                <form class="form pt-3" method="post" action="{{ $formURL }}" enctype="multipart/form-data">
                    @csrf

                    @if(isset($ledgerType))
                    <input name=id type=hidden value="{{$ledgerType->id}}">
                    @endif

                    <div class="form-group">
                        <label>الاسم</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name=name
                                value="{{ (isset($ledgerType)) ? $ledgerType->LDTP_NAME : old('name') }}" required>
                        </div>
                        <small class="text-danger">{{$errors->first('name')}}</small>
                    </div>

                    <button type="submit" class="btn btn-success mr-2">Add New Type</button>

                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-subtitle">اضف صنف جديد من نوع</h5>
                <form class="form pt-3" method="post" action="{{ $subFormURL }}" enctype="multipart/form-data">
                    @csrf

                    @if(isset($ledgerSubType))
                    <input name=id type=hidden value="{{$ledgerSubType->id}}">
                    @endif

                    <div class="form-group">
                        <div class="input-group mb-3">
                            <select name=typeID class="select2 form-control custom-select" required>
                                <option disabled hidden selected value="">الحساب</option>
                                @foreach($ledgerTypes as $ledgerType)
                                <option value="{{ $ledgerType->id }}" @if(isset($ledgerSubType) && $ledgerType->
                                    id==$ledgerSubType->LDST_LDTP_ID)
                                    selected
                                    @endif
                                    >
                                    {{$ledgerType->LDTP_NAME}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <small class="text-danger">{{$errors->first('typeID')}}</small>
                    </div>
                    <div class="form-group">
                        <label>الاسم</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name=name
                                value="{{ (isset($ledgerSubType)) ? $ledgerSubType->LDST_NAME : old('name') }}" required>
                        </div>
                        <small class="text-danger">{{$errors->first('name')}}</small>
                    </div>

                    <button type="submit" class="btn btn-success mr-2">Add Sub Type</button>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">الانواع</h4>
                <h6 class="card-subtitle">Show Available Classifications</h6>
                <div class="table-responsive m-t-40">
                    <table id="myTable"
                        class="table color-bordered-table table-striped full-color-table full-info-table hover-table"
                        data-display-length='-1' data-order="[]">
                        <thead>
                            <tr>
                                <th>نوع</th>
                                <th>صنف</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ledgerSubTypes as $ledgerSubType)
                            <tr>
                                <td>
                                    <a href="{{ url('ledgertype/edit/' . $ledgerSubType->LDST_LDTP_ID) }}">
                                        {{$ledgerSubType->LDTP_NAME}}
                                    </a>
                                </td>
                                <td>{{$ledgerSubType->LDST_NAME}}</td>
                                <td><a href="{{ url('ledgersubtype/edit/' . $ledgerSubType->id) }}"><img
                                            src="{{ asset('images/edit.png') }}" width=25 height=25></a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection