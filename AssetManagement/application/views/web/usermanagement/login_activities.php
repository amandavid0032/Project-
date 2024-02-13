<div id="page-wrapper">
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
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
                                    <th>Serial No.</th>
                                    <th>Login Date Time</th>
                                    <th>Ip Address</th>
                                    <th>Login Agent</th>
                                    <th>Login Platform</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $c = 0;
                                foreach ($page_data as $user):?>
                                        <tr>
                                            <td><?= ++$c; ?></td>
                                            <td><?= $user->last_login ?></td>
                                            <td><?= $user->ip_address ?></td>
                                            <td><?= $user->login_agent ?></td>
                                            <td><?= $user->platform ?></td>
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