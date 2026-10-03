<?php
namespace App\Models;
use CodeIgniter\Model;

// This connects to our "category" table (just like CustomerModel does for customers)
class CategoryModel extends Model
{
    protected $table = 'category';
    protected $primaryKey = 'category_id';
    protected $allowedFields = ['category_name'];
}