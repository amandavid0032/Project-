<div id="page-wrapper">
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb text-sm text-right">
                <li><a href="<?= base_url('dashboard') ?>">Home</a></li>
                <li>View Audits</li>
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
                        <table class="table table-striped table-bordered table-hover" id="audit_management">
                            <thead>
                                <tr>
                                    <th>Serial No.</th>
                                    <th>Qr And Bar Code Number</th>
                                    <th>Rfid or Id</th>
                                    <th>Asset</th>
                                    <th>Subnumber</th>
                                    <th>Fa</th>
                                    <th>Class</th>
                                    <th>Asset Class</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $c = 0;
                                foreach ($page_data as $user) : ?>
                                    <tr>
                                        <td><?= ++$c; ?></td>
                                        <td><?= $user->qr_and_bar_code_number; ?></td>
                                        <td><?= $user->rfid_or_id; ?></td>
                                        <td><?= $user->asset; ?></td>
                                        <td><?= $user->subnumber; ?></td>
                                        <td><?= $user->fa; ?></td>
                                        <td><?= $user->class; ?></td>
                                        <td><?= $user->asset_class; ?></td>
                                        <td>
                                            <a href="<?= base_url("view_asset_profile/") . base64_encode($user->tid); ?>" onclick="return confirm('Are you sure you want to View the profile of <?= ucwords($user->qr_and_bar_code_number); ?> ?')" class="btn btn-warning btn-xs" title="View Profile">View</a>

                                            <a href="<?= base_url("edit_asset_profile/") . base64_encode($user->tid); ?>" onclick="return confirm('Are you sure you want to Edit the profile of <?= ucwords($user->qr_and_bar_code_number); ?> ?')" class="btn btn-success btn-xs" title="Edit Profile">Edit</a>

                                            <a href="<?= base_url("delete_asset_profile/") . base64_encode($user->tid); ?>" onclick="return confirm('Are you sure you want to Delete the profile of <?= ucwords($user->qr_and_bar_code_number); ?> ?')" class="btn btn-danger btn-xs" title="Delete Profile">Delete</a>

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