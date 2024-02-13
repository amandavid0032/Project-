 <?php
        if ($feedback = $this->session->flashdata('message')) :
                $color_message = $this->session->flashdata('color') ?>
         <div class="alert alert-<?= $color_message; ?> msg" role="alert">
                 <script type="text/javascript"></script>
                 <?= $feedback;
                        if ($this->session->flashdata('stop_message') != '1') :
                        ?>
                         <script type="text/javascript">
                                 setTimeout(function() {
                                         $('.msg').fadeOut();
                                 }, 5000);
                         </script>
                 <?php endif; ?>
         </div>
 <?php endif; ?>
 <style>
#java-script-error {
        display: none;
}
 </style>
 <div id="java-script-error">
        <div class="alert" role="alert" id="java-script-error-color">
                <span id="java-script-error-message"></span>
        </div>
 </div>