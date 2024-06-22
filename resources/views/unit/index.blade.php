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
                <div id="unitModal" class="modal fade" data-backdrop="static" data-keyboard="false" data-width="400">

                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">

                                <button type="button" class="close"
                                        data-dismiss="modal"></button>
                                <h4 class="modal-title">الوحدات القياسية</h4>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-danger hidden validation_error">
                                    {{trans('attributes.validation_error')}}
                                </div>
                                {!! Form::open(array('url' => 'addUnit', 'method' => 'post', 'id' => 'unit_form')) !!}

                                <input type="hidden" name="id" id="id" value="">
                                <div class="form-body">
                                    <div class="alert alert-danger display-hide details-error">
                                        {{trans('attributes.validation_error')}}
                                    </div>


                                    <div class="row ">

                                        <div class="col-sm-6 ">
                                            <div class="form-group form-md-line-input">
                                                <input type="text" name="name_en" id="name_en"
                                                       class="form-control name_en"
                                                       placeholder="الوحدة بالانجليزي">
                                                <label for="form_control_1">الوحدة بالانجليزي</label>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 ">
                                            <div class="form-group form-md-line-input">
                                                <input type="text" name="name_ar" id="name_ar"
                                                       class="form-control name_ar"
                                                       placeholder="الوحدة بالعربي">
                                                <label for="form_control_1">الوحدة بالعربي</label>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>

                                    </div>

                                    <!--<div class="modal-footer">

                                        <button type="submit"
                                                class="btn btn-success add_unit">
                                            <span>{{trans('attributes.add')}}</span><i
                                                    class="fa fa-spin fa-spinner hidden"></i>
                                        </button>
                                        <button class="btn btn-danger"
                                                data-dismiss="modal">{{trans('attributes.cancel')}}</button>
                                    </div>-->
                                </div>
                                <div class="form-actions right">
                                    <button type="button" data-dismiss="modal" class="btn dark btn-outline">إلغاء
                                    </button>
                                    <button type="submit" class="btn green">إضافة</button>
                                </div>


                                {!! Form::close() !!}
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
                                    <span class="caption-subject bold uppercase">عرض الوحدات القياسية</span>
                                </div>

                            </div>
                            <div class="portlet-body">
                                <div class="table-toolbar">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="btn-group">
                                                <a class='btn btn-success btn-sm' data-toggle='modal'
                                                   data-target='#unitModal' onclick="showModal()">
                                                    <i class='fa fa-create '></i><i
                                                            class='fa fa-lg fa-spin fa-spinner hidden'></i>
                                                    اضافة وحدة قياس </a>


                                            </div>
                                        </div>

                                        <!--<div class="col-md-8">
                                            <a style="float: left"
                                               class="dt-button button-excel2 buttons-html5 btn yellow btn-outline"
                                               tabindex="0" aria-controls="data-table" href="#"><i
                                                        class="fa fa-file-excel-o"></i> {{trans('category.excel')}}</a>
                                        </div>-->
                                    </div>

                                </div>


                                <table id="data-table"
                                       class="table table-striped table-bordered ">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>الاسم بالعربي</th>
                                        <th>الاسم بالانجليزي</th>
                                        <th>التحكم</th>
                                        {{-- <th>{{trans('attributes.controls')}}</th>
                                         <th style="display: none">الحالة</th>--}}
                                    </tr>
                                    </thead>
                                    <tbody>

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
            right: 0 !important;
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
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>--}}
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="{{url('')}}/assets/pages/scripts/table-datatables-managed.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL SCRIPTS -->
    <!-- BEGIN PAGE LEVEL PLUGINS  modal-->
    {{--    <script src="{{url('')}}/assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>--}}
    <!-- END PAGE LEVEL PLUGINS -->

    <!-- BEGIN PAGE LEVEL PLUGINS validation-->
    <script src="{{url('')}}/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"
            type="text/javascript"></script>


    <!-- BEGIN PAGE LEVEL SCRIPTS validation-->
    <script src="{{url('')}}/assets/pages/scripts/form-validation-md.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL SCRIPTS -->

    <script>

        $(function () {
            var lang = {!! $languages !!};
           
            var form1 = $('#form_sample_2');

            function resetFormError() {

                var error1 = $('.alert-danger', form1);
                error1.hide();
                var validator = form1.validate();
                validator.resetForm();
            }


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
                            columns: [0,1,2]
                        }
                    }

                ],
                "dom": "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable

                ajax: {
                    url: '{{url("unit/contentListData")}}',
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

                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name_ar', name: 'name_ar'},
                    {data: 'name_en', name: 'name_en'},
                    {data: 'action', name: 'action'},
                    /*{data: 'city_status', name: 'city_status',visible: false},*/

                ]
            });
        });

        $('.buttons-excel').addClass('hidden');
        /*$('.button-excel2').click(function () {
            $('.buttons-excel').click()
        });*/

        function setUnitValue(id, name_en, name_ar) {


            $('#id').val(id);
            $('#name_en').val(name_en);
            $('#name_ar').val(name_ar);

        }

        function delUnit(id) {
            //alert('fff');
            //return;
            var x = '';
            var r = confirm('سيتم حذف الوحدة ,هل انت متاكد من ذلك؟');
            var currentToken = $('meta[name="csrf-token"]').attr('content');

            if (r == true) {
                x = 1;
            } else {
                x = 0;
            }
            if (x == 1) {
                $.ajax({
                    url: 'delUnit/' + id,
                    type: 'post',
                    dataType: 'json',
                    data: {_token: currentToken},
                    success: function (data) {
                        alert('تم حذف الوحدة');
                        location.reload();
                    }
                });
            }
        }
        function showModal()
        {

            $('#id').val('');
            $('#name_en').val('');
            $('#name_ar').val('');

        }
       
        var unitFormValidation = function () {
            var handleValidation = function () {
                // alert('handleValidation'+  $('#prd_image').val());
                var form = $('#unit_form');
                var errormsg = $('.alert-danger', form);
                var successmsg = $('.alert-success', form);

                form.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "", // validate all fields including form hidden input
                    rules: {
                        name_en: {
                            required: true,
                        },
                        name_ar: {
                            required: true,
                        }
                    },

                    messages: { // custom messages for radio buttons and checkboxes
                        name_en: {
                            required: "الرجاء ادخال الوحدة ",
                        },
                        name_ar: {
                            required: "الرجاء ادخال الوحدة ",
                            }
                    }
                    ,

                    errorPlacement: function (error, element) { // render error placement for each input type

                        if (element.attr("data-error-container")) {
                            error.appendTo(element.attr("data-error-container"));
                        } else if (element.parent(".form-group").size() > 0) {

                            error.insertAfter(element.parent(".form-group"));
                            form.find('.help-block-error').addClass('font-red-flamingo');
                        } else {
                            error.insertAfter(element); // for other inputs, just perform default behavior
                        }
                    }
                    ,

                    invalidHandler: function (event, validator) { //display error alert on form submit
                        successmsg.hide();
                        errormsg.show();
                        //  $('#spnMsg').text('يـوجد بـعـض الادخـالات الخـاطئة، الرجـاء التأكد من القيم المدخلة');
                        App.scrollTo(errormsg, -200);
                    },


                    highlight: function (element) { // hightlight error inputs

                        $(element)
                            .closest('.form-group').addClass('has-error'); // set error class to the control group
                    },

                    unhighlight: function (element) { // revert the change done by hightlight
                        $(element)
                            .closest('.form-group').removeClass('has-error'); // set error class to the control group
                    },

                    success: function (label) {
                        label
                            .closest('.form-group').removeClass('has-error'); // set success class to the control group
                    },


                    submitHandler: function (form, event) {
                        event.preventDefault();
                        errormsg.hide();
                        unitSubmit();
                    }

                })
                ;
            }
            return {
                //main function to initiate the module
                init: function () {
                    handleValidation();

                }

            };

        }();
        function unitSubmit() {
    var action = $('#unit_form').attr('action');

    var formData = new FormData($('#unit_form')[0]);
    $.ajax({
            url: action,
            type: 'POST',
            dataType: 'json',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {


                alert('تمت العملية بنجاح')

                location.reload();

            },
            error: function (err) {
                console.log(err);
            }
            /*  error:function(err){
                  console.log(err);

              }*/
        }
    )
    //   });
}
        unitFormValidation.init();
    </script>
@endpush
