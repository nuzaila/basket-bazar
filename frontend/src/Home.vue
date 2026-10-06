<script setup>
import { ref, onMounted } from 'vue'

const products = ref([])
const categories = ref([])
const promotions = ref([])

onMounted(async () => {
  const productsResponse = await fetch('http://localhost:8080/products')
  products.value = await productsResponse.json()

  const categoriesResponse = await fetch('http://localhost:8080/categories')
  categories.value = await categoriesResponse.json()

  const promotionsResponse = await fetch('http://localhost:8080/promotions')
  promotions.value = await promotionsResponse.json()
})
</script>

<template>
  <h1>Welcome to Basket Bazar</h1>

  <h2>Shop by Category</h2>
  <div v-for="cat in categories" :key="cat.category_id">
    <button>{{ cat.category_name }}</button>
  </div>

  <h2>Special Offers</h2>
  <div v-for="promo in promotions" :key="promo.promotion_id">
    <p>{{ promo.title }} - {{ promo.discount_percentage }}% off</p>
  </div>

  <h2>All Products</h2>
  <div v-for="product in products" :key="product.product_id">
    <h3>{{ product.product_name }}</h3>
    <p>Rs. {{ product.price }}</p>
  </div>
</template>