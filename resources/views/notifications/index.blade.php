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

            <!-- END PAGE BAR --> <!-- BEGIN PAGE TITLE-->
   <div id="notiModal" class="modal fade" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h4 class="modal-title">الإشعارات</h4>
                            </div>
                            <div class="modal-body">
                                <div class="scroller" style="height:300px" data-always-visible="1" data-rail-visible1="1">
                                    <br/>

                                    {!! Form::open(array('url' => 'notifications/send_fcm', 'method' => 'post', 'id' => 'noti_form')) !!}

                                    {{--<form class="form-horizontal" role="form" method="post" action="{{url('sendMultipleFcm')}}">--}}
                                        <div class="form-body">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">نوع الإشعار</label>
                                                <div class="col-md-9">
                                                    <select class="form-control custList" id="not_type" name="not_type">
                                                        <option value="2">اشعار عام</option>
                                                       <!-- <option value="3">عروض</option>-->
                                                    </select>
                                                </div>
                                            </div>
                                            <br/>

                                            <hr>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">العنوان</label>
                                                <div class="col-md-9">
                                                    <input type="text" name="title" class="form-control" placeholder="عنوان الإشعار"> </div>
                                            </div>
                                            <br/>
                                            <hr>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">النص</label>
                                                <div class="col-md-9">
                                                    <textarea autocomplete="off" class="form-control" rows="3" name="notification" cols="50"></textarea> </div>
                                            </div>
                                              <div class="form-group">
                                            <label class="control-label col-md-4">تاريخ نهاية الاشعار</label>
                                            <div class="col-md-8">
                                                <div class="input-group input-medium date date-picker" data-date-format="yyyy-mm-dd" data-date-start-date="+0d">
                                                    <input type="text" class="form-control" name="expire_date" id="expire_date" readonly>
                                                    <span class="input-group-btn">
                                                                            <button class="btn default" type="button">
                                                                                <i class="fa fa-calendar"></i>
                                                                            </button>
                                                                        </span>
                                                </div>
                                                <!-- /input-group -->
                                                {{--<span class="help-block"> Select date </span>--}}
                                            </div>
                                        </div>



                                        </div>
                                        <div class="form-actions right">
                                            <button type="button" data-dismiss="modal" class="btn dark btn-outline">إلغاء</button>
                                             <button type="submit" class="btn green">إرسال</button>
                                        </div>

                                    </form>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                           <!-- <div class="modal-footer">
                                <button type="button" data-dismiss="modal" class="btn dark btn-outline">إلغاء</button>
                                <button type="submit" class="btn green">إرسال</button>
                            </div>-->
                        </div>
                    </div>
                </div>

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
                                    <span class="caption-subject bold uppercase"> الإشعارات</span>
                                </div>
                                <div class= "row">
                                    <div class="col-md-2">
                                        <div class="btn-group">
                                            <a class="btn sbold green" data-toggle="modal"
                                               data-target="#notiModal"> اضافة إشعار جديد  </a>



                                        </div>
                                    </div>
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
                                                <!-- Begin: life time  stats red-sunglo-->
                                                <div class="portlet box red-sunglo">
                                                    <div class="portlet-title">
                                                        <div class="caption">
                                                            <i class="fa fa-thumb-tack"></i>عرض الإشعارات
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
                                                                    <a href="#notview_1" data-toggle="tab">
                                                                        اشعارات الطلبات الجديدة </a>
                                                                </li>
                                                                <li>
                                                                    <a href="#notview_2" data-toggle="tab"> كل اشعارات الطلبات</a>
                                                                </li>
                                                               <li>
                                                                    <a href="#notview_3" data-toggle="tab">
                                                                        اشعارات عامة </a>
                                                                </li>
                                                                <li>
                                                                    <a href="#notview_4" data-toggle="tab">
                                                                        العروض </a>
                                                                </li>
                                                            </ul>
                                                            <div class="tab-content">
                                                                <div class="tab-pane active" id="notview_1">
                                                                    <div class="scroller" style="height: 600px;">

                                                                        <table class="table table-striped table-bordered "
                                                                               id="data-table1">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>{{trans('notifications.not_id')}}</th>
                                                                                {{--  <th>{{trans('notifications.not_type')}}</th>--}}
                                                                                <th>{{trans('notifications.not_title')}}</th>
                                                                                <th>{{trans('notifications.not_text')}}</th>
                                                                                <th>{{trans('notifications.not_date')}}</th>
                                                                                <th>{{trans('notifications.seen_date')}}</th>
                                                                                <th>{{trans('orders.order_no')}}</th>
                                                                                <th>{{trans('orders.customer_name')}}</th>
                                                                                <th>{{trans('orders.order_status')}}</th>
                                                                                {{--   <th>{{trans('orders.driver_name')}}</th>--}}
                                                                                <th>{{trans('orders.order_date')}}</th>
                                                                                <th>{{trans('attributes.controls')}}</th>

                                                                            </tr>
                                                                            </thead>

                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                <div class="tab-pane" id="notview_2">
                                                                    <div class="scroller" style="height: 600px;">

                                                                        <table class="table table-striped table-bordered "
                                                                               id="data-table2">
                                                                            <thead>
                                                                            <tr>

                                                                                <th>{{trans('notifications.not_id')}}</th>
                                                                                {{--    <th>{{trans('notifications.not_type')}}</th>--}}
                                                                                <th>{{trans('notifications.not_title')}}</th>
                                                                                <th>{{trans('notifications.not_text')}}</th>
                                                                                <th>{{trans('notifications.not_date')}}</th>
                                                                                <th>{{trans('notifications.seen_date')}}</th>
                                                                                <th>{{trans('orders.order_no')}}</th>
                                                                                <th>{{trans('orders.customer_name')}}</th>
                                                                                <th>{{trans('orders.order_status')}}</th>
                                                                                {{--  <th>{{trans('orders.driver_name')}}</th>--}}
                                                                                <th>{{trans('orders.order_date')}}</th>
                                                                                <th>{{trans('attributes.controls')}}</th>

                                                                            </tr>
                                                                            </thead>

                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                 <div class="tab-pane" id="notview_3">
                                                                    <div class="scroller" style="height: 600px;">

                                                                        <table class="table table-striped table-bordered "
                                                                               id="data-table3">
                                                                            <thead>
                                                                            <tr>

                                                                                <th>{{trans('notifications.not_id')}}</th>
                                                                                <th>{{trans('notifications.not_title')}}</th>
                                                                                <th>{{trans('notifications.not_text')}}</th>
                                                                                <th>{{trans('notifications.not_date')}}</th>


                                                                            </tr>
                                                                            </thead>

                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                   <div class="tab-pane" id="notview_4">
                                                                    <div class="scroller" style="height: 600px;">

                                                                        <table class="table table-striped table-bordered "
                                                                               id="data-table4">
                                                                            <thead>
                                                                            <tr>

                                                                                <th>{{trans('notifications.not_id')}}</th>
                                                                                <th>{{trans('notifications.not_title')}}</th>
                                                                                <th>{{trans('notifications.not_text')}}</th>
                                                                                <th>{{trans('notifications.not_date')}}</th>


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
        .custList {
             height: 40px !important;
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
                    url: '{{url("/")}}/notifications/contentListData/1',
                    type: 'POST',
                    data: function (d) {

                        // d.parent = $('#single-prepend-text').val();
                        //  d.status = $('#single-prepend-text2').val();
                    },
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },

                },


                /*
                                <th>{{trans('notifications.not_id')}}</th>
                <th>{{trans('notifications.not_type')}}</th>
                <th>{{trans('notifications.not_title')}}</th>
                <th>{{trans('notifications.not_text')}}</th>
                <th>{{trans('notifications.not_date')}}</th>
                <th>{{trans('notifications.seen_date')}}</th>
                <th>{{trans('orders.order_no')}}</th>
                <th>{{trans('orders.customer_name')}}</th>
                <th>{{trans('orders.order_status')}}</th>
                <th>{{trans('orders.driver_name')}}</th>
                <th>{{trans('orders.order_date')}}</th>
                <th>{{trans('attributes.controls')}}</th>
*/

                columns: [

                    {data: 'not_id', name: 'not_id', 'class': 'not_id'},
                    /*   {data: 'not_type', name: 'not_type', 'class': 'not_type'},*/
                    {data: 'notification_title', name: 'notification_title', 'class': 'notification_title'},
                    {data: 'not_ar', name: 'not_ar', 'class': 'not_ar'},
                    {data: 'not_date', name: 'not_date', 'class': 'not_date'},
                    {data: 'seen_date', name: 'seen_date', 'class': 'seen_date'},
                    {data: 'ord_id', name: 'ord_id', 'class': 'ord_id'},
                    {data: 'name', name: 'name', 'class': 'name'},
                    {
                        data: 'ord_status', name: 'ord_status',
                        render: function (data, type, full, meta) {
                            if (data == '1')
                                return "<label class='label bg-blue'>Pending</label>";
                            else if (data == '2')
                                return "<label class='label bg-purple-plum'>Assigned</label>";
                            else if (data == '3')
                                return "<label class='label bg-yellow-gold'>Inprogress</label>";
                            else if (data == '4')
                                return "<label class='label bg-green-haze'>Confirm Delivery</label>";
                            else if (data == '5')
                                return "<label class='label bg-blue-chambray'>Confirm Receive</label>";
                            else if (data == '6')
                                return "<label class='label bg-red'>Cancel</label>";
                            else
                            return data;


                        },
                        'class': 'ord_status'
                    },
                    //     {data: 'driver_name', name: 'driver_name', 'class': 'driver_name'},
                    {data: 'ord_createdAt', name: 'ord_createdAt', 'class': 'ord_createdAt'},
                    /*   {data: 'action', name: 'action', 'class': 'action'},*/
                    {
                        data: 'action', name: 'action',
                        render: function (data, type, full, meta) {

                            return data;


                        },
                        'class': 'action'
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
                    },
                    {data: 'ord_createdAt', name: 'ord_createdAt', 'class': 'ord_createdAt'},
                    {
                        data: 'action', name: 'action',

                        'class': 'control'
                    },*/


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
                    url: '{{url("/")}}/notifications/contentListData/2',
                    type: 'POST',
                    data: function (d) {
                        // d.parent = $('#single-prepend-text').val();
                        //  d.status = $('#single-prepend-text2').val();
                    },
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },

                },

                columns: [{data: 'not_id', name: 'not_id', 'class': 'not_id'},
                    /*    {data: 'not_type', name: 'not_type', 'class': 'not_type'},*/
               {data: 'notification_title', name: 'notification_title', 'class': 'notification_title'},
                    {data: 'not_ar', name: 'not_ar', 'class': 'not_ar'},
                    {data: 'not_date', name: 'not_date', 'class': 'not_date'},
                    {data: 'seen_date', name: 'seen_date', 'class': 'seen_date'},
                    {data: 'ord_id', name: 'ord_id', 'class': 'ord_id'},
                    {data: 'name', name: 'name', 'class': 'name'},
                    {
                        data: 'ord_status', name: 'ord_status',
                        render: function (data, type, full, meta) {
                            if (data == '1')
                                return "<label class='label bg-blue'>Pending</label>";
                            else if (data == '2')
                                return "<label class='label bg-purple-plum'>Assigned</label>";
                            else if (data == '3')
                                return "<label class='label bg-yellow-gold'>Inprogress</label>";
                            else if (data == '4')
                                return "<label class='label bg-green-haze'>Confirm Delivery</label>";
                            else if (data == '5')
                                return "<label class='label bg-blue-chambray'>Confirm Receive</label>";
                            else if (data == '6')
                                return "<label class='label bg-red'>Cancel</label>";


                        },
                        'class': 'ord_status'
                    },
                    //    {data: 'driver_name', name: 'driver_name', 'class': 'driver_name'},
                    {data: 'ord_createdAt', name: 'ord_createdAt', 'class': 'ord_createdAt'},
                    {data: 'action', name: 'action', 'class': 'action'},


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
                    url: '{{url("/")}}/notifications/contentListData/3',
                    type: 'POST',
                    data: function (d) {
                        // d.parent = $('#single-prepend-text').val();
                        //  d.status = $('#single-prepend-text2').val();
                    },
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },

                },

                columns: [{data: 'not_id', name: 'not_id', 'class': 'not_id'},
                    /*    {data: 'not_type', name: 'not_type', 'class': 'not_type'},*/
                    /*  {data: 'notification_title', name: 'notification_title', 'class': 'notification_title'},*/
                    {
                        data: 'not_title', name: 'not_title','class': 'not_title'},

                    {data: 'not_ar', name: 'not_ar', 'class': 'not_ar'},
                    {data: 'not_date', name: 'not_date', 'class': 'not_date'},



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
                    url: '{{url("/")}}/notifications/contentListData/4',
                    type: 'POST',
                    data: function (d) {
                        // d.parent = $('#single-prepend-text').val();
                        //  d.status = $('#single-prepend-text2').val();
                    },
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },

                },

                columns: [{data: 'not_id', name: 'not_id', 'class': 'not_id'},
                    /*    {data: 'not_type', name: 'not_type', 'class': 'not_type'},*/
                    /*  {data: 'notification_title', name: 'notification_title', 'class': 'notification_title'},*/
                    {
                        data: 'not_title', name: 'not_title','class': 'not_title'},

                    {data: 'not_ar', name: 'not_ar', 'class': 'not_ar'},
                    {data: 'not_date', name: 'not_date', 'class': 'not_date'},



                ]
            });
            /*$('#data-table_filter').parent().append('' +
                '<div style="margin-left:15%;" id="data-table_filter2" class="dataTables_filter">' +
                '<label>{{trans('main.status')}} : ' +
                '<select id="statusFilter2" class="form-control statusFilter3" style="display: inline!important;" >' +
                '<option value="all">{{trans("users.all")}}</option>' +
                '<option value="1">Pending</option>' +
                '<option value="2">Assigned</option>' +
                '<option value="3">In progress</option>' +
                '<option value="4">Confirm Delivery</option>' +
                '<option value="5">Confirm Receive</option>' +
                '<option value="6">Cancel</option>' +
                '</select>' +
                '</label>' +
                '</div>' +
                '' +
                '');
*/
            /*  $('#data-table1_wrapper').on('change', '.statusFilter3', function () {

                  var filter_value = $(this).val();
                  var new_url = '{{url("/")}}/order/contentListData/' + filter_value;
                table.ajax.url(new_url).load();

            });*/

            /*
                        $('.buttons-excel').addClass('hidden');
                        $('.button-excel2').click(function () {
                            $('.buttons-excel').click()
                        });
            */

            // delivery_order

            $('#data-table1').on('click', '.btnSeen', function () {

               // alert($(this).attr('data-id'));
                $.ajax({
                    type: 'POST',
                    url: 'seenNoti',
                    data: {noti_id:$(this).attr('data-id')},
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    //   },
                    success: function (msg) {
                        //alert(msg);
                        /*  $.each(msg, function (key, val) {
                              appVue.ord_num = $(this).parents('tr').find('.ord_number').text();
                              appVue.ord_id = $(this).parent().find('.ord_id_hidden').val();
                              appVue.modal_show = 1;
                              $('#stack1').modal('show');
                          });
  */
                        //    alert('location1:  '+locations);
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
