@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                
                <div class="row">
                    <h4 class="col-lg-7 card-title">{{$sales->CLNT_NAME}} Sales #{{$sales->id}} Summary</h4>
                    <div class="col-lg-5 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                            <a style="font-family: 'Allerta Stencil'" href="{{url('sales/invoice/'.$sales->id)}}" class="btn btn-success d-none d-lg-block m-l-15">Invoice</a>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <strong>Date:</strong>
                        <p>{{$sales->SALS_DATE}}</p>
                    </div>

                    <div class="col-lg-3">
                        <strong>Number of Items:</strong>
                        <p id=numberOfInv >{{$totalNum}}</p>
                    </div>
                    
                    <div class="col-lg-3">
                        <strong>Total Price:</strong>
                        <p id=totalPrice >{{$sales->SALS_TOTL_PRCE}}</p>
                    </div>

                    <div class="col-lg-3">
                        <strong>Total Paid:</strong>
                        <p id=totalPrice >{{$sales->SALS_PAID}}</p>
                    </div>
                </div>
                <div class=row>
                    <div class="col-lg-12">
                    <strong>Comment</strong>
                    <p>{{($sales->SALS_CMNT) ?? 'N/A'}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Items Sold</h4>
                <h6 class="card-subtitle">Show All Invoice items</h6>
                <div class="table-responsive m-t-40">
                    <table id="myTable" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]" >
                        <thead>
                            <tr>
                                <th>ماركه</th>
                                <th>صنف</th>
                                <th>موديل#</th>
                                <th>36</th>
                                <th>38</th>
                                <th>40</th>
                                <th>42</th>
                                <th>44</th>
                                <th>46</th>
                                <th>48</th>
                                <th>50</th>
                                <th>عدد</th>
                                <th>سعر</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                            <tr>
                                <td><strong>{{$item->BRND_NAME}}</strong></td>
                                <td><strong>{{$item->MODL_UNID}}</strong></td>
                                <td><strong>{{$item->RAW_NAME}}-{{$item->TYPS_NAME}}</strong></td>
                                <td><strong>{{$item->FNSH_36_SOLD}}</strong></td>
                                <td><strong>{{$item->FNSH_38_SOLD}}</strong></td>
                                <td><strong>{{$item->FNSH_40_SOLD}}</strong></td>
                                <td><strong>{{$item->FNSH_42_SOLD}}</strong></td>
                                <td><strong>{{$item->FNSH_44_SOLD}}</strong></td>
                                <td><strong>{{$item->FNSH_46_SOLD}}</strong></td>
                                <td><strong>{{$item->FNSH_48_SOLD}}</strong></td>
                                <td><strong>{{$item->FNSH_50_SOLD}}</strong></td>
                                <td><strong>{{$item->itemsCount}}</strong></td>
                                <td><strong>{{$item->SLIT_PRCE}}</strong></td>
                            </tr> 
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                            <td style="text-align:left" colspan=11><strong>Total Price: </strong></td>
                            <td colspan=2><strong>{{number_format($sales->SALS_TOTL_PRCE, 2)}}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection