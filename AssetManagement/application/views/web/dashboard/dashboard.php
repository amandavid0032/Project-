<div id="page-wrapper">
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb text-sm text-right">
                <li><a href="<?= base_url('dashboard') ?>">Home</a></li>
                <li>Dashboard</li>
                <!-- <li class="active">Inbox</li> -->
            </ol>
            <div class="panel panel-primary">
                <div class="panel-body">
                    <h4 class="text-uppercase margin-none"><i class="fa fa-dashboard"></i> Dashboard</h4>
                </div>
            </div>
            <?php $this->load->view('web/includes/message'); ?>
            <div class="row">
                <div class="ts-main-content">
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="panel panel-default  box-white panel-status panel-danger">
                            <div class="panel-body padding-none">
                                <!-- <a href="#"> -->
                                <div class="row">
                                    Total User
                                    <div class="col-xs-3">
                                        <i class="fa fa-user fa-5x panel-status-icon"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge">
                                            <span id="vehicle_count"><?php echo $user_count ?></span>
                                        </div>
                                    </div>
                                </div>
                                <!-- </a> -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="panel panel-default  box-white panel-status panel-info">
                        <div class="panel-body padding-none">
                            <!-- <a href="#"> -->
                            <div class="row">
                                Total Location
                                <div class="col-xs-3">
                                    <i class="fa fa-map-marker fa-5x panel-status-icon" aria-hidden="true"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge">
                                        <span id="vehicle_count"><?php echo $location_count ?></span>
                                    </div>
                                </div>
                            </div>
                            <!-- </a> -->
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="col-md-12">
                            <div class="panel panel-default  box-white panel-status panel-success">
                                <div class="panel-body padding-none">
                                    <!-- <a href="#"> -->
                                    <div class="row">
                                        Total Asset
                                        <div class="col-xs-3">
                                            <i class="fa fa-rss fa-5x panel-status-icon" aria-hidden="true"></i>
                                        </div>
                                        <div class="col-xs-9 text-right">
                                            <div class="huge">
                                                <span id="vehicle_count"><?php echo $asset_count ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- </a> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /#page-wrapper -->