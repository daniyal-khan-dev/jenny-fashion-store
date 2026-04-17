<?php
session_start();
include('Pages/includes/header.php');
?>

<!-- ── HERO ABOUT ──────────────────────────────── -->
<section class="about__Section section--padding">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="about__img-wrap">
                    <img src="assets/img/other/about.webp" alt="About Jenny Fashion Store" class="about__hero--img">
                    <div class="about__badge">
                        <span class="about__badge--num">15<sup>+</sup></span>
                        <span class="about__badge--label">Years Experience</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about__content--pad">
                    <span class="section__tag">About Us</span>
                    <h2 class="about__hero--title">Curated by Colour,<br>Crafted with Love</h2>
                    <p class="about__hero--desc">Jenny Fashion Store was born out of a passion for beauty and elegance. Since 2009, we've been dedicated to bringing premium cosmetics, fragrances, and jewellery to women who appreciate quality and style.</p>
                    <p class="about__hero--desc">Every product in our collection is personally curated to ensure authenticity, quality, and value. We believe beauty should be accessible to everyone — from everyday essentials to luxury statement pieces.</p>
                    <div class="row g-4 about__stats--row">
                        <div class="col-6">
                            <div class="about__stat">
                                <h3 class="about__stat--num">500+</h3>
                                <p>Curated Products</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="about__stat">
                                <h3 class="about__stat--num">50K+</h3>
                                <p>Happy Customers</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="about__stat">
                                <h3 class="about__stat--num">15+</h3>
                                <p>Top Brands</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="about__stat">
                                <h3 class="about__stat--num">4.9★</h3>
                                <p>Average Rating</p>
                            </div>
                        </div>
                    </div>
                    <a href="<?= $routes['user']['shop'] ?>" class="primary__btn" style="margin-top:1rem;">Shop Our Collection</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── OUR JOURNEY TIMELINE ────────────────────── -->
<section class="timeline--section section--padding" style="background:linear-gradient(135deg,#FDF6F0,#F5EEE8);">
    <div class="container">
        <div class="section__heading text-center mb-40">
            <span class="section__tag">Our Story</span>
            <h2 class="section__heading--maintitle">Our Journey Through the Years</h2>
        </div>
        <div class="timeline">
            <div class="timeline__item timeline__item--left">
                <div class="timeline__dot"></div>
                <div class="timeline__card">
                    <span class="timeline__year">2009</span>
                    <h3 class="timeline__title">The Beginning</h3>
                    <p class="timeline__desc">Jenny Fashion Store opened its first boutique in Karachi, starting with a small collection of handpicked lipsticks and foundation ranges from international brands.</p>
                </div>
            </div>
            <div class="timeline__item timeline__item--right">
                <div class="timeline__dot"></div>
                <div class="timeline__card">
                    <span class="timeline__year">2013</span>
                    <h3 class="timeline__title">Expanding Into Jewellery</h3>
                    <p class="timeline__desc">We launched our jewellery line — bridal sets, everyday earrings, and statement necklaces — becoming a one-stop destination for beauty and elegance.</p>
                </div>
            </div>
            <div class="timeline__item timeline__item--left">
                <div class="timeline__dot"></div>
                <div class="timeline__card">
                    <span class="timeline__year">2017</span>
                    <h3 class="timeline__title">Fragrances & Skincare</h3>
                    <p class="timeline__desc">Added a luxury fragrance collection and skincare range to our catalogue, partnering with globally recognised brands to bring authentic products to our customers.</p>
                </div>
            </div>
            <div class="timeline__item timeline__item--right">
                <div class="timeline__dot"></div>
                <div class="timeline__card">
                    <span class="timeline__year">2021</span>
                    <h3 class="timeline__title">Going Online</h3>
                    <p class="timeline__desc">Launched our e-commerce platform, reaching customers across Pakistan and internationally. Over 10,000 orders delivered in the first year alone.</p>
                </div>
            </div>
            <div class="timeline__item timeline__item--left">
                <div class="timeline__dot timeline__dot--active"></div>
                <div class="timeline__card timeline__card--active">
                    <span class="timeline__year">Today</span>
                    <h3 class="timeline__title">50,000+ Happy Clients</h3>
                    <p class="timeline__desc">Serving a loyal community of beauty lovers nationwide, with 500+ products, same-day dispatch, and a 4.9-star average customer rating.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── OUR VALUES ──────────────────────────────── -->
<section class="values--section section--padding">
    <div class="container">
        <div class="section__heading text-center mb-40">
            <span class="section__tag">Our Values</span>
            <h2 class="section__heading--maintitle">What We Stand For</h2>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="why__card text-center h-100">
                    <div class="why__card--icon mx-auto" style="background:rgba(201,127,95,.1);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#C97F5F" stroke-width="1.5">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        </svg>
                    </div>
                    <h3 class="why__card--title">Authenticity</h3>
                    <p class="why__card--desc">Every product is 100% genuine, sourced directly from verified global brands and authorised distributors.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="why__card why__card--featured text-center h-100">
                    <div class="why__card--icon mx-auto" style="background:rgba(201,127,95,.15);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#C97F5F" stroke-width="1.5">
                            <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z" />
                        </svg>
                    </div>
                    <h3 class="why__card--title">Passion</h3>
                    <p class="why__card--desc">We are driven by a genuine love for beauty — dedicated to helping every customer feel confident and beautiful.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="why__card text-center h-100">
                    <div class="why__card--icon mx-auto" style="background:rgba(201,127,95,.1);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#C97F5F" stroke-width="1.5">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M12 8v4l3 3" />
                        </svg>
                    </div>
                    <h3 class="why__card--title">Speed</h3>
                    <p class="why__card--desc">Same-day dispatch on orders placed before 2 PM. Your beauty essentials reach you as quickly as possible.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="why__card text-center h-100">
                    <div class="why__card--icon mx-auto" style="background:rgba(201,127,95,.1);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#C97F5F" stroke-width="1.5">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                        </svg>
                    </div>
                    <h3 class="why__card--title">Excellence</h3>
                    <p class="why__card--desc">From product selection to unboxing, we hold every aspect of our service to the highest possible standard.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── TEAM ────────────────────────────────────── -->
<section class="team--section section--padding">
    <div class="container">
        <div class="section__heading text-center mb-40">
            <span class="section__tag">The People Behind It</span>
            <h2 class="section__heading--maintitle">Meet Our Team</h2>
            <p class="section__heading--desc">Passionate beauty experts dedicated to bringing you the very best every day.</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-lg-3 col-md-6">
                <div class="team__card">
                    <div class="team__avatar" style="background:linear-gradient(135deg,#C97F5F,#8B4A35);">JA</div>
                    <h4 class="team__name">Jenny Ahmed</h4>
                    <span class="team__role">Founder &amp; CEO</span>
                    <p class="team__bio">A beauty visionary with 15+ years in the cosmetics industry. Jenny built this brand from the ground up with a single goal — making premium beauty accessible to all.</p>
                    <div class="team__social">
                        <a href="#" class="team__social--link"><svg width="14" height="14" viewBox="0 0 9 15" fill="currentColor">
                                <path d="M7.62891 8.625L8.01172 6.10938H5.57812V4.46875C5.57812 3.75781 5.90625 3.10156 7 3.10156H8.12109V0.941406C8.12109 0.941406 7.10938 0.75 6.15234 0.75C4.15625 0.75 2.84375 1.98047 2.84375 4.16797V6.10938H0.601562V8.625H2.84375V14.75H5.57812V8.625H7.62891Z" />
                            </svg></a>
                        <a href="#" class="team__social--link"><svg width="14" height="14" viewBox="0 0 14 13" fill="currentColor">
                                <path d="M7.125 3.60547C5.375 3.60547 3.98047 5.02734 3.98047 6.75C3.98047 8.5 5.375 9.89453 7.125 9.89453C8.84766 9.89453 10.2695 8.5 10.2695 6.75C10.2695 5.02734 8.84766 3.60547 7.125 3.60547ZM7.125 8.80078C6.00391 8.80078 5.07422 7.89844 5.07422 6.75C5.07422 5.62891 5.97656 4.72656 7.125 4.72656C8.24609 4.72656 9.14844 5.62891 9.14844 6.75C9.14844 7.89844 8.24609 8.80078 7.125 8.80078ZM11.1172 3.49609C11.1172 3.08594 10.7891 2.75781 10.3789 2.75781C9.96875 2.75781 9.64062 3.08594 9.64062 3.49609C9.64062 3.90625 9.96875 4.23438 10.3789 4.23438C10.7891 4.23438 11.1172 3.90625 11.1172 3.49609ZM13.1953 4.23438C13.1406 3.25 12.9219 2.375 12.2109 1.66406C11.5 0.953125 10.625 0.734375 9.64062 0.679688C8.62891 0.625 5.59375 0.625 4.58203 0.679688C3.59766 0.734375 2.75 0.953125 2.01172 1.66406C1.30078 2.375 1.08203 3.25 1.02734 4.23438C0.972656 5.24609 0.972656 8.28125 1.02734 9.29297C1.08203 10.2773 1.30078 11.125 2.01172 11.8633C2.75 12.5742 3.59766 12.793 4.58203 12.8477C5.59375 12.9023 8.62891 12.9023 9.64062 12.8477C10.625 12.793 11.5 12.5742 12.2109 11.8633C12.9219 11.125 13.1406 10.2773 13.1953 9.29297C13.25 8.28125 13.25 5.24609 13.1953 4.23438Z" />
                            </svg></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="team__card team__card--featured">
                    <div class="team__avatar" style="background:linear-gradient(135deg,#8B4A35,#C97F5F);">SK</div>
                    <h4 class="team__name">Sara Khan</h4>
                    <span class="team__role">Head of Curation</span>
                    <p class="team__bio">Sara handpicks every product in our catalogue, travelling internationally to source the finest cosmetics and jewellery from top-tier brands.</p>
                    <div class="team__social">
                        <a href="#" class="team__social--link"><svg width="14" height="14" viewBox="0 0 9 15" fill="currentColor">
                                <path d="M7.62891 8.625L8.01172 6.10938H5.57812V4.46875C5.57812 3.75781 5.90625 3.10156 7 3.10156H8.12109V0.941406C8.12109 0.941406 7.10938 0.75 6.15234 0.75C4.15625 0.75 2.84375 1.98047 2.84375 4.16797V6.10938H0.601562V8.625H2.84375V14.75H5.57812V8.625H7.62891Z" />
                            </svg></a>
                        <a href="#" class="team__social--link"><svg width="14" height="14" viewBox="0 0 14 13" fill="currentColor">
                                <path d="M7.125 3.60547C5.375 3.60547 3.98047 5.02734 3.98047 6.75C3.98047 8.5 5.375 9.89453 7.125 9.89453C8.84766 9.89453 10.2695 8.5 10.2695 6.75C10.2695 5.02734 8.84766 3.60547 7.125 3.60547ZM7.125 8.80078C6.00391 8.80078 5.07422 7.89844 5.07422 6.75C5.07422 5.62891 5.97656 4.72656 7.125 4.72656C8.24609 4.72656 9.14844 5.62891 9.14844 6.75C9.14844 7.89844 8.24609 8.80078 7.125 8.80078ZM11.1172 3.49609C11.1172 3.08594 10.7891 2.75781 10.3789 2.75781C9.96875 2.75781 9.64062 3.08594 9.64062 3.49609C9.64062 3.90625 9.96875 4.23438 10.3789 4.23438C10.7891 4.23438 11.1172 3.90625 11.1172 3.49609ZM13.1953 4.23438C13.1406 3.25 12.9219 2.375 12.2109 1.66406C11.5 0.953125 10.625 0.734375 9.64062 0.679688C8.62891 0.625 5.59375 0.625 4.58203 0.679688C3.59766 0.734375 2.75 0.953125 2.01172 1.66406C1.30078 2.375 1.08203 3.25 1.02734 4.23438C0.972656 5.24609 0.972656 8.28125 1.02734 9.29297C1.08203 10.2773 1.30078 11.125 2.01172 11.8633C2.75 12.5742 3.59766 12.793 4.58203 12.8477C5.59375 12.9023 8.62891 12.9023 9.64062 12.8477C10.625 12.793 11.5 12.5742 12.2109 11.8633C12.9219 11.125 13.1406 10.2773 13.1953 9.29297C13.25 8.28125 13.25 5.24609 13.1953 4.23438Z" />
                            </svg></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="team__card">
                    <div class="team__avatar" style="background:linear-gradient(135deg,#d4a76a,#C97F5F);">FN</div>
                    <h4 class="team__name">Fatima Noor</h4>
                    <span class="team__role">Customer Experience</span>
                    <p class="team__bio">Fatima leads our customer care team, ensuring every interaction is warm, helpful, and leaves our clients feeling valued and satisfied.</p>
                    <div class="team__social">
                        <a href="#" class="team__social--link"><svg width="14" height="14" viewBox="0 0 9 15" fill="currentColor">
                                <path d="M7.62891 8.625L8.01172 6.10938H5.57812V4.46875C5.57812 3.75781 5.90625 3.10156 7 3.10156H8.12109V0.941406C8.12109 0.941406 7.10938 0.75 6.15234 0.75C4.15625 0.75 2.84375 1.98047 2.84375 4.16797V6.10938H0.601562V8.625H2.84375V14.75H5.57812V8.625H7.62891Z" />
                            </svg></a>
                        <a href="#" class="team__social--link"><svg width="14" height="14" viewBox="0 0 14 13" fill="currentColor">
                                <path d="M7.125 3.60547C5.375 3.60547 3.98047 5.02734 3.98047 6.75C3.98047 8.5 5.375 9.89453 7.125 9.89453C8.84766 9.89453 10.2695 8.5 10.2695 6.75C10.2695 5.02734 8.84766 3.60547 7.125 3.60547ZM7.125 8.80078C6.00391 8.80078 5.07422 7.89844 5.07422 6.75C5.07422 5.62891 5.97656 4.72656 7.125 4.72656C8.24609 4.72656 9.14844 5.62891 9.14844 6.75C9.14844 7.89844 8.24609 8.80078 7.125 8.80078ZM11.1172 3.49609C11.1172 3.08594 10.7891 2.75781 10.3789 2.75781C9.96875 2.75781 9.64062 3.08594 9.64062 3.49609C9.64062 3.90625 9.96875 4.23438 10.3789 4.23438C10.7891 4.23438 11.1172 3.90625 11.1172 3.49609ZM13.1953 4.23438C13.1406 3.25 12.9219 2.375 12.2109 1.66406C11.5 0.953125 10.625 0.734375 9.64062 0.679688C8.62891 0.625 5.59375 0.625 4.58203 0.679688C3.59766 0.734375 2.75 0.953125 2.01172 1.66406C1.30078 2.375 1.08203 3.25 1.02734 4.23438C0.972656 5.24609 0.972656 8.28125 1.02734 9.29297C1.08203 10.2773 1.30078 11.125 2.01172 11.8633C2.75 12.5742 3.59766 12.793 4.58203 12.8477C5.59375 12.9023 8.62891 12.9023 9.64062 12.8477C10.625 12.793 11.5 12.5742 12.2109 11.8633C12.9219 11.125 13.1406 10.2773 13.1953 9.29297C13.25 8.28125 13.25 5.24609 13.1953 4.23438Z" />
                            </svg></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── CTA BANNER ──────────────────────────────── -->
<section style="background:linear-gradient(135deg,#C97F5F,#8B4A35); padding:7rem 0;">
    <div class="container text-center">
        <span class="section__tag section__tag--light">Ready to Shop?</span>
        <h2 style="font-family:'Playfair Display',serif;font-size:3.5rem;font-weight:700;color:#fff;margin:1.5rem 0 1rem;">Discover Your Perfect Beauty Match</h2>
        <p style="font-family:'Inter',sans-serif;font-size:1.6rem;color:rgba(255,255,255,.85);margin-bottom:3rem;max-width:520px;margin-left:auto;margin-right:auto;">Explore 500+ premium products curated just for you. New arrivals every week.</p>
        <div style="display:flex;gap:1.5rem;justify-content:center;flex-wrap:wrap;">
            <a href="<?= $routes['user']['shop'] ?>" style="background:#fff;color:#C97F5F;font-family:'Inter',sans-serif;font-size:1.4rem;font-weight:700;padding:1.4rem 3rem;border-radius:50px;display:inline-block;transition:all .3s ease;box-shadow:0 8px 24px rgba(0,0,0,.15);">Shop Now</a>
            <a href="<?= $routes['user']['collection'] ?>" style="background:rgba(255,255,255,.15);color:#fff;font-family:'Inter',sans-serif;font-size:1.4rem;font-weight:700;padding:1.4rem 3rem;border-radius:50px;border:2px solid rgba(255,255,255,.4);display:inline-block;transition:all .3s ease;">View Collection</a>
        </div>
    </div>
</section>

<?php include('Pages/includes/footer.php'); ?>