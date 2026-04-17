<?php
include_once('functions/userfunction.php');
?>

<!-- Start collection section -->
<section class="shop__collection--section section--padding">
    <div class="container">
        <div class="section__heading text-center mb-40">
            <h2 class="section__heading--maintitle">Shop By Category</h2>

        </div>
        <?php
        $categories = getAllActive('categories');

        if ($categories !== false && mysqli_num_rows($categories) > 0) {
        ?>
            <div class="shop__collection--column5 swiper">
                <div class="swiper-wrapper">
                    <?php while ($item = mysqli_fetch_assoc($categories)) { ?>

                        <div class="swiper-slide">
                            <div class="shop__collection--card text-center">
                                <a class="shop__collection--link" href="<?= $routes['user']['c-product'] ?>?category=<?= urlencode($item['name']); ?>&source=shopbycategory">
                                    <img
                                        class="shop__collection--img"
                                        src="admin/assets/img/category/<?= htmlspecialchars($item['image']); ?>"
                                        alt="<?= htmlspecialchars($item['name']); ?>"
                                        style="border-radius: 50%;">

                                    <h3 class="shop__collection--title mb-0">
                                        <?= htmlspecialchars($item['name']); ?>
                                    </h3>
                                </a>
                            </div>
                        </div>

                    <?php } ?>
                </div>

                <div class="swiper__nav--btn swiper-button-next">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </div>

                <div class="swiper__nav--btn swiper-button-prev">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </div>
            </div>

        <?php
        } else {
            echo '<div class="text-center py-4">
            <p style="font-size:16px; color:#666;">
                No categories are available at the moment. Please check back later or contact support for assistance.
            </p>
          </div>';
        }
        ?>

    </div>
</section>
<!-- End collection section -->