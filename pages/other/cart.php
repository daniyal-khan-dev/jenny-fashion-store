<?php
session_start();
include_once __DIR__ . '/../../functions/userfunction.php';

// Auth check before any HTML
if (!isset($_SESSION['auth'])) {
    $_SESSION['message2'] = "Please login to continue";
    header("Location: /jenny/login");
    exit();
}

if (isset($_SESSION['user_role']) && $_SESSION['user_role'] != 0) {
    header("Location: /jenny/admin/dashboard");
    exit();
}

include_once __DIR__ . '/../includes/header.php';

$items     = getCartItems();
$cartItems = [];
$subtotal  = 0;
while ($row = mysqli_fetch_assoc($items)) {
    $row['line_total'] = (float)$row['d_price'] * (int)$row['prod_qty'];
    $subtotal         += $row['line_total'];
    $cartItems[]       = $row;
}
$userId = (int)$_SESSION['auth_user']['id'];
?>

<section class="cart__section section--padding">
    <div class="container">
        <div class="section__heading text-center mb-40">
            <h2 class="section__heading--maintitle">Shopping Cart</h2>
        </div>

        <?php if (count($cartItems) > 0): ?>
            <div class="row" id="mycart">
                <!-- Cart Items -->
                <div class="col-lg-8 mb-40">
                    <div class="mycart-primary">
                        <div class="mycart-header">
                            <span>Product</span>
                            <span style="text-align:center;">Price</span>
                            <span style="text-align:center;">Qty</span>
                            <span style="text-align:center;">Total</span>
                            <span></span>
                        </div>

                        <?php foreach ($cartItems as $citem): ?>
                            <div class="product_data">
                                <div class="product-img">
                                    <img src="admin/assets/img/products/<?= htmlspecialchars($citem['image']) ?>" alt="<?= htmlspecialchars($citem['name']) ?>">
                                    <div>
                                        <div class="product-name">
                                            <?= htmlspecialchars($citem['name']) ?>
                                        </div>
                                    </div>
                                </div>
                                <!-- Unit price -->
                                <div class="product-unt">
                                    <span class="cart__price" data-price="<?= (float)$citem['d_price'] ?>">$<?= number_format($citem['d_price'], 2) ?></span>
                                </div>
                                <!-- Qty control -->
                                <div class="product-qty">
                                    <div style="display:flex;align-items:center;border:2px solid rgba(201,127,95,.25);border-radius:50px;overflow:hidden;">
                                        <button class="decrement--btn" type="button" style="width:34px;height:34px;background:none;border:none;font-size:1.6rem;font-weight:700;color:#C97F5F;cursor:pointer;display:flex;align-items:center;justify-content:center;">−</button>
                                        <input type="hidden" id="remaingQty" class="remaingQty" value="<?= (int)$citem['itemQty'] ?>" >
                                        <input type="number" class="input-qty" value="<?= (int)$citem['prod_qty'] ?>" data-product-id="<?= (int)$citem['prod_id'] ?>" style="width:40px;border:none;text-align:center;font-family:'Inter',sans-serif;font-size:1.3rem;font-weight:700;background:transparent;padding:0;outline:none;">
                                        <button class="increment--btn" type="button" style="width:34px;height:34px;background:none;border:none;font-size:1.6rem;font-weight:700;color:#C97F5F;cursor:pointer;display:flex;align-items:center;justify-content:center;">+</button>
                                    </div>
                                </div>
                                <!-- Line total -->
                                <div class="product-ttl">
                                    <span id="total<?= (int)$citem['prod_id'] ?>">$<?= number_format($citem['line_total'], 2) ?></span>
                                </div>
                                <!-- Delete -->
                                <button class="deleteItem" type="button" data-cart-id="<?= (int)$citem['cid'] ?>">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M4.707 3.293L3.293 4.707 10.586 12l-7.293 7.293 1.414 1.414L12 13.414l7.293 7.293 1.414-1.414L13.414 12l7.293-7.293-1.414-1.414L12 10.586 4.707 3.293z" />
                                    </svg>
                                </button>
                            </div>
                        <?php endforeach; ?>

                        <!-- Footer row -->
                        <div class="mycart-footer">
                            <a href="<?= $routes['user']['shop'] ?>">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                </svg>
                                Continue Shopping
                            </a>
                            <button id="clearCartBtn" type="button">
                                Clear Cart
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-lg-4 mb-40">
                    <div class="cartOrderSummary">
                        <div class="summary-head">
                            <h3>Order Summary</h3>
                        </div>
                        <div class="summary-body">
                            <div class="sum-sub-head">
                                <span class="label">Subtotal (<?= count($cartItems) ?> item<?= count($cartItems) > 1 ? 's' : '' ?>)</span>
                                <span class="detail" id="cart-subtotal">$<?= number_format($subtotal, 2) ?></span>
                            </div>
                            <div class="sum-sub-head">
                                <span class="label">Shipping</span>
                                <span class="shipping">FREE</span>
                            </div>
                            <div class="sum-sub-head2">
                                <span class="span-1">Total</span>
                                <span class="span-2" id="cart-total">$<?= number_format($subtotal, 2) ?></span>
                            </div>
                            <a href="<?= $routes['user']['checkout'] ?>" class="btn-checkout">Proceed to Checkout</a>
                            <p>🔒 Secure checkout</p>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div style="text-align:center;padding:6rem 2rem;" id="mycart">
                <div style="width:90px;height:90px;background:linear-gradient(135deg,#FDF6F0,#EDD5C5);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 2rem;">
                    <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="#C97F5F" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <h3 style="font-family:'Playfair Display',serif;font-size:2.6rem;color:#3C3836;margin-bottom:1rem;">Your Cart is Empty</h3>
                <p style="font-family:'Inter',sans-serif;font-size:1.5rem;color:#9a8f8b;margin-bottom:2.5rem;">Looks like you haven't added anything yet.</p>
                <a href="<?= $routes['user']['shop'] ?>" class="primary__btn" style="text-decoration:none;">Browse Shop</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>

<?php if (count($cartItems) > 0): ?>
    <script> 
        // Cart scripts — placed after footer so jQuery + Swal are available
        (function() {
            var userId = <?= $userId ?>;

            function postData(url, data, callback) {
                var params = Object.keys(data).map(function(k) {
                    return encodeURIComponent(k) + '=' + encodeURIComponent(data[k]);
                }).join('&');
                var xhr = new XMLHttpRequest();
                xhr.open('POST', url, true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) callback(xhr.responseText.trim());
                };
                xhr.send(params);
            }

            function updateTotal(row) {
                var inp = row.querySelector('.input-qty');
                var price = parseFloat(row.querySelector('.cart__price').dataset.price);
                var qty = parseInt(inp.value, 10);
                var prodId = parseInt(inp.dataset.productId, 10);
                row.querySelector('#total' + prodId).textContent = '$' + (price * qty).toFixed(2);
                updateCartSummary();
                postData('functions/update-cart-quantity.php', {
                    user_id: userId,
                    prod_id: prodId,
                    qty: qty
                }, function() {});
            }

            function updateCartSummary() {
                var subtotal = 0;
            
                document.querySelectorAll('.product_data').forEach(function(row) {
                    var price = parseFloat(row.querySelector('.cart__price').dataset.price);
                    var qty = parseInt(row.querySelector('.input-qty').value, 10);
                    subtotal += price * qty;
                });
            
                document.getElementById('cart-subtotal').textContent = '$' + subtotal.toFixed(2);
                document.getElementById('cart-total').textContent = '$' + subtotal.toFixed(2);
            }
                
            document.querySelectorAll('.increment--btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var row = this.closest('.product_data');
                    var inp = row.querySelector('.input-qty');
                    var rmQty = row.querySelector('.remaingQty');
                
                    var v = parseInt(inp.value, 10) || 1;
                    var remaining = parseInt(rmQty.value);
                
                    // ✅ Check before increasing
                    if (v >= remaining) {
                        showAlert("error", "Oops! Product quantity exceeded!");
                        return;
                    }
                
                    if (v < 99) {
                        inp.value = v + 1;
                        updateTotal(row);
                    }
                });
            });

            document.querySelectorAll('.decrement--btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var row = this.closest('.product_data');
                    var inp = row.querySelector('.input-qty');
                    var v = parseInt(inp.value, 10) || 1;
                    if (v > 1) {
                        inp.value = v - 1;
                        updateTotal(row);
                    }
                });
            });

            document.getElementById('clearCartBtn').addEventListener('click', function() {
                Swal.fire({
                    title: 'Clear cart?',
                    text: 'All items will be removed.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#C97F5F',
                    cancelButtonColor: '#aaa',
                    confirmButtonText: 'Yes, clear it'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        postData('functions/clear-cart.php', {}, function(response) {
                            if (response === 'success') {
                                showAlert("success", "Cart Cleared!");

                                setTimeout(function() {
                                    location.reload();
                                }, 1100);
                            } else {
                                showAlert("error", "Oops! Something Went Wrong");
                            }
                        });
                    }
                });
            });

            document.querySelectorAll('.deleteItem').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var cartId = this.dataset.cartId;
                    postData('functions/delete-from-cart.php', {
                        cart_id: cartId
                    }, function(response) {
                        if (response === 'success') {
                            showAlert("success", "Item Removed From Cart!");
                            setTimeout(function() {
                                location.reload();
                            }, 1100);
                        } else {
                            showAlert("error", "Oops! Something went wrong.");
                        }
                    });
                });
            });
        })();
    </script>
<?php endif; ?>