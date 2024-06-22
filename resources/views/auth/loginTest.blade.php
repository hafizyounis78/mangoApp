
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
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->


    <head>
        <meta charset="utf-8" />
        <title>Raffle Admin Panel</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="#1 selling multi-purpose bootstrap admin theme sold in themeforest marketplace packed with angularjs, material design, rtl support with over thausands of templates and ui elements and plugins to power any type of web applications including saas and admin dashboards. Preview page of Theme #1 for "
            name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="{{url('')}}/assets2/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="{{url('')}}/assets2/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="{{url('')}}/assets2/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="{{url('')}}/assets2/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="{{url('')}}/assets2/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="{{url('')}}/assets2/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="{{url('')}}/assets2/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="{{url('')}}/assets2/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="{{url('')}}/assets2/pages/css/login-3.min.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="favicon.ico" /> </head>
    <!-- END HEAD -->

    <body class=" login">
        <!-- BEGIN LOGO -->
        <div class="logo">
            <h2 style="font-family: Serif">Raffle</h3>
        </div>
        <!-- END LOGO -->
    

        <!-- BEGIN LOGIN -->
        <div class="content">
            <!-- BEGIN LOGIN FORM -->
             <form class="login-form" action="{{ url('/login') }}" method="post">
              {{ csrf_field() }}
                <h3 class="form-title">Login to your account</h3>
                <div class="alert alert-danger display-hide">
                    <button class="close" data-close="alert"></button>
                    <span> Enter any username and password. </span>
                </div>
                <div class="form-group">
                    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                    <label class="control-label visible-ie8 visible-ie9">Username</label>
                    <div class="input-icon">
                        <i class="fa fa-user"></i>
                        <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Username" name="username" /> 
                     @if ($errors->has('username'))
                      <span class="help-block">
                      <p class="alert alert-danger">{{ $errors->first('username') }}</p>
                      </span>
                     @endif
                        </div>
                </div>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">Password</label>
                    <div class="input-icon">
                        <i class="fa fa-lock"></i>
                        <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="password" /> 

                       @if ($errors->has('password'))
                          <span class="help-block">
                         <strong>{{ $errors->first('password') }}</strong>
                          </span>
                          @endif
                        </div>
                </div>
                <div class="form-actions">
                    <label class="rememberme mt-checkbox mt-checkbox-outline">
                        <input type="checkbox" name="remember" value="1" /> Remember me
                        <span></span>
                    </label>
                    <button type="submit" class="btn green pull-right"> Login </button>
                </div>
                
            </form>
            <!-- END LOGIN FORM -->
            
        </div>
        <div class="copyright">         <div align="center" style="font-family: Tahoma;">{{ __('main.develop') }}</div>
    <div align="center" style="font-family: Tahoma;color: white;">{{ __('main.develop_address') }}</div>
    <div align="center" style="font-family: Tahoma;color: white;">{{ date('Y') }}</div>
</div>
        <!-- END LOGIN -->
        <!--[if lt IE 9]>
<script src="{{url('')}}/assets2/global/plugins/respond.min.js"></script>
<script src="{{url('')}}/assets2/global/plugins/excanvas.min.js"></script> 
<script src="{{url('')}}/assets2/global/plugins/ie8.fix.min.js"></script> 
<![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="{{url('')}}/assets2/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="{{url('')}}/assets2/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="{{url('')}}/assets2/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="{{url('')}}/assets2/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="{{url('')}}/assets2/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="{{url('')}}/assets2/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="{{url('')}}/assets2/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
        <script src="{{url('')}}/assets2/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
        <script src="{{url('')}}/assets2/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="{{url('')}}/assets2/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="{{url('')}}/assets2/pages/scripts/login.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <!-- END THEME LAYOUT SCRIPTS -->
    </body>

</html>