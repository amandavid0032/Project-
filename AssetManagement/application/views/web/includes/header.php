<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Alex Grozav">

    <title><?= $title ?> | <?= $company ?></title>

    <!-- Bootstrap Core CSS -->
    <link href="<?= base_url() ?>public/css/bootstrap.css" rel="stylesheet">



    <!-- MetisMenu CSS -->
    <link href="<?= base_url() ?>public/css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="<?= base_url() ?>public/css/plugins/timeline.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="<?= base_url() ?>public/js/plugins/dataTables/theme_datatables/buttons.dataTables.min.css" rel="stylesheet">
    <link href="<?= base_url() ?>public/js/plugins/dataTables/theme_datatables/jquery.dataTables.min.css" rel="stylesheet">


    <!-- Custom CSS -->
    <link href="<?= base_url() ?>public/css/smartech.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="<?= base_url() ?>public/css/plugins/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?= base_url() ?>public/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- Animate CSS -->
    <link href="<?= base_url() ?>public/css/animate.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="sidebar-toggle hidden-xs" href="javascript:void(0);"><i class="fa fa-bars fa-2x"></i></a>
            </div>
            <ul class="nav navbar-top-links navbar-right">


                <!-- /.dropdown -->
                <li class="dropdown pull-right">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li>
                            <a href="<?= base_url('my_profile'); ?>"><i class="fa fa-user fa-fw"></i>My Profile</a>
                        </li>
                        <li><a href="<?= base_url('update_profile'); ?>"><i class="fa fa-gear fa-fw"></i>Update Profile</a>
                        </li>
                        <li><a href="<?= base_url('change_password') ?>"><i class="fa fa-gear fa-fw"></i>Change Password</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="<?= base_url('logout') ?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
            </ul>
            <!-- /.navbar-top-links -->
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="sidebar-profile text-center">
                            <span class="sidebar-profile-picture">

                                <img height="75" style="margin-top: -15%; border-radius: 0px;" src="<?= base_url('public/img/profile.jpeg') ?>" alt="Profile Picture" />
                            </span>
                            <h4 class="sidebar-profile-name">ASSET MANAGEMENT SYSTEM
                            </h4>
                        </li>
                        <li style="margin-top: -10%;">
                            <a href="<?= base_url('dashboard'); ?>" title="Dahsboard">
                                <span class="sidebar-item-icon fa-stack">
                                    <i class="fa fa-square fa-stack-2x text-primary"></i>
                                    <i class="fa fa-dashboard fa-stack-1x fa-inverse"></i>
                                </span>
                                <span class="sidebar-item-title">Dashboard</span>
                            </a>
                        </li>
                        <?php if ($user_type == 's') : ?>
                            <li>
                                <a href="#">
                                    <span class="sidebar-item-icon fa-stack ">
                                        <i class="fa fa-square fa-stack-2x text-primary"></i>
                                        <i class="fa fa-bar-chart-o fa-stack-1x fa-inverse"></i>
                                    </span>
                                    <span class="sidebar-item-arrow fa arrow"></span>
                                    <span class="sidebar-item-title" title="Company Mangement">Company Mangement</span>
                                </a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="<?= base_url('add_company'); ?>" title="Add Company">Add Company</a>
                                    </li>
                                </ul>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="<?= base_url('view_companies'); ?>" title="View Companies">View Companies</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sidebar-item-icon fa-stack ">
                                        <i class="fa fa-square fa-stack-2x text-primary"></i>
                                        <i class="fa fa-bar-chart-o fa-stack-1x fa-inverse"></i>
                                    </span>
                                    <span class="sidebar-item-arrow fa arrow"></span>
                                    <span class="sidebar-item-title" title="Project Management">Location Management</span>
                                </a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="<?= base_url('add_location'); ?>" title="Add Project">Add Location</a>
                                    </li>
                                </ul>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="<?= base_url('view_locations'); ?>" title="View Projects">View Location</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sidebar-item-icon fa-stack ">
                                        <i class="fa fa-square fa-stack-2x text-primary"></i>
                                        <i class="fa fa-bar-chart-o fa-stack-1x fa-inverse"></i>
                                    </span>
                                    <span class="sidebar-item-arrow fa arrow"></span>
                                    <span class="sidebar-item-title" title="User Management">User Management </span>
                                </a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="<?= base_url('add_user') ?>" title="Add Uesrs">Add User</a>
                                    </li>
                                </ul>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="<?= base_url('view_users') ?>" title="View Users">View Users</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sidebar-item-icon fa-stack ">
                                        <i class="fa fa-square fa-stack-2x text-primary"></i>
                                        <i class="fa fa-bar-chart-o fa-stack-1x fa-inverse"></i>
                                    </span>
                                    <span class="sidebar-item-arrow fa arrow"></span>
                                    <span class="sidebar-item-title" title="Project Management">Device Management</span>
                                </a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="<?= base_url('add_device'); ?>" title="Add Project">Add Device</a>
                                    </li>
                                </ul>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="<?= base_url('view_devices'); ?>" title="View Projects">View Device</a>
                                    </li>
                                </ul>
                            </li>

                            <li>
                                <a href="#">
                                    <span class="sidebar-item-icon fa-stack ">
                                        <i class="fa fa-square fa-stack-2x text-primary"></i>
                                        <i class="fa fa-bar-chart-o fa-stack-1x fa-inverse"></i>
                                    </span>
                                    <span class="sidebar-item-arrow fa arrow"></span>
                                    <span class="sidebar-item-title" title="Project Management">audit Management</span>
                                </a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="<?= base_url('add_audit'); ?>" title="Add Audit">Add Audit</a>
                                    </li>
                                </ul>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="<?= base_url('view_audit'); ?>" title="View Audits">View Audits</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sidebar-item-icon fa-stack ">
                                        <i class="fa fa-square fa-stack-2x text-primary"></i>
                                        <i class="fa fa-bar-chart-o fa-stack-1x fa-inverse"></i>
                                    </span>
                                    <span class="sidebar-item-arrow fa arrow"></span>
                                    <span class="sidebar-item-title" title="Asset Management">Asset Management</span>
                                </a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="<?= base_url('add_asset'); ?>" title="Add Asset">Add Asset</a>
                                    </li>
                                </ul>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a download href="<?= EXCEL_PATH ?>" title="Download Sample">Download Sample</a>
                                    </li>
                                </ul>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="<?= base_url('upload_excel'); ?>" title="Upload Excel">Upload Excel</a>
                                    </li>
                                </ul>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="<?= base_url('current_excel'); ?>" title="Current Excel">Current Excel</a>
                                    </li>
                                </ul>
                                <!-- <ul class="nav nav-second-level">
                                    <li>
                                        <a href="<?= base_url('set_limit'); ?>" title="Set Limit">Set Limit</a>
                                    </li>
                                </ul> -->
                            </li>
                            <li>
                                <a href="#">
                                    <span class="sidebar-item-icon fa-stack ">
                                        <i class="fa fa-square fa-stack-2x text-primary"></i>
                                        <i class="fa fa-bar-chart-o fa-stack-1x fa-inverse"></i>
                                    </span>
                                    <span class="sidebar-item-arrow fa arrow"></span>
                                    <span class="sidebar-item-title" title="Reports">Reports</span>
                                </a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="<?= base_url('scanned_tags'); ?>" title="Scanned Tags">Scanned Tags</a>
                                    </li>
                                </ul>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="<?= base_url('unscanned_tags'); ?>" title="Unscanned Tags">Unscanned Tags</a>
                                    </li>
                                </ul>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="<?= base_url('log_file'); ?>" title="Unidentify Tags">Unidentify Tags</a>
                                    </li>
                                </ul>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="<?= base_url('moved_tags'); ?>" title="Moved Tags">Moved Tags</a>
                                    </li>
                                </ul>
                            </li>
                        <?php elseif (isset($check_excle) && $check_excle > 0) : ?>
                            <li>
                                <a href="#">
                                    <span class="sidebar-item-icon fa-stack ">
                                        <i class="fa fa-square fa-stack-2x text-primary"></i>
                                        <i class="fa fa-bar-chart-o fa-stack-1x fa-inverse"></i>
                                    </span>
                                    <span class="sidebar-item-arrow fa arrow"></span>
                                    <span class="sidebar-item-title" title="Scan Tags">Scan Tags</span>
                                </a>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="<?= base_url('read_rfid_tags'); ?>" title="Read Tags">Read Tags</a>
                                    </li>
                                </ul>
                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="<?= base_url('scan_qr_tags'); ?>" title="Scan QR Tags">Scan QR Tags</a>
                                    </li>
                                </ul>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>