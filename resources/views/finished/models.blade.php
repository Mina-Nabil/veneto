@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{$pageTitle}}</h4>
                <h5 class="card-subtitle">Add/Edit Brands</h5>
                <form class="form pt-3" method="post" action="{{ $formURL }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="input-group mb-3">
                                <select name=model[] class="select2 form-control custom-select" required>
                                    <option disabled hidden selected value="">Models</option>
                                    @foreach($models as $model)
                                    <option value="{{ $model->id }}">
                                        {{$model->MODL_UNID}} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="input-group mb-3">
                                <select name=brand[] class="select2 form-control custom-select" style="width: 100%; height:50px;" required>
                                    <option disabled selected hidden value="">Brands</option>
                                    @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">
                                        {{$brand->BRND_NAME}} </option>
                                    @endforeach
                                </select>
                            </div>
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
                <h4 class="card-title">ماركات</h4>
                <h6 class="card-subtitle">Show Available Models</h6>
                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]">
                        <thead>
                            <tr>
                                <th>Brand</th>
                                <th>Model</th>
                                <th>Empty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($finished['data'] as $row)
                            <tr>
                                <td>{{$row->BRND_NAME}}</td>
                                <td>{{$row->MODL_UNID}}</td>
                                <td><button class="btn btn-warning mr-2" onclick="confirmAndGoTo('{{ url('finished/empty/' . $row->id)}}', 'Delete All Inventory Items')">Empty Inventory</button></td>
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