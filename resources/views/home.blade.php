<style>

    .center {
        display: block;
        margin-left: auto;
        margin-right: auto;
        width: 60%;
        margin-top: 50px;
    }

    .google-maps {
        position: relative;
        padding-bottom: 75%;
    / / This is the aspect ratio height: 0;
        overflow: hidden;
    }

    .google-maps iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100% !important;
        height: 100% !important;
    }
</style>

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

                <!-- BEGIN PAGE CONTENT-->
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 margin-bottom-10">
                        <div class="dashboard-stat blue-madison">
                            <div class="visual">
                                <i class="fa fa-briefcase fa-icon-medium"></i>
                            </div>
                            <div class="details">
                                <div class="number">
                                    {{round($Lifetime_Sales,2)}}<small> SAR </small>
                                </div>
                                <div class="desc">
                                     المبيعات اليومية
                                </div>
                            </div>
                            {{-- <a class="more" href="#">
                                 View more <i class="m-icon-swapright m-icon-white"></i>
                             </a>--}}
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <div class="dashboard-stat red-intense">
                            <div class="visual">
                                <i class="fa fa-shopping-cart"></i>
                            </div>
                            <div class="details">
                                <div class="number">
                                    {{$Total_Orders}}
                                </div>
                                <div class="desc">
                                     عدد الطلبات اليومية
                                </div>
                            </div>
                            {{--<a class="more" href="#">
                                View more <i class="m-icon-swapright m-icon-white"></i>
                            </a>--}}
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <div class="dashboard-stat green-haze">
                            <div class="visual">
                                <i class="fa fa-group fa-icon-medium"></i>
                            </div>
                            <div class="details">
                                <div class="number">
                                    {{$total_cust}}
                                </div>
                                <div class="desc">
                                    عدد الزبائن
                                </div>
                            </div>
                            {{-- <a class="more" href="#">
                                 View more <i class="m-icon-swapright m-icon-white"></i>
                             </a>--}}
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-12">
                        <!-- Begin: life time stats red-sunglo-->
                        <div class="portlet box red-sunglo">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-thumb-tack"></i>عرض الطلبات
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="collapse">
                                    </a>

                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="tabbable-line">
                                    <ul class="nav nav-tabs">
                                        <li class="active">
                                            <a href="#orderview_1" data-toggle="tab">
                                                اخر 10 </a>
                                        </li>
                                        <li>
                                            <a href="#orderview_2" data-toggle="tab">
                                                قيد الانتظار </a>
                                        </li>
                                        <li>
                                            <a href="#orderview_3" data-toggle="tab">
                                                تم التوصيل </a>
                                        </li>
                                        <li>
                                            <a href="#orderview_4" data-toggle="tab">
                                                إلغاء الطلب </a>
                                        </li>
                                        <li>
                                            <a href="#orderview_5" data-toggle="tab">
                                                اماكن الطلبات </a>
                                        </li>

                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="orderview_1">
                                            <div class="scroller" style="height: 300px;">
                                                <table class="table table-striped table-hover table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>
                                                            رقم الطلب.
                                                        </th>
                                                        <th>
                                                            المستخدم
                                                        </th>
                                                        <th>
                                                            تاريخ الطلب
                                                        </th>
                                                        <th>
                                                                 المبلغ SAR
                                                        </th>
                                                        <th>
                                                            حالة الطلب
                                                        </th>

                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php $class = '' ?>
                                                    @foreach($last_orders as $order)
                                                        @if($order->ord_status==1)
                                                            <?php $class = 'blue'?>
                                                        @elseif($order->ord_status==2)
                                                            <?php $class = 'purple-plum'?>
                                                        @elseif($order->ord_status==3)
                                                            <?php $class = 'yellow-gold'?>
                                                        @elseif($order->ord_status==4)
                                                            <?php $class = 'green-haze'?>
                                                        @elseif($order->ord_status==5)
                                                            <?php $class = 'blue-chambray'?>
                                                        @elseif($order->ord_status==6)
                                                            <?php $class = 'red'?>
                                                        @endif
                                                        <tr>
                                                            <td>
                                                               <a href="{{url('orderDetails?ord_id='.$order->ord_id.'')}}" class='btn btn-primary btn-sm btnSeen' data-id='$id'>
                                                                <i class='fa fa-eye'></i><i class='fa fa-lg fa-spin fa-spinner hidden'></i>
                                                                    {{$order->ord_id}} </a>
                                                            </td>
                                                            <td>
                                                               <a href="{{url('orderDetails?ord_id='.$order->ord_id.'')}}"  data-id='$id'>
                                                                    {{$order->name}} </a>
                                                            </td>
                                                            <td>
                                                                {{$order->ord_createdAt}}
                                                            </td>
                                                            <td>
                                                                {{$order->ord_totalAfterTax}}
                                                            </td>
                                                            <td>

													<span class="label label-sm bg-{{$class}}">
													{{$order->status_desc}} </span>
                                                            </td>

                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="orderview_2">
                                            <div class="scroller" style="height: 300px;">
                                                <table class="table table-striped table-hover table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>
                                                            رقم الطلب.
                                                        </th>
                                                        <th>
                                                            المستخدم
                                                        </th>
                                                        <th>
                                                            تاريخ الطلب
                                                        </th>
                                                        <th>
                                                                 المبلغ SAR
                                                        </th>
                                                        <th>
                                                            حالة الطلب
                                                        </th>
                                                    </thead>
                                                    <tbody>
                                                    <?php $class = '' ?>
                                                    @foreach($pending_orders as $order)
                                                        @if($order->ord_status==1)
                                                            <?php $class = 'blue'?>
                                                        @elseif($order->ord_status==2)
                                                            <?php $class = 'purple-plum'?>
                                                        @elseif($order->ord_status==3)
                                                            <?php $class = 'yellow-gold'?>
                                                        @elseif($order->ord_status==4)
                                                            <?php $class = 'green-haze'?>
                                                        @elseif($order->ord_status==5)
                                                            <?php $class = 'blue-chambray'?>
                                                        @elseif($order->ord_status==6)
                                                            <?php $class = 'red'?>
                                                        @endif
                                                        <tr>
                                                            <td>
                                                                <a href="#">
                                                                    {{$order->ord_id}} </a>
                                                            </td>
                                                            <td>
                                                                <a href="#">
                                                                    {{$order->name}} </a>
                                                            </td>
                                                            <td>
                                                                {{$order->ord_createdAt}}
                                                            </td>
                                                            <td>
                                                                {{$order->ord_totalAfterTax}}
                                                            </td>
                                                            <td>

													<span class="label label-sm bg-{{$class}}">
													{{$order->status_desc}} </span>
                                                            </td>

                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="orderview_3">
                                            <div class="scroller" style="height: 300px;">
                                                <table class="table table-striped table-hover table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>
                                                            رقم الطلب.
                                                        </th>
                                                        <th>
                                                            المستخدم
                                                        </th>
                                                        <th>
                                                            تاريخ الطلب
                                                        </th>
                                                        <th>
                                                                 المبلغ SAR
                                                        </th>
                                                        <th>
                                                            حالة الطلب
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php $class = '' ?>
                                                    @foreach($complete_orders as $order)
                                                        @if($order->ord_status==1)
                                                            <?php $class = 'blue'?>
                                                        @elseif($order->ord_status==2)
                                                            <?php $class = 'purple-plum'?>
                                                        @elseif($order->ord_status==3)
                                                            <?php $class = 'yellow-gold'?>
                                                        @elseif($order->ord_status==4)
                                                            <?php $class = 'green-haze'?>
                                                        @elseif($order->ord_status==5)
                                                            <?php $class = 'blue-chambray'?>
                                                        @elseif($order->ord_status==6)
                                                            <?php $class = 'red'?>
                                                        @endif
                                                        <tr>
                                                            <td>
                                                                <a href="#">
                                                                    {{$order->ord_id}} </a>
                                                            </td>
                                                            <td>
                                                                <a href="#">
                                                                    {{$order->name}} </a>
                                                            </td>
                                                            <td>
                                                                {{$order->ord_createdAt}}
                                                            </td>
                                                            <td>
                                                                {{$order->ord_totalAfterTax}}
                                                            </td>
                                                            <td>

													<span class="label label-sm bg-{{$class}}">
													{{$order->status_desc}} </span>
                                                            </td>

                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="orderview_4">
                                            <div class="scroller" style="height: 300px;">
                                                <table class="table table-striped table-hover table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>
                                                            رقم الطلب.
                                                        </th>
                                                        <th>
                                                            المستخدم
                                                        </th>
                                                        <th>
                                                            تاريخ الطلب
                                                        </th>
                                                        <th>
                                                                 المبلغ SAR
                                                        </th>
                                                        <th>
                                                            حالة الطلب
                                                        </th>

                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php $class = '' ?>
                                                    @foreach($canceled_orders as $order)
                                                        @if($order->ord_status==1)
                                                            <?php $class = 'blue'?>
                                                        @elseif($order->ord_status==2)
                                                            <?php $class = 'purple-plum'?>
                                                        @elseif($order->ord_status==3)
                                                            <?php $class = 'yellow-gold'?>
                                                        @elseif($order->ord_status==4)
                                                            <?php $class = 'green-haze'?>
                                                        @elseif($order->ord_status==5)
                                                            <?php $class = 'blue-chambray'?>
                                                        @elseif($order->ord_status==6)
                                                            <?php $class = 'red'?>
                                                        @endif
                                                        <tr>
                                                            <td>
                                                                <a href="#">
                                                                    {{$order->ord_id}} </a>
                                                            </td>
                                                            <td>
                                                                <a href="#">
                                                                    {{$order->name}} </a>
                                                            </td>
                                                            <td>
                                                                {{$order->ord_createdAt}}
                                                            </td>
                                                            <td>
                                                                {{$order->ord_totalAfterTax}}
                                                            </td>
                                                            <td>

													<span class="label label-sm bg-{{$class}}">
													{{$order->status_desc}} </span>
                                                            </td>

                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="orderview_5">
                                            <div class="scroller" style="height: 300px;">
                                                <table class="table table-striped table-hover table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>
                                                            المدينة
                                                        </th>

                                                        <th>
                                                            عدد الطلبات
                                                        </th>
                                                       
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($list_orders_by_Loc as $loc)
                                                        <tr>
                                                            <td>
                                                                <a href="#">
                                                                    {{$loc->city_name}} </a>
                                                            </td>
                                                            <td>{{$loc->total_orders}}
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
                <div class="row">
                    <div class="col-md-6">
                        <!-- Begin: life time stats -->
                        <div class="portlet box blue-steel">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-thumb-tack"></i>المنتجات
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="collapse">
                                    </a>

                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="tabbable-line">
                                    <ul class="nav nav-tabs">
                                        {{--<li class="active">
                                            <a href="#overview_1" data-toggle="tab">
                                                Top Selling </a>
                                        </li>--}}
                                        <li class="active">
                                            <a href="#overview_2" data-toggle="tab">
                                                الفئات الجديدة </a>
                                        </li>
                                        <li>
                                            <a href="#overview_3" data-toggle="tab">
                                                المنتجات الجديدة </a>
                                        </li>


                                    </ul>
                                    <div class="tab-content">

                                        <div class="tab-pane active" id="overview_2">
                                            <div class="scroller" style="height: 300px;">
                                                <table class="table table-striped table-hover table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>
                                                            الفئة
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($last_category as $cat)
                                                        <tr>
                                                            <td>
                                                                {{$cat->cat_name}}
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="overview_3">
                                            <div class="scroller" style="height: 300px;">
                                                <table class="table table-striped table-hover table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>
                                                            Product name
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($last_products as $prd)
                                                        <tr>
                                                            <td>
                                                                {{$prd->product_name}}
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
                    <div class="col-md-6">
                        <!-- Begin: life time stats -->
                        <div class="portlet box red-sunglo">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-bar-chart-o"></i>العائدات المالية الشهرية
                                </div>
                                <div class="tools">
                                   <!-- <a href="#portlet-config" data-toggle="modal" class="config">
                                    </a>
                                    <a href="javascript:;" class="reload">
                                    </a>-->
                                </div>
                                <ul class="nav nav-tabs" style="margin-right: 10px">
                                    <li class="active">
                                        <a href="#portlet_tab1" data-toggle="tab">SAR المبلغ الاجمالي </a>
                                    </li>
                                    <li>
                                        <a href="#portlet_tab2" id="statistics_orders_tab" data-toggle="tab">
                                            الطلبات </a>
                                    </li>

                                </ul>
                            </div>
                            <div class="portlet-body">
                                <div class="tab-content">

                                    <div class="tab-pane active" id="portlet_tab1">
                                        <div class="scroller" style="height: 390px !important">
                                            <div id="statistics_1" class="chart">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="portlet_tab2">
                                        <div class="scroller" style="height: 390px !important">
                                            <div id="statistics_2" class="chart">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{--<div class="well no-margin no-border">--}}
                                {{--<div class="row">--}}
                                {{--<div class="col-md-3 col-sm-3 col-xs-6 text-stat">--}}
                                {{--<span class="label label-success">--}}
                                {{--Revenue: </span>--}}
                                {{--<h3>$1,234,112.20</h3>--}}
                                {{--</div>--}}
                                {{--<div class="col-md-3 col-sm-3 col-xs-6 text-stat">--}}
                                {{--<span class="label label-info">--}}
                                {{--Tax: </span>--}}
                                {{--<h3>$134,90.10</h3>--}}
                                {{--</div>--}}
                                {{--<div class="col-md-3 col-sm-3 col-xs-6 text-stat">--}}
                                {{--<span class="label label-danger">--}}
                                {{--Shipment: </span>--}}
                                {{--<h3>$1,134,90.10</h3>--}}
                                {{--</div>--}}
                                {{--<div class="col-md-3 col-sm-3 col-xs-6 text-stat">--}}
                                {{--<span class="label label-warning">--}}
                                {{--Orders: </span>--}}
                                {{--<h3>235090</h3>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                            </div>
                        </div>
                        <!-- End: life time stats -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <!-- Begin: life time stats -->
                        <div class="portlet box green-haze">

                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-thumb-tack"></i>المستخدمين
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="collapse">
                                    </a>

                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="tabbable-line">
                                    <ul class="nav nav-tabs">

                                        <li class="active">
                                            <a href="#userview_1" data-toggle="tab">
                                                الزبائن الجدد</a>
                                        </li>


                                    </ul>
                                    <div class="tab-content">

                                        <div class="tab-pane active" id="userview_1">
                                            <div class="scroller" style="height: 300px;">
                                                <table class="table table-striped table-hover table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>
                                                            المستخدم
                                                        </th>
                                                        <th>
                                                            عدد الطلبات
                                                        </th>
                                                        <th>
                                                                 المبلغ SAR
                                                        </th>
                                                        <th>
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($lastTenCustomer as $customer)
                                                        <tr>
                                                            <td>
                                                                <a href="#">
                                                                    {{$customer->name}} </a>
                                                            </td>
                                                            <td>
                                                                {{$customer->total_order}}
                                                            </td>
                                                            <td>
                                                                {{round($customer->total_amount,2)}}
                                                            </td>
                                                             <td>
                                                                <a class="btn red btn-outline sbold user-info" data-toggle="modal"  
                                                                data-target="#userDetails" onclick="getUserInfo({{$customer->id}},1);"> عرض  </a>

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
                    </div>
                    <div class="col-md-6">
                        <!-- Begin: life time stats -->
                        <div class="portlet box green">

                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-thumb-tack"></i>السائقين
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="collapse">
                                    </a>

                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="tabbable-line">
                                    <ul class="nav nav-tabs">

                                        <li class="active">
                                            <a href="#userview_2" data-toggle="tab">
                                                السائقين الجدد </a>
                                        </li>

                                    </ul>
                                    <div class="tab-content">

                                        <div class="tab-pane active" id="userview_2">
                                            <div class="scroller" style="height: 300px;">
                                                <table class="table table-striped table-hover table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th>
                                                            السائق
                                                        </th>
                                                        <th>
                                                            عدد الطلبات
                                                        </th>

                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($list_of_drivers as $driver)
                                                        <tr>
                                                            <td>
                                                                <a href="#">
                                                                    {{$driver->name}} </a>
                                                            </td>
                                                            <td>
                                                                {{$driver->total_order}}
                                                            </td>
                                                            <td>
                                                                <a class="btn red btn-outline sbold user-info"
                                                                   data-toggle="modal" data-target="#userDetails"
                                                                   onclick="getUserInfo({{$driver->id}},2);"> عرض </a>

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
                    </div>
                    <!-- End: life time stats -->
                </div>
                   <div id="userDetails" class="modal fade" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h4 class="modal-title">بيانات المستخدم</h4>
                            </div>
                            <div class="modal-body">
                                <div class="scroller" style="height:300px" data-always-visible="1" data-rail-visible1="1">

                                            <!-- BEGIN FORM-->
                                            <form class="form-horizontal" role="form">
                                                <div class="form-body">
                                                  {{--  <h2 class="margin-bottom-20"> View User Info - Bob Nilson </h2>--}}
                                                    <h3 class="form-section">البيانات الشخصية</h3>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-3">الاسم</label>
                                                                <div class="col-md-9">
                                                                    <p class="form-control-static" id="name"> </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--/span-->
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-5">البريد الإلكتروني</label>
                                                                <div class="col-md-7">
                                                                    <p class="form-control-static" id="email">  </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--/span-->
                                                    </div>
                                                    <!--/row-->
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">رقم الجوال</label>
                                                                <div class="col-md-8">
                                                                    <p class="form-control-static" id="mobile">  </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-4">نوع الجهاز</label>
                                                                <div class="col-md-8">
                                                                    <p class="form-control-static" id="deviceType">  </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--/span-->

                                                        <!--/span-->
                                                    </div>
                                                    <!--/row-->
                                                    <div class="row">

                                                        <!--/span-->
                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-6">تاريخ الاشتراك</label>
                                                                <div class="col-md-6">
                                                                    <p class="form-control-static" id="created_at">  </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-7">

                                                            <div class="form-group">
                                                                <label class="control-label col-md-3">الصورة الشخصية</label>
                                                                <div class="col-md-9">
                                                                    <img id="image"
                                                                           src="{{url("/")}}/img/defualt_user.jpg"
                                                                         width="200" height="150">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!--/span-->
                                                    </div>
                                                    <!--/row-->
                                                    <h3 class="form-section">العنوان</h3>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-3">تفصيل العنوان:</label>
                                                                <div class="col-md-9">
                                                                    <p class="form-control-static" id="city">  </p>
                                                                    <p class="form-control-static" id="adr_address">  </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                            </form>
                                            <!-- END FORM-->

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" data-dismiss="modal" class="btn dark btn-outline">Close</button>
                                <!--<button type="button" class="btn green">Save changes</button>-->
                            </div>
                        </div>
                    </div>
                </div>
                  <div id="productDetails" class="modal fade" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h4 class="modal-title">بيانات المنتج</h4>
                            </div>
                            <div class="modal-body">
                                <div class="scroller" style="height:300px" data-always-visible="1"
                                     data-rail-visible1="1">

                                    <!-- BEGIN FORM-->
                                    <form class="form-horizontal" role="form">
                                        <div class="form-body">
                                            {{--  <h2 class="margin-bottom-20"> View User Info - Bob Nilson </h2>--}}
                                            <h3 class="form-section">البيانات الاساسية</h3>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">الاسم عربي:</label>
                                                        <div class="col-md-7">
                                                            <p class="form-control-static" id="name_ar"></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-5">الاسم انجليزي:</label>
                                                        <div class="col-md-7">
                                                            <p class="form-control-static" id="name_en"></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--/span-->
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-4">الفئة:</label>
                                                        <div class="col-md-8">
                                                            <p class="form-control-static" id="cat_name"></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--/span-->

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label col-md-5">السعر:</label>
                                                        <div class="col-md-7">
                                                            <p class="form-control-static" id="prd_price"></p>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <!--/row-->
                                            <div class="row">


                                                <div class="col-md-7">

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">الصورة </label>
                                                        <div class="col-md-9">

                                                            <img id="prd_image" src="" width="200" height="150">
                                                        </div>
                                                    </div>
                                                </div>

                                                <!--/span-->
                                            </div>
                                           
                                        </div>

                                    </form>
                                    <!-- END FORM-->

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" data-dismiss="modal" class="btn dark btn-outline">Close</button>
                                <!--<button type="button" class="btn green">Save changes</button>-->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN MARKERS PORTLET-->
                        <div id="map" class="gmaps" style="height: 500px">
                        </div>
                        {{--<div class="portlet solid yellow">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-gift"></i>التوزيع الجغرافي للطلبات
                                </div>

                            </div>
                            <div id="map" class="portlet-body gmaps" >

                            </div>
                        </div>--}}
                        <!-- END MARKERS PORTLET-->
                    </div>
                </div>
                <br>
                <div class="row">

                    <div class="col-md-12">
                        <!-- Begin: life time stats -->
                        <div class="portlet box blue-steel">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-thumb-tack"></i>المبيعات
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="collapse">
                                    </a>
                                  
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="tabbable-line">
                                    <ul class="nav nav-tabs">
                                        <li class="active">
                                            <a href="#overview_1" data-toggle="tab">
                                                الأعلى مبيعا </a>
                                        </li>
                                        {{-- <li>
                                             <a href="#overview_2" data-toggle="tab">
                                                 New Category </a>
                                         </li>
                                         <li>
                                             <a href="#overview_3" data-toggle="tab">
                                                 New Product </a>
                                         </li>--}}


                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="overview_1">
                                            <div class="scroller" style="height: 300px;">
                                                <table class="table table-striped table-hover table-bordered">
                                                    <thead>

                                                    <tr>
                                                        <th>
                                                            المنتج
                                                        </th>
                                                        <th>
                                                         SAR   السعر
                                                        </th>
                                                        <th>
                                                            الكمية
                                                        </th>

                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($Top_selling as $prod)
                                                        <tr>
                                                           <td>
                                                                <a 
                                                                   data-toggle="modal" data-target="#productDetails"
                                                                   onclick="getProdInfo({{$prod->prd_id}});">
                                                                    {{$prod->product_name}}
                                                                </a>
                                                            </td>
                                                            <td>
                                                                {{$prod->total_amount}}
                                                            </td>
                                                            <td>
                                                                {{$prod->total_quantity}}
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
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN BASIC CHART PORTLET-->
                        <div class="portlet box blue">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-gift"></i>رسم بياني للميبعات
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="collapse">
                                    </a>
                                  
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div id="chart_1_1_legendPlaceholder">
                                </div>
                                <div id="chart_1_1" class="chart">
                                </div>
                            </div>
                        </div>
                        <!-- END BASIC CHART PORTLET-->
                    </div>
                </div>
                <!-- END PAGE CONTENT-->
            </div>
            <!-- END CONTENT BODY -->
        </div>
        <!-- END CONTENT -->
    </div>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('includes/footer')
    @push('js')
        <script>
            // Initialize and add the map
            var locations = [];

            function initMap() {

                // var locations = [];
                $.ajax({
                    type: 'POST',
                    url: 'ordersMap',
                    // data:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    headers:
                        {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    //   },
                    success: function (msg) {
                       // alert(msg);
                        $.each(msg, function (key, val) {
                            //alert(key+val.lat);
                            locations[key] = [val.ord_id, val.lat, val.lng];
                            var uluru = {lat: 24.774265, lng: 46.738586};
                            var uluru2 = {lat: 24.80, lng: 46.80};
                            // The map, centered at Uluru

                            var map = new google.maps.Map(
                                document.getElementById('map'), {zoom: 10, center: uluru});
                            // The marker, positioned at Uluru
                            /* var locations = [
                                 ['m0', 24.774265, 46.738586, 4],
                                 ['m1', 24.80, 151.259052, 5],
                                 ['m2', 24.80, 46.80, 3],
                                 ['m3', 24.90, 46.88, 2],
                                 ['m4', 24.85, 46.90, 1]
                             ];*/
                            //      setTimeout(function() {
                            var marker, i;
                            //   alert('location2:  ' + locations);
                            for (i = 0; i < locations.length; i++)
                                //alert(locations[i][1]);
                                 var marker = new google.maps.Marker({

                                     position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                                     map: map
                                 })

                            //     },3000);
                        });

                        //    alert('location1:  '+locations);
                    }
                });
                // The location of Uluru

            }

            // marker = new google.maps.Marker({position: uluru2, map: map});


            //********************************//

        </script>
       <!-- <script async defer
                src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA_nAiYK8hpagburSEhPVL-ywyovSvhQNc&callback=initMap">
        </script>-->
            <script src="//maps.googleapis.com/maps/api/js?key=AIzaSyDJ-vBN5LUSAHvFIztDylaT2lkxAyn1mNU&callback=initMap" async="" defer="defer" type="text/javascript"></script>
        {{--<script src="{{url('')}}/assets/global/plugins/jquery.min.js" type="text/javascript"></script>--}}
        <script src="{{url('')}}/assets/global/plugins/flot/jquery.flot.js" type="text/javascript"></script>
        <script src="{{url('')}}/assets/global/plugins/flot/jquery.flot.resize.js" type="text/javascript"></script>
        <script src="{{url('')}}/assets/global/plugins/flot/jquery.flot.categories.js" type="text/javascript"></script>
        <script src="{{url('')}}/assets/pages/scripts/charts-flotcharts.js"></script>
        <script src="{{url('')}}/assets/pages/scripts/ecommerce-dashboard.js"></script>
        <script src="{{url('')}}/assets/global/plugins/moment.min.js" type="text/javascript"></script>
         <script type="text/javascript">
            var base_url = '{!! url('/') !!}';
        </script>

        {{--<script src="{{url('')}}/assets/pages/scripts/index.js" type="text/javascript"></script>--}}
        <script>
            jQuery(document).ready(function () {

                //   Index.init();
                //    Index.initDashboardDaterange();
                

            });
          function getUserInfo(id,type) {
           
               $('#name').html('');

               $('#email').html('');

               $('#mobile').html();

           //    $('#image').attr("src",'http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image');
            $('#image').attr("src", base_url+'/img/defualt_user.jpg');

               $('#deviceType').html('');

               $('#created_at').html('');

               $('#adr_address').html('');

               $('#city').html('');
               $.ajax({
                   type: 'POST',
                   url: 'getUserInfo/'+id,
                   // data:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                   headers:
                       {
                           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                       },
                   //   },
                   success: function (msg) {

                     //  alert(msg);
                       $.each(msg, function (key, val) {
                         //  alert(key+val.name);

                             $('#name').html(val.name);

                               $('#email').html(val.email);

                               $('#mobile').html(val.mobile);
                              
                              if(type==1)
                              {
                                if(val.image!='')
                                    $('#image').attr("src",val.image);


                              }
                              else {

                                if (val.image != '')
                                    $('#image').attr("src", base_url + '/storage' + val.image);

                              }
                              if (val.deviceType == 1)
                                $('#deviceType').html('Android');
                              else
                                $('#deviceType').html('iOS');
                              // $('#deviceType').html(val.deviceType);

                               $('#created_at').html(val.created_at);

                               $('#adr_address').html(val.adr_address);

                               $('#city').html(val.city);




                       });

                       //    alert('location1:  '+locations);
                   }
               });

           }
            function getProdInfo(id) {


                $('#name_ar').html('');
                $('#name_en').html('');
                $('#cat_name').html('');

                $('#prd_price').html('');
                $('#image').attr("src","");
                $.ajax({
                    type: 'POST',
                    url: 'getProdInfo/' + id,
                    // data:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    headers:
                        {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    //   },
                    success: function (msg) {

                      
                            $('#name_ar').html(msg.product.name_ar);
                            $('#name_en').html(msg.product.name_en);
                            $('#cat_name').html(msg.product.cat_name);

                            $('#prd_price').html(msg.product.prd_price);
                            $('#prd_image').attr("src", base_url + '/storage/product/img/' + msg.product.prd_image);

                      
                    }
                });

            }
        </script>


    @endpush
@endsection

