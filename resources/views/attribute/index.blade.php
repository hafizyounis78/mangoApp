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
                                <h4 class="modal-title">{{trans('attributes.attributes')}}</h4>
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
                                        <div class="form-group form-md-checkboxes">
                                            <label class="col-md-2 control-label" for="form_control_1">خاصية مميزة</label>
                                            <div class="col-md-10">
                                                <div class="md-checkbox-inline">
                                                    <div class="md-checkbox">
                                                        <input type="checkbox" id="isSizeAttribute"  value="1" class="md-check">
                                                        <label for="isSizeAttribute">
                                                            <span></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span>هل هي حجم ؟ </label>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div style="margin-bottom: 10px;">
                                            <h4 class="modal-title"> {{trans('attributes.attributes_value')}}</h4>
                                        </div>
                                        <div class="form-group mt-repeater">

                                            <div data-repeater-list="group-c" class="add-repeater-item">
                                                <div data-repeater-item="" class="mt-repeater-item">
                                                    <div class="row mt-repeater-row">
                                                        @foreach($languages as $lang)
                                                            <div class="col-md-5 langCol">
                                                                <div class="form-group">
                                                                    <label for="form_control_1">{{trans('attributes.name')}}
                                                                        ({{$lang->lng_name}}
                                                                        )</label>
                                                                    <input type="text" placeholder="{{trans('attributes.value')}}"

                                                                           name="attr_value_{{$lang->lng_id}}"
                                                                           class="form-control attr_value_{{$lang->lng_id}}">

                                                                    <span class="help-block"></span>
                                                                </div>
                                                            </div>

                                                        @endforeach

                                                        <div class="col-md-1">
                                                            <a href="javascript:;" data-repeater-delete=""
                                                               class="btn btn-danger mt-repeater-delete">
                                                                <i class="fa fa-close"></i>
                                                            </a>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                            <a href="javascript:;" data-repeater-create=""
                                               class="btn btn-info mt-repeater-add">
                                                <i class="fa fa-plus"></i> {{trans('attributes.add_new_value')}}</a>
                                        </div>
                                        <div class="modal-footer">

                                            <button type="submit"
                                                    class="btn btn-success add_atr"> <span>{{trans('attributes.add')}}</span><i
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
                                    <span class="caption-subject bold uppercase"> {{trans('attributes.attributes')}}</span>
                                </div>

                            </div>
                            <div class="portlet-body">
                                <div class="table-toolbar">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="btn-group">
                                                <a id="attribute"
                                                   class="btn sbold green">{{trans('attributes.add_new_attribute')}}
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
                                                        class="fa fa-file-excel-o"></i> {{trans('category.excel')}}</a>
                                        </div>
                                    </div>

                                    {{--
                                    <div class="row" style="margin-top: 15px;">
                                        <div class="col-sm-6">
                                            <label>{{trans('category.select_parent')}}</label>
                                            <div class="form-group">

                                                <div class="input-group select2-bootstrap-prepend">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-default" type="button"
                                                            data-select2-open="single-prepend-text">
                                                        <span class="glyphicon glyphicon-search"></span>
                                                    </button>
                                                </span>
                                                    <select id="single-prepend-text"
                                                            class="form-control select2 select2-hidden-accessible"
                                                            tabindex="-1" aria-hidden="true">
                                                        <option value="all">{{trans('category.all')}}</option>
                                                        <option value="-1">{{trans('category.no_parent')}}</option>
                                                        @foreach($categories as $cat)
                                                            <option value="{{$cat->cat_id}}">{{$cat->cat_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <label>{{trans('category.status')}}</label>
                                            <div class="form-group">

                                                <div class="input-group select2-bootstrap-prepend">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-default" type="button"
                                                            data-select2-open="single-prepend-text2">
                                                        <span class="glyphicon glyphicon-search"></span>
                                                    </button>
                                                </span>
                                                    <select id="single-prepend-text2"
                                                            class="form-control select2 select2-hidden-accessible"
                                                            tabindex="-1" aria-hidden="true">
                                                        <option value="all">{{trans('category.all')}}</option>
                                                        <option value="1">{{trans('category.active')}}</option>
                                                        <option value="-1">{{trans('category.not_active')}}</option>

                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                   --}}
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
                                        <th>{{trans('attributes.attributes_value')}}</th>
                                        <th>{{trans('attributes.add_date')}}</th>
                                        <th>{{trans('attributes.controls')}}</th>

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
                arr_atv: [],
                arr_atv2: 1,
                edit: 0,
                edit_id: 0,
                tests: 3
            }
        });
        $(function () {

            function resetFormError() {
                var form1 = $('#form_sample_2');
                var error1 = $('.alert-danger', form1);
                error1.hide();
                var validator = form1.validate();
                validator.resetForm();
            }

            function rulesRepeater() {
                $('.mt-repeater .mt-repeater-item').each(function (k, v) {
                    $('input[name="group-c[' +
                        k +
                        '][attr_value_1]"]').rules('add', {
                        required: true
                    });

                    $('input[name="group-c[' +
                        k +
                        '][attr_value_2]"]').rules('add', {
                        required: true
                    });
                });
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
                            columns: [0, 2, 4]
                        }
                    }

                ],
                "dom": "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable

                ajax: {
                    url: '{{url("/")}}/attribute/contentListData',
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

                    {data: 'atr_name', name: 'atr_name', 'class': 'atr_name'},
                    {data: 'attr_value_name', name: 'attr_value_name', 'class': 'attr_value_name'},
                    {data: 'created_at', name: 'created_at', 'class': 'created_at'},
                    {data: 'control', name: 'control', 'class': 'control'},


                ]
            });


            $('#data-table').on('change', '.btnToggle input[type="radio"]', function () {
                // alert($(this).find('input[type="radio"]:checked').val());
                // alert($(this).val())    ;


                var this_ = $(this).parents('.btnToggle');
                var active = $(this).val();
                var status2 = $('#single-prepend-text2').val();
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
                        url: "{{url('statusCategory')}}",
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
                                var selected = $('#single-prepend-text2').val();
                                if (active == -1) {
                                    message = "Suspend user successfully";
                                    if (selected != "all") {
                                        var new_url = '{{url("/")}}/category/contentListData';
                                        table.ajax.url(new_url).load();
                                    }

                                } else {

                                    message = "Activate user successfully";
                                    if (selected != "all") {
                                        var new_url = '{{url("/")}}/category/contentListData';
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
           /* $('#data-table').on('click', '.delete', function () {
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

            });*/

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

            if (appVue.edit == 0) {
                rulesRepeater();
            }


            $('.add_atr').click(function () {

                var is_sizeAttribute = -1;
                if($('#isSizeAttribute').is(':checked')) {
                    is_sizeAttribute = 1;
                }

                rulesRepeater();
                var form1 = $('#form_sample_2');
                form1.valid();

                var error1 = $('.alert-danger', form1);
                var this_ = $(this);

                var arr_atr_value = [];
                var arr_atr = [];
                for (var i = 0; i < lang.length; i++) {
                    arr_atr.push({lang: lang[i].lng_id, name: $('.name_' + lang[i].lng_id).val()});

                }

                $('.mt-repeater').find('.mt-repeater-item').each(function () {
                    for (var i = 0; i < lang.length; i++) {
                        //attr_value_
                        arr_atr_value.push({
                            lang: lang[i].lng_id,
                            name: $(this).find('.attr_value_' + lang[i].lng_id).val()
                        });
                    }

                });
                var json_arr_atr_value = JSON.stringify(arr_atr_value);
                var json_arr_atr = JSON.stringify(arr_atr);


                var formData = new FormData();
                formData.append('json_arr_atr', json_arr_atr);
                formData.append('json_arr_atr_value', json_arr_atr_value);
                formData.append('is_sizeAttribute', is_sizeAttribute);


                var url = "{{url('addAttribute')}}";
                if (appVue.edit == 0) {
                    url = "{{url('addAttribute')}}";
                } else {
                    formData.append('id', appVue.edit_id);
                    url = "{{url('editAttribute')}}";
                }


                if (form1.valid()) {
                    error1.hide();
                    this_.prop('disabled', true);
                    this_.find('i').removeClass('hidden');
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
                            this_.find('i').addClass('hidden');

                            if (e == 1) {
                                table.ajax.url('{{url("/")}}/attribute/contentListData').load();
                                appVue.edit = 0;
                                appVue.edit_id = 0;
                                appVue.arr_atv = [];
                                $('.mt-repeater-item').not(':first').remove();
                                $('.mt-repeater-item').each(function () {
                                    $(this).find('input').val("");
                                });

                                $('#stack1').modal('hide');

                                $('.validation_error').addClass('hidden');
                            } else {
                                if (error1.is(":hidden")) {
                                    $('.validation_error').removeClass('hidden');
                                }

                            }


                        }

                    });
                } else {
                    $('.validation_error').addClass('hidden');
                }


            });
            $('#attribute').click(function () {

                resetFormError();
                $('.mt-repeater-item').remove();
                $('.mt-repeater-add').click();

                appVue.edit = 0;
                appVue.edit_id = 0;
                $('.add_atr').find('span').text("{{trans('attributes.add')}}");
                for (var i = 0; i < lang.length; i++) {
                    $('.name_' + lang[i].lng_id).val("");

                }
                $('#isSizeAttribute').prop('checked' , false);
                $('#stack1').modal('show');

            });


            $('#data-table').on('click', '.edit', function () {
                var form1 = $('#form_sample_2');
                resetFormError();

                var this_ = $(this);
                var id = $(this).parent().find('.atr_id_hidden').val();
                $('.add_atr').find('span').text("{{trans('attributes.save_change')}}");
                $('.mt-repeater-item').not(':first').remove();
                appVue.arr_atv2 = 1;
                appVue.edit_id = id;

                this_.prop('disabled', true);
                this_.find(':nth-child(1)').addClass('hidden');
                this_.find(':nth-child(2)').removeClass('hidden');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{url('getAttributeData')}}",
                    method: "get",
                    data: {id: id},
                    success: function (e) {

                        console.log(e);
                        this_.prop('disabled', false);
                        this_.find(':nth-child(2)').addClass('hidden');
                        this_.find(':nth-child(1)').removeClass('hidden');
                        var arr_atv = [];

                        if(e.atr_isSizeAttribute == 1) {
                         $('#isSizeAttribute').prop('checked' , true);
                        }else {
                            $('#isSizeAttribute').prop('checked' , false);
                        }

                        for (var i = 0; i < e.atr.length; i++) {
                            $('.name_' + e.atr[i].lng_id).val(e.atr[i].trn_text);
                        }

                        /* for (var i = 0; i < e.atv.length; i++) {

                             $('input[name="group-c[' +
                                 i +
                                 '][attr_value_1]"]').val(e.atv[i].text);

                             $('input[name="group-c[' +
                                 i +
                                 '][attr_value_2]"]').val(e.atv[i].text);
                       }
*/



                        for (var i = 0; i < e.atv.length; i++) {
                            arr_atv.push(e.atv[i].text);
                        }
                        //   appVue.arr_atv2 = arr_atv.length;
                        $('.mt-repeater-item').remove();
                        for (var i = 0; i < arr_atv.length; i++) {
                            $('.mt-repeater-add').click();
                        }

                        rulesRepeater();

                        for (var i = 0; i < arr_atv.length; i++) {
                            for (var j = 0; j < arr_atv[i].length; j++) {

                                $('input[name="group-c[' +
                                    i +
                                    '][attr_value_' +
                                    arr_atv[i][j].lng_id +
                                    ']"]').val(arr_atv[i][j].trn_text);
                            }


                        }


                        //$('.mt-repeater-item').remove();

                        //  appVue.arr_atv = arr_atv;
                        appVue.edit = 1;
                        $('#stack1').modal('show');


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
function delAttr(id) {
    
    var x = '';
    var r = confirm('هل انت متأكد من عملية الحذف');
    var currentToken = $('meta[name="csrf-token"]').attr('content');


    if (r == true) {
        x = 1;
    } else {
        x = 0;
    }
    if (x == 1) {
        $.ajax({
            type: 'post',
            url: 'delAttr',
            data: {id: id,  _token: currentToken},
            dataType: 'json',

            success: function (data) {

                  location.reload();

            }
        });
    }
}

    </script>
@endpush
