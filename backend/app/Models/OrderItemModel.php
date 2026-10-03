<?php
namespace App\Models;
use CodeIgniter\Model;

// Connects to the "orderitem" table - stores which products are in an order
class OrderItemModel extends Model
{
    protected $table = 'orderitem';
    protected $primaryKey = 'order_item_id';
    protected $allowedFields = ['order_id', 'product_id', 'quantity', 'price'];
}