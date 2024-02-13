<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb text-sm text-right">
                <li><a href="<?= base_url('view_projects') ?>">Home</a></li>
                <li>Upload Excel</li>
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
                        <div class="col-lg-12">
                            <form role="form" method="post" onsubmit="return confirm('Are you sure you want to Upload Excel ?');" action="<?= base_url('save_upload_excel'); ?>" enctype='multipart/form-data'>
                                <div class="form-group">
                                    <label for="upload_excel">Upload Excel</label>
                                    <input type="file" name="upload_excel" id="upload_excel" class="form-control" accept="application/vnd.ms-excel" />
                                </div>
                                <button type="submit" class="btn btn-success" name="add">Upload</button>
                                <button type="reset" class="btn btn-info">Clear</button>
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
<?php
$this->load->view('web/includes/footer');
?>
<script>
$('#company_id').change(function(e){
    let company_id = e.target.value;
    let elmentId = '#project_id';
    $.ajax({
        url:'<?= base_url('get_projects'); ?>',
        data : {company_id : company_id},
        method : 'POST',
        success : function(response){
            $(elmentId).html('');
            $(elmentId).append(`<option value=''> Select Project</option>`)
            response.map(element => {
                $(elmentId).append(`<option value='${element.pid}' >${element.project_name}</option>`)
            })
        }
    })  
})
</script>