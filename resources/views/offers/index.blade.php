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
                <div id="offersModal" class="modal fade" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h4 class="modal-title">تعديل بيانات العرض</h4>
                            </div>
                            <div class="modal-body">
                                <div class="scroller" style="height:300px" data-always-visible="1"
                                     data-rail-visible1="1">
                                    <br/>

                                    {!! Form::open(array('url' => 'updateOffer', 'method' => 'post', 'id' => 'offer_form')) !!}

                                    {{--<form class="form-horizontal" role="form" method="post" action="{{url('sendMultipleFcm')}}">--}}
                                    <input type="hidden" name="prd_of_id" id="prd_of_id" value="">
                                    <input type="hidden" name="ofr_id" id="ofr_id" value="">
                                    <div class="form-body">

                                        <div class="form-group">
                                            <label class="col-md-3 control-label"> نسبة الخصم %</label>
                                            <div class="col-md-9">
                                                <input type="text" id="ofr_discount" name="ofr_discount"
                                                       class="form-control"
                                                       placeholder="%"></div>
                                        </div>
                                        <br/>

                                        <hr>

                                        <div class="form-group">
                                            <label class="control-label col-md-3">فترة العرض</label>
                                            <div class="col-md-4">
                                                <div class="input-group input-large date-picker input-daterange"
                                                     data-date="" data-date-format="yyyy/mm/dd">
                                                    <input type="text" class="form-control" name="from" id="from">
                                                    <span class="input-group-addon"> الى </span>
                                                    <input type="text" class="form-control" name="to" id="to">

                                                </div>
                                                <!-- /input-group
                                                <span class="help-block"> اختار التاريخ </span>-->
                                            </div>
                                        </div>
                                        <br/>

                                        <hr>
                                        {{--<div class="form-group">--}}
                                            {{--<label class="control-label col-md-3"> فعال </label>--}}
                                            {{--<div class="col-md-4 ">--}}

                                                {{--<input class="form-control" type="checkbox" value="0"--}}
                                                       {{--name="ofr_isDeleted"--}}
                                                       {{--id="ofr_isDeleted">--}}
                                            {{--</div>--}}
                                        {{--</div>--}}


                                        {{--<br/>--}}
                                        {{--<hr>--}}

                                    </div>
                                    <div class="form-actions right">
                                        <button type="button" data-dismiss="modal" class="btn dark btn-outline">إلغاء</button>
                                        <button type="submit" class="btn green">تعديل</button>
                                    </div>

                                    </form>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                            {{--<div class="modal-footer">
                                <button type="button" data-dismiss="modal" class="btn dark btn-outline">إلغاء</button>
                                <button type="submit" class="btn green">إرسال</button>
                            </div>--}}
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
                        @if(session()->get('errors'))
                            <div class="alert alert-danger">
                                {{session()->get('errors')}}
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
                                    <span class="caption-subject bold uppercase">العروض</span>
                                </div>

                            </div>
                            <div class="portlet-body">
                                <div class="table-toolbar">
                                     <div class="col-md-2">
                                       
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


                                <table id="data-table"
                                       class="table table-striped table-bordered ">
                                    <thead>
                                    <tr>
                                        <th>رقم العرض</th>
                                        <th>تاريخ بداية العرض</th>
                                        <th>تاريخ نهاية العرض</th>
                                        <th>نسبة الخصم</th>
                                        <th>حالة العرض</th>
                                        <th>{{trans('products.name')}}</th>
                                        <th>{{trans('products.image')}}</th>
                                        {{--    <th>{{trans('products.category')}}</th>--}}
                                        <th>{{trans('products.price')}}</th>
                                        <th>التحكم</th>
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
    {{--  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
  --}}
    {{--<link href="{{url('')}}/assets/global/plugins/bootstrap-toastr/toastr.min.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <link rel="stylesheet" href="{{asset('css/slideShowImage.css')}}">--}}
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="{{url('')}}/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css"/>
    <link href="{{url('')}}/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet"
          type="text/css"/>
    <!-- END PAGE LEVEL PLUGINS -->


    <!-- BEGIN PAGE LEVEL PLUGINS  modal-->
    {{-- <link href="{{url('')}}/assets/apps/css/todo-rtl.min.css" rel="stylesheet" type="text/css"/>--}}

    <!-- END PAGE LEVEL PLUGINS -->


    <!-- BEGIN PAGE LEVEL PLUGINS  Tree-->
    {{--<link href="{{url('')}}/assets/global/plugins/jstree/dist/themes/default/style.min.css" rel="stylesheet"
          type="text/css"/>--}}
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

    </style>
@endpush

@push('js')

    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="{{url('')}}/assets/global/scripts/datatable.js" type="text/javascript"></script>
    <script src="{{url('')}}/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
    <script src="{{url('')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js"
            type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>--}}
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
    {{--<script src="{{url('')}}/assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>--}}
    <!-- END PAGE LEVEL PLUGINS -->

    <!-- BEGIN PAGE LEVEL SCRIPTS modal-->
    <!--{{--  <script src="{{url('')}}/assets/pages/scripts/ui-modals.min.js" type="text/javascript"></script>--}}-->
    <!-- END PAGE LEVEL SCRIPTS -->


    <!-- BEGIN PAGE LEVEL PLUGINS Tree-->
    <!--{{--  <script src="{{url('')}}/assets/global/plugins/jstree/dist/jstree.min.js" type="text/javascript"></script>--}}-->
    <!-- END PAGE LEVEL PLUGINS -->

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <!--{{--<script src="{{url('')}}/assets/pages/scripts/ui-tree.min.js" type="text/javascript"></script>--}}-->
    <!-- END PAGE LEVEL SCRIPTS -->
    <script src="{{url('')}}/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"
            type="text/javascript"></script>
    <script>

        $(function () {

            // $('.input-daterange').daterangepicker();
            var table = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                //searching: true,
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
                          columns: [0, 1, 2,3,4,5,7]
                          }
                      }

                  ],
                  "dom": "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable
  
                ajax: {
                    url: '{{ url("getOffersList") }}',
                    type: 'POST',
                    data: function (d) {
                        //d.parent = $('#single-prepend-text').val();
                        // d.status = $('#single-prepend-text2').val();
                    },
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },

                },

                columns: [
                    {data: 'ofr_id', width: "10%", name: 'ofr_id', 'class': 'ofr_id'},
                    {data: 'ofr_start', width: "20%", name: 'ofr_start', 'class': 'ofr_start'},
                    {data: 'ofr_end', width: "20%", name: 'ofr_end', 'class': 'ofr_end'},
                    {
                        data: 'ofr_discount', width: "10%", name: 'ofr_discount',

                        render: function (data, type, full, meta) {

                            return data + "%";

                        },

                        'class': 'ofr_discount'
                    },
                    {
                        data: 'ofr_isDeleted', width: "20%", name: 'ofr_isDeleted',

                        render: function (data, type, full, meta) {

                            if (data == 1)
                                return "غير فعال";
                            else
                                return "فعال";
                        }


                        ,
                        'class': 'ofr_isDeleted'
                    },

                    {data: 'ptr_name', width: "20%", name: 'ptr_name', 'class': 'ptr_name'},

                    {
                        data: 'image', name: 'image',
                        render: function (data, type, full, meta) {
                            return "<img src=\"" + data + "\" height=\"50\"/>";

                        }
                    },
                    /*                    {data: 'cat_id', name: 'cat_id', 'class': 'parent_name'},*/
                    {data: 'prd_price', name: 'prd_price', 'class': 'prd_price'},
                    {
                        data: 'action', name: 'action',

                        'class': 'control'
                    },

                ]
            });

          
          /*  $('#data-table').on('click', '.delete', function () {
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
        });

        function delOffer(id) {
            var x = '';
            var r = confirm('سيتم ايقاف العرض ,هل انت متاكد من ذلك؟');
            var currentToken = $('meta[name="csrf-token"]').attr('content');

            if (r == true) {
                x = 1;
            } else {
                x = 0;
            }
            if (x == 1) {
                $.ajax({
                    url: 'destroy/' + id,
                    type: 'post',
                    dataType: 'json',
                    data: {_token: currentToken},
                    success: function (data) {
                        alert('تم ايقاف العرض');
                        location.reload();
                    }
                });
            }
        }

        function setProdValue(data) {
            /* var from = "10-11-2011";
             var numbers = from.match(/\d+/g);
             var date = new Date(numbers[2], numbers[0]-1, numbers[1]);
 */
            var sdate = new Date(data.ofr_start);
            var smonth = sdate.getMonth(); // month (in integer 0-11)
            var syear = sdate.getFullYear(); // year
            var sday = sdate.getDate(); // day
            //alert(syear+','+(smonth+1)+','+sday);
            var formated_start_date = new Date(syear, smonth, sday);
            var edate = new Date(data.ofr_end);
            var emonth = edate.getMonth(); // month (in integer 0-11)
            var eyear = edate.getFullYear(); // year
            var eday = edate.getDate(); // day
            //alert(syear+','+(smonth+1)+','+sday);
            var formated_end_date = new Date(eyear, emonth, eday);
            // alert(formated_start_date);
            $('#prd_of_id').val(data.prd_id);
            $('#ofr_id').val(data.ofr_id);
            $('#ofr_discount').val(data.ofr_discount);

            $('#from').datepicker("setDate", formated_start_date);
            $('#to').datepicker("setDate", formated_end_date);
/*            $("#ofr_isDeleted").val(data.ofr_isDeleted);
            if (data.ofr_isDeleted == 0)
            {

                $("#ofr_isDeleted").prop("checked", true);

            }
            else
                $("#ofr_isDeleted").prop("checked", false);
*/
           /* $('#myCheckbox').attr('checked', 'checked');

            For unchecking the box:

                $('#myCheckbox').removeAttr('checked');*/
           // else


              //  $("#ofr_isDeleted").attr("checked", false);
            //$('#ofr_isDeleted').val(data.ofr_isDeleted);


        }
          var offerFormValidation = function () {
            var handleValidation = function () {
                // alert('handleValidation'+  $('#prd_image').val());
                var form = $('#offer_form');
                var errormsg = $('.alert-danger', form);
                var successmsg = $('.alert-success', form);

                form.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "", // validate all fields including form hidden input
                    rules: {
                        ofr_discount: {
                            required: true,
                            number: true,
                            min: 1,
                            max:100
                        },
                        from: {
                            required: true,
                            date:true,
                        },
                        to: {
                            required: true,
                            date:true,
                        } ,
                    },

                    messages: { // custom messages for radio buttons and checkboxes
                        ofr_discount: {
                            required: "الرجاء ادخل نسبة الخصم",
                            number: "تأكد من القيمة المدخلة",
                            min: "يجب ان تكون القيمة اكبر او تساوي 1",
                            max:"يجب ان لايزيد العرض عن 100%"
                        }
                        ,
                        from: {
                            required: "الرجاء اختيار تاريخ",
                            date:"يرجى التأكد من القيمة المدخلة",
                        }
                        ,
                        to: {
                            required: "الرجاء اختيار تاريخ",
                            date:"يرجى التأكد من القيمة المدخلة",
                        }
                    }
                    ,

                    errorPlacement: function (error, element) { // render error placement for each input type

                        if (element.attr("data-error-container")) {
                            error.appendTo(element.attr("data-error-container"));
                        } else if (element.parents('.input-daterange').size() > 0) {
                            //alert($('.input-datarange').find('.help-block-error') != null);
                            /*if(element.parent(".input-daterange").find('.help-block-error')!= null)
                                return false;
                            else
                            {*/
                            // alert('dd')
                         //   error.insertAfter(element);
                            //}
                            //if (element.attr("help-block-error"))
                            //  if($('.help-block-error').html()=='')
//alert($('.help-block-error').size());
                            // error.appendTo(element.attr("help-block-error-date"));
                            // form.find('.help-block-error-date').addClass('font-red-flamingo');
                        }else if (element.parent(".form-group").size() > 0) {

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
                        offerSubmit();
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
        function offerSubmit() {

            var action = $('#offer_form').attr('action');

            var formData = new FormData($('#offer_form')[0]);
            $.ajax({
                    url: action,
                    type: 'POST',
                    dataType: 'json',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (data) {

                        if (data.success) {
                            alert('تمت العملية بنجاح');

                           
                            $('#offersModal').modal('hide');
                            window.location.href = window.location.href;
                        }
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
        offerFormValidation.init();

    </script>
@endpush
