<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb text-sm text-right">
                <li><a href="<?= base_url('dashboard') ?>">Home</a></li>
                <li><a href="<?= base_url('view_audit') ?>">View Audit</a></li>
                <li>View Audit</li>
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
                        <form role="form" method="post" onsubmit="return confirm('Are you sure you want to Update Location ?');" action="<?= base_url('save_update_audit'); ?>">
                                <input type="hidden" name="project_id" value="<?= $page_data_database->aid; ?> ">
                                <div class="form-group">
                                    <label>Location Name</label>
                                    <select class="form-control" name="location_id" required>
                                        <option value="">Select Location </option>
                                        <?php foreach ($page_data as $data) :?>
                                            <option value="<?= $data->pid ?>"<?= $data->pid == $page_data_database->location_id ? 'SELECTED' : '' ?>> <?= ucwords($data->location_name); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                 <div class="form-group">
                                    <label>Audit Name</label>
                                    <input type="text" name="audit_name" class="form-control" placeholder="Enter audit Name" required maxlength="150" value="<?= ucwords($page_data_database->audit_name); ?>">
                                </div>
                                <div class="form-group">
                                    <label>End-Date</label>
                                    <input type="text" name="end_date" class="form-control"  required maxlength="150" value="<?= ucwords($page_data_database->end_date); ?>">
                                </div>
                                <button type="submit" class="btn btn-success" name="add">Update</button>
                                <tr>
                                <td colspan="2"><button onclick="goBack()" class="btn btn-success btn-md" title="Go Back to Previous Page">Go Back</button></td>
                            </tr>
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