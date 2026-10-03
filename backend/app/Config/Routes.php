<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->post('register', 'AuthController::register');
$routes->post('login', 'AuthController::login');

$routes->post('add-product', 'ProductController::addProduct');
$routes->get('products', 'ProductController::viewProducts');

$routes->post('add-promotion', 'PromotionController::addPromotion');
$routes->get('promotions', 'PromotionController::viewPromotions');

$routes->post('add-to-cart', 'CartController::addToCart');
$routes->get('cart', 'CartController::viewCart');

$routes->post('place-order', 'OrderController::placeOrder');
$routes->get('orders', 'OrderController::viewOrders');

$routes->post('submit-inquiry', 'InquiryController::submitInquiry');
$routes->get('inquiries', 'InquiryController::viewInquiries');