<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        /* Loading Spinner */
        .spinner{margin:0;width:70px;height:18px;margin:-35px 0 0 -9px;position:absolute;top:50%;left:50%;text-align:center}.spinner > div{width:18px;height:18px;background-color:#333;border-radius:100%;display:inline-block;-webkit-animation:bouncedelay 1.4s infinite ease-in-out;animation:bouncedelay 1.4s infinite ease-in-out;-webkit-animation-fill-mode:both;animation-fill-mode:both}.spinner .bounce1{-webkit-animation-delay:-.32s;animation-delay:-.32s}.spinner .bounce2{-webkit-animation-delay:-.16s;animation-delay:-.16s}@-webkit-keyframes bouncedelay{0%,80%,100%{-webkit-transform:scale(0.0)}40%{-webkit-transform:scale(1.0)}}@keyframes bouncedelay{0%,80%,100%{transform:scale(0.0);-webkit-transform:scale(0.0)}40%{transform:scale(1.0);-webkit-transform:scale(1.0)}}
    </style>
    <meta charset="UTF-8">
<!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
<title>CaseReview | Login</title>
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/bootstrap/css/bootstrap.css">

<link rel="stylesheet" type="text/css" href="<?php echo base_url('');?>assets/helpers/utils.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('');?>assets/helpers/colors.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('');?>assets/elements/badges.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('');?>assets/elements/buttons.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('');?>assets/elements/content-box.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('');?>assets/elements/dashboard-box.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('');?>assets/elements/forms.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('');?>assets/elements/images.css">

<link rel="stylesheet" type="text/css" href="<?php echo base_url('');?>assets/elements/response-messages.css">

<link rel="stylesheet" type="text/css" href="<?php echo base_url('');?>assets/icons/fontawesome/fontawesome.css">

<link rel="stylesheet" type="text/css" href="<?php echo base_url('');?>assets/widgets/theme-switcher/themeswitcher.css">

<link rel="stylesheet" type="text/css" href="<?php echo base_url('');?>assets/snippets/login-box.css">
<!-- APPLICATIONS -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url('');?>assets/applications/mailbox.css">
<!-- Admin theme -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url('');?>assets/themes/admin/color-schemes/default.css">

<link rel="stylesheet" type="text/css" href="../../assets/themes/admin/layout.css">

<script type="text/javascript" src="<?php echo base_url('');?>assets/js-core/jquery-core.js"></script>
     
    <script type="text/javascript">
        $(window).load(function(){
            setTimeout(function() {
                $('#loading').fadeOut( 400, "linear" );
            }, 300);
        });
    </script>
</head>
<body>

<div id="loading">
    <div class="spinner">
        <div class="bounce1"></div>
        <div class="bounce2"></div>
        <div class="bounce3"></div>
    </div>
	</div>

<style type="text/css">

    html,body {
        height: 100%;
        background: #9FD7F5;
    }
	.content-box {
		background: #fff !important;
	}

</style>
 <?php //echo "<pre>";print_r($_SESSION); ?>
<div class="center-vertical" id="login_box">
    <div class="center-content row">
	<section class="login_content center-margin col-xs-11 col-sm-5">
		<h3 class="text-center pad25B font-gray font-size-23"><img src="<?php echo base_url("assets/images/logo.png");?>" alt="Tax Reports"  ></h3>
        <form action="<?php echo base_url(); ?>index.php/login/Get_login"  id="login-validation"  method="post">
            <!--<h3 class="text-center pad25B font-gray text-transform-upr font-size-23 logo-content-big">Admin Panel<span class="opacity-80"></span></h3>-->
			 <p style="color:#F3D8D8;font-size:15px;"><?php if(!empty($msg)){echo $msg;}?>	 	</p>
            <div id="login-form" class="content-box">
                <div class="content-box-wrapper pad20A">
                    <div class="form-group">
					<label for="email">Username:</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-addon addon-inside bg-gray">
                                <i class="glyph-icon icon-user"></i>
                            </span>
                            <input type="text" name="email" id="email" class="form-control" id="exampleInputEmail1" placeholder="Username" required>
                        </div>
                    </div>
                    <div class="form-group">
					<label for="exampleInputPassword1">Password:</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-addon addon-inside bg-gray">
                                <i class="glyph-icon icon-unlock-alt"></i>
                            </span>
                            <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="checkbox-primary col-md-6" style="height: 20px;">
                            <label>
                                <input type="checkbox" id="loginCheckbox1" class="custom-checkbox">
                                Remember me
                            </label>
                        </div>
                        <!--<div class="text-right col-md-6">
                            <a href="#" class="switch-button" switch-target="#login-forgot" switch-parent="#login-form" title="Recover password">Forgot your password?</a>
                        </div>-->
                    </div>
				</div>
					<div class="button-pane">
                        <button type="submit" class="btn btn-block btn-primary">Login</button>
                    </div>
            </div>
			</form>
			</section>
    </div>
</div>



    <!-- WIDGETS 

<script type="text/javascript" src="../../assets/tether/js/tether.js"></script>-->
<script type="text/javascript" src="<?php echo base_url('');?>assets/bootstrap/js/bootstrap.js"></script>

<!-- Screenfull -->

<script type="text/javascript" src="<?php echo base_url('');?>assets/widgets/screenfull/screenfull.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/content-box/contentbox.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/overlay/overlay.js"></script>
<!--<script type="text/javascript" src="<?php //echo base_url();?>assets/js-init/widgets-init.js"></script>
<script type="text/javascript" src="<?php //echo base_url();?>assets/themes/admin/layout.js"></script>-->
<!--<script type="text/javascript" src="<?php //echo base_url();?>assets/widgets/theme-switcher/themeswitcher.js"></script>-->
</body>
</html>
