<?php
session_start();
include('Pages/includes/header.php');
include_once('functions/userfunction.php');

$categories = getAllActive('categories');
$catList    = [];
while ($cat = mysqli_fetch_assoc($categories)) $catList[] = $cat;

$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$filterCat  = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$sortBy     = isset($_GET['sort']) ? $_GET['sort'] : 'default';

if ($searchTerm !== '') {
    $safe    = mysqli_real_escape_string($con, $searchTerm);
    $catCond = $filterCat > 0 ? " AND p.category_id = $filterCat" : '';
    $sortSQL = match($sortBy) {
        'price_asc'  => 'p.d_price ASC',
        'price_desc' => 'p.d_price DESC',
        'name_asc'   => 'p.name ASC',
        default      => 'p.trending DESC, p.id ASC',
    };
    $query   = "SELECT p.*, c.name as cat_name FROM products p LEFT JOIN categories c ON p.category_id=c.id WHERE p.status=1$catCond AND (p.name LIKE '%$safe%' OR p.s_description LIKE '%$safe%') ORDER BY $sortSQL";
    $products = mysqli_query($con, $query);
} elseif ($filterCat > 0) {
    $sortSQL = match($sortBy) {
        'price_asc'  => 'p.d_price ASC',
        'price_desc' => 'p.d_price DESC',
        'name_asc'   => 'p.name ASC',
        default      => 'p.trending DESC, p.id ASC',
    };
    $query   = "SELECT p.*, c.name as cat_name FROM products p LEFT JOIN categories c ON p.category_id=c.id WHERE p.status=1 AND p.category_id=$filterCat ORDER BY $sortSQL";
    $products = mysqli_query($con, $query);
} else {
    $sortSQL = match($sortBy) {
        'price_asc'  => 'p.d_price ASC',
        'price_desc' => 'p.d_price DESC',
        'name_asc'   => 'p.name ASC',
        default      => 'p.trending DESC, p.id ASC',
    };
    $query   = "SELECT p.*, c.name as cat_name FROM products p LEFT JOIN categories c ON p.category_id=c.id WHERE p.status=1 ORDER BY $sortSQL";
    $products = mysqli_query($con, $query);
}

$totalProducts = $products ? mysqli_num_rows($products) : 0;
$rating = rand(4, 5);
$reviews = rand(50, 250);
?>

<!-- Shop Controls -->
<section class="shop__controls--section">
    <div class="container">
        <!-- Search Bar -->
        <div class="shop__search--bar">
            <form method="GET" action="<?= $routes['user']['shop'] ?>" class="shop__search--form">
                <div class="shop__search--input-wrap">
                    <svg class="shop__search--icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                    <input
                        type="text"
                        name="search"
                        value="<?= htmlspecialchars($searchTerm) ?>"
                        placeholder="Search products, categories..."
                        class="shop__search--input"
                        autocomplete="off"
                    >
                    <?php if($filterCat > 0): ?><input type="hidden" name="category" value="<?= $filterCat ?>"><?php endif; ?>
                    <?php if($sortBy !== 'default'): ?><input type="hidden" name="sort" value="<?= htmlspecialchars($sortBy) ?>"><?php endif; ?>
                    <?php if($searchTerm): ?>
                    <a href="<?= $routes['user']['shop'] ?><?= $filterCat ? '?category='.$filterCat : '' ?>" class="shop__search--clear" title="Clear search">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
                    </a>
                    <?php endif; ?>
                    <button type="submit" style="border: none !important;" class="shop__search--btn">Search</button>
                </div>
            </form>
        </div>

        <!-- Category Filter Tabs -->
        <div class="shop__filter--row">
            <div class="shop__category--tabs">
                <a href="<?= $routes['user']['shop'] ?><?= $sortBy !== 'default' ? '?sort='.$sortBy : '' ?><?= $searchTerm ? ($sortBy !== 'default' ? '&' : '?').'search='.urlencode($searchTerm) : '' ?>"
                   class="shop__cat--tab <?= $filterCat === 0 ? 'active' : '' ?>">All</a>
                <?php foreach($catList as $cat): ?>
                <a href="<?= $routes['user']['shop'] ?>?category=<?= $cat['id'] ?><?= $sortBy !== 'default' ? '&sort='.$sortBy : '' ?><?= $searchTerm ? '&search='.urlencode($searchTerm) : '' ?>"
                   class="shop__cat--tab <?= $filterCat === (int)$cat['id'] ? 'active' : '' ?>">
                   <?= htmlspecialchars($cat['name']) ?>
                </a>
                <?php endforeach; ?>
            </div>

            <div class="shop__sort--wrap">
                <form method="GET" action="<?= $routes['user']['shop'] ?>" id="sort-form">
                    <?php if($filterCat > 0): ?><input type="hidden" name="category" value="<?= $filterCat ?>"><?php endif; ?>
                    <?php if($searchTerm): ?><input type="hidden" name="search" value="<?= htmlspecialchars($searchTerm) ?>"><?php endif; ?>
                    <label class="shop__sort--label" for="sort-select">Sort by:</label>
                    <select name="sort" id="sort-select" class="shop__sort--select" onchange="document.getElementById('sort-form').submit()">
                        <option value="default"  <?= $sortBy === 'default'    ? 'selected' : '' ?>>Featured</option>
                        <option value="price_asc"  <?= $sortBy === 'price_asc'  ? 'selected' : '' ?>>Price: Low to High</option>
                        <option value="price_desc" <?= $sortBy === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
                        <option value="name_asc"   <?= $sortBy === 'name_asc'   ? 'selected' : '' ?>>Name: A–Z</option>
                    </select>
                </form>
                <span class="shop__product--count"><?= $totalProducts ?> product<?= $totalProducts !== 1 ? 's' : '' ?></span>
            </div>
        </div>

        <?php if($searchTerm): ?>
        <div class="shop__search--notice">
            <?php if($totalProducts > 0): ?>
                Showing <strong><?= $totalProducts ?></strong> result<?= $totalProducts !== 1 ? 's' : '' ?> for &ldquo;<strong><?= htmlspecialchars($searchTerm) ?></strong>&rdquo;
            <?php else: ?>
                No results for &ldquo;<strong><?= htmlspecialchars($searchTerm) ?></strong>&rdquo; — try different keywords or <a href="<?= $routes['user']['shop'] ?>">browse all products</a>.
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Products Grid -->
<section class="product__section section--padding pt-0">
    <div class="container">
        <?php if ($totalProducts > 0): ?>
        <div class="row mb--n30">
            <?php while ($item = mysqli_fetch_assoc($products)): ?>
            <div class="col-lg-3 col-md-4 col-sm-6 col-6 custom-col mb-30">
                <article class="product__card">
                    <div class="product__card--thumbnail">
                        <a class="product__card--thumbnail__link display-block" href="<?= $routes['user']['product'] ?>?product=<?= urlencode($item['name']) ?>&sur=shop">
                            <img class="product__card--thumbnail__img" src="admin/assets/img/products/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                        </a>
                        <?php if ($item['trending'] == 1): ?>
                        <span class="product__badge product__badge--trending">Trending</span>
                        <?php endif; ?>
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

                        <h3 class="product__card--title"><a href="<?= $routes['user']['product'] ?>?product=<?= urlencode($item['name']) ?>&sur=shop"><?= htmlspecialchars($item['name']) ?></a></h3>
               
                        <div class="product__card--price">
                            <span class="current__price">$<?= number_format($item['d_price'], 2) ?></span>
                            <span class="old__price">$<?= number_format($item['price'], 2) ?></span>
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
            <?php endwhile; ?>
        </div>
        <?php else: ?>
        <div class="shop__empty--state text-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#C97F5F" stroke-width="1"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
            <h3>No products found</h3>
            <p>Try adjusting your search or filter criteria.</p>
            <a href="<?= $routes['user']['shop'] ?>" class="primary__btn">View All Products</a>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include('Pages/includes/footer.php'); ?>
