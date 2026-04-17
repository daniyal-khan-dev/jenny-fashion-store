</div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/js/login.js"></script>

<script>
    window.routes = {
        signup: "<?= $routes['auth']['signup-api']; ?>",
        signin: "<?= $routes['auth']['signin-api']; ?>",
    };

        document.addEventListener("DOMContentLoaded", function() {
        <?php if (isset($_SESSION['message1'])) : ?>
            showAlert("success", <?= json_encode($_SESSION['message1']); ?>);
            <?php unset($_SESSION['message1']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['message2'])) : ?>
            showAlert("error", <?= json_encode($_SESSION['message2']); ?>);
            <?php unset($_SESSION['message2']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['message1a'])) : ?>
            showAlert("error", <?= json_encode($_SESSION['message1a']); ?>);
            <?php unset($_SESSION['message1a']); ?>
            setTimeout(function() {
                window.location.assign("index.php");
            }, 2000);
        <?php endif; ?>
    });
</script>

</body>
</html>
