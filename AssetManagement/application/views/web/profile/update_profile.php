<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb text-sm text-right">
                <li><a href="<?= base_url('dashboard') ?>">Home</a></li>
                <li><a href="<?= base_url('my_profile') ?>">My Profile</a></li>
                <li>Update Profile</li>
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
                            <form role="form" method="post" onsubmit="return confirm('Are you sure you want to Update your profile ?');" action="<?= base_url('saveprofile') ?>">
                                <div class="form-group">
                                    <label>First Name </label>
                                    <input type="text" name="first_name" class="form-control" placeholder="Please Enter First Name " required maxlength="40" value="<?= $page_data->first_name ?>">
                                </div>
                                <div class="form-group">
                                    <label>Last Name </label>
                                    <input type="text" name="last_name" class="form-control" placeholder="Please Enter Last Name" required maxlength="40" value="<?= $page_data->last_name ?>">
                                </div>
                                <button type="submit" class="btn btn-success" name="update">Update</button>
                                <a href="<?= base_url('my_profile') ?>" onclick="return confirm('Are you sure you want to cancel ?')" class="btn btn-info">Cancel</a>
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