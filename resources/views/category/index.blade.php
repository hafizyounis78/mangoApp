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
                                    <span class="caption-subject bold uppercase"> {{trans('category.categories')}}</span>
                                </div>

                            </div>
                            <div class="portlet-body">
                                <div class="table-toolbar">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="btn-group">
                                                <a href="{{ url('category/create') }}" id="sample_editable_1_new"
                                                   class="btn sbold green">{{trans('category.add_categories')}}
                                                </a>


                                            </div>
                                            <div class="btn-group">
                                                <a href="{{ url('subcategory/create') }}" id="sample_editable_1_new"
                                                   class="btn sbold green">{{trans('category.add_subCategories')}}
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
                                     
                                        <div class="col-md-5">
                                            <a style="float: left"
                                               class="dt-button button-excel2 buttons-html5 btn yellow btn-outline"
                                               tabindex="0" aria-controls="data-table" href="#"><i
                                                        class="fa fa-file-excel-o"></i> {{trans('category.excel')}}</a>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 15px;">
                                        <div class="col-sm-12">
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

                                        {{--
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
                                        --}}
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
                                        <th>{{trans('category.name')}}</th>
                                        <th>{{trans('category.image')}}</th>
                                        <th>{{trans('category.parent')}}</th>
                                       {{-- <th>{{trans('category.status')}}</th> --}}
                                        <th>{{trans('category.add_date')}}</th>
                                        <th>{{trans('category.control')}}</th>


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
    <link rel="stylesheet" href="{{asset('css/slideShowImage.css')}}">
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
                            columns: [0, 2, 4]
                        }
                    }

                ],
                "dom": "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable

                ajax: {
                    url: '{{url("/")}}/category/contentListData',
                    type: 'POST',
                    data: function (d) {
                        d.parent = $('#single-prepend-text').val();
                       // d.status = $('#single-prepend-text2').val();
                    },
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },

                },

                columns: [
                    {data: 'cat_name', width: "20%", name: 'cat_name', 'class': 'cat_name'},
                    {data: 'image', name: 'image', 'class': 'image'},
                    {data: 'parent_name', name: 'parent_name', 'class': 'parent_name'},
                    {{-- {data: 'status', name: 'status', 'class': 'status'}, --}}
                    {data: 'created_at', name: 'created_at', 'class': 'created_at'},
                    {data: 'control', name: 'control', 'class': 'control'},

                ]
            });

            $('#single-prepend-text').on('change', function () {
                var filter_value = $(this).val();
                var new_url = '{{url("/")}}/category/contentListData';
                table.ajax.url(new_url).load();
            });

            $('#single-prepend-text2').on('change', function () {
                var new_url = '{{url("/")}}/category/contentListData';
                table.ajax.url(new_url).load();
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

            $('#data-table').on('click', 'tr td:first-child', function () {
                var id = $(this).parent().find('.id_hidden').val();
                $('#stack1').modal('show');
            });

            $('.buttons-excel').addClass('hidden');
            $('.button-excel2').click(function () {
                $('.buttons-excel').click()
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
  function delCat(id) {
            //alert('fff');
            //return;
            var x = '';
            var r = confirm('سيتم حذف الفئة ,هل انت متاكد من ذلك؟');
            var currentToken = $('meta[name="csrf-token"]').attr('content');

            if (r == true) {
                x = 1;
            } else {
                x = 0;
            }
            if (x == 1) {
                $.ajax({
                    url: 'category/' + id,
                    type: 'delete',
                    dataType: 'json',
                    data: {_token: currentToken},
                    success: function (data) {
                        if(data.success) {


                            alert('تم حذف الفئة بنجاح');
                            location.reload();
                        }
                        else
                        {
                            alert(data.msg);
                           // alert('لايمكن حذف هذه الفئة ')
                        }

                    }
                });
            }
        }

    </script>
@endpush
