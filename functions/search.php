<?php
include_once('../config/dbcon.php');

if (isset($_POST['txtsearch']) && !empty(trim($_POST['txtsearch']))) {
    $search = trim($_POST['txtsearch']);
    $safe   = mysqli_real_escape_string($con, $search);

    $query  = "SELECT p.*, c.name AS cat_name
               FROM products p
               LEFT JOIN categories c ON p.category_id = c.id
               WHERE p.status = 1
               AND (p.name LIKE '%$safe%' OR p.s_description LIKE '%$safe%' OR c.name LIKE '%$safe%')
               ORDER BY p.trending DESC, p.name ASC
               LIMIT 8";

    $result = mysqli_query($con, $query);
    $count  = $result ? mysqli_num_rows($result) : 0;

    if ($count > 0) {
        $enc = urlencode($search);
        echo '<div class="search__results--wrap">';
        echo '<p class="search__count">' . $count . ' result' . ($count !== 1 ? 's' : '') . ' for &ldquo;<strong>' . htmlspecialchars($search) . '</strong>&rdquo;</p>';
        echo '<div class="search__results--list">';
        while ($p = mysqli_fetch_assoc($result)) {
            $badge = $p['trending'] ? '<span class="search__badge">Trending</span>' : '';
            $desc  = htmlspecialchars(mb_substr($p['s_description'], 0, 55));
            echo '
            <a class="search__result--item" href="product-view.php?product=' . urlencode($p['name']) . '&sur=shop">
                <div class="search__result--img">
                    <img src="uploads/products/' . htmlspecialchars($p['image']) . '" alt="' . htmlspecialchars($p['name']) . '">
                    ' . $badge . '
                </div>
                <div class="search__result--body">
                    <span class="search__result--cat">' . htmlspecialchars($p['cat_name']) . '</span>
                    <h4 class="search__result--name">' . htmlspecialchars($p['name']) . '</h4>
                    <p class="search__result--desc">' . $desc . '…</p>
                    <div class="search__result--prices">
                        <span class="s__price--now">$' . number_format($p['d_price'], 2) . '</span>
                        <span class="s__price--was">$' . number_format($p['price'], 2) . '</span>
                    </div>
                </div>
            </a>';
        }
        echo '</div>';
        echo '<a href="shop.php?search=' . $enc . '" class="search__view--all">See all results for &ldquo;' . htmlspecialchars($search) . '&rdquo; &rarr;</a>';
        echo '</div>';
    } else {
        echo '
        <div class="search__no--results">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#C97F5F" stroke-width="1.5" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/><path d="M8 11h6M11 8v6"/></svg>
            <p>No results for &ldquo;<strong>' . htmlspecialchars($search) . '</strong>&rdquo;</p>
            <a href="shop.php">Browse all products</a>
        </div>';
    }
}
?>
