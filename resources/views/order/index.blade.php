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
                <div class="modal fade" id="stack1" tabindex="-1" role="basic" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h4 class="modal-title">{{trans('orders.order_delivery')}}</h4>
                            </div>
                            <div class="modal-body form">
                                <div class="alert alert-danger hidden validation_error">
                                    {{trans('attributes.validation_error')}}
                                </div>
                                <form action="#" class="form-horizontal form-row-seperated">

                                    <div class="form-group last">
                                        <label class="col-sm-4 control-label">موصل الطلب</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">

                                                <select class="form-control select2 col-sm-12 multiple"
                                                        data-placeholder="" id="drivers"
                                                        name="drivers" multiple>
                                                    <option value=""> اختر..</option>

                                                    @foreach($list_of_drivers as $user)
                                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                </form>
                               {{-- <form id="form_sample_2" class="form-horizontal form-row-seperated">

                                    <div class="form-group last">
                                            <label for="drivers" class="col-sm-4 control-label">الموصلين
                                                المتاحين</label>


                                            <div class="col-sm-8">

                                                <select class="form-control select2 col-sm-12 "
                                                        data-placeholder="" id="drivers"
                                                        name="drivers" multiple>
                                                    <option value=""> اختر..</option>

                                                    @foreach($list_of_drivers as $user)
                                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>




                                </form>--}}
                            </div>
                            <div class="modal-footer">
                                <button type="submit"
                                        class="btn btn-success save_delivery">
                                    <span>حفظ</span><i
                                            class="fa fa-spin fa-spinner hidden"></i>
                                </button>
                                <button class="btn btn-danger"
                                        data-dismiss="modal">{{trans('attributes.cancel')}}</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <div id="stack" class="modal fade" data-backdrop="static" data-keyboard="false" data-width="400">

                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">

                                <button type="button" class="close"
                                        data-dismiss="modal"></button>
                                <h4 class="modal-title">{{trans('orders.order_delivery')}}</h4>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-danger hidden validation_error">
                                    {{trans('attributes.validation_error')}}
                                </div>
                                <form id="form_sample_2">

                                    <div class="form-body">


                                        <div class="form-group">
                                            <label for="drivers" class="col-md-3 control-label">
                                                {{trans('orders.driver')}}</label>


                                            <div class="col-md-4">

                                                <select class="form-control select2 "
                                                        data-placeholder="" id="drivers"
                                                        name="drivers" multiple>
                                                    <option value=""> اختر..</option>

                                                    @foreach($list_of_drivers as $user)
                                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                    </div>


                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="submit"
                                        class="btn btn-success save_delivery">
                                    <span>حفظ</span><i
                                            class="fa fa-spin fa-spinner hidden"></i>
                                </button>
                                <button class="btn btn-danger"
                                        data-dismiss="modal">{{trans('attributes.cancel')}}</button>
                            </div>


                        </div>
                    </div>
                </div>

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
                                    <span class="caption-subject bold uppercase"> {{trans('orders.orders')}}</span>
                                </div>

                            </div>
                            <div class="portlet-body">
                                <div class="table-toolbar">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <div class="btn-group">
                                                {{--  <a id="order"
                                                     class="btn sbold green">اضافة عرض
                                                  </a>
  --}}

                                            </div>
                                        </div>
                                        <div class="row">

                                            <div class="col-md-12">
                                                <!-- Begin: life time stats red-sunglo-->
                                                <div class="portlet box red-sunglo">
                                                    <div class="portlet-title">
                                                        <div class="caption">
                                                            <i class="fa fa-thumb-tack"></i>{{trans('orders.orders_display')}}
                                                        </div>
                                                        <div class="tools">
                                                            <a href="javascript:;" class="collapse">
                                                            </a>

                                                        </div>
                                                    </div>
                                                    <div class="portlet-body">
                                                        <div class="tabbable-line">
                                                            <ul class="nav nav-tabs">

                                                                <li class="active">
                                                                    <a href="#orderview_1" data-toggle="tab">
                                                                        {{trans('orders.pending')}} </a>
                                                                </li>
                                                                <li>
                                                                    <a href="#orderview_2" data-toggle="tab">
                                                                        {{trans('orders.assigned')}} </a>
                                                                </li>
                                                                <li>
                                                                    <a href="#orderview_3" data-toggle="tab">
                                                                       {{trans('orders.complete')}}  </a>
                                                                </li>
                                                                <li>
                                                                    <a href="#orderview_4" data-toggle="tab">
                                                                        {{trans('orders.canceled')}} </a>
                                                                </li>


                                                            </ul>
                                                            <div class="tab-content">
                                                                <div class="tab-pane active" id="orderview_1">
                                                                    <div class="scroller" style="height: 600px;">

                                                                        <table class="table table-striped table-bordered "
                                                                               id="data-table1">
                                                                            <thead>
                                                                            <tr>

                                                                                 <th>{{trans('orders.order_no')}}</th>
                                                                                <th>{{trans('orders.customer_name')}}</th>
                                                                                <th>{{trans('orders.order_status')}}</th>
                                                                                <th>يوم التسليم</th>
                                                                                <th>تاريخ التسليم</th>
                                                                                <th>الفترة</th>
                                                                                <th>{{trans('orders.order_date')}}</th>
                                                                                 <th>المدينة</th>
                                                                                <th>تفصيل العنوان</th>
                                                                                <th>{{trans('attributes.controls')}}</th>
                                                                            </tr>
                                                                            </thead>

                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                <div class="tab-pane" id="orderview_2">
                                                                    <div class="scroller" style="height: 600px;">

                                                                        <table class="table table-striped table-bordered "
                                                                               id="data-table2">
                                                                            <thead>
                                                                            <tr>

                                                                                <th>{{trans('orders.order_no')}}</th>
                                                                                <th>{{trans('orders.customer_name')}}</th>
                                                                                <th>{{trans('orders.order_status')}}</th>
                                                                                <th>{{trans('orders.driver_name')}}</th>
                                                                                <th>{{trans('orders.order_date')}}</th>
                                                                                 <th>المدينة</th>
                                                                                <th>تفصيل العنوان</th>
                                                                            {{--    <th>{{trans('attributes.controls')}}</th>--}}

                                                                            </tr>
                                                                            </thead>

                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                <div class="tab-pane" id="orderview_3">
                                                                    <div class="scroller" style="height: 600px;">

                                                                        <table class="table table-striped table-bordered "
                                                                               id="data-table3">
                                                                            <thead>
                                                                            <tr>

                                                                                <th>{{trans('orders.order_no')}}</th>
                                                                                <th>{{trans('orders.customer_name')}}</th>
                                                                                <th>{{trans('orders.order_status')}}</th>
                                                                                <th>{{trans('orders.driver_name')}}</th>
                                                                                <th>{{trans('orders.order_date')}}</th>
                                                                                 <th>المدينة</th>
                                                                                <th>تفصيل العنوان</th>
                                                                            {{--    <th>{{trans('attributes.controls')}}</th>--}}

                                                                            </tr>
                                                                            </thead>

                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                <div class="tab-pane" id="orderview_4">
                                                                    <div class="scroller" style="height: 600px;">

                                                                        <table class="table table-striped table-bordered "
                                                                               id="data-table4">
                                                                            <thead>
                                                                            <tr>

                                                                                <th>{{trans('orders.order_no')}}</th>
                                                                                <th>{{trans('orders.customer_name')}}</th>
                                                                                <th>{{trans('orders.order_status')}}</th>
                                                                               <!-- <th>{{trans('orders.driver_name')}}</th>-->
                                                                                <th>{{trans('orders.order_date')}}</th>
                                                                                <th>المدينة</th>
                                                                                <th>تفصيل العنوان</th>
                                                                              {{--  <th>{{trans('attributes.controls')}}</th>--}}

                                                                            </tr>
                                                                            </thead>

                                                                        </table>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End: life time stats -->
                                            </div>
                                        </div>
                                        {{--<div class="col-md-2">
                                            <a style="float: left"
                                               class="dt-button button-excel2 buttons-html5 btn yellow btn-outline"
                                               tabindex="0" aria-controls="data-table" href="#"><i
                                                        class="fa fa-file-excel-o"></i> {{trans('category.excel')}}</a>
                                        </div>--}}
                                    </div>

                                </div>


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
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    {{--<link href="{{url('')}}/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css"/>--}}
    <link rel="stylesheet" type="text/css" href="{{url('')}}/assets/global/plugins/select2/css/select2.css"/>
    <link href="{{url('')}}/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet"
          type="text/css"/>
    <!-- END PAGE LEVEL PLUGINS -->


    <!-- BEGIN PAGE LEVEL PLUGINS  modal-->
    <link href="{{url('')}}/assets/apps/css/todo-rtl.min.css" rel="stylesheet" type="text/css"/>

    <!-- END PAGE LEVEL PLUGINS -->


    <!-- BEGIN PAGE LEVEL PLUGINS  Tree-->
    <link href="{{url('')}}/assets/global/plugins/jstree/dist/themes/default/style.min.css" rel="stylesheet"
          type="text/css"/>
    <!-- END PAGE LEVEL PLUGINS -->


    <!-- BEGIN PAGE LEVEL PLUGINS Reapeter -->
    <link href="{{url('')}}/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css"
          rel="stylesheet" type="text/css"/>
    <!-- END PAGE LEVEL PLUGINS -->

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

        .close {
            right: 0 !important;
        }
        .multiple {


            width: 340px !important;

        }
         .portlet-body {

            padding-top: 0px !important;

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

    <!-- BEGIN PAGE LEVEL PLUGINS -->
    {{--<script src="{{url('')}}/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>--}}
    <!-- END PAGE LEVEL PLUGINS -->

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="{{url('')}}/assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>--}}
    <!-- END PAGE LEVEL SCRIPTS -->

    <!-- BEGIN PAGE LEVEL PLUGINS  modal-->
    <script src="{{url('')}}/assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->

    <!-- BEGIN PAGE LEVEL SCRIPTS modal-->
    <script src="{{url('')}}/assets/pages/scripts/ui-modals.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL SCRIPTS -->


    <!-- BEGIN PAGE LEVEL PLUGINS Tree-->
    <script src="{{url('')}}/assets/global/plugins/jstree/dist/jstree.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="{{url('')}}/assets/pages/scripts/ui-tree.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL SCRIPTS -->

    <!-- BEGIN PAGE LEVEL PLUGINS Reapeter-->
    <script src="{{url('')}}/assets/global/plugins/jquery-repeater/jquery.repeater.js" type="text/javascript"></script>
    <script src="{{url('')}}/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"
            type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->


    <!-- BEGIN PAGE LEVEL SCRIPTS Reapeter-->
    <script src="{{url('')}}/assets/pages/scripts/form-repeater.js" type="text/javascript"></script>
    <script src="{{url('')}}/assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL SCRIPTS -->


    <!-- BEGIN PAGE LEVEL PLUGINS validation-->
    <script src="{{url('')}}/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"
            type="text/javascript"></script>
    <script src="{{url('')}}/assets/global/plugins/jquery-validation/js/additional-methods.min.js"
            type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->

    <!-- BEGIN PAGE LEVEL SCRIPTS validation-->
    <script src="{{url('')}}/assets/pages/scripts/form-validation-md.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL SCRIPTS -->
    <script src="{{url('')}}/js/vue.js"></script>

    <script>


        var appVue = new Vue({
            el: "#stack1",
            data: {
                deliveries: [],
                ord_num: 0,
                ord_id: 0,
                modal_show: 0
            }
        });


        $(function () {

            /* function ajaxDriver() {
                 $.ajaxSetup({
                     headers: {
                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                     }
                 });
                 $.ajax({
                     url: "url('getDriver')",
                     method: "get",
                     data: {},
                     success: function (e) {
                         appVue.deliveries = e;
                         //alert(e);
                     }

                 });
             }*/
            $(document).ready(function () {
                $('.select2').select2();
            });
            //  var lang = {!! $languages !!};

            var table1 = $('#data-table1').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                "language": {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
                    sSearch: "{{trans('pagination.sSearch')}}",
                    sZeroRecords: "{{trans('pagination.sZeroRecords')}}",
                    sLengthMenu: "{{trans('pagination.sLengthMenu')}}",
                },
                "bInfo": false,
                order: [ [0, 'desc'] ],
                buttons: [
                    {
                        extend: 'excel',
                        className: 'btn yellow btn-outline ',
                        exportOptions: {
                            columns: [0, 2, 4]
                        }
                    }

                ],
                "dom": "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable

                ajax: {
                    url: '{{url("/")}}/order/contentListData',
                    type: 'POST',
                    data: {"status":1},
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },

                },

                columns: [

                    {data: 'ord_id', name: 'ord_id', 'class': 'ord_id'},
                    {data: 'user_name', name: 'user_name', 'class': 'user_name'},
                    {
                        data: 'ord_status_desc', name: 'ord_status',
                        render: function (data, type, full, meta) {
                            if (data == 'Pending')
                                return "<label class='label bg-blue'>" + data + "</label>";
                            else if (data == 'Assigned')
                                return "<label class='label bg-purple-plum'>" + data + "</label>";
                            else if (data == 'Inprogress')
                                return "<label class='label bg-yellow-gold'>" + data + "</label>";
                            else if (data == 'Confirm Delivery')
                                return "<label class='label bg-green-haze'>" + data + "</label>";
                            else if (data == 'Confirm Receive')
                                return "<label class='label bg-blue-chambray'>" + data + "</label>";
                            else if (data == 'Cancel')
                                return "<label class='label bg-red'>" + data + "</label>";

                        },
                        'class': 'ord_status'
                    },
                    {data: 'day_name', name: 'day_name', 'class': 'day_name'},
                    {data: 'ord_schdule_date', name: 'ord_schdule_date', 'class': 'ord_schdule_date'},
                    {data: 'schedule_period', name: 'schedule_period', 'class': 'schedule_period'},
                    {data: 'ord_createdAt', name: 'ord_createdAt', 'class': 'ord_createdAt'},
                    {data: 'city', name: 'city', 'class': 'city'},
                    {data: 'adr_address', name: 'adr_address', 'class': 'adr_address'},
                    {
                        data: 'action', name: 'action',

                        'class': 'control'
                    },


                ]
            });
            var table2 = $('#data-table2').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                "language": {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
                    sSearch: "{{trans('pagination.sSearch')}}",
                    sZeroRecords: "{{trans('pagination.sZeroRecords')}}",
                    sLengthMenu: "{{trans('pagination.sLengthMenu')}}",
                },
                "bInfo": false,
                  order: [ [0, 'desc'] ],
                buttons: [
                    {
                        extend: 'excel',
                        className: 'btn yellow btn-outline ',
                        exportOptions: {
                            columns: [0, 2, 4]
                        }
                    }

                ],
                "dom": "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable

                ajax: {
                    url: '{{url("/")}}/order/contentListData',
                    type: 'POST',
                    data: {"status":2},
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },

                },

                columns: [

                    {data: 'ord_id', name: 'ord_id', 'class': 'ord_id'},
                    {data: 'user_name', name: 'user_name', 'class': 'user_name'},
                    {
                        data: 'ord_status_desc', name: 'ord_status',
                        render: function (data, type, full, meta) {
                            if (data == 'Pending')
                                return "<label class='label bg-blue'>" + data + "</label>";
                            else if (data == 'Assigned')
                                return "<label class='label bg-purple-plum'>" + data + "</label>";
                            else if (data == 'Inprogress')
                                return "<label class='label bg-yellow-gold'>" + data + "</label>";
                            else if (data == 'Confirm Delivery')
                                return "<label class='label bg-green-haze'>" + data + "</label>";
                            else if (data == 'Confirm Receive')
                                return "<label class='label bg-blue-chambray'>" + data + "</label>";
                            else if (data == 'Cancel')
                                return "<label class='label bg-red'>" + data + "</label>";


                        },
                        'class': 'ord_status'
                    },
                    {
                        data: 'driver_names', name: 'driver_names',
                        render: function (data, type, full, meta) {
                            if (data != null)
                                return "<label class='label label-primary'>" + data + "</label>";
                            else
                                return '';

                        },

                        'class': 'driver'
                    },
                    {data: 'ord_createdAt', name: 'ord_createdAt', 'class': 'ord_createdAt'},
                     {data: 'city', name: 'city', 'class': 'city'},
                    {data: 'adr_address', name: 'adr_address', 'class': 'adr_address'},
                    /*{
                        data: 'action', name: 'action',

                        'class': 'control'
                    },*/


                ]
            });
            var table3 = $('#data-table3').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                "language": {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
                    sSearch: "{{trans('pagination.sSearch')}}",
                    sZeroRecords: "{{trans('pagination.sZeroRecords')}}",
                    sLengthMenu: "{{trans('pagination.sLengthMenu')}}",
                },
                "bInfo": false,
                  order: [ [0, 'desc'] ],
                buttons: [
                    {
                        extend: 'excel',
                        className: 'btn yellow btn-outline ',
                        exportOptions: {
                            columns: [0, 2, 4]
                        }
                    }

                ],
                "dom": "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable

                ajax: {
                    url: '{{url("/")}}/order/contentListData',
                    type: 'POST',
                    data: {"status":4},
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },

                },

                columns: [

                    {data: 'ord_id', name: 'ord_id', 'class': 'ord_id'},
                    {data: 'user_name', name: 'user_name', 'class': 'user_name'},
                    {
                        data: 'ord_status_desc', name: 'ord_status',
                        render: function (data, type, full, meta) {
                            if (data == 'Pending')
                                return "<label class='label bg-blue'>" + data + "</label>";
                            else if (data == 'Assigned')
                                return "<label class='label bg-purple-plum'>" + data + "</label>";
                            else if (data == 'Inprogress')
                                return "<label class='label bg-yellow-gold'>" + data + "</label>";
                            else if (data == 'Confirm Delivery')
                                return "<label class='label bg-green-haze'>" + data + "</label>";
                            else if (data == 'Confirm Receive')
                                return "<label class='label bg-blue-chambray'>" + data + "</label>";
                            else if (data == 'Cancel')
                                return "<label class='label bg-red'>" + data + "</label>";


                        },
                        'class': 'ord_status'
                    },
                    {
                        data: 'driver_names', name: 'driver_names',
                        render: function (data, type, full, meta) {
                            if (data != null)
                                return "<label class='label label-primary'>" + data + "</label>";
                            else
                                return '';

                        },

                        'class': 'driver'
                    },
                    {data: 'ord_createdAt', name: 'ord_createdAt', 'class': 'ord_createdAt'},
                    {data: 'city', name: 'city', 'class': 'city'},
                    {data: 'adr_address', name: 'adr_address', 'class': 'adr_address'},
                  /*  {
                        data: 'action', name: 'action',

                        'class': 'control'
                    },*/


                ]
            });
            var table4 = $('#data-table4').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                "language": {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
                    sSearch: "{{trans('pagination.sSearch')}}",
                    sZeroRecords: "{{trans('pagination.sZeroRecords')}}",
                    sLengthMenu: "{{trans('pagination.sLengthMenu')}}",
                },
                "bInfo": false,
                  order: [ [0, 'desc'] ],
                buttons: [
                    {
                        extend: 'excel',
                        className: 'btn yellow btn-outline ',
                        exportOptions: {
                            columns: [0, 2, 4]
                        }
                    }

                ],
                "dom": "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable

                ajax: {
                    url: '{{url("/")}}/order/contentListData',
                    type: 'POST',
                    data: {"status":6},
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },

                },

                columns: [

                    {data: 'ord_id', name: 'ord_id', 'class': 'ord_id'},
                    {data: 'user_name', name: 'user_name', 'class': 'user_name'},
                    {
                        data: 'ord_status_desc', name: 'ord_status',
                        render: function (data, type, full, meta) {
                            if (data == 'Pending')
                                return "<label class='label bg-blue'>" + data + "</label>";
                            else if (data == 'Assigned')
                                return "<label class='label bg-purple-plum'>" + data + "</label>";
                            else if (data == 'Inprogress')
                                return "<label class='label bg-yellow-gold'>" + data + "</label>";
                            else if (data == 'Confirm Delivery')
                                return "<label class='label bg-green-haze'>" + data + "</label>";
                            else if (data == 'Confirm Receive')
                                return "<label class='label bg-blue-chambray'>" + data + "</label>";
                            else if (data == 'Cancel')
                                return "<label class='label bg-red'>" + data + "</label>";


                        },
                        'class': 'ord_status'
                    },
                    /*{
                        data: 'driver_names', name: 'driver_names',
                        render: function (data, type, full, meta) {
                            if (data != null)
                                return "<label class='label label-primary'>" + data + "</label>";
                            else
                                return '';

                        },

                        'class': 'driver'
                    },*/
                    {data: 'ord_createdAt', name: 'ord_createdAt', 'class': 'ord_createdAt'},
                     {data: 'city', name: 'city', 'class': 'city'},
                    {data: 'adr_address', name: 'adr_address', 'class': 'adr_address'},
                    /*{
                        data: 'action', name: 'action',

                        'class': 'control'
                    },*/


                ]
            });


            $('#data-table1').on('click', '.delivery_order', function () {

                //  $('input[name="radio2"]').prop('checked', false);
                appVue.ord_num = $(this).parents('tr').find('.ord_number').text();
                appVue.ord_id = $(this).parent().find('.ord_id_hidden').val();
                appVue.modal_show = 1;
                $('#stack1').modal('show');

            });

            $('.save_delivery').on('click', function () {
                var delivery_id = $("#drivers").val();

                var ord_id = appVue.ord_id;

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{url('assignDriverToOrder')}}",
                    method: "get",
                    data: {delivery_id: delivery_id, ord_id: ord_id},
                    success: function (e) {
                        var new_url1 = '{{url("/")}}/order/contentListData/1';
                        var new_url2 = '{{url("/")}}/order/contentListData/2';
                        table1.ajax.url(new_url1).load();
                        table2.ajax.url(new_url2).load();
                        appVue.modal_show = 0;
                        // ajaxDriver();
                        $('#stack1').modal('hide');

                    }

                });


            });

            //  ajaxDriver();
            setInterval(function () {
                if (appVue.modal_show == 1) {
                    //       ajaxDriver();
                }

            }, 10000);

        });


    </script>
@endpush
