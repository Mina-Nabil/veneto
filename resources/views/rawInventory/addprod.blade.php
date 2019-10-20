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
                        <label>Raw Inventory*</label>
                        <div class="input-group mb-3">
                            <select onchange="setMax()" id=raw name=raw class="select2 form-control custom-select" style="width: 100%; height:36px;">
                                <option selected disabled>Pick From Inventory</option>
                                @foreach($raws as $raw)
                                <option value="{{ $raw->id }}">
                                    {{$raw->id}}: {{$raw->RAW_NAME}}-{{$raw->TYPS_NAME}}-{{$raw->MODL_NAME}}  ({{$raw->COLR_NAME}}) Amount: {{number_format($raw->RINV_METR, 2)}} 
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <small class="text-danger">{{$errors->first('raw')}}</small>
                    </div>


                    <div class="form-group">
                        <label>To Production</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-arrow-right"></i></span>
                            </div>
                            <input type="number" id=in step=0.01 class="form-control" placeholder="Example: 2.5" name=in value="0" required>
                        </div>
                        <small class="text-danger">{{$errors->first('in')}}</small>
                    </div>

                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                    <a href="{{url('raw/prod/show') }}" class="btn btn-dark">Cancel</a>

                </form>
            </div>
        </div>
    </div>
</div>


<script>
function setMax(){

    var data = <?php echo json_encode($raws)?>;
    var e = document.getElementById("raw");
    var selected = e.selectedIndex;
    var id = e.options[e.selectedIndex].value;
    var max = data[selected-1].RINV_METR;
    $("input").attr({"max": max});
 
}
</script>
@endsection
