<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb text-sm text-right">
                <li><a href="<?= base_url('view_projects') ?>">Home</a></li>
                <li><a href="<?= base_url('view_projects') ?>">View Projects</a></li>
                <li>View Project</li>
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
                            <form role="form" method="post" onsubmit="return confirm('Are you sure you want to Update Location ?');" action="<?= base_url('save_update_location'); ?>">
                                <input type="hidden" name="project_id" value="<?= $page_data_database->pid; ?>">
                                <div class="form-group">
                                    <label>Company Name</label>
                                    <select class="form-control" name="company_id" required>
                                        <option value="">Select Company </option>
                                        <?php foreach ($page_data as $data) : ?>
                                            <option value="<?= $data->cid ?>" <?= $data->cid == $page_data_database->company_id ? 'SELECTED' : '' ?>> <?= ucwords($data->company_name); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Location Name</label>
                                    <input type="text" name="location_name" class="form-control" placeholder="Enter Location Name" required maxlength="150" value="<?= ucwords($page_data_database->location_name); ?>">
                                </div>
                                <button type="submit" class="btn btn-success" name="add">Update</button>
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