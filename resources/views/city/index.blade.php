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
                <div id="stack1" class="modal fade" data-backdrop="static" data-keyboard="false" data-width="400">

                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">

                                <button type="button"  class="close"
                                        data-dismiss="modal"></button>
                                <h4 class="modal-title">{{trans('city.city')}}</h4>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-danger hidden validation_error">
                                    {{trans('attributes.validation_error')}}
                                </div>
                                <form id="form_sample_2">


                                    <div class="form-body">
                                        <div class="alert alert-danger display-hide details-error">
                                            {{trans('attributes.validation_error')}}
                                        </div>




                                        <div class="row atr_name">
                                            @foreach($languages as $lang)
                                                <div class="col-sm-6 atr_value_name">
                                                    <div class="form-group form-md-line-input">
                                                        <input type="text" name="name_{{$lang->lng_id}}"
                                                               class="form-control name_{{$lang->lng_id}}"
                                                               placeholder="{{trans('category.name')}}">
                                                        <label for="form_control_1"> {{trans('attributes.name')}} ({{$lang->lng_name}})</label>
                                                        <span class="help-block"></span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="modal-footer">

                                            <button type="submit"
                                                    class="btn btn-success add_city"> <span>{{trans('attributes.add')}}</span><i
                                                        class="fa fa-spin fa-spinner hidden"></i>
                                            </button>
                                            <button class="btn btn-danger" data-dismiss="modal">{{trans('attributes.cancel')}}</button>
                                        </div>
                                    </div>
                                </form>
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
                                    <span class="caption-subject bold uppercase">{{trans('city.cities')}}</span>
                                </div>

                            </div>
                            <div class="portlet-body">
                                <div class="table-toolbar">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="btn-group">
                                                <a id="addCity"
                                                   class="btn sbold green">{{trans('city.add_new_city')}}
                                                </a>


                                            </div>
                                        </div>
                                        <div class="col-md-2">

                                        </div>
                                        <div class="col-md-8">
                                            <a style="float: left"
                                               class="dt-button button-excel2 buttons-html5 btn yellow btn-outline"
                                               tabindex="0" aria-controls="data-table" href="#"><i
                                                        class="fa fa-file-excel-o"></i> {{trans('category.excel')}}</a>
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
                                        <th>{{trans('attributes.name')}}</th>
                                        <th>الحالة</th>
                                        <th>{{trans('attributes.controls')}}</th>
                                         <th style="display: none">الحالة</th>

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
    <link href="{{url('')}}/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css"/>
    <link href="{{url('')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap-rtl.css"
          rel="stylesheet" type="text/css"/>
    <!-- END PAGE LEVEL PLUGINS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">

    <link href="{{url('')}}/assets/global/plugins/bootstrap-toastr/toastr.min.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="{{url('')}}/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css"/>
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
            right: 0!important;
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
    <script src="{{url('')}}/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="{{url('')}}/assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>
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
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>

    <script>

        var appVue = new Vue({
            el: '#stack1',
            data: {
                edit: 0,
                edit_id: 0,

            }
        });
        $(function () {
            var form1 = $('#form_sample_2');
            function resetFormError() {

                var error1 = $('.alert-danger', form1);
                error1.hide();
                var validator = form1.validate();
                validator.resetForm();
            }

            var lang = {!! $languages !!};

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
                            columns: [0, 3]
                        }
                    }

                ],
                "dom": "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable

                ajax: {
                    url: '{{url("/")}}/city/contentListData',
                    type: 'POST',
                    data: function (d) {
                        // d.parent = $('#single-prepend-text').val();
                        //  d.status = $('#single-prepend-text2').val();
                    },
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },

                },

                columns: [

                    {data: 'city_name', name: 'city_name', 'class': 'city_name'},
                    {data: 'status', name: 'status', 'class': 'status'},
                    {data: 'control', name: 'control', 'class': 'control'},
                    {data: 'city_status', name: 'city_status',visible: false},

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
                var new_url = '{{url("/")}}/city/contentListData/' + filter_value;
                table.ajax.url(new_url).load();

            });



            $('#data-table').on('change', '.btnToggle input[type="radio"]', function () {


                var this_ = $(this).parents('.btnToggle');
                var active = $(this).val();
                var id = $(this).parents('.btnToggle').find('.id_hidden').val();


                    this_.find('.stateUser').addClass('hidden');
                    this_.find('.loader').removeClass('hidden');

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{url('statusCity')}}",
                        method: "get",
                        data: {id: id, active: active},
                        success: function (e) {

                                var selected = $('select#statusFilter2 option:selected').val();
                                if (active == -1) {
                                    if (selected != "all") {
                                        var new_url = '{{url("/")}}/city/contentListData/' + 1;
                                        table.ajax.url(new_url).load();
                                    }

                                } else {

                                    if (selected != "all") {
                                        var new_url = '{{url("/")}}/city/contentListData/' + -1;
                                        table.ajax.url(new_url).load();
                                    }
                                }


                            this_.find('.stateUser').removeClass('hidden');
                            this_.find('.loader').addClass('hidden');
                        }

                    });



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
                            url: "{{url('delUser')}}" + "/" + id,
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


            for (var i = 1; i <= 2; i++) {
                var namepp = ".name_" + i;
                $(namepp).rules('add', {
                    required: true
                });
            }


            $('#addCity').click(function () {

                resetFormError();
                appVue.edit = 0;
                appVue.edit_id = 0;

                $('.add_city').find('span').text("{{trans('attributes.add')}}");
                for (var i = 0; i < lang.length; i++) {
                    $('.name_' + lang[i].lng_id).val("");

                }
                $('#stack1').modal('show');

            });
            $('.add_city').click(function() {
                var this_ = $(this);
                if(form1.valid()) {
                    var formData = new FormData();
                    var arr_city = [];
                    for (var i = 0; i < lang.length; i++) {
                        arr_city.push({lang: lang[i].lng_id, name: $('.name_' + lang[i].lng_id).val()});

                    }
                    var json_arr_city = JSON.stringify(arr_city);

                    var formData = new FormData();
                    formData.append('json_arr_city', json_arr_city);
                    formData.append('id', appVue.edit_id);
                    if(appVue.edit == 0) {
                        url = "{{url('addCity')}}";
                    }else {
                        url = "{{url('editCity')}}";
                    }

                    this_.prop('disabled', true);
                    this_.find(':nth-child(1)').addClass('hidden');
                    this_.find(':nth-child(2)').removeClass('hidden');
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: url,
                        // dataType: 'text',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        type: 'post',
                        success: function (e) {
                            this_.prop('disabled', false);
                            this_.find(':nth-child(2)').addClass('hidden');
                            this_.find(':nth-child(1)').removeClass('hidden');

                           if(e == 1) {
                               table.ajax.url('{{url("/")}}/city/contentListData').load();
                               $('#stack1').modal('hide');
                           }
                        }

                    });

                }

            });
            $('#data-table').on('click', '.edit', function () {
                var form1 = $('#form_sample_2');
                resetFormError();
                appVue.edit = 1;
                var this_ = $(this);
                var id = $(this).parent().find('.city_id_hidden').val();
                appVue.edit = 1;
                appVue.edit_id = id;
                $('.add_city').find('span').text("{{trans('attributes.save_change')}}");

                this_.prop('disabled', true);
                this_.find(':nth-child(1)').addClass('hidden');
                this_.find(':nth-child(2)').removeClass('hidden');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{url('getCityData')}}",
                    method: "get",
                    data: {id: id},
                    success: function (e) {
                        this_.prop('disabled', false);
                        this_.find(':nth-child(2)').addClass('hidden');
                        this_.find(':nth-child(1)').removeClass('hidden');

                        for(var i=0 ; i < e.length ; i++) {
                            $('.name_'+e[i].lng_id).val(e[i].trn_text);
                        }

                        $('#stack1').modal('show');
                        appVue.edit = 1;
                        appVue.edit_id = id;
                    }

                });

            });


        });

        /*
            const arr1 = ['1_a', '2_b', '3_c', '4_d'];
            const arr2 = ['5_e', '6_f', '7_g'];
            const arr3 = ['8_x', '9_y'];

            const all = [arr1, arr2, arr3];

            const output = all.reduce(function (acc, cu) {
                var ret = [];
                acc.map(function (obj) {
                    cu.map(function (obj_1) {
                        ret.push(obj + '-' + obj_1)
                    });
                });
                return ret;
            });



            var arra2 = [];
            for(var i=0; i < output.length ; i++) {
                var arre = output[i].split('-');
                var arrt =[];
                for(var j =0 ; j < arre.length ; j++) {
                    var str_arr = arre[j].split('_');
                    var id = str_arr[0];
                    var text = str_arr[1];
                    arrt.push({id:id , text:text});
                }
                arra2.push(arrt);


            }
            console.log(arra2);
            */


    </script>
@endpush
