<div id="page-wrapper">
    <div class="row">
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
                            <form action="javascript:void(0)">
                            <div class="form-group">
                                <label>Total Count</label>
                                <div class="form-control" id="totalCount"><?= $totalCount; ?></div>
                            </div>    
                            <div class="form-group">
                                <label>Enter Tag</label>
                                    <input type="text" name="tag" class="form-control" placeholder="Enter Tag" required maxlength="40" id="read-tag-input" autofocus="true">
                                </div>
                                <button type="reset" class="btn btn-info" id="clear">Clear</button>
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
<?php $this->load->view('web/includes/footer'); ?>
<script>
    let tagRead = document.querySelector('#read-tag-input');
    tagRead.addEventListener('change',function(e){
        e.preventDefault();
        if(e.target.value.length > 8) {
            $.ajax({
                url : '<?= base_url('/save_qr_reader_tag')?>',
                method : 'POST',
                data : {tag : e.target.value},
                success : function(response){
                    if(response.status == false ){
                        alert(response.message);
                    } else {
                        $('#java-script-error').css({'display':'block'});
                        $('#java-script-error-color').addClass('alert-'+response.color);
                        $('#java-script-error-message').text(response.message);
                        $('#totalCount').text(response.totalCount);

                        setTimeout(function(){
                            $('#java-script-error').css({'display':'none'});
                            $('#java-script-error-color').removeClass('alert-'+response.color);
                            $('#java-script-error-message').text(''); 
                        },3000);
                    }
                    e.target.value = '';
                },
                error(err){
                    console.log(err);
                }
            })
        }
    });
</script>
