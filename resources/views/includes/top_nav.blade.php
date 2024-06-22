<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner ">
        <!-- BEGIN LOGO -->

        <div class="page-logo">
                    <a href="{{asset('/')}}">
                        <img  height="50"  src="{{url('public/img/ic_launcher_round.png')}}" alt="logo" class="logo-default" style="margin-top: -1px;border-radius: 50%!important;">
                    </a>
                    <div class="menu-toggler sidebar-toggler">
                        <span>

                        </span>
                    </div>
                </div>

        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
            <span></span>
        </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">

                <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <i class="icon-bell"></i>
                        <span class="badge badge-default" id="head_count"> 0 </span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="external">
                            <h3 id="h3_head_count">
                                <span class="bold">12 pending</span> notifications</h3>
                            <a href="{{url('notifications')}}">view all</a>
                        </li>
                        <li>
                            <ul class="dropdown-menu-list scroller" style="height: 250px;" data-handle-color="#637283" id="menu_noti">

                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="dropdown dropdown-user">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="false">
                        <!--<img alt="" class="img-circle" src="{{url('/')}}/assets/layouts/layout/img/avatar3_small.jpg" />-->
                         <img alt="" class="img-circle" src="{{url('/')}}/assets/layouts/layout/img/avatar-default-icon.png" />
                        <span class="username username-hide-on-mobile"> {{ @Auth::user()->name }} </span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-default">
                        <li>
                            <a href="{{ url('users/profile') }}">
                                <i class="icon-user"></i>{{trans('users.my_profile')}}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('logout') }}">
                                <i class="icon-key"></i>{{trans('users.logout')}}</a>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
    <!-- END HEADER INNER -->
</div>
<div class="clearfix"> </div>
@push('js')
<script type="text/javascript">
        var base_url = '{!! url('/') !!}';
    </script>
<script type="text/javascript">
    function get_not()
    {
        $.ajax({
            type: 'POST',
            url:  base_url+'/get_noti',
           // data: {noti_id:$(this).attr('data-id')},
            'headers': {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            //   },
            success: function (msg) {
               // alert(msg);
                var sp = msg.split('╩');
               // alert(sp[0]);
                if(sp[0] == 0)
                {
                 //   alert(sp[1]);

                    $("#head_count").text(sp[1]);
                    $("#h3_head_count").html("You have <strong>"+sp[1]+" </strong> Notifications");
                }
                if(sp[2] > 0 )
                {
                    $("#menu_noti").html(sp[3]);
                }
            }
        });

    }
    $(document).ready(function(e) {
        get_not();
        setInterval(function(){
            get_not();
        },10000);
    
    });
</script>
    @endpush