@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{$reportTitle}}</h4>
                <h6 class="card-subtitle">{{$reportDesc}}</h6>
                <div class="table-responsive m-t-40">
                    <table id="myTable-client" class="table color-bordered-table table-striped full-color-table full-info-table hover-table" data-display-length='-1' data-order="[]">
                        <thead>
                            <tr>
                                <th>تاريخ</th>
                                <th>وصف</th>
                                <th>مبيعات</th>
                                <th>نقديه</th>
                                <th>اوراق دفع</th>
                                <th>خصم</th>
                                <th>مرتجع</th>
                                <th>رصيد</th>
                                <th>Comment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ops as $op)
                            <tr>
                                <td>
                                    {{date_format(date_create($op->CLTR_DATE), "d-m-Y")}}
                                </td>
                                <td>
                                    <?php 
                                    $salesArr = explode(' ', $op->CLTR_CMNT) ;
                                    if(isset($op->CLTR_DESC)){
                                        $descArr = explode(' ', $op->CLTR_DESC) ;
                                       
                                    }
                                ?>
                                    @if(isset($descArr) && $descArr[0]=='Sales' && is_numeric($descArr[1]))
                                    <a href="{{url('/sales/items/' . $descArr[1]) }}">
                                        مبيعات {{$descArr[1]}}
                                    </a>
                                    @elseif(isset($descArr) && $descArr[0]=='Sales' && $descArr[1]=='Return')
                                    <a href="{{url('/sales/items/' . $descArr[2]) }}">
                                        مرتجع {{$descArr[2]}}
                                    </a>
                                    @elseif($salesArr[0]=='Sales' && is_numeric($salesArr[1]) && (!isset($salesArr[2])
                                    || $salesArr[2]=='Comment:') )
                                    <a href="{{url('/sales/items/' . $salesArr[1]) }}">
                                        مبيعات {{$salesArr[1]}}
                                    </a>

                                    @else
                                    {{ (strlen($op->CLTR_DESC)>25) ?  mb_substr($op->CLTR_DESC,0,22, "utf-8") . '...' : $op->CLTR_DESC}}
                                    @endif
                                </td>
                                <td>{{number_format($op->CLTR_SALS_AMNT, 2)}}</td>
                                <td>{{number_format($op->CLTR_CASH_AMNT, 2)}}</td>
                                <td>{{number_format($op->CLTR_NTPY_AMNT, 2)}}</td>
                                <td>{{number_format($op->CLTR_DISC_AMNT, 2)}}</td>
                                <td>{{number_format($op->CLTR_RTRN_AMNT, 2)}}</td>
                                <td>{{number_format($op->CLTR_BLNC, 2)}}</td>
                                <td>
                                    @if(isset($op->CLTR_CMNT) && strcmp($op->CLTR_CMNT, '')!=0 )
                                    <button type="button" style="padding:.1rem" class="btn btn-secondary" data-container="body" title="" data-toggle="popover" data-placement="bottom"
                                        data-content="{{$op->CLTR_CMNT}}" data-original-title="Comment:">
                                        @endif
                                        <i class="far fa-list-alt"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                        @if(isset($totals))
                        <tfoot>
                            <tr>
                                <td ><strong>Start Balance</strong></td>
                                <td ><strong>{{number_format($startBalance, 2)}}</strong></td>
                                <td><strong>{{number_format($totals->totalPurch, 2)}} </strong></td>
                                <td><strong>{{number_format($totals->totalCash, 2)}}</strong></td>
                                <td><strong>{{number_format($totals->totalNotes, 2)}}</strong></td>
                                <td><strong>{{number_format($totals->totalDisc, 2)}}</strong></td>
                                <td><strong>{{number_format($totals->totalReturn, 2)}}</strong></td>
                                <td ><strong>End: {{number_format($balance, 2)}}</strong></td>
                                <td ></td>

                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/node_modules/jquery/jquery-3.2.1.min.js') }}"></script>
<!-- This is data table -->
<script src="{{ asset('assets/node_modules/datatables/datatables.min.js') }}"></script>

<!-- start - This is for export functionality only -->
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
<script>
    const dclient = new Date();
        const yearClient = dclient.getFullYear(); // 2019
        const dayClient = dclient.getDate();
        const monthClient = dclient.getMonth()+1;
        const formattedClient = dayClient + "/" + monthClient + "/" + yearClient;


    var table = $('#myTable-client').DataTable({
                    "displayLength": 25,
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            extend: 'print',
                            exportOptions: {
                                columns: [ 0, 1, 2, 3,4,5 ,6,7]
                            },
                            text: 'Print',
                            title: 'Veneto - {{$reportTitle}}',
                            footer: true,
                            messageTop: "Date: " + formattedClient,
                            customize: function (win) {
                                $(win.document.body)
                                    .prepend('<center><img src="{{asset('images / dark - logo.png')}}" style="position:absolute; margin: auto; ; margin-top: 460px ; left: 0; right: 0; opacity:0.2" /></center>')
                                    .css('font-size', '24px')

                                //$('#stampHeader' ).addClass( 'stampHeader' );
                                $(win.document.body).find('table')
                                    .css('border', 'solid')
                                    .css('margin-top', '20px')
                                    .css('font-size', 'inherit');
                                $(win.document.body).find('th')
                                    .css('border', 'solid')
                                    .css('border', '!important')
                                    .css('border-width', '1px')
                                    .css('font-size', 'inherit')
                                $(win.document.body).find('td')
                                    .css('border', 'solid')
                                    .css('border', '!important')
                                    .css('border-width', '1px');
                                $(win.document.body).find('tr')
                                    .css('border', 'solid')
                                    .css('border', '!important')
                                    .css('border-width', '1px')
                            }
                        }, {
                            extend: 'excel',
                            title: 'Veneto',
                            footer: true,

                        }
                    ]
                });

</script>
@endsection