<?php
namespace App\Controllers;

use App\Models\CartModel;
use App\Models\CartItemModel;

class CartController extends BaseController
{
    // Adds a product to a customer's cart
    public function addToCart()
    {
        $cartModel = new CartModel();
        $cartItemModel = new CartItemModel();

        $customerId = $this->request->getPost('customer_id');
        $productId = $this->request->getPost('product_id');
        $quantity = $this->request->getPost('quantity');

        // Step 1: Check if this customer already has a cart
        $existingCart = $cartModel->where('customer_id', $customerId)->first();

        if ($existingCart) {
            // They already have a cart - use its ID
            $cartId = $existingCart['cart_id'];
        } else {
            // No cart yet - create one for them
            $cartId = $cartModel->insert(['customer_id' => $customerId]);
        }

        // Step 2: Add the product into that cart
        $cartItemModel->save([
            'cart_id' => $cartId,
            'product_id' => $productId,
            'quantity' => $quantity,
        ]);

        return 'Product added to cart!';
    }

    // Shows everything in a customer's cart
    public function viewCart()
    {
        $cartModel = new CartModel();
        $cartItemModel = new CartItemModel();

        $customerId = $this->request->getGet('customer_id');

        $cart = $cartModel->where('customer_id', $customerId)->first();

        if (!$cart) {
            return 'This customer has no cart yet.';
        }

        $items = $cartItemModel->where('cart_id', $cart['cart_id'])->findAll();

        return json_encode($items);
    }
}