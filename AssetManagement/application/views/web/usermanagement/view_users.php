<div id="page-wrapper">
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb text-sm text-right">
                <li><a href="<?= base_url('dashboard') ?>">Home</a></li>
                <li>View Users</li>
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
                        <table class="table table-striped table-bordered table-hover" id="user_managment">
                            <thead>
                                <tr>
                                    <th>Sno</th>
                                    <th>Location</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>User Id</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Last Login</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $c = 0;
                                foreach ($page_data as $user) {
                                    if ($this->session->userdata('user_id') != $user->user_id && $user->user_id != 1) { ?>
                                        <tr>
                                            <td><?= ++$c; ?></td>
                                            <td><?= ucwords($user->location_name); ?></td>
                                            <td><?= ucfirst($user->first_name); ?></td>
                                            <td><?= ucfirst($user->last_name) ?></td>
                                            <td><?= $user->phone_number ?></td>
                                            <td><?= $user->user_type == 'a' ? 'Admin' : 'Employee'; ?></td>
                                            <td><?= $user->status == 1 ? 'Active' : 'Inactive'; ?></td>
                                            <td><?= $user->last_login ? $user->last_login : 'N/A'; ?></td>
                                            <td>
                                                <a href="<?= base_url("view_user_profile/") . base64_encode($user->user_id); ?>" onclick="return confirm('Are you sure you want to View the profile of <?= $user->phone_number; ?> ?')" class="btn btn-warning btn-xs" title="View Profile">View</a>

                                                <a href="<?= base_url("edit_user_profile/") . base64_encode($user->user_id); ?>" onclick="return confirm('Are you sure you want to Edit the profile of <?= $user->phone_number; ?> ?')" class="btn btn-success btn-xs" title="Edit Profile">Edit</a>
                                                <a href="<?= base_url("reset_password/") . base64_encode($user->user_id); ?>" onclick="return confirm('Are you sure you want to Reset the password of <?= $user->phone_number; ?> ?')" class="btn btn-info btn-xs" title="Reset Password">Reset Password</a>
                                                <a href="<?= base_url("change_status/") . base64_encode($user->user_id) . '/' . base64_encode($user->status); ?>" onclick="return confirm('Are you sure you want to change the Status of <?= $user->phone_number; ?> ?')" class="btn btn-<?= $user->status == 1 ? 'danger' : 'warning'; ?> btn-xs" title="Change Status"><?= $user->status == 0 ? 'Active' : 'Inactive'; ?></a>
                                                <a href="<?= base_url("login_activities/") . base64_encode($user->user_id) ?>" onclick="return confirm('Are you sure you want to View Login Activities <?= $user->phone_number; ?> ?')" class="btn btn-default btn-xs" title="Login Activities">Activities</a>
                                            </td>
                                        </tr>
                                <?php }
                                } ?>
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