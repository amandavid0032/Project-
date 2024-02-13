<div id="page-wrapper">
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb text-sm text-right">
                <li><a href="<?= base_url('dashboard') ?>">Home</a></li>
                <li>View Companies</li>
            </ol>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="margin-none">
                        <i class="fa fa-th fa-fw"></i> <?= strtoupper($title); ?>
                    </h4>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <?php $this->load->view('web/includes/message'); ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="company_management">
                            <thead>
                                <tr>
                                    <th>Serial No.</th>
                                    <th>Company Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $c = 0;
                                foreach ($page_data as $user) : ?>
                                    <tr>
                                        <td><?= ++$c; ?></td>
                                        <td><?= ucwords($user->company_name); ?></td>
                                        <td><?= $user->status == 1 ? 'Active' : 'Inactive'; ?></td>
                                        <td>
                                            <a href="<?= base_url("view_company_profile/") . base64_encode($user->cid); ?>" onclick="return confirm('Are you sure you want to View the profile of <?= ucwords($user->company_name); ?> ?')" class="btn btn-warning btn-xs" title="View Profile">View</a>

                                            <a href="<?= base_url("edit_company_profile/") . base64_encode($user->cid); ?>" onclick="return confirm('Are you sure you want to Edit the profile of <?= ucwords($user->company_name); ?> ?')" class="btn btn-success btn-xs" title="Edit Profile">Edit</a>

                                            <a href="<?= base_url("change_status_company/") . base64_encode($user->cid) . '/' . base64_encode($user->status); ?>" onclick="return confirm('Are you sure you want to change the Status of <?= ucwords($user->company_name); ?> ?')" class="btn btn-<?= $user->status == 1 ? 'danger' : 'warning'; ?> btn-xs" title="Change Status"><?= $user->status == 0 ? 'Active' : 'Inactive'; ?></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
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