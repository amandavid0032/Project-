<div id="page-wrapper">

    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb text-sm text-right">
                <li><a href="<?= base_url('dashboard') ?>">Home</a></li>
                <li><a href="<?= base_url('view_companies') ?>">View Companies</a></li>
                <li>View Company</li>
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
                                <td>Device Name</td>
                                <td><b><?= $page_data->device_name; ?></b></td>
                            </tr>
                            <tr>
                                <td>Device Id</td>
                                <td><b><?= $page_data->device_id; ?></b></td>
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