@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">All Items Sold</h4>
                <h6 class="card-subtitle">Show All Invoice items</h6>
                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
                        <thead>
                            <tr>
                                <th>ماركه</th>
                                <th>صنف</th>
                                <th>موديل</th>
                                <th>36</th>
                                <th>38</th>
                                <th>40</th>
                                <th>42</th>
                                <th>44</th>
                                <th>46</th>
                                <th>48</th>
                                <th>50</th>
                                <th>عدد</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items['data'] as $item)
                            <tr>
                                <td>{{$item->BRND_NAME}}</td>
                                <td>{{$item->MODL_UNID}}</td>
                                <td>{{$item->RAW_NAME}}-{{$item->TYPS_NAME}}</td>
                                <td>{{$item->sold36}}</td>
                                <td>{{$item->sold38}}</td>
                                <td>{{$item->sold40}}</td>
                                <td>{{$item->sold42}}</td>
                                <td>{{$item->sold44}}</td>
                                <td>{{$item->sold46}}</td>
                                <td>{{$item->sold48}}</td>
                                <td>{{$item->sold50}}</td>
                                <td>{{$item->itemsCount}}</td>
                            </tr> 
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan=3><strong>Totals</strong></td>
                                <td><strong>{{$items['totals']->sold36}}</strong></td>
                                <td><strong>{{$items['totals']->sold40}}</strong></td>
                                <td><strong>{{$items['totals']->sold38}}</strong></td>
                                <td><strong>{{$items['totals']->sold42}}</strong></td>
                                <td><strong>{{$items['totals']->sold44}}</strong></td>
                                <td><strong>{{$items['totals']->sold46}}</strong></td>
                                <td><strong>{{$items['totals']->sold48}}</strong></td>
                                <td><strong>{{$items['totals']->sold50}}</strong></td>
                                <td><strong>{{$items['totals']->itemsCount}}</strong></td>
                            </tr> 
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection