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
                <div class="panel-body">
                    <div class="row">
                        <form action="<?= base_url('unscanned_tags') ?>" method="post" class="col-md-4">
                            <div class="form-group">
                                <label for="select-job">Select Audit</label>
                                <select id="select-job" class="form-control" name="audit_id">
                                    <option value="">Select Audit</option>
                                    <?php foreach ($audit_data as $value) : ?>
                                        <option value="<?= $value->aid ?>"><?= ucwords($value->audit_name) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-success btn-sm" type="submit">Find</button>
                            </div>
                        </form>
                    </div>

                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="row">
                            <div class="table-responsive" style="overflow:auto">
                                <table class="table table-striped table-bordered table-hover" id="unscanned_tags">
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
                                                <tD><?= $user->location ?></tD>
                                                <td><?= $user->asset_name ?></td>
                                                <td><?= $user->asset_description ?></td>
                                                <td><?= $user->capitalized_on ?></td>
                                                <td><?= $user->Curr_bk_val ?></td>
                                                <td><?= $user->store_name ?></td>
                                                <td><?= $user->status == 1 ? 'Active' : 'Inactive'; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>

                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-lg-12 -->
        </div>



    </div>
    <!-- /#page-wrapper -->