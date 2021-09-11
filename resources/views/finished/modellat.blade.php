@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{$pageTitle}}</h4>
                <h5 class="card-subtitle">Add/Edit Models</h5>
                <form class="form pt-3" method="post" action="{{ $formURL }}" enctype="multipart/form-data">
                    @csrf

                    @if(isset($model))
                    <input name=id type=hidden value="{{$model->id}}">
                    @endif
                    <div class="row">
                        <div class="col-6 form-group">
                            <label>Name</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon11"><i class="ti-eraser"></i></span>
                                </div>
                                <input type="text" placeholder="Enter Model Name" class="form-control" name=name value="{{ (isset($model)) ? $model->MODL_NAME : old('name') }}" required>
                            </div>
                            <small class="text-danger">{{$errors->first('name')}}</small>
                        </div>

                        <div class="col-6 form-group">
                            <label>ID</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon11"><i class="ti-barcode"></i></span>
                                </div>
                                <input type="text" placeholder="Enter Model Name" class="form-control" name=serial value="{{ (isset($model)) ? $model->MODL_UNID : old('serial') }}" required>
                            </div>
                            <small class="text-danger">{{$errors->first('serial')}}</small>
                        </div>
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
                <h4 class="card-title">موديلات</h4>
                <h6 class="card-subtitle">Show Available Models</h6>
                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Show</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($models as $model)
                            <tr>
                                <td>{{$model->MODL_UNID}}</td>
                                <td>{{$model->MODL_NAME}}</td>
                                <td> @if($model->MODL_HDDN)
                                    <button class="btn btn-success mr-2" onclick="confirmAndGoTo('{{ url('models/hide/' . $model->id)}}', 'show this model')">Show</button>
                                    @else
                                    <button class="btn btn-danger mr-2" onclick="confirmAndGoTo('{{ url('models/hide/' . $model->id)}}', 'hide this model')">Hide</button>
                                    @endif
                                </td>
                                <td><a href="{{ url('models/edit/' . $model->id) }}"><img src="{{ asset('images/edit.png') }}" width=25 height=25></a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmAndGoTo(url, action){
         Swal.fire({
             text: "Are you sure you want to " + action + "?",
             icon: "warning",
             showCancelButton: true,
         }).then((isConfirm) => {
     if(isConfirm.value){
         window.location.href = url;
         }
     });
 }
</script>
@endsection