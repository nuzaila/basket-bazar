<?php
namespace App\Controllers;

use App\Models\PromotionModel;

class PromotionController extends BaseController
{
    // ADMIN adds a new promotion
    public function addPromotion()
    {
        $model = new PromotionModel();

        $data = [
            'product_id'           => $this->request->getPost('product_id'),
            'title'                => $this->request->getPost('title'),
            'discount_percentage'  => $this->request->getPost('discount_percentage'),
            'start_date'           => $this->request->getPost('start_date'),
            'end_date'             => $this->request->getPost('end_date'),
        ];

        $model->save($data);

        return 'Promotion added successfully!';
    }

    // CUSTOMER views all current promotions
    public function viewPromotions()
    {
        $model = new PromotionModel();
        $promotions = $model->findAll();
        return json_encode($promotions);
    }
        // ADMIN edits/updates an existing promotion
    public function updatePromotion($id)
    {
        $model = new PromotionModel();

        $data = [
            'product_id'           => $this->request->getPost('product_id'),
            'title'                => $this->request->getPost('title'),
            'discount_percentage'  => $this->request->getPost('discount_percentage'),
            'start_date'           => $this->request->getPost('start_date'),
            'end_date'             => $this->request->getPost('end_date'),
        ];

        $model->update($id, $data);
        return 'Promotion updated successfully!';
    }

    // ADMIN deletes a promotion
    public function deletePromotion($id)
    {
        $model = new PromotionModel();
        $model->delete($id);
        return 'Promotion deleted successfully!';
    }
}