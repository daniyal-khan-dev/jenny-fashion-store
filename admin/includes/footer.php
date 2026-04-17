</main>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        <?php if (isset($_SESSION['message1'])) : ?>
            showAlert("success", <?= json_encode($_SESSION['message1']); ?>);
            <?php unset($_SESSION['message1']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['message2'])) : ?>
            showAlert("error", <?= json_encode($_SESSION['message2']); ?>);
            <?php unset($_SESSION['message2']); ?>
        <?php endif; ?>
    });
</script>

<!-- jQUERY JS - CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- SWEET ALERT JS - CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- BOOTSTRAP JS - CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

<!-- Perfect Scrollbar JS -->
<script src="https://cdn.jsdelivr.net/npm/perfect-scrollbar@1.5.5/dist/perfect-scrollbar.min.js"></script>

<!-- CUSTOM JS -->
<script src="assets/js/script.js"></script>

</body>

</html>