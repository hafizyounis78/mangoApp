<!DOCTYPE html>
<!--
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.7
Version: 4.7
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
Renew Support: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" dir="rtl">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
    <meta charset="utf-8"/>
   <title>Mango Admin Panel</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="#1 selling multi-purpose bootstrap admin theme sold in themeforest marketplace packed with angularjs, material design, rtl support with over thausands of templates and ui elements and plugins to power any type of web applications including saas and admin dashboards. Preview page ofRTL  Theme #1 for blank page layout"
          name="description"/>
    <meta content="" name="author"/>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    {{--<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet"
          type="text/css"/>--}}
    <link href="{{url('')}}/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="{{url('')}}/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="{{url('')}}/assets/global/plugins/bootstrap/css/bootstrap-rtl.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="{{url('')}}/assets/global/plugins/bootstrap-switch/css/bootstrap-switch-rtl.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="{{url('')}}/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
              
    <link href="{{url('')}}/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
    <link href="{{url('')}}/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="{{url('')}}/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="{{url('')}}/assets/global/css/components-rtl.min.css" rel="stylesheet" id="style_components"
          type="text/css"/>
    <link href="{{url('')}}/assets/global/css/plugins-rtl.min.css" rel="stylesheet" type="text/css"/>
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <link href="{{url('')}}/assets/layouts/layout/css/layout-rtl.min.css" rel="stylesheet" type="text/css"/>
    <link href="{{url('')}}/assets/layouts/layout/css/themes/darkblue-rtl.min.css" rel="stylesheet" type="text/css"
          id="style_color"/>
    <link href="{{url('')}}/assets/layouts/layout/css/custom-rtl.min.css" rel="stylesheet" type="text/css"/>
    @stack('css')
    <!-- END THEME LAYOUT STYLES -->
    <script src="{{url('')}}/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
    <link rel="shortcut icon" href="favicon.ico"/>
       <style>
        @font-face {
            font-family: "helveticaneuelt";
            src: url("{{url('')}}/public/fonts/helveticaneueltarabic-roman-42.ttf") ;
        }

        body {
    font-family: "helveticaneuelt" !important;
    direction: rtl;
    }
        select.input-sm {

            height: 32px;
        }
        .statusFilter3,.statusFilter1,.statusFilter2{

            font-size: 10px; !important;
        }
    </style>
</head>
<!-- END HEAD -->

<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
<div class="page-wrapper">
    <!-- BEGIN HEADER -->
    @include('includes.top_nav')
    @yield('content')

</div>



<script src="{{url('')}}/assets/global/plugins/respond.min.js"></script>
<script src="{{url('')}}/assets/global/plugins/excanvas.min.js"></script>
<script src="{{url('')}}/assets/global/plugins/ie8.fix.min.js"></script>

<!-- BEGIN CORE PLUGINS -->
{{--
<script src="{{url('')}}/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
--}}
<script src="{{url('')}}/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="{{url('')}}/assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
<script src="{{url('')}}/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js"
        type="text/javascript"></script>
<script src="{{url('')}}/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="{{url('')}}/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js"
        type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script type="text/javascript" src="{{url('')}}/assets/global/plugins/select2/js/select2.min.js"></script>
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="{{url('')}}/assets/global/plugins/moment.min.js" type="text/javascript"></script>
<script src="{{url('')}}/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
<script src="{{url('')}}/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="{{url('')}}/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
<script src="{{url('')}}/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    
<script src="{{url('')}}/assets/global/scripts/app.min.js" type="text/javascript"></script>
<script src="{{url('')}}/assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
<!-- END THEME GLOBAL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="{{url('')}}/assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
<script src="{{url('')}}/assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
<script src="{{url('')}}/assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
<script src="{{url('')}}/assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>
<!-- END THEME LAYOUT SCRIPTS -->
@stack('js')
</body>

</html>