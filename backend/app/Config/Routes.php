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

$routes->post('update-product/(:num)', 'ProductController::updateProduct/$1');
$routes->get('delete-product/(:num)', 'ProductController::deleteProduct/$1');

$routes->post('add-category', 'CategoryController::addCategory');
$routes->get('categories', 'CategoryController::viewCategories');
$routes->post('update-category/(:num)', 'CategoryController::updateCategory/$1');
$routes->get('delete-category/(:num)', 'CategoryController::deleteCategory/$1');

$routes->post('update-promotion/(:num)', 'PromotionController::updatePromotion/$1');
$routes->get('delete-promotion/(:num)', 'PromotionController::deletePromotion/$1');

$routes->post('update-order-status/(:num)', 'OrderController::updateOrderStatus/$1');
$routes->get('all-orders', 'OrderController::viewAllOrders');

$routes->get('respond-inquiry/(:num)', 'InquiryController::respondInquiry/$1');

$routes->get('search-products', 'ProductController::searchProduct');

$routes->post('update-cart-item/(:num)', 'CartController::updateCartItem/$1');
$routes->get('remove-cart-item/(:num)', 'CartController::removeCartItem/$1');

$routes->post('admin-login', 'AuthController::adminLogin');