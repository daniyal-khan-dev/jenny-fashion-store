<?php
session_start();
include('pages/includes/header.php');
include_once('functions/userfunction.php');
?>

<!-- Start collection section -->
<section class="product__section section--padding">
    <div class="container">
        <?php
        $categories = getAllActive('categories');

        if ($categories !== false && mysqli_num_rows($categories) > 0) {
        ?>
            <div class="section__heading text-center mb-40">
                <h2 class="section__heading--maintitle">COLLECTION</h2>
            </div>
            
            <div class="product__section--inner">
                <div class="row justify-content-center">
                    <?php while ($item = mysqli_fetch_assoc($categories)): ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-6 mb-3">
                        <article class="product__card">
                            <div class="product__card--thumbnail">
                                <a class="product__card--thumbnail__link display-block" href="<?= $routes['user']['c-product'] ?>?category=<?= $item['name']; ?>&source=collection">
                                    <img class="product__card--thumbnail__img" src="admin/assets/img/category/<?= $item['image']; ?>" alt="product-img">
                                </a>
                            </div>
                            <div class="product__card--content text-center">
                                <h3 class="product__card--title"><a href="<?= $routes['user']['c-product'] ?>?category=<?= $item['name']; ?>&source=collection"><?= $item['name']; ?></a></h3>
                            </div>
                        </article>
                    </div>
                    <?php endwhile; ?>
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

<?php include('pages/includes/footer.php'); ?>