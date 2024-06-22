<meta name="csrf-token" content="<?php echo csrf_token() ?>"> // This will retrieve the necessary token.
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
                                    <span class="caption-subject bold uppercase">ثوابت النظام</span>
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
                                                            <i class="fa fa-thumb-tack"></i>عرض الثوابت
                                                        </div>
                                                        <div class="tools">
                                                            <a href="javascript:;" class="collapse">
                                                            </a>

                                                        </div>
                                                    </div>
                                                    <div class="portlet-body">

                                                        <table class="table table-striped table-hover table-bordered"
                                                               id="sample_editable_1">
                                                            <thead>
                                                            <tr>
                                                                <th> #</th>
                                                                <th> Setting</th>
                                                                <th> Value</th>

                                                                <th> Edit</th>
                                                                <th> </th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>

                                                            @foreach($appsetting as $setting )
                                                                <tr>
                                                                    <td> {{$setting->id}} </td>
                                                                    <td> {{$setting->desc_setting}} </td>
                                                                    <td> {{$setting->value}} </td>

                                                                    <td>
                                                                        <a class="edit" href="javascript:;"> Edit </a>
                                                                    </td>
                                                                    <td>
                                                                        <a class="delete" href="javascript:;">
                                                                             </a>
                                                                    </td>
                                                                </tr>
                                                           @endforeach
                                                            </tbody>
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

    @include('includes/footer')
    @stop

     @push('css')
        <link href="{{url('')}}/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
            <link href="{{url('')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap-rtl.css"
                  rel="stylesheet" type="text/css"/>

   <style>

       .pagination {
           font-size: 10px !important;
       }
   </style>
        @endpush

        @push('js')

        <!-- BEGIN PAGE LEVEL PLUGINS -->

            {{--<script src="{{url('')}}/assets/global/scripts/datatable.js" type="text/javascript"></script>--}}
            <script src="{{url('')}}/assets/global/plugins/datatables/datatables.min.js"
                    type="text/javascript"></script>
            <script src="{{url('')}}/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js"
                    type="text/javascript"></script>
            <script src="{{url('')}}/assets/pages/scripts/table-datatables-editable.js" type="text/javascript"></script>
            <!-- END PAGE LEVEL SCRIPTS -->

    @endpush
