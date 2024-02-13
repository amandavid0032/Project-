<div id="page-wrapper">
    <!-- /.row -->
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
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <tr>
                                <td>Audit Name</td>
                                <td><b><?= ucfirst($page_data->audit_name); ?></b></td>
                            </tr>
                            <tr>
                                <td>Location Name</td>
                                <td><b><?= ucfirst($page_data->location_name); ?></b></td>
                            </tr>
                            <tr>
                                <td>End Date</td>
                                <td><b><?= getFormatedDate($page_data->end_date); ?></b></td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td><b><?= $page_data->status == 1 ? 'Active' : 'Inactive'; ?></b></td>
                            </tr>
                            <tr>
                                <td>Created By </td>
                                <td><b><?= $page_data->created_by; ?></b></td>
                            </tr>
                            <tr>
                                <td>Created Date </td>
                                <td><b><?= getFormatedDate($page_data->created_dt); ?></b></td>
                            </tr>
                            <tr>
                                <td>Modified By </td>
                                <td><b><?= !empty($page_data->modified_by) ? $page_data->modified_by : 'N/A'; ?></b></td>
                            </tr>
                            <tr>
                                <td>Modified Date </td>
                                <td><b><?= !empty($page_data->modified_dt) ?  getFormatedDate($page_data->modified_dt) : 'N/A' ?></b></td>
                            </tr>
                            <tr>
                                <td colspan="2"><button onclick="goBack()" class="btn btn-success btn-md" title="Go Back to Previous Page">Go Back</button></td>
                            </tr>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
</div>
<!-- /#page-wrapper -->