<?php
namespace App\Controllers;
use App\Models\CustomerModel;

class AuthController extends BaseController
{

    public function register()
    {
        $model = new CustomerModel(); 

        
        $data = [
            'name' => $this->request->getPost('name'),
            'phone' => $this->request->getPost('phone'),
            'email' => $this->request->getPost('email'),
            
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'delivery_address' => $this->request->getPost('delivery_address'),
        ];

        $model->save($data); 

        return 'Registration successful!'; 
    }


    public function login()
    {
        $model = new CustomerModel();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Look in the database for a customer with that email
        $customer = $model->where('email', $email)->first();

        // Check: does that customer exist AND does the password match?
        if ($customer && password_verify($password, $customer['password'])) {
            return 'Login successful! Welcome ' . $customer['name'];
        } else {
            return 'Invalid email or password.';
        }
    }
        // ADMIN logs in separately from customers
    public function adminLogin()
    {
        $model = new \App\Models\AdminModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $admin = $model->where('username', $username)->first();

        if ($admin && password_verify($password, $admin['password'])) {
            return 'Admin login successful! Welcome ' . $admin['username'];
        } else {
            return 'Invalid admin username or password.';
        }
    }
}