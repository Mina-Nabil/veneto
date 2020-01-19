@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Finished Inventory</h4>
                <h6 class="card-subtitle">Show All Available Finished items</h6>
                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
                        <thead>
                            <tr>
                                <th>موديل</th>
                                <th>صنف</th>
                                <th>ماركه</th>
                                <th>سعر</th>
                                <th>36</th>
                                <th>38</th>
                                <th>40</th>
                                <th>42</th>
                                <th>44</th>
                                <th>46</th>
                                <th>48</th>
                                <th>50</th>
                                <th>عدد</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($finished['data'] as $row)
                            <tr>
                                <td>{{$row->MODL_UNID}}</td>
                                <td>{{$row->RAW_NAME}}-{{$row->TYPS_NAME}}</td>
                                <td>{{$row->BRND_NAME}}</td>
                                <td>{{$row->FNSH_PRCE}}</td>
                                <td>{{$row->FNSH_36_AMNT}}</td>
                                <td>{{$row->FNSH_38_AMNT}}</td>
                                <td>{{$row->FNSH_40_AMNT}}</td>
                                <td>{{$row->FNSH_42_AMNT}}</td>
                                <td>{{$row->FNSH_44_AMNT}}</td>
                                <td>{{$row->FNSH_46_AMNT}}</td>
                                <td>{{$row->FNSH_48_AMNT}}</td>
                                <td>{{$row->FNSH_50_AMNT}}</td>
                                <td>{{$row->itemsCount}}</td>
                                <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding:.1rem .2rem" >
                                        Action
                                    </button>
                                    <div class="dropdown-menu">
                                        <button class="dropdown-item" data-toggle="modal" data-target="#editPrice{{$row->id}}" >Edit Price</button>
                                    </div> 
                            
                                </td>
                            </tr> 

                            <div id="editPrice{{$row->id}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Edit Item Price</h4>
                                            @if(isset($row->MODL_IMGE))
                                        <img src="{{ asset( 'storage/'. $row->MODL_IMGE ) }}" width=50 height=50>
                                    @endif
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        </div>
                                        <form action="{{ url('finished/edit/price') }}" method=post>
                                        @csrf
                                        <div class="modal-body">
                                            <input type=hidden name=id value="{{$row->id}}" >

                                                <div class="form-group col-md-12 m-t-0">
                                                <h5>Price</h5>
                                                    <input type="number" step=0.01 min=0 class="form-control form-control-line" name=price  required >
                                                </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-warning waves-effect waves-light">Submit</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan=4><strong>Totals:</strong></td>
                                <td><strong>{{$finished['totals']->total36}}</strong></td>
                                <td><strong>{{$finished['totals']->total38}}</strong></td>
                                <td><strong>{{$finished['totals']->total40}}</strong></td>
                                <td><strong>{{$finished['totals']->total42}}</strong></td>
                                <td><strong>{{$finished['totals']->total44}}</strong></td>
                                <td><strong>{{$finished['totals']->total46}}</strong></td>
                                <td><strong>{{$finished['totals']->total48}}</strong></td>
                                <td><strong>{{$finished['totals']->total50}}</strong></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
