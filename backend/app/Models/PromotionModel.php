<?php
namespace App\Models;
use CodeIgniter\Model;

// Connects to our "promotion" table
class PromotionModel extends Model
{
    protected $table = 'promotion';
    protected $primaryKey = 'promotion_id';
    protected $allowedFields = ['product_id', 'title', 'discount_percentage', 'start_date', 'end_date'];
}