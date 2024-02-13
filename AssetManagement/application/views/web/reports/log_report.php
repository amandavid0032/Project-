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
                        <a href="<?= base_url("remove_logs"); ?>" onclick="return confirm('Are you sure you want to delete all log data  ?')" class="btn btn-danger btn-sm pull-right" title="clear all logs">Clear all log</a>
                    </h4>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <?php $this->load->view('web/includes/message'); ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="log_file">
                            <thead>
                                <tr>
                                    <th>Serial No.</th>
                                    <!-- <th>Location id</th>
                                    <th>User Id</th> -->
                                    <th>RFID</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $c = 0;
                                foreach ($page_data as $user) : ?>
                                    <tr>
                                        <td><?= ++$c; ?></td>
                                        <!-- <td><?= $user->location_id ?></td>
                                        <td><?= $user->user_id ?></td> -->
                                        <td><?= $user->rfid_or_id  ?></td>
                                        <td><?= $user->status ? 'Exist'  : 'Not Exist' ?></td>
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