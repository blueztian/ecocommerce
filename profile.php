<?php
require_once __DIR__ . '/bootstrap.php';

$pageTitle   = 'My Profile — EcoCommerce';
$currentPage = 'profile';
$cart        = new CartService();
$cartCount   = $cart->getCount();

$orderRepo = new OrderRepository();
$sid       = session_id();
$orders    = $orderRepo->getBySession($sid);
$summary   = $orderRepo->getSummaryForSession($sid);

// Handle basic profile save
$profileMsg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {
    $name  = trim(substr($_POST['name'] ?? '', 0, 100));
    $email = trim(substr($_POST['email'] ?? '', 0, 150));
    $phone = trim(substr($_POST['phone'] ?? '', 0, 30));

    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $profileMsg = 'error:Please enter a valid email address.';
    } else {
        $db   = getDB();
        $stmt = $db->prepare(
            'INSERT INTO customer_profiles (session_id, name, email, phone)
             VALUES (?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE name = VALUES(name), email = VALUES(email), phone = VALUES(phone)'
        );
        $stmt->execute([$sid, $name, $email, $phone]);
        $_SESSION['profile'] = ['name' => $name, 'email' => $email, 'phone' => $phone];
        $profileMsg = 'success:Profile saved successfully.';
    }
}

// Load saved profile
if (!isset($_SESSION['profile'])) {
    try {
        $db   = getDB();
        $stmt = $db->prepare('SELECT name, email, phone FROM customer_profiles WHERE session_id = ?');
        $stmt->execute([$sid]);
        $row = $stmt->fetch();
        $_SESSION['profile'] = $row ?: ['name' => '', 'email' => '', 'phone' => ''];
    } catch (PDOException) {
        $_SESSION['profile'] = ['name' => '', 'email' => '', 'phone' => ''];
    }
}
$profile = $_SESSION['profile'];

include 'includes/header.php';
include 'includes/navbar.php';
?>
<main style="margin-top:85px; min-height:60vh; padding:40px 15px;">
    <div class="container profile-wrap">
        <h1 class="h2 section-title" style="text-align:left; margin-bottom:30px;">
            <span class="span">My</span> Profile
        </h1>

        <div class="profile-grid">
            <!-- Left: Info -->
            <div class="profile-left">
                <div class="profile-card">
                    <div class="profile-avatar">
                        <ion-icon name="person-circle-outline" aria-hidden="true"></ion-icon>
                    </div>
                    <div class="profile-identity">
                        <div class="profile-name"><?= $profile['name'] !== '' ? e($profile['name']) : 'Guest Customer' ?></div>
                        <?php if ($profile['email']): ?>
                            <div class="profile-email"><?= e($profile['email']) ?></div>
                        <?php endif; ?>
                        <div class="profile-session">Session customer</div>
                    </div>
                </div>

                <!-- Stats -->
                <div class="profile-stats">
                    <div class="stat-card">
                        <div class="stat-num"><?= (int)$summary['total_orders'] ?></div>
                        <div class="stat-label">Orders</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-num"><?= (int)$summary['total_items'] ?></div>
                        <div class="stat-label">Items Purchased</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-num" style="font-size:1.8rem"><?= formatPrice((float)$summary['total_spent']) ?></div>
                        <div class="stat-label">Total Spent</div>
                    </div>
                </div>

                <!-- Edit Profile Form -->
                <div class="profile-card" style="margin-top:20px">
                    <h3 class="h3" style="margin-bottom:16px; font-size:2rem">Your Information</h3>
                    <?php if ($profileMsg !== ''): ?>
                        <?php [$type, $msg] = explode(':', $profileMsg, 2); ?>
                        <div class="cart-notice <?= $type === 'error' ? 'cart-error' : 'cart-success' ?>" style="margin-bottom:16px"><?= e($msg) ?></div>
                    <?php endif; ?>
                    <form method="POST" action="profile.php" class="profile-form">
                        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                        <div class="form-field">
                            <label for="pf-name">Full Name</label>
                            <input type="text" id="pf-name" name="name" value="<?= e($profile['name']) ?>" placeholder="Your name" maxlength="100">
                        </div>
                        <div class="form-field">
                            <label for="pf-email">Email Address</label>
                            <input type="email" id="pf-email" name="email" value="<?= e($profile['email']) ?>" placeholder="your@email.com" maxlength="150">
                        </div>
                        <div class="form-field">
                            <label for="pf-phone">Phone Number</label>
                            <input type="tel" id="pf-phone" name="phone" value="<?= e($profile['phone']) ?>" placeholder="+63 900 000 0000" maxlength="30">
                        </div>
                        <button type="submit" class="btn" style="width:100%; text-align:center; padding:12px">Save Profile</button>
                    </form>
                </div>
            </div>

            <!-- Right: Orders -->
            <div class="profile-right">
                <h2 class="h3" style="margin-bottom:20px">Order History</h2>
                <?php if (empty($orders)): ?>
                <div class="profile-empty">
                    <ion-icon name="bag-outline" aria-hidden="true"></ion-icon>
                    <p>You haven't placed any orders yet.</p>
                    <a href="index.php#shop" class="btn" style="margin-top:16px">Start Shopping</a>
                </div>
                <?php else: ?>
                <div class="order-history-list">
                    <?php foreach ($orders as $o): ?>
                    <div class="order-history-card">
                        <div class="order-history-header">
                            <div>
                                <span class="order-number">Order #<?= (int)$o['id'] ?></span>
                                <span class="order-date"><?= date('M j, Y', strtotime($o['created_at'])) ?></span>
                            </div>
                            <span class="order-status-badge status-<?= e($o['status']) ?>"><?= e(ucfirst($o['status'])) ?></span>
                        </div>
                        <div class="order-history-meta">
                            <span><?= (int)$o['total_qty'] ?> item<?= (int)$o['total_qty'] !== 1 ? 's' : '' ?></span>
                            <span class="order-history-total"><?= formatPrice((float)$o['total_amount']) ?></span>
                        </div>
                        <a href="order.php?id=<?= (int)$o['id'] ?>" class="btn btn-outline" style="font-size:1.3rem; padding:7px 16px">View Order</a>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>
<?php include 'includes/footer.php'; ?>
