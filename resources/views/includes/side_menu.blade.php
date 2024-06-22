<!-- BEGIN SIDEBAR -->
<div class="page-sidebar-wrapper">
    <!-- BEGIN SIDEBAR -->
    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
    <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
    <div class="page-sidebar navbar-collapse collapse">
        <!-- BEGIN SIDEBAR MENU -->
        <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
        <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
        <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
        <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
        <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
        <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
            <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
            <li class="sidebar-toggler-wrapper hide">
                <div class="sidebar-toggler">
                    <span></span>
                </div>
            </li>
            <!-- END SIDEBAR TOGGLER BUTTON -->
            <!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->
           {{-- <li class="sidebar-search-wrapper">
                <!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
                <!-- DOC: Apply "sidebar-search-bordered" class the below search form to have bordered search box -->
                <!-- DOC: Apply "sidebar-search-bordered sidebar-search-solid" class the below search form to have bordered & solid search box -->
               --}}{{-- <form class="sidebar-search  sidebar-search-bordered" action="page_general_search_3.html" method="POST">
                    <a href="javascript:;" class="remove">
                        <i class="icon-close"></i>
                    </a>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search...">
                        <span class="input-group-btn">
                                            <a href="javascript:;" class="btn submit">
                                                <i class="icon-magnifier"></i>
                                            </a>
                                        </span>
                    </div>
                </form>--}}{{--
                <!-- END RESPONSIVE QUICK SEARCH FORM -->
            </li>--}}
            <li class="nav-item {{@$menu == "home" ?'open active' : ''}}">
                <a href="{{ url('/') }}" class="nav-link nav-toggle">
                    <i class="icon-layers"></i>
                    <span class="title">{{trans('main.main')}}</span>
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                </a>

            </li>
            <li class="nav-item {{@$menu == "users" ?'open active' : ''}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-layers"></i>
                    <span class="title">{{trans('users.users')}}</span>
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item  {{@$sub_menu == "users-admin-show" ?'open active' : ''}}">
                        <a href="{{ url('admin') }}" class="nav-link ">
                            <span class="title">{{trans('users.display_admin_users')}}</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    <li class="nav-item  {{@$sub_menu == "users-driver-show" ?'open active' : ''}}">
                        <a href="{{ url('drivers') }}" class="nav-link ">
                            <span class="title">{{trans('users.display_drivers_users')}}</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    <li class="nav-item  {{@$sub_menu == "users-show" ?'open active' : ''}}">
                        <a href="{{ url('users') }}" class="nav-link ">
                            <span class="title">{{trans('users.display_users')}}</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                 {{--   <li class="nav-item  {{@$sub_menu == "users-create" ?'open active' : ''}}">
                        <a href="{{ url('users/create') }}" class="nav-link ">
                            <span class="title">{{trans('users.add_users')}}</span>
                        </a>
                    </li>--}}

                </ul>
            </li>
            <li class="nav-item {{@$menu == "category" ?'open active' : ''}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-layers"></i>
                    <span class="title">{{trans('category.category')}}</span>
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item  {{@$sub_menu == "category-display" ?'open active' : ''}}">
                        <a href="{{url('category')}}" class="nav-link ">
                            <span class="title">{{trans('category.display_categories')}}</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    <li class="nav-item  {{@$sub_menu == "category-create" ?'open active' : ''}}">
                        <a href="{{url('category/create')}}" class="nav-link ">
                            <span class="title">{{trans('category.add_categories')}}</span>
                        </a>
                    </li>
                    <li class="nav-item  {{@$sub_menu == "subcategory-create" ?'open active' : ''}}">
                        <a href="{{url('subcategory/create')}}" class="nav-link ">
                            <span class="title">{{trans('category.add_subCategories')}}</span>
                        </a>
                    </li>

                </ul>
            </li>
            <li class="nav-item {{@$menu == "attribute" ?'open active' : ''}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-layers"></i>
                    <span class="title">{{trans('main.attribute')}}</span>
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item {{@$sub_menu == "attribute" ?'open active' : ''}}">
                        <a href="{{url('attribute')}}" class="nav-link ">
                            <span class="title">{{trans('main.display_attribute')}}</span>
                            <span class="selected"></span>
                        </a>
                    </li>


                </ul>
            </li>
            <li class="nav-item">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-layers"></i>
                    <span class="title">{{trans('main.products')}}</span>
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item  active open">
                        <a href="{{ route('products') }}" class="nav-link ">
                            <span class="title">{{trans('main.display_products')}}</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    <li class="nav-item  ">
                        <a href="{{ route('addProduct') }}" class="nav-link ">
                            <span class="title">{{trans('main.add_products')}}</span>
                        </a>
                    </li>

                </ul>
            </li>
             <li class="nav-item">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-layers"></i>
                    <span class="title">العروض</span>
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item  active open">
                        <a href="{{ route('offers') }}" class="nav-link ">
                            <span class="title">عروض المنتجات</span>
                            <span class="selected"></span>
                        </a>
                    </li>

                </ul>
            </li>
              <li class="nav-item">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-layers"></i>
                    <span class="title">الكابونات</span>
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item  active open">
                        <a href="{{ route('coupons') }}" class="nav-link ">
                            <span class="title">عرض الكابونات</span>
                            <span class="selected"></span>
                        </a>
                    </li>

                </ul>
            </li>
            <li class="nav-item">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-layers"></i>
                    <span class="title">{{trans('main.orders')}}</span>
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item  active open">
                        <a href="{{url('orders')}}" class="nav-link ">
                            <span class="title">{{trans('main.view_orders')}}</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                   {{-- <li class="nav-item  ">
                        <a href="layout_ajax_page.html" class="nav-link ">
                            <span class="title">{{trans('main.add_products')}}</span>
                        </a>
                    </li>--}}

                </ul>
            </li>

             <li class="nav-item {{@$menu == "setting" ?'open active' : ''}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-layers"></i>
                    <span class="title">{{trans('main.system_setting')}}</span>
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item {{@$sub_menu == "city" ?'open active' : ''}}">
                        <a href="{{url('city')}}" class="nav-link ">
                            <span class="title">{{trans('city.cities')}}</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    <li class="nav-item {{@$sub_menu == "appsetting" ?'open active' : ''}}">
                        <a href="{{url('setting')}}" class="nav-link ">
                            <span class="title">{{trans('main.appSetting')}}</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                     <li class="nav-item {{@$sub_menu == "unit" ?'open active' : ''}}">
                        <a href="{{url('unit')}}" class="nav-link ">
                            <span class="title">الوحدات القياسية</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    <li class="nav-item {{@$sub_menu == "delivery" ?'open active' : ''}}">
                        <a href="{{url('delivery')}}" class="nav-link ">
                            <span class="title">اعدادات فترات العمل</span>
                            <span class="selected"></span>
                        </a>
                    </li>

                </ul>
            </li>
            <li class="nav-item {{@$menu == "notification" ?'open active' : ''}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-layers"></i>
                    <span class="title">{{trans('main.system_notifications')}}</span>
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item {{@$sub_menu == "noti_display" ?'open active' : ''}}">
                        <a href="{{url('notifications')}}" class="nav-link ">
                            <span class="title">{{trans('main.system_not_disp')}}</span>
                            <span class="selected"></span>
                        </a>
                    </li>


                </ul>
            </li>
            <li class="nav-item {{@$menu == "instructions" ?'open active' : ''}}">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-layers"></i>
                    <span class="title">التعليمات</span>
                    <span class="selected"></span>
                    <span class="arrow open"></span>
                </a>
                <ul class="sub-menu ">
                    <li class="nav-item  {{@$sub_menu == "instructions-display" ?'open active' : ''}}">
                        <a href="{{url('instructions')}}" class="nav-link ">
                            <span class="title">عرض التعليمات</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                    <li class="nav-item  {{@$sub_menu == "instructions-create" ?'open active' : ''}}">
                        <a href="{{url('instructions/create')}}" class="nav-link ">
                            <span class="title">اضافة تعليمات</span>
                        </a>
                    </li>

                </ul>
            </li>


        </ul>
        <!-- END SIDEBAR MENU -->
        <!-- END SIDEBAR MENU -->
    </div>
    <!-- END SIDEBAR -->
</div>
<!-- END SIDEBAR -->