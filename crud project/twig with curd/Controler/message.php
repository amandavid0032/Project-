<?php 
if (isset($_GET['message'])) :
?>
    <div class="alert alert-<?= $_GET['color'] ?> my-3" role="alert" id="feedback">
        <?= urldecode($_GET['message']); ?>
    </div>
<?php endif; ?>
<script>
    setTimeout(() => {
        const feedback = document.querySelector('#feedback');
        if (feedback) {
            feedback.remove();
        }
    }, 2000);
</script>
