import { createRouter, createWebHistory } from 'vue-router'

import Login from '../Login.vue'
import Home from '../Home.vue'
import Category from '../Category.vue'
import ProductDetails from '../ProductDetails.vue'
import MyCart from '../MyCart.vue'
import OrderConfirmation from '../OrderConfirmation.vue'
import About from '../About.vue'
import Contact from '../Contact.vue'

const routes = [
  { path: '/', component: Home },
  { path: '/login', component: Login },
  { path: '/category', component: Category },
  { path: '/product', component: ProductDetails },
  { path: '/cart', component: MyCart },
  { path: '/order-confirmation', component: OrderConfirmation },
  { path: '/about', component: About },
  { path: '/contact', component: Contact }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router