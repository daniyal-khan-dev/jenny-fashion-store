<?php
$trendingproducts = getAllTrending();
$rating = rand(4, 5);
$reviews = rand(50, 250);
?>

<!-- Start product section -->
<section class="product__section section--padding">
    <div class="container">
        <div class="section__heading text-center mb-40">
            <h2 class="section__heading--maintitle">Trending Products</h2>
        </div>
        <div class="product__section--inner">
            <div class="row mb--n30">
                <?php
                if (mysqli_num_rows($trendingproducts) > 0) {
                    while ($item = mysqli_fetch_assoc($trendingproducts)) {
                ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-6 custom-col mb-30">
                        <article class="product__card">
                            <div class="product__card--thumbnail">
                                <a class="product__card--thumbnail__link d-block" href="<?= $routes['user']['product'] ?>?product=<?= urlencode($item['name']); ?>&sur=shop">
                                    <img class="product__card--thumbnail__img" src="admin/assets/img/products/<?= $item['image']; ?>" alt="product-img">
                                </a>
                            </div>

                            <div class="product__card--content text-center">
                                <ul class="rating product__card--rating d-flex justify-content-center">
                                    <?php for ($i = 1; $i <= 5; $i++) { ?>
                                        <li class="rating__list">
                                            <span class="rating__icon">
                                                <svg width="14" height="13" viewBox="0 0 14 13"
                                                    fill="<?= ($i <= $rating) ? 'currentColor' : '#ccc'; ?>"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M6.08398 0.921875L4.56055 4.03906L1.11523 4.53125C0.505859 4.625 0.271484 5.375 0.716797 5.82031L3.17773 8.23438L2.5918 11.6328C2.49805 12.2422 3.1543 12.7109 3.69336 12.4297L6.76367 10.8125L9.81055 12.4297C10.3496 12.7109 11.0059 12.2422 10.9121 11.6328L10.3262 8.23438L12.7871 5.82031C13.2324 5.375 12.998 4.625 12.3887 4.53125L8.9668 4.03906L7.41992 0.921875C7.16211 0.382812 6.36523 0.359375 6.08398 0.921875Z" />
                                                </svg>
                                            </span>
                                        </li>
                                    <?php } ?>
                                    <li>
                                        <span class="rating__review--text">(<?= $reviews ?> Reviews)</span>
                                    </li>
                                </ul>

                                <h3 class="product__card--title"><a href="<?= $routes['user']['product'] ?>?product=<?= $item['name']; ?>&sur=shop"><?= $item['name']; ?></a></h3>
                                <div class="product__card--price">
                                    <span class="current__price">$<?= $item['d_price']; ?></span>
                                    <span class="old__price">$<?= $item['price']; ?></span>
                                </div>

                                <div class="product__card--actions">
                                    <button class="product__card--action-btn add-to-cart-btn" onclick="addToCartAjax(<?= $item['id']; ?>, 1)" type="button">
                                        <svg width="13" height="13" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12.25 7.66667V4.33333C12.25 3.44928 11.8549 2.60143 11.1517 1.97631C10.4484 1.35119 9.49456 1 8.5 1C7.50544 1 6.55161 1.35119 5.84835 1.97631C5.14509 2.60143 4.75 3.44928 4.75 4.33333V7.66667M1.9375 6H15.0625L16 16H1L1.9375 6Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        Add to Cart
                                    </button>
                                    <a class="product__card--action-btn" href="<?= $routes['user']['product'] ?>?product=<?= urlencode($item['name']); ?>&sur=shop">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                        View
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>
                <?php
                    }
                } else {
                    echo '<div class="text-center py-4">
                            <p style="font-size:16px; color:#666;">
                                No products are available at the moment. Please check back later or contact support for assistance.
                            </p>
                        </div>';
                }
                ?>
            </div>
        </div>
    </div>
</section>
<!-- End product section -->