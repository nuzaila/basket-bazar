<?php
namespace App\Models;
use CodeIgniter\Model;

// Connects to the "inquiry" table
class InquiryModel extends Model
{
    protected $table = 'inquiry';
    protected $primaryKey = 'inquiry_id';
    protected $allowedFields = ['name', 'contact_info', 'message', 'status', 'admin_id'];
}