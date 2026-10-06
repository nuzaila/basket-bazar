<script setup>
import { ref } from 'vue'

const customerId = 1

const keyword = ref('')
const results = ref([])

async function search() {
  const response = await fetch(`http://localhost:8080/search-products?keyword=${keyword.value}`)
  results.value = await response.json()
}

const quantity = ref(1)

async function addToCart(productId) {
  const formData = new FormData()
  formData.append('customer_id', customerId)
  formData.append('product_id', productId)
  formData.append('quantity', quantity.value)

  const response = await fetch('http://localhost:8080/add-to-cart', {
    method: 'POST',
    body: formData
  })
  const result = await response.text()
  alert(result)
}
</script>

<template>
  <h2>Search Products</h2>
  <input v-model="keyword" placeholder="Search by name">
  <button @click="search">Search</button>

  <div v-for="product in results" :key="product.product_id">
    <h3>{{ product.product_name }}</h3>
    <p>Rs. {{ product.price }}</p>
    <p>{{ product.description }}</p>
    <p>Stock: {{ product.stock_quantity }}</p>

    <label>Quantity:</label>
    <input v-model="quantity" type="number" min="1">
    <button @click="addToCart(product.product_id)">Add to Cart</button>
  </div>
</template>