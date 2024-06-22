@extends('layouts.main')

@section('content')
    <div class="page-container">

    @include('includes.side_menu')
    <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <!-- BEGIN CONTENT BODY -->
            <div class="page-content">
                <!-- BEGIN PAGE BAR -->
            @include('includes.breadcrumb')
            <!-- The Modal -->

                <!-- END PAGE BAR --> <!-- BEGIN PAGE TITLE-->

                <div class="row" style="margin-top: 30px;">

                    <div class="col-md-12">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption font-dark">
                                    <i class="icon-settings font-dark"></i>
                                    <span class="caption-subject bold uppercase"> عرض تفاصيل الطلب</span>
                                </div>

                            </div>
                            <div class="portlet-body">
                                <div class="table-toolbar">


                                </div>

                                <h3 style="color: #1BBC9B">بيانات الزبون</h3>
                                <hr/>
                                <table id="data-table"
                                       class="table table-striped table-bordered ">
                                    <thead>
                                    <tr>

                                        <th>{{trans('users.name')}}</th>
                                        <th>{{trans('users.email')}}</th>
                                        <th>{{trans('users.mobile')}}</th>
                                        <th>{{trans('users.add_date')}}</th>


                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>{{$user->mobile}}</td>
                                        <td>{{$user->created_at}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                                <br/>
                                <h3 style="color:#1BBC9B">بيانات الطلب</h3>
                                <hr/>
                                <table id="data-table"
                                       class="table table-striped table-bordered ">
                                    <thead>
                                    <tr>

                                        <th>رقم الطلب</th>
                                        <th>تاريخ الطلب</th>
                                        <th>المبلغ المطلوب</th>
                                        <th>الكوبون</th>


                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>{{$order->ord_id}}</td>
                                        <td>{{$order->ord_createdAt}}</td>
                                        <td>{{$order->ord_totalAfterTax}}</td>
                                        <td>{{$order->coupon_id}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                                <table id="data-table"
                                       class="table table-striped table-bordered ">
                                    <thead>
                                    <tr>

                                        <th>#</th>
                                        <th>المنتج</th>
                                        <th>الكمية المطلوبة</th>
                                        <th>المبلغ قبل الخصم</th>
                                        <th>المبلغ بعد الخصم</th>


                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i = 1 ?>
                                    @foreach($orderDetails as $item)

                                        <tr>
                                            <td> {{$i++}}</td>
                                            <td> {{getProductTranslation($item->prd_id)->ptr_name}}</td>
                                            <td>{{$item->odt_quantity}}</td>
                                            <td>{{$item->odt_price}}</td>
                                            <td>{{$item->odt_discount}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- END EXAMPLE TABLE PORTLET-->
                    </div>
                </div>

            </div>
            <!-- /Main Content -->
        </div>
        <!-- END CONTENT BODY -->
    </div>
    <!-- END CONTENT -->

    <!-- END CONTAINER -->
    @include('includes/footer')
@stop

@push('css')
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="{{url('')}}/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css"/>
    <link href="{{url('')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap-rtl.css"
          rel="stylesheet" type="text/css"/>
    <!-- END PAGE LEVEL PLUGINS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">

    <link href="{{url('')}}/assets/global/plugins/bootstrap-toastr/toastr.min.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <link rel="stylesheet" href="{{asset('css/slideShowImage.css')}}">
    <style>
        .btn-default.btn-on-1.active {
            background-color: #006FFC;
            color: white;
        }

        .btn-default.btn-off-1.active {
            background-color: #DA4F49;
            color: white;
        }

        .hidden {
            display: none;
        }

        .dataTables_wrapper .dataTables_processing {
            width: 200px;
            display: inline-block;
            padding: 7px;
            left: 50%;
            margin-left: -100px;
            margin-top: 10px;
            text-align: center;
            color: #3f444a;
            border: none;
            vertical-align: middle;
            -webkit-box-shadow: none;
            -moz-box-shadow: none;
            box-shadow: none;
            z-index: 100 !important;
        }

        .dataTables_wrapper .dataTables_processing {

            top: 60%;
        }

        .btn.btn-outline.yellow {
            border-color: #2e7b21;
            color: #2e7b21;
            background: 0 0;
        }

        .btn.btn-outline.yellow:hover {
            background-color: #209538;
        }

        .button-excel2:focus {
            background-color: #209538 !important;
        }

        .dataTables_wrapper .dataTables_filter input {
            margin-left: 0;
        }

    </style>
@endpush

@push('js')

    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="{{url('')}}/assets/global/scripts/datatable.js" type="text/javascript"></script>
    <script src="{{url('')}}/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
    <script src="{{url('')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js"
            type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="{{url('')}}/assets/pages/scripts/table-datatables-managed.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL SCRIPTS -->

@endpush
