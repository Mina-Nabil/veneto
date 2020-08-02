@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Targets History</h4>
                <h5 class="card-subtitle">Show Previous Targets Data</h5>
                <form class="form pt-3" method="post" action="{{ $targetHistoryFormURL }}" enctype="multipart/form-data" >
                @csrf
             

                    <div class="form-group">
                        <label>Year</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-calendar"></i></span>
                            </div>
                            <input type="text" class="form-control" name=year min=2020 required>
                        </div>
                        <small class="text-danger">{{$errors->first('year')}}</small>
                    </div>

                    <div class="form-group">
                        <label>Month</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon11"><i class="ti-calendar"></i></span>
                            </div>
                            <select class="form-control" name=month  required>
                                <option value=1>January</option>
                                <option value=2>February</option>
                                <option value=3>March</option>
                                <option value=4>April</option>
                                <option value=5>May</option>
                                <option value=6>June</option>
                                <option value=7>July</option>
                                <option value=8>August</option>
                                <option value=9>September</option>
                                <option value=10>October</option>
                                <option value=11>November</option>
                                <option value=12>December</option>
                            </select>
                        </div>
                        <small class="text-danger">{{$errors->first('month')}}</small>
                    </div>

                    <button type="submit" class="btn btn-success mr-2">Submit</button>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection