<script setup>
import { ref, onMounted } from 'vue'

const customerId = 1
const cartItems = ref([])

async function loadCart() {
  const response = await fetch(`http://localhost:8080/cart?customer_id=${customerId}`)
  cartItems.value = await response.json()
}

onMounted(loadCart)

async function updateQuantity(cartItemId, newQuantity) {
  const formData = new FormData()
  formData.append('quantity', newQuantity)

  await fetch(`http://localhost:8080/update-cart-item/${cartItemId}`, {
    method: 'POST',
    body: formData
  })
  loadCart()
}

async function removeItem(cartItemId) {
  await fetch(`http://localhost:8080/remove-cart-item/${cartItemId}`)
  loadCart()
}

const fullName = ref('')
const phone = ref('')
const address = ref('')
const orderMessage = ref('')

async function placeOrder() {
  const formData = new FormData()
  formData.append('customer_id', customerId)
  formData.append('delivery_address', address.value)

  const response = await fetch('http://localhost:8080/place-order', {
    method: 'POST',
    body: formData
  })
  orderMessage.value = await response.text()
  loadCart() // cart should now be empty
}
</script>

<template>
  <h2>My Cart</h2>
  <div v-for="item in cartItems" :key="item.cart_item_id">
    Product ID: {{ item.product_id }}
    Quantity: 
    <input 
      type="number" 
      :value="item.quantity" 
      @change="updateQuantity(item.cart_item_id, $event.target.value)"
    >
    <button @click="removeItem(item.cart_item_id)">Remove</button>
  </div>

  <h2>Delivery Details</h2>
  <input v-model="fullName" placeholder="Full Name"><br>
  <input v-model="phone" placeholder="Phone Number"><br>
  <input v-model="address" placeholder="Address"><br>

  <button @click="placeOrder">Place the Order</button>
  <p>{{ orderMessage }}</p>
</template>