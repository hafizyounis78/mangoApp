@extends('layouts.main')

@section('content')
        <div class="page-container">

                    <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE BAR -->
                    <div class="page-bar">
                        <ul class="page-breadcrumb">
                            <li>
                                <a href="">{{ __('main.site_title') }}</a>
                                <i class="fa fa-circle"></i>
                            </li>
                            <li>
                                <span>The page not found</span>
                            </li>
                        </ul>
                    </div>
                    <!-- END PAGE BAR -->             <!-- BEGIN PAGE TITLE-->
                    <h1 class="page-title"> page not found
                    </h1>
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
                    <!-- Main Content -->
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="row">
                                <div class="col-md-12 page-404">
                                    <div class="number font-green"> 403 </div>
                                    <div class="details">
                                        <h3>You are not allowed to access this page</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END SAMPLE FORM PORTLET-->
                </div>
                <!-- /Main Content -->
            </div>
            <!-- END CONTENT BODY -->
        </div>
        <!-- END CONTENT -->
    </div>
    <!-- END CONTAINER -->
@stop
