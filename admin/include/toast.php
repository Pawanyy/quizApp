<link rel="stylesheet" href="<?php echo BASE_URL; ?>/dist/custom/plugin/froiden-helper/helper.min.css">
<script src="<?php echo BASE_URL; ?>/dist/custom/plugin/froiden-helper/helper.min.js"></script>
<?php if(isset($_SESSION['sucmsg'])) { ?>
<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert">
    <div class="toast toast-success" style="">
        <div class="toast-message">
            <?php echo $_SESSION['sucmsg']; ?>
        </div>
    </div>
</div>
<?php unset($_SESSION['sucmsg']); } ?>

<?php if(isset($_SESSION['errmsg'])) { ?>
<div id="toast-container" class="toast-top-right" aria-live="polite" role="alert">
    <div class="toast toast-error" style="">
        <div class="toast-message">
            <?php echo $_SESSION['errmsg']; ?>
        </div>
    </div>
</div>
<?php unset($_SESSION['errmsg']); } ?>
<script>
$(document).ready(function() {
    setTimeout(function() {
        $('.toast').fadeOut('slow');
    }, 1500);
});
</script>