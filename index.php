<?php
session_start();
include('pages/includes/header.php');
include_once('functions/userfunction.php');
?>

<!-- ══════ HERO SLIDER ══════ -->
<section class="hero__slider--section">
    <div class="hero__slider--activation swiper">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <div class="home__two--slider__items slide-bg-1">
                    <div class="container">
                        <div class="slider__items--inner">
                            <div class="row align-items-center">
                                <div class="col-lg-6 col-md-6">
                                    <div class="slider__content">
                                        <span class="slider__tag">New Season Collection</span>
                                        <h2 class="slider__maintitle h1">Beauty is Whatever<br>Brings You Joy</h2>
                                        <p class="slider__desc">50% OFF on the most popular cosmetic brands. Order all classy products today!</p>
                                        <div class="slider__btn--group">
                                            <a class="primary__btn slider__btn" href="<?= $routes['user']['collection'] ?>">SHOP NOW 
                                                <svg width="17" height="12" viewBox="0 0 17 12" fill="none">
                                                    <path d="M15.9732 5.19375L11.1893 0.460018C11.1225 0.392216 11.0412 0.338185 10.9507 0.301395C10.8601 0.264605 10.7623 0.245867 10.6636 0.246372C10.5648 0.246877 10.4672 0.266615 10.377 0.304329C10.2869 0.342044 10.2061 0.396903 10.14 0.465385C10.001 0.610077 9.9245 0.79778 9.92549 0.992021C9.92649 1.18626 10.0049 1.37316 10.1454 1.51643L13.6531 4.9864L0.935903 5.05145C0.734471 5.06613 0.546408 5.15137 0.409525 5.29006C0.272641 5.42874 0.197086 5.61057 0.19805 5.799C0.199014 5.98743 0.276425 6.16848 0.41472 6.30575C0.553015 6.44303 0.74194 6.52635 0.943512 6.53896L13.6586 6.47392L10.1866 9.98155C10.0475 10.1262 9.97108 10.3139 9.97207 10.5082C9.97306 10.7024 10.0514 10.8893 10.192 11.0326C10.2588 11.1004 10.3401 11.1544 10.4306 11.1912C10.5212 11.228 10.6189 11.2467 10.7177 11.2462C10.8165 11.2457 10.9141 11.226 11.0042 11.1883C11.0944 11.1506 11.1751 11.0957 11.2413 11.0272L15.9786 6.25458C16.1206 6.1093 16.1989 5.91956 16.1979 5.72303C16.1969 5.5265 16.1167 5.33757 15.9732 5.19375Z" fill="currentColor" />
                                                </svg>
                                            </a>
                                            <a class="outline__btn" href="<?= $routes['user']['shop'] ?>">View All</a>
                                        </div>
                                        <div class="slider__stats">
                                            <div class="stat__item"><span class="stat__num">500+</span><span class="stat__label">Products</span></div>
                                            <div class="stat__divider"></div>
                                            <div class="stat__item"><span class="stat__num">50K+</span><span class="stat__label">Happy Clients</span></div>
                                            <div class="stat__divider"></div>
                                            <div class="stat__item"><span class="stat__num">15+</span><span class="stat__label">Brands</span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="hero__slider--thumbnail text-right">
                                        <img class="slider__layer--img style2" src="assets/img/slider/slider1-layer.png" alt="slider-img">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="swiper-slide">
                <div class="home__two--slider__items slide-bg-2">
                    <div class="container">
                        <div class="slider__items--inner">
                            <div class="row align-items-center">
                                <div class="col-lg-6 col-md-6">
                                    <div class="slider__content">
                                        <span class="slider__tag">Exclusive Collection</span>
                                        <h2 class="slider__maintitle h1">Jewelry to Fit Every Budget &amp; Occasion</h2>
                                        <p class="slider__desc">50% OFF on the most popular Jewellery brands. Order all classy products today!</p>
                                        <div class="slider__btn--group">
                                            <a class="primary__btn slider__btn" href="<?= $routes['user']['collection'] ?>">SHOP NOW 
                                                <svg width="17" height="12" viewBox="0 0 17 12" fill="none">
                                                    <path d="M15.9732 5.19375L11.1893 0.460018C11.1225 0.392216 11.0412 0.338185 10.9507 0.301395C10.8601 0.264605 10.7623 0.245867 10.6636 0.246372C10.5648 0.246877 10.4672 0.266615 10.377 0.304329C10.2869 0.342044 10.2061 0.396903 10.14 0.465385C10.001 0.610077 9.9245 0.79778 9.92549 0.992021C9.92649 1.18626 10.0049 1.37316 10.1454 1.51643L13.6531 4.9864L0.935903 5.05145C0.734471 5.06613 0.546408 5.15137 0.409525 5.29006C0.272641 5.42874 0.197086 5.61057 0.19805 5.799C0.199014 5.98743 0.276425 6.16848 0.41472 6.30575C0.553015 6.44303 0.74194 6.52635 0.943512 6.53896L13.6586 6.47392L10.1866 9.98155C10.0475 10.1262 9.97108 10.3139 9.97207 10.5082C9.97306 10.7024 10.0514 10.8893 10.192 11.0326C10.2588 11.1004 10.3401 11.1544 10.4306 11.1912C10.5212 11.228 10.6189 11.2467 10.7177 11.2462C10.8165 11.2457 10.9141 11.226 11.0042 11.1883C11.0944 11.1506 11.1751 11.0957 11.2413 11.0272L15.9786 6.25458C16.1206 6.1093 16.1989 5.91956 16.1979 5.72303C16.1969 5.5265 16.1167 5.33757 15.9732 5.19375Z" fill="currentColor" />
                                                </svg></a>
                                            <a class="outline__btn" href="<?= $routes['user']['shop'] ?>">View All</a>
                                        </div>
                                        <div class="slider__stats">
                                            <div class="stat__item"><span class="stat__num">500+</span><span class="stat__label">Products</span></div>
                                            <div class="stat__divider"></div>
                                            <div class="stat__item"><span class="stat__num">50K+</span><span class="stat__label">Happy Clients</span></div>
                                            <div class="stat__divider"></div>
                                            <div class="stat__item"><span class="stat__num">15+</span><span class="stat__label">Brands</span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="hero__slider--thumbnail text-right">
                                        <img class="slider__layer--img style2" src="assets/img/slider/slider2-layer.png" alt="slider-img">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="swiper-slide">
                <div class="home__two--slider__items slide-bg-3">
                    <div class="container">
                        <div class="slider__items--inner">
                            <div class="row align-items-center">
                                <div class="col-lg-6 col-md-6">
                                    <div class="slider__content">
                                        <span class="slider__tag">Limited Time Offer</span>
                                        <h2 class="slider__maintitle h1">Beauty is Who You Are — Jewelry is the Icing</h2>
                                        <p class="slider__desc">50% OFF on cosmetics &amp; jewellery. Order all classy products today!</p>
                                        <div class="slider__btn--group">
                                            <a class="primary__btn slider__btn" href="<?= $routes['user']['collection'] ?>">SHOP NOW
                                                <svg width="17" height="12" viewBox="0 0 17 12" fill="none">
                                                    <path d="M15.9732 5.19375L11.1893 0.460018C11.1225 0.392216 11.0412 0.338185 10.9507 0.301395C10.8601 0.264605 10.7623 0.245867 10.6636 0.246372C10.5648 0.246877 10.4672 0.266615 10.377 0.304329C10.2869 0.342044 10.2061 0.396903 10.14 0.465385C10.001 0.610077 9.9245 0.79778 9.92549 0.992021C9.92649 1.18626 10.0049 1.37316 10.1454 1.51643L13.6531 4.9864L0.935903 5.05145C0.734471 5.06613 0.546408 5.15137 0.409525 5.29006C0.272641 5.42874 0.197086 5.61057 0.19805 5.799C0.199014 5.98743 0.276425 6.16848 0.41472 6.30575C0.553015 6.44303 0.74194 6.52635 0.943512 6.53896L13.6586 6.47392L10.1866 9.98155C10.0475 10.1262 9.97108 10.3139 9.97207 10.5082C9.97306 10.7024 10.0514 10.8893 10.192 11.0326C10.2588 11.1004 10.3401 11.1544 10.4306 11.1912C10.5212 11.228 10.6189 11.2467 10.7177 11.2462C10.8165 11.2457 10.9141 11.226 11.0042 11.1883C11.0944 11.1506 11.1751 11.0957 11.2413 11.0272L15.9786 6.25458C16.1206 6.1093 16.1989 5.91956 16.1979 5.72303C16.1969 5.5265 16.1167 5.33757 15.9732 5.19375Z" fill="currentColor" />
                                                </svg></a>
                                            <a class="outline__btn" href="<?= $routes['user']['shop'] ?>">View All</a>
                                        </div>
                                        <div class="slider__stats">
                                            <div class="stat__item"><span class="stat__num">500+</span><span class="stat__label">Products</span></div>
                                            <div class="stat__divider"></div>
                                            <div class="stat__item"><span class="stat__num">50K+</span><span class="stat__label">Happy Clients</span></div>
                                            <div class="stat__divider"></div>
                                            <div class="stat__item"><span class="stat__num">15+</span><span class="stat__label">Brands</span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="hero__slider--thumbnail text-right">
                                        <img class="slider__layer--img style2" src="assets/img/slider/slider3-layer.png" alt="slider-img">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="slider__pagination swiper-pagination"></div>
    </div>
</section>

<!-- ══════ FEATURES / TRUST BAR ══════ -->
<section class="features__bar--section">
    <div class="container">
        <div class="features__bar--inner">
            <div class="feature__item">
                <div class="feature__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                </div>
                <div class="feature__text">
                    <h4>Free Shipping</h4>
                    <p>On all orders over $50</p>
                </div>
            </div>
            <div class="feature__divider"></div>
            <div class="feature__item">
                <div class="feature__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                    </svg>
                </div>
                <div class="feature__text">
                    <h4>100% Authentic</h4>
                    <p>Genuine products guaranteed</p>
                </div>
            </div>
            <div class="feature__divider"></div>
            <div class="feature__item">
                <div class="feature__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                        <polyline points="9,22 9,12 15,12 15,22" />
                    </svg>
                </div>
                <div class="feature__text">
                    <h4>Easy Returns</h4>
                    <p>30-day hassle-free returns</p>
                </div>
            </div>
            <div class="feature__divider"></div>
            <div class="feature__item">
                <div class="feature__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.64A2 2 0 012 1h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 8.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z" />
                    </svg>
                </div>
                <div class="feature__text">
                    <h4>24/7 Support</h4>
                    <p>Always here to help you</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══════ SHOP BY CATEGORY ══════ -->
<?php include('./pages/other/categories.php'); ?>

<!-- ══════ PROMOTIONAL BANNER ══════ -->
<section class="promo__banner--section">
    <div class="container">
        <div class="promo__banner--inner" style="background: linear-gradient(135deg, #C97F5F 0%, #8B4A35 100%); border-radius: 24px; padding: 5rem 4rem; position:relative; overflow:hidden;">
            <div class="row align-items-center">
                <div class="col-lg-8 col-md-8">
                    <span style="font-family:'Inter',sans-serif; font-size:1.2rem; font-weight:700; letter-spacing:0.15em; color:rgba(255,255,255,0.75); text-transform:uppercase; display:block; margin-bottom:1rem;">Limited Time Deal</span>
                    <h2 style="font-family:'Playfair Display',serif; font-size:3.5rem; font-weight:700; color:#fff; line-height:1.2; margin-bottom:1.5rem;">Flat 50% Off On<br>Fresh Organic Cosmetics</h2>
                    <p style="font-family:'Inter',sans-serif; font-size:1.5rem; color:rgba(255,255,255,0.85); margin-bottom:0;">Order today and get free shipping on your first purchase!</p>
                </div>
                <div class="col-lg-4 col-md-4 text-center text-md-end" style="margin-top: 2rem;">
                    <a href="<?= $routes['user']['collection'] ?>" class="promo__cta--btn">SHOP NOW →</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══════ TRENDING PRODUCTS ══════ -->
<?php include('./pages/other/trendingproducts.php'); ?>

<!-- ══════ WHY CHOOSE US ══════ -->
<section class="why__us--section section--padding" style="background: linear-gradient(135deg, #FDF6F0 0%, #F5EEE8 100%);">
    <div class="container">
        <div class="section__heading text-center mb-40">
            <span class="section__tag">Our Promise</span>
            <h2 class="section__heading--maintitle">Why Choose Jenny Fashion Store?</h2>
            <p class="section__heading--desc">We are committed to bringing you the finest beauty &amp; jewellery products with unmatched service.</p>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-30">
                <div class="why__card">
                    <div class="why__card--icon" style="background: linear-gradient(135deg, #C97F5F22, #C97F5F11);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#C97F5F" stroke-width="1.5">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                        </svg>
                    </div>
                    <h3 class="why__card--title">Premium Quality</h3>
                    <p class="why__card--desc">Every product is hand-picked from top global brands and tested for quality, safety, and authenticity.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-30">
                <div class="why__card why__card--featured">
                    <div class="why__card--icon" style="background: linear-gradient(135deg, #C97F5F44, #C97F5F22);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#C97F5F" stroke-width="1.5">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M12 6v6l4 2" />
                        </svg>
                    </div>
                    <h3 class="why__card--title">Fast Delivery</h3>
                    <p class="why__card--desc">Same-day dispatch on all orders placed before 2 PM. Track your delivery in real-time from our app.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-30">
                <div class="why__card">
                    <div class="why__card--icon" style="background: linear-gradient(135deg, #C97F5F22, #C97F5F11);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#C97F5F" stroke-width="1.5">
                            <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z" />
                        </svg>
                    </div>
                    <h3 class="why__card--title">Made with Love</h3>
                    <p class="why__card--desc">We curate every product with care to ensure your beauty journey is joyful, affordable, and memorable.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══════ BANNER GRID ══════ -->
<section class="banner__section">
    <div class="container-fluid p-0">
        <div class="row no-gutter mb--n30">
            <div class="col-lg-6 col-md-6 col-sm-6 mb-30">
                <div class="banner__box border-radius-5 position-relative">
                    <a class="d-block" href="<?= $routes['user']['collection'] ?>">
                        <img class="banner__box--thumbnail border-radius-5" src="assets/img/banner/banner15.webp" alt="banner-img">
                        <div class="fullwidth__banner--box__content left">
                            <p class="fullwidth__banner--box__subtitle">Jenny Fashion Store</p>
                            <h2 class="fullwidth__banner--box__title">Jewellery<br>Online</h2>
                            <span class="banner__box--content__btn primary__btn">SHOP NOW</span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-6 mb-30">
                <div class="banner__box border-radius-5 position-relative">
                    <a class="d-block" href="<?= $routes['user']['collection'] ?>">
                        <img class="banner__box--thumbnail border-radius-5" src="assets/img/banner/banner16.png" alt="banner-img">
                        <div class="fullwidth__banner--box__content right">
                            <p class="fullwidth__banner--box__subtitle">Jenny Fashion Store</p>
                            <h2 class="fullwidth__banner--box__title">Cosmetics<br>Online</h2>
                            <span class="banner__box--content__btn primary__btn">SHOP NOW</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══════ TESTIMONIALS ══════ -->
<section class="testimonials--section section--padding">
    <div class="container">
        <div class="section__heading text-center mb-40">
            <span class="section__tag">Customer Reviews</span>
            <h2 class="section__heading--maintitle">What Our Clients Say</h2>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-30">
                <div class="testimonial__card">
                    <div class="testimonial__stars">★★★★★</div>
                    <p class="testimonial__text">"Absolutely love the products from Jenny Fashion Store! The rose water toner transformed my skin. Fast delivery and beautiful packaging!"</p>
                    <div class="testimonial__author">
                        <div class="testimonial__avatar">SA</div>
                        <div>
                            <h5 class="testimonial__name">Sara Ahmed</h5>
                            <span class="testimonial__role">Skincare Enthusiast</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-30">
                <div class="testimonial__card testimonial__card--featured">
                    <div class="testimonial__stars">★★★★★</div>
                    <p class="testimonial__text">"The bridal jewellery set was stunning! Got so many compliments at my wedding. Quality is unmatched and the price is very reasonable."</p>
                    <div class="testimonial__author">
                        <div class="testimonial__avatar">FN</div>
                        <div>
                            <h5 class="testimonial__name">Fatima Noor</h5>
                            <span class="testimonial__role">Verified Buyer</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-30">
                <div class="testimonial__card">
                    <div class="testimonial__stars">★★★★★</div>
                    <p class="testimonial__text">"I ordered the Oud Royal Perfume and it was exactly as described — rich, long-lasting scent. Will definitely be ordering again!"</p>
                    <div class="testimonial__author">
                        <div class="testimonial__avatar">ZK</div>
                        <div>
                            <h5 class="testimonial__name">Zara Khan</h5>
                            <span class="testimonial__role">Fragrance Lover</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══════ NEWSLETTER SIGNUP ══════ -->
<section class="newsletter__section--homepage section--padding" style="background: linear-gradient(135deg, #3C3836 0%, #2a2422 100%); padding: 6rem 0;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-30">
                <span class="section__tag section__tag--light">Stay Updated</span>
                <h2 style="font-family:'Playfair Display',serif; font-size:3rem; font-weight:700; color:#fff; line-height:1.3; margin-bottom:1.5rem;">Get Exclusive Deals &amp;<br>Beauty Tips Weekly</h2>
                <p style="font-family:'Inter',sans-serif; font-size:1.5rem; color:rgba(255,255,255,0.7); line-height:1.8;">Subscribe to our newsletter and never miss a sale, new launch, or beauty hack from our experts.</p>
            </div>
            <div class="col-lg-6">
                <form class="home__newsletter--form" id="home-newsletter-form" action="#">
                    <div class="home__newsletter--input-wrap">
                        <input type="email" placeholder="Enter your email address..." id="news-letter-input" required class="home__newsletter--input">
                        <button type="submit" class="home__newsletter--btn">Subscribe</button>
                    </div>
                    <p style="font-family:'Inter',sans-serif; font-size:1.2rem; color:rgba(255,255,255,0.45); margin-top:1rem;">No spam. Unsubscribe anytime.</p>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include('pages/includes/footer.php'); ?>

<script>
    document.getElementById('home-newsletter-form')?.addEventListener('submit', function(e) {
        e.preventDefault();
        document.getElementById('news-letter-input').value = '';
        showAlert("success", "Thank you for joining our beauty community!");
    });
</script>