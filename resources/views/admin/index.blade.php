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
                                    <span class="caption-subject bold uppercase"> {{trans('users.users')}}</span>
                                </div>

                            </div>
                            <div class="portlet-body">
                                <div class="table-toolbar">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="btn-group">
                                                <a href="{{ route('admin.create') }}" id="sample_editable_1_new"

                                                   class="btn sbold green">{{trans('users.add_users')}}
                                                </a>


                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            {{--  <select id="statusFilter" class="form-control" style="margin-bottom: 13px;">
                                                  <option value="all">All</option>
                                                  <option value="1">Active</option>
                                                  <option value="-1">Not active</option>
                                              </select>
                                              --}}
                                        </div>
                                        <div class="col-md-8">
                                            <a style="float: left"
                                               class="dt-button button-excel2 buttons-html5 btn yellow btn-outline"
                                               tabindex="0" aria-controls="data-table" href="#"><i
                                                        class="fa fa-file-excel-o"></i> {{trans('users.excel')}}</a>
                                        </div>
                                    </div>

                                </div>


                                <table id="data-table"
                                       class="table table-striped table-bordered ">
                                    <thead>
                                    <tr>
                                        <!--table-hover table-checkable order-column
                                        <th>
                                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                                <input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" />
                                                <span></span>
                                            </label>
                                        </th>
                                        -->
                                        <th>{{trans('users.name')}}</th>
                                        <th>{{trans('users.email')}}</th>
                                        <th>{{trans('users.mobile')}}</th>
                                        <th>{{trans('users.user_state')}}</th>
                                        <th>{{trans('users.type_user')}}</th>
                                        <th>{{trans('users.add_date')}}</th>
                                        <th>{{trans('users.control')}}</th>
                                         <th style="display: none">{{trans('users.user_state')}}</th>

                                    </tr>
                                    </thead>

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
    <link href="{{url('')}}/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
    <link href="{{url('')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap-rtl.css" rel="stylesheet" type="text/css" />
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
            background-color:  #209538!important;
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
    <script src="{{url('')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="{{url('')}}/assets/pages/scripts/table-datatables-managed.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL SCRIPTS -->
    <script>

        $(function () {



            var table = $('#data-table').DataTable({
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
                            columns: [0, 1, 2, 4,7]
                        }
                    }

                ],
                "dom": "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable

                ajax: {
                    url: '{{url("/")}}/admin/contentListData',
                    type: 'POST',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },

                },

                columns: [

                    {data: 'name', width: "20%", name: 'name', 'class': 'name'},
                    {data: 'email', name: 'email', 'class': 'email'},
                    {data: 'mobile', name: 'mobile', 'class': 'mobile'},
                    {data: 'active', name: 'active', orderable: false, "searchable": false},
                    {data: 'type', name: 'type', 'class': 'type'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'control', name: 'control', 'class': 'control', orderable: false, "searchable": false},
                     {data:'user_status',name:'user_status',visible:false},

                ]
            });



            $('#data-table_filter').parent().append('' +
                '<div style="margin-left:15%;" id="data-table_filter2" class="dataTables_filter">' +
                '<label>{{trans('main.status')}} : ' +
                '<select id="statusFilter2" class="form-control statusFilter3" style="display: inline!important;" >' +
                '<option value="all">{{trans("users.all")}}</option>' +
                '<option value="1">{{trans("users.active")}}</option>' +
                '<option value="-1">{{trans("users.not_active")}}</option>' +
                '</select>' +
                '</label>' +
                '</div>' +
                '' +
                '');

            $('#data-table_wrapper').on('change' , '.statusFilter3' , function() {

                var filter_value = $(this).val();
                var new_url = '{{url("/")}}/admin/contentListData/' + filter_value;
                table.ajax.url(new_url).load();

            });



            $('#data-table').on('change', '.btnToggle input[type="radio"]', function () {
                // alert($(this).find('input[type="radio"]:checked').val());
                // alert($(this).val())    ;


                var this_ = $(this).parents('.btnToggle');
                var active = $(this).val();
                var status2 = $('#statusFilter2').val();
                var id = $(this).parents('.btnToggle').find('.id_hidden').val();

                if (active != status2) {
                    this_.find('.stateUser').addClass('hidden');
                    this_.find('.loader').removeClass('hidden');

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{url('activeAdmin')}}",
                        method: "get",
                        data: {id: id, active: active},
                        success: function (e) {

                            var message = "";
                            if (e.data == 0) {
                                message = "You can't change state of user";
                               // $.toaster({priority: 'danger', message: message});
                                if (active == -1) {
                                    this_.find('.btn-on-1').addClass('active');
                                    this_.find('.btn-off-1').removeClass('active');

                                } else {
                                    this_.find('.btn-off-1').addClass('active');
                                    this_.find('.btn-on-1').removeClass('active');
                                }
                            } else {
                                var selected = $('select#statusFilter2 option:selected').val();
                                if (active == -1) {
                                    message = "Suspend user successfully";
                                    if (selected != "all") {
                                        var new_url = '{{url("/")}}/admin/contentListData/' + 1;
                                        table.ajax.url(new_url).load();
                                    }

                                } else {

                                    message = "Activate user successfully";
                                    if (selected != "all") {
                                        var new_url = '{{url("/")}}/admin/contentListData/' + -1;
                                        table.ajax.url(new_url).load();
                                    }

                                }
                                //$.toaster({priority: 'success', message: message});
                            }

                            this_.find('.stateUser').removeClass('hidden');
                            this_.find('.loader').addClass('hidden');

                        }

                    });
                }


            });
            $('#data-table').on('click', '.delete', function () {
                var id = $(this).find('.id_hidden').val();
                swal({

                        title: "{{trans('main.sure_delete')}}",
                        text: "{{trans('main.delete')}}!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "{{trans('main.yes_delete')}}!",
                        cancelButtonText: "{{trans('main.cancle')}}",
                        closeOnConfirm: false
                    },
                    function () {

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: "{{url('delAdmin')}}" + "/" + id,
                            method: "get",
                            data: {},
                            success: function (data) {
                                if (data.status == true) {

                                    $("#row-" + id).fadeOut();
                                    $("#row-" + id).remove();
                                    swal("{{trans('main.done')}}", "{{trans('main.delete_success')}}", 'success');
                                }
                                else {
                                    swal("{{trans('main.error')}}", "{{trans('main.delete_error')}}", 'error');
                                }

                            }

                        });


                    });

            });


            $('.buttons-excel').addClass('hidden');
            $('.button-excel2').click(function () {
                $('.buttons-excel').click()
            });
        });


    </script>
@endpush
