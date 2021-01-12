@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row justify-content-center" >
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Previous Years</div>
                <div class="card-body">
                    <form class="form pt-3" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Year</label>
                            <div class="input-group mb-3">
                                <select name=year class="select form-control custom-select" style="width: 100%; height:36px;">
                                    <option disabled>Pick A Year</option>
                                    @foreach($years as $year)
                                    <option value="{{ $year->toto }}"  
                                        @if($thisYear == $year->toto)
                                        selected
                                        @endif                                        
                                        >{{$year->toto}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <small class="text-danger">{{$errors->first('year')}}</small>
                        </div>
                        <div class=p-10>
                            <button type="submit" id=checker class="btn btn-success mr-2">&emsp;Submit&emsp;</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">مصاريف مجمعه</div>

                <div class="card-body" style="overflow-x: scroll; width:auto; white-space: nowrap;">
                    <table id="myTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]">
                        <thead>
                            <tr>
                                <th>بيان</th>
                                <th>يناير</th>
                                <th>فبراير</th>
                                <th>مارس</th>
                                <th>ابريل</th>
                                <th>مايو</th>
                                <th>يونيو</th>
                                <th>يوليو</th>
                                <th>اغسطس</th>
                                <th>سبتمبر</th>
                                <th>اكتوبر</th>
                                <th>نوفمبر</th>
                                <th>ديسمبر</th>
                                <th>اجمالي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($masareef as $masroof)
                            <tr>
                                <td><strong>{{$masroof['subTypeName']}}</strong></td>

                                <td>{{number_format($masroof[1] ->totalIn - $masroof[1] ->totalOut, 2)}}</td>
                                <td>{{number_format($masroof[2] ->totalIn - $masroof[2] ->totalOut, 2)}}</td>
                                <td>{{number_format($masroof[3] ->totalIn - $masroof[3] ->totalOut, 2)}}</td>
                                <td>{{number_format($masroof[4] ->totalIn - $masroof[4] ->totalOut, 2)}}</td>
                                <td>{{number_format($masroof[5] ->totalIn - $masroof[5] ->totalOut, 2)}}</td>
                                <td>{{number_format($masroof[6] ->totalIn - $masroof[6] ->totalOut, 2)}}</td>
                                <td>{{number_format($masroof[7] ->totalIn - $masroof[7] ->totalOut, 2)}}</td>
                                <td>{{number_format($masroof[8] ->totalIn - $masroof[8] ->totalOut, 2)}}</td>
                                <td>{{number_format($masroof[9] ->totalIn - $masroof[9] ->totalOut, 2)}}</td>
                                <td>{{number_format($masroof[10]->totalIn - $masroof[10]->totalOut, 2)}}</td>
                                <td>{{number_format($masroof[11]->totalIn - $masroof[11]->totalOut, 2)}}</td>
                                <td>{{number_format($masroof[12]->totalIn - $masroof[12]->totalOut, 2)}}</td>
                                <td><strong>{{number_format($masroof[13]->totalIn - $masroof[13]->totalOut, 2)}}</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
    
                            <td><strong>Totals</strong></td>

                            <td>{{number_format($totals['masareef'][1], 2)}}</td>
                            <td>{{number_format($totals['masareef'][2], 2)}}</td>
                            <td>{{number_format($totals['masareef'][3], 2)}}</td>
                            <td>{{number_format($totals['masareef'][4], 2)}}</td>
                            <td>{{number_format($totals['masareef'][5], 2)}}</td>
                            <td>{{number_format($totals['masareef'][6], 2)}}</td>
                            <td>{{number_format($totals['masareef'][7], 2)}}</td>
                            <td>{{number_format($totals['masareef'][8], 2)}}</td>
                            <td>{{number_format($totals['masareef'][9], 2)}}</td>
                            <td>{{number_format($totals['masareef'][10], 2)}}</td>
                            <td>{{number_format($totals['masareef'][11], 2)}}</td>
                            <td>{{number_format($totals['masareef'][12], 2)}}</td>
                            <td><strong>{{number_format($totals['masareef'][13], 2)}}</strong></td>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection