<?php
// $item must be set. Expected keys: cart_item_id, product_id, name, price, image, quantity
?>
<tr class="cart-item-row">
    <td class="cart-item-image">
        <img src="<?= e($item['image']) ?>" alt="<?= e($item['name']) ?>" width="80" height="80">
    </td>
    <td class="cart-item-name"><?= e($item['name']) ?></td>
    <td class="cart-item-price"><?= formatPrice((float)$item['price']) ?></td>
    <td class="cart-item-qty">
        <form class="qty-form" data-cart-item-id="<?= (int)$item['cart_item_id'] ?>">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <input type="number" name="quantity" value="<?= (int)$item['quantity'] ?>"
                   min="1" max="99" class="qty-input">
            <button type="submit" class="qty-update-btn">Update</button>
        </form>
    </td>
    <td class="cart-item-subtotal">
        <?= formatPrice((float)$item['price'] * (int)$item['quantity']) ?>
    </td>
    <td class="cart-item-remove">
        <form method="POST" action="actions/cart-remove.php">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="cart_item_id" value="<?= (int)$item['cart_item_id'] ?>">
            <button type="submit" class="remove-btn" title="Remove">
                <i class="ri-delete-bin-line"></i>
            </button>
        </form>
    </td>
</tr>
