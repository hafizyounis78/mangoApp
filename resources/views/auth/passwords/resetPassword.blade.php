<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link href="{{url('')}}/assets2/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

</head>
<body style="background-color: rgba(238,243,235,0.97)">

<div class="row">
    <div class="col-sm-3"></div>
    <div class="col-sm-6" style="top:20px;background-color: rgba(252,255,245,0.97)">
        <h3 align="center">Password Reset</h3>
        <h5 align="center">
            Seems like you forget password , if this is true click below
            to reset password.
        </h5>
        <a style="margin: 2px 40%;" class="btn btn-primary" href="{{url('showResetForm/'.$token)}}">reset password</a>
        <h5 align="center" style="padding: 5px;">
           If you did not forget your password you can safely ignore this email.
        </h5>
    </div>
    <div class="col-sm-3"></div>

</div>

<script src="{{url('')}}/assets2/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="{{url('')}}/assets2/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
</body>
</html>