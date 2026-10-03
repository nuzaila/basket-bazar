<?php
namespace App\Controllers;

use App\Models\InquiryModel;

class InquiryController extends BaseController
{
    // Customer submits a question through the contact form
    public function submitInquiry()
    {
        $model = new InquiryModel();

        $data = [
            'name' => $this->request->getPost('name'),
            'contact_info' => $this->request->getPost('contact_info'),
            'message' => $this->request->getPost('message'),
            'status' => 'New', // every new inquiry starts as "New"
        ];

        $model->save($data);

        return 'Inquiry submitted successfully!';
    }

    // Admin views all inquiries
    public function viewInquiries()
    {
        $model = new InquiryModel();
        $inquiries = $model->findAll();
        return json_encode($inquiries);
    }
        // ADMIN responds to / resolves an inquiry
    public function respondInquiry($id)
    {
        $model = new InquiryModel();

        $data = [
            'status' => 'Resolved',
        ];

        $model->update($id, $data);
        return 'Inquiry marked as resolved!';
    }
}