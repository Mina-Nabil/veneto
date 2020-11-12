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

                    @if(isset($brand))
                    <input name=id type=hidden value="{{$brand->id}}">
                    @endif

                    <div class="form-group">
                        <label>Brands</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-eraser"></i></span>
                            </div>
                            <input type="text" placeholder="Enter Brand Name, Example: Via Veneto" class="form-control" name=name value="{{ (isset($brand)) ? $brand->BRND_NAME : old('name') }}"
                                required>
                        </div>
                        <small class="text-danger">{{$errors->first('name')}}</small>
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
                <h6 class="card-subtitle">Show Available Brands</h6>
                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Show</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($brands as $brand)
                            <tr>
                                <td>{{$brand->BRND_NAME}}</td>
                                <td> @if($brand->BRND_HDDN)
                                    <button class="btn btn-success mr-2" onclick="confirmAndGoTo('{{ url('brands/hide/' . $brand->id)}}', 'show this brand')">Show</button>
                                    @else
                                    <button class="btn btn-danger mr-2" onclick="confirmAndGoTo('{{ url('brands/hide/' . $brand->id)}}', 'hide this brand')">Hide</button>
                                    @endif
                                </td>
                                <td><a href="{{ url('brands/edit/' . $brand->id) }}"><img src="{{ asset('images/edit.png') }}" width=25 height=25></a></td>
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

