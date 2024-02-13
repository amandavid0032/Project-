<div id="page-wrapper">
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb text-sm text-right">
                <li><a href="<?= base_url('view_projects') ?>">Home</a></li>
                <li>Current Excel</li>
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
                        <table class="table table-striped table-bordered table-hover">
                            <tr>
                                <th>Field</th>
                                <th>Value</th>
                                <th>Action</th>
                            </tr>
                            <tr>
                                <td>Total Count</td>
                                <td><?= $total_count[0]->total_count ?></td>
                                <td>
                                    <?php if ($total_count[0]->total_count) : ?>
                                        <a href="<?= base_url("remove_all"); ?>" onclick="return confirm('Are you sure you want to delete all log data ?')" class="btn btn-danger btn-xs" title="View Profile">Remove All</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Origianl Count</td>
                                <td><?= $original_count ?></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Duplicate Count</td>
                                <td><?= $duplicate_count ?></td>
                                <td>
                                    <?php if ($duplicate_count) : ?>
                                        <a href="<?= base_url("remove_duplicate"); ?>" onclick="return confirm('Are you sure you want to delete duplicate data ?')" class="btn btn-danger btn-xs" title="View Profile">Remove Duplicate</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                        <br />
                        <br />

                        <div class="table-responsive" style="overflow:auto;">
                            <table class="table table-striped table-bordered table-hover" id="current_excel">
                                <thead>
                                    <tr>
                                        <th>Serial No.</th>
                                        <th>QR and Bar Code Number</th>
                                        <th>RFID</th>
                                        <th>Asset</th>
                                        <th>Type</th>
                                        <th>Type of Asset</th>
                                        <th>Asset Class</th>
                                        <th>Location</th>
                                        <th>Asset Name</th>
                                        <th>Asset Description</th>
                                        <th>Capitilized On</th>
                                        <th>Current Book Value</th>
                                        <th>Vedor Name</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $c = 0;
                                    foreach ($page_data as $user) : ?>
                                        <tr>
                                            <td><?= ++$c; ?></td>
                                            <td><?= $user->qr_and_bar_code_number ?></td>
                                            <td><?= $user->rfid_or_id  ?></td>
                                            <td><?= $user->asset  ?></td>
                                            <td><?= $user->data_exist == 0 ? 'Original' : 'Duplicate'; ?></td>
                                            <td><?= $user->type_of_Assets ?></td>
                                            <td><?= $user->asset_class ?></td>
                                            <tD><?= $user->location_name ?></tD>
                                            <td><?= $user->asset_name ?></td>
                                            <td><?= $user->asset_description ?></td>
                                            <td><?= $user->capitalized_on ?></td>
                                            <td><?= $user->Curr_bk_val ?></td>
                                            <td><?= $user->store_name ?></td>
                                            <td><?= $user->status == 1 ? 'Active' : 'Inactive'; ?></td>
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