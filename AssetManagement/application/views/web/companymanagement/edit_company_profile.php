<div id="page-wrapper">
    <div class="row">
    <div class="col-lg-12">
            <ol class="breadcrumb text-sm text-right">
                <li><a href="<?= base_url('dashboard') ?>">Home</a></li>
                <li><a href="<?= base_url('view_companies') ?>">View Companies</a></li>
                <li>Edit Company</li>
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
                    <div class="row">
                        <div class="col-lg-12">
                            <form role="form" method="post" onsubmit="return confirm('Are you sure you want to Update Company ?');" action="<?= base_url('save_update_company'); ?>">
                                <div class="form-group">
                                    <input type="hidden" name="company_id" value="<?= $page_data->cid; ?>">
                                    <label>Company Name </label>
                                    <input type="text" name="company_name" class="form-control" value="<?= ucwords($page_data->company_name); ?>" placeholder="Enter Company Name" required maxlength="150">
                                </div>
                                
                                <button type="submit" class="btn btn-success" title="Update" name="update">Update</button>
                                <a href="<?= base_url('view_companies'); ?>" class="btn btn-info" title="Cancle">Cancle</a>
                            </form>
                        </div>

                    </div>
                    <!-- /.row (nested) -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->