<?php
namespace App\Controllers;

use App\Models\ProductModel;

class ProductController extends BaseController
{
    // This runs when ADMIN adds a new product
    public function addProduct()
    {
        $model = new ProductModel();

        $data = [
            'category_id'    => $this->request->getPost('category_id'),
            'product_name'   => $this->request->getPost('product_name'),
            'description'    => $this->request->getPost('description'),
            'price'          => $this->request->getPost('price'),
            'image'          => $this->request->getPost('image'),
            'stock_quantity' => $this->request->getPost('stock_quantity'),
        ];

        $model->save($data); // saves it into the product table

        return 'Product added successfully!';
    }

    // This runs when a CUSTOMER wants to see all products
    public function viewProducts()
    {
        $model = new ProductModel();

        $products = $model->findAll(); // gets every product from the table

        // Just show it as plain text for now, so we can test it easily
        return json_encode($products);
    }
}