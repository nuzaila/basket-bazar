<?php
namespace App\Models;
use CodeIgniter\Model;

// This connects to our "product" table
class ProductModel extends Model
{
    protected $table = 'product';
    protected $primaryKey = 'product_id';
    protected $allowedFields = ['category_id', 'product_name', 'description', 'price', 'image', 'stock_quantity'];
}