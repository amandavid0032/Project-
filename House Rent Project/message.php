<?php
if (isset($_GET['message'])) : ?>
    <div class="alert alert-<?= $_GET['color'] ?> my-3" role="alert" id="feedback">
        <?= urldecode($_GET['message']); ?>
    </div>
<?php endif; ?>

<script>
    setTimeout(() => {
        document.querySelector('#feedback').style.display = 'none';
    }, 10000);
</script>