<script setup>
import { ref, onMounted } from 'vue'

const products = ref([])
const customerId = 1 // hardcoded for now, same as before

async function loadProducts() {
  const response = await fetch('http://localhost:8080/products')
  products.value = await response.json()
}

onMounted(loadProducts)

async function addToCart(productId) {
  const formData = new FormData()
  formData.append('customer_id', customerId)
  formData.append('product_id', productId)
  formData.append('quantity', 1)

  const response = await fetch('http://localhost:8080/add-to-cart', {
    method: 'POST',
    body: formData
  })
  const result = await response.text()
  alert(result)
}
</script>

<template>
  <h2>Products</h2>
  <div v-for="product in products" :key="product.product_id">
    <h3>{{ product.product_name }}</h3>
    <p>Rs. {{ product.price }}</p>
    <button @click="addToCart(product.product_id)">Add to Cart</button>
  </div>
</template>