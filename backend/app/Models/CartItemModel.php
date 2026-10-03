<?php
namespace App\Models;
use CodeIgniter\Model;

// Connects to "cartitem" table - this stores WHICH products are in a cart
class CartItemModel extends Model
{
    protected $table = 'cartitem';
    protected $primaryKey = 'cart_item_id';
    protected $allowedFields = ['cart_id', 'product_id', 'quantity'];
}