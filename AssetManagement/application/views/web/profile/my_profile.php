<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb text-sm text-right">
                <li><a href="<?= base_url('dashboard') ?>">Home</a></li>
                <li>My Profile</li>
                <!-- <li class="active">Inbox</li> -->
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
                        <div class="panel-body">
                            <p> <a href="<?= base_url('update_profile') ?>" class="btn btn-success">Update Profile</a></td>
                                <td><a href="<?= base_url('change_password'); ?>" class="btn btn-info">Change Password</a>
                            </p>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <tbody>
                                        <tr>
                                            <td>First Name</td>
                                            <td><b><?= ucfirst($page_data->first_name); ?></b></td>
                                        </tr>
                                        <tr>
                                            <td>Last Name</td>
                                            <td><b><?= ucfirst($page_data->last_name); ?></b></td>
                                        </tr>
                                        <tr>
                                            <td>Phone Number </td>
                                            <td><b><?= $page_data->phone_number; ?></b></td>
                                        </tr>
                                        <tr>
                                            <td>Last Login </td>
                                            <td><b><?= $page_data->last_login; ?></b></td>
                                        </tr>
                                        <tr>
                                            <td>Created Date</td>
                                            <td><b><?= $page_data->created_dt; ?></b></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>