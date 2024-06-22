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
                    <!-- END PAGE BAR -->             <!-- BEGIN PAGE TITLE-->
                    <h1 class="page-title"> الرئيسية
                        <small></small>
                    </h1>
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->

                {{--@include('includes.statistics')--}}
                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
        </div>
        @include('includes/footer')
    @stop