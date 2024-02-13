<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb text-sm text-right">
                <li><a href="<?= base_url('dashboard') ?>">Home</a></li>
                <li><a href="<?= base_url('my_profile') ?>">My Profile</a></li>
                <li>Change Password</li>
            </ol>
        </div>
        <div class="col-lg-2"></div>
        <div class="col-lg-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="margin-none">
                        <i class="fa fa-th fa-fw"></i> <?= strtoupper($title); ?>
                    </h4>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <?php $this->load->view('web/includes/message'); ?>
                        <div class="col-lg-12">
                            <form role="form" method="post" onsubmit="return confirm('Are you sure you want to change password ?');" action="<?= base_url('save_password') ?>">
                                <div class="form-group">
                                    <label>Old Password </label>
                                    <input type="password" name="old_password" id="old_password" class="form-control" placeholder="Enter Old Password " required maxlength="40">
                                </div>
                                <div class="form-group">
                                    <label>New Password </label>
                                    <input type="password" name="new_password" id="new_password" class="form-control" placeholder="Enter New Password " required maxlength="40">
                                </div>
                                <div class="form-group">
                                    <label>Confrim Password </label>
                                    <input type="password" name="con_password" id="con_password" class="form-control" placeholder="Enter Confirm Password " required maxlength="40">
                                </div>
                                <button type="submit" class="btn btn-success" name="change">Change</button>
                                <button onclick="goBack()" class="btn btn-info btn-md" title="Go Back to Previous Page">Go Back</button>
                            </form>
                        </div>

                    </div>
                    <!-- /.row (nested) -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->