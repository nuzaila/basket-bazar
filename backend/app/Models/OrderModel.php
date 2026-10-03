<?php
namespace App\Models;
use CodeIgniter\Model;

// Connects to the "order" table
class OrderModel extends Model
{
    protected $table = 'order';
    protected $primaryKey = 'order_id';
    protected $allowedFields = ['customer_id', 'order_date', 'delivery_address', 'total_amount', 'status'];
}