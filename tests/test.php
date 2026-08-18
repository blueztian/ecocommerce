<?php
/**
 * Basic tests for EcoCommerce PHP logic.
 * Run from the project root: php tests/test.php
 * No external dependencies required.
 */

define('RUNNING_TESTS', true);

ob_start(); // Prevent headers already sent when switching sessions

// ---- Setup: unique session for test isolation ----
session_id('test-' . uniqid());

require_once __DIR__ . '/../bootstrap.php';

// Minimal test framework
$passed = 0;
$failed = 0;

function test(string $name, bool $condition): void {
    global $passed, $failed;
    if ($condition) {
        echo "\e[32m✔ {$name}\e[0m\n";
        $passed++;
    } else {
        echo "\e[31m✘ {$name}\e[0m\n";
        $failed++;
    }
}

// Ensure CSRF token is set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// ---- Product tests ----
$repo = new ProductRepository();
$allProducts = $repo->getAll();

test('1. Product retrieval returns array',   is_array($allProducts));
test('2. At least one product in DB',       count($allProducts) > 0);

$firstProduct = $allProducts[0] ?? null;
if ($firstProduct) {
    $byId = $repo->getById((int)$firstProduct['id']);
    test('3. getById returns correct product', $byId && $byId['id'] === $firstProduct['id']);
}

$missing = $repo->getById(999999);
test('4. getById returns false for missing product', $missing === false);

// ---- Cart tests ----
$cart = new CartService();

// Clear any stale data for this test session
$cart->clearCart();
test('5. Cart starts empty', $cart->getCount() === 0);

if ($firstProduct) {
    $pid = (int)$firstProduct['id'];

    // Add to cart
    $added = $cart->addItem($pid, 1);
    test('6. addItem returns true for valid product', $added);
    test('7. Cart count is 1 after adding one item', $cart->getCount() === 1);

    // Add same product again — should increment
    $cart->addItem($pid, 1);
    $items = $cart->getItems();
    $qty = 0;
    foreach ($items as $item) {
        if ((int)$item['product_id'] === $pid) $qty = (int)$item['quantity'];
    }
    test('8. Adding same product increments quantity to 2', $qty === 2);

    // Cart total matches expected
    $expectedTotal = (float)$firstProduct['price'] * 2;
    $actualTotal   = $cart->getTotal();
    test('9. Cart total calculated server-side correctly', abs($actualTotal - $expectedTotal) < 0.01);

    // Update quantity
    $cartItemId = (int)($items[0]['cart_item_id'] ?? 0);
    if ($cartItemId) {
        $updated = $cart->updateItem($cartItemId, 5);
        test('10. updateItem returns true', $updated);
        test('11. Cart count reflects updated qty', $cart->getCount() === 5);

        // Remove item
        $removed = $cart->removeItem($cartItemId);
        test('12. removeItem returns true', $removed);
        test('13. Cart is empty after removal', $cart->getCount() === 0);
    }

    // Add item back for order test
    $cart->addItem($pid, 1);
}

// ---- Invalid product add ----
$addInvalid = $cart->addItem(999999, 1);
test('14. addItem returns false for invalid product', $addInvalid === false);

// ---- Order tests ----
// Ensure cart has something
if ($cart->getCount() === 0 && $firstProduct) {
    $cart->addItem((int)$firstProduct['id'], 1);
}

// Empty-cart order rejection: use a fresh session
session_write_close();
session_id('test-empty-' . uniqid());
session_start();
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

$emptyOrderService = new OrderService();
$emptyResult = $emptyOrderService->placeOrder();
test('15. Placing order with empty cart returns false', $emptyResult === false);

// Restore test session and place real order
session_write_close();
session_id('test-' . uniqid());
session_start();
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

$cart2 = new CartService();
if ($firstProduct) {
    $cart2->addItem((int)$firstProduct['id'], 2);
}

$orderService = new OrderService();
$orderId = $orderService->placeOrder();
test('16. Successful order placement returns order ID',  is_int($orderId) && $orderId > 0);
test('17. Cart cleared after successful order', $cart2->getCount() === 0);

if ($orderId) {
    $order = $orderService->getOrderById($orderId);
    test('18. Order record exists in DB', $order !== false);
    test('19. Order status is "placed"', $order['status'] === 'placed');

    $orderItems = $orderService->getOrderItems($orderId);
    test('20. Order has line items', count($orderItems) > 0);
}

// ---- Clear cart test ----
$cart->addItem((int)($firstProduct['id'] ?? 1), 1);
$cart->clearCart();
test('21. clearCart empties the session cart', $cart->getCount() === 0);

// ---- Search tests ----
$searchRepo = new ProductRepository();

$searchResults = $searchRepo->search('bamboo');
test('22. search() returns array',         is_array($searchResults));
test('23. search("bamboo") finds results', count($searchResults) > 0);

$emptySearch = $searchRepo->search('xyzzy_notaproduct_12345');
test('24. search() returns empty for no match', count($emptySearch) === 0);

// Case-insensitive: should match same as lowercase
$upperResults = $searchRepo->search('BAMBOO');
test('25. search() is case-insensitive', count($upperResults) > 0);

// ---- OrderRepository tests ----
require_once __DIR__ . '/../repositories/OrderRepository.php';

// Place an order in main test session to work with
session_write_close();
$testSid = 'test-ord-' . uniqid();
session_id($testSid);
session_start();
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

$cart3 = new CartService();
if ($firstProduct) {
    $cart3->addItem((int)$firstProduct['id'], 1);
}
$orderSvc2 = new OrderService();
$testOrderId = $orderSvc2->placeOrder();

$orderRepo = new OrderRepository();

// History returns current session orders
$history = $orderRepo->getBySession($testSid);
test('26. getBySession returns orders for current session', count($history) > 0);

// History does NOT return other session's orders
$otherHistory = $orderRepo->getBySession('completely_different_session_99999');
test('27. getBySession isolates by session_id', count($otherHistory) === 0);

// Detail accessible from correct session
if ($testOrderId) {
    $detail = $orderRepo->getByIdForSession($testOrderId, $testSid);
    test('28. getByIdForSession returns order for correct session', $detail !== false);

    // Detail NOT accessible from wrong session
    $denied = $orderRepo->getByIdForSession($testOrderId, 'wrong_session_id');
    test('29. getByIdForSession denies wrong session', $denied === false);

    // Items populated
    $items2 = $orderRepo->getItems($testOrderId);
    test('30. getItems returns line items for order', count($items2) > 0);
}

// Summary totals
$summary = $orderRepo->getSummaryForSession($testSid);
test('31. getSummaryForSession returns total_orders >= 1',   (int)$summary['total_orders'] >= 1);
test('32. getSummaryForSession returns total_items >= 1',    (int)$summary['total_items'] >= 1);
test('33. getSummaryForSession returns total_spent > 0',     (float)$summary['total_spent'] > 0);

// ---- Wishlist tests ----
$wishlistSvc = new WishlistService();
test('34. Wishlist starts empty', $wishlistSvc->getCount() === 0);

$prodId = $firstProduct ? (int)$firstProduct['id'] : 1;
$wishlistSvc->add($prodId);
test('35. Wishlist add works', $wishlistSvc->getCount() === 1);
test('36. Wishlist contains product', $wishlistSvc->contains($prodId) === true);

$wishlistSvc->add($prodId);
test('37. Wishlist duplicate prevention works', $wishlistSvc->getCount() === 1);

$wishlistSvc->remove($prodId);
test('38. Wishlist remove works', $wishlistSvc->getCount() === 0);
test('39. Wishlist no longer contains product', $wishlistSvc->contains($prodId) === false);

$wishlistSvc->add(0);
$wishlistSvc->add(-1);
test('40. Invalid wishlist product prevented', $wishlistSvc->getCount() === 0);

// ---- Summary ----
ob_end_flush();
echo "\n";
echo "Passed: {$passed} | Failed: {$failed}\n";
exit($failed > 0 ? 1 : 0);

