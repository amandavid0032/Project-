<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="VTSLORAWAN">
    <meta name="author" content="wavelinx">

    <title> <?= $title; ?> | <?= $company; ?></title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= base_url() ?>public/favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?= base_url() ?>public/favicon.ico" type="image/x-icon">

    <!-- Bootstrap Core CSS -->
    <link href="<?= base_url('public/css/bootstrap.css') ?>" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="<?= base_url('public/css/plugins/metisMenu/metisMenu.min.css') ?>" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?= base_url('public/css/smartech.css') ?>" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?= base_url('public/font-awesome/css/font-awesome.min.css') ?>" rel="stylesheet" type="text/css">

    <!-- Animate CSS -->
    <link href="<?= base_url('public/css/animate.css') ?>" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title text-center">SIGN IN</h3>
                    </div>
                    <div class="panel-body">
                        <?php $this->load->view('web/includes/message'); ?>
                        <form role="form" method="post" action="<?= base_url('check') ?>" autocomplete="off">
                            <fieldset>
                                <div class="form-group">
                                    <label for="phone_number">User Id</label>
                                    <input type="number" class="form-control phone_number" placeholder="Enter User Id" name="phone_number" required="" id="phone_number" min="1" maxlength="20" minlength="6" autofocus>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" placeholder="Enter Password" name="password" required="">
                                </div>
                                <!-- <div class="checkbox">
                                        <label>
                                            <input name="remember" type="checkbox" value="Remember Me">Remember Me
                                        </label>
                                    </div> -->
                                <button type="submit" class="btn btn-success" name="login">Login</button> <button type="reset" class="btn btn-info">Clear</button>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery Version 1.11.0 -->
    <script src="<?= base_url('public/js/jquery-1.11.0.js') ?>"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?= base_url('public/js/bootstrap.min.js') ?>"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?= base_url('public/js/plugins/metisMenu/metisMenu.min.js') ?>"></script>

    <!-- Custom Theme JavaScript -->
    <script src="<?= base_url('public/js/smartech.js') ?>"></script>

</body>

</html>