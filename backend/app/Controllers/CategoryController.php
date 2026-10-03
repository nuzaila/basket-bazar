<?php
namespace App\Controllers;

use App\Models\CategoryModel;

class CategoryController extends BaseController
{
    // ADMIN adds a new category
    public function addCategory()
    {
        $model = new CategoryModel();
        $data = ['category_name' => $this->request->getPost('category_name')];
        $model->save($data);
        return 'Category added successfully!';
    }

    // CUSTOMER/ADMIN views all categories
    public function viewCategories()
    {
        $model = new CategoryModel();
        $categories = $model->findAll();
        return json_encode($categories);
    }

    // ADMIN edits/updates an existing category
    public function updateCategory($id)
    {
        $model = new CategoryModel();
        $data = ['category_name' => $this->request->getPost('category_name')];
        $model->update($id, $data);
        return 'Category updated successfully!';
    }

    // ADMIN deletes a category
    public function deleteCategory($id)
    {
        $model = new CategoryModel();
        try {
            $model->delete($id);
            return 'Category deleted successfully!';
        } catch (\Exception $e) {
            return 'Cannot delete this category - it is linked to existing products.';
        }
    }
}