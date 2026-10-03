<?php
namespace App\Models;
use CodeIgniter\Model;

// Connects to our "cart" table - this just links a cart to a customer
class CartModel extends Model
{
    protected $table = 'cart';
    protected $primaryKey = 'cart_id';
    protected $allowedFields = ['customer_id'];
}