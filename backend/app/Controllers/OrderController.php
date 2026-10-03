<?php
namespace App\Controllers;

use App\Models\CartModel;
use App\Models\CartItemModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\ProductModel;

class OrderController extends BaseController
{
    // This runs when customer clicks "Place Order"
    public function placeOrder()
    {
        $cartModel = new CartModel();
        $cartItemModel = new CartItemModel();
        $orderModel = new OrderModel();
        $orderItemModel = new OrderItemModel();
        $productModel = new ProductModel();

        $customerId = $this->request->getPost('customer_id');
        $deliveryAddress = $this->request->getPost('delivery_address');

        // Step 1: Find this customer's cart
        $cart = $cartModel->where('customer_id', $customerId)->first();

        if (!$cart) {
            return 'No cart found for this customer.';
        }

        // Step 2: Get everything inside that cart
        $cartItems = $cartItemModel->where('cart_id', $cart['cart_id'])->findAll();

        if (empty($cartItems)) {
            return 'Cart is empty, cannot place order.';
        }

        // Step 3: Work out the total price by checking each product's price
        $total = 0;
        foreach ($cartItems as $item) {
            $product = $productModel->find($item['product_id']);
            $total += $product['price'] * $item['quantity'];
        }

        // Step 4: Create the actual Order record
        $orderId = $orderModel->insert([
            'customer_id' => $customerId,
            'order_date' => date('Y-m-d H:i:s'), // today's date and time
            'delivery_address' => $deliveryAddress,
            'total_amount' => $total,
            'status' => 'Pending', // every new order starts as "Pending"
        ]);

        // Step 5: Copy each cart item into OrderItem (so we have a permanent record)
        foreach ($cartItems as $item) {
            $product = $productModel->find($item['product_id']);
            $orderItemModel->save([
                'order_id' => $orderId,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $product['price'],
            ]);
        }

        // Step 6: Empty the cart now that the order is placed
        $cartItemModel->where('cart_id', $cart['cart_id'])->delete();

        return 'Order placed successfully! Order ID: ' . $orderId;
    }

    // Lets customer see their past orders
    public function viewOrders()
    {
        $orderModel = new OrderModel();
        $customerId = $this->request->getGet('customer_id');
        $orders = $orderModel->where('customer_id', $customerId)->findAll();
        return json_encode($orders);
    }
        // ADMIN updates an order's delivery status
    public function updateOrderStatus($id)
    {
        $orderModel = new OrderModel();

        $data = [
            'status' => $this->request->getPost('status'),
        ];

        $orderModel->update($id, $data);
        return 'Order status updated successfully!';
    }

    // ADMIN views ALL orders (not just one customer's)
    public function viewAllOrders()
    {
        $orderModel = new OrderModel();
        $orders = $orderModel->findAll();
        return json_encode($orders);
    }
}