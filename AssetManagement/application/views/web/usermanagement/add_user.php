<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb text-sm text-right">
                <li><a href="<?= base_url('dashboard') ?>">Home</a></li>
                <li>Add User</li>
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
                    <?php $this->load->view('web/includes/message'); ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <form role="form" method="post" onsubmit="return confirm('Are you sure you want to Add User ?');" action="<?= base_url('save_user'); ?>">
                                <div class="form-group">
                                    <label>Location Name</label>
                                    <select class="form-control" name="location_id" required>
                                        <option value="">Select Location </option>
                                        <?php foreach ($page_data as $data) : ?>
                                            <option value="<?= $data->pid ?>"><?= ucwords($data->location_name); ?> (<?= ucwords($data->company_name); ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>User Id </label>
                                    <input type="number" name="phone_number" class="form-control" placeholder="Enter User Id " required maxlength="20" min="1" minlength="6">
                                </div>
                                <div class="form-group">
                                    <label>First Name </label>
                                    <input type="text" name="first_name" class="form-control" placeholder="Enter First Name " required maxlength="40">
                                </div>
                                <div class="form-group">
                                    <label>Last Name </label>
                                    <input type="text" name="last_name" class="form-control" placeholder="Enter Last Name " required maxlength="40">
                                </div>
                                <div class="form-group">
                                    <label>User Type </label>
                                    <select class="form-control" name="user_type" required>
                                        <option value="">User Type </option>
                                        <option value="a">Admin</option>
                                        <option value="e">Employee</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-success" name="add">Add</button>
                                <button type="reset" class="btn btn-info">Clear</button>
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