<script setup>
import { ref } from 'vue'

const name = ref('')
const phone = ref('')
const email = ref('')
const password = ref('')
const address = ref('')
const message = ref('')

async function register() {
  const formData = new FormData()
  formData.append('name', name.value)
  formData.append('phone', phone.value)
  formData.append('email', email.value)
  formData.append('password', password.value)
  formData.append('delivery_address', address.value)

  const response = await fetch('http://localhost:8080/register', {
    method: 'POST',
    body: formData
  })
  message.value = await response.text()
}

const loginEmail = ref('')
const loginPassword = ref('')
const loginMessage = ref('')

async function login() {
  const formData = new FormData()
  formData.append('email', loginEmail.value)
  formData.append('password', loginPassword.value)

  const response = await fetch('http://localhost:8080/login', {
    method: 'POST',
    body: formData
  })
  loginMessage.value = await response.text()
}
</script>

<template>
  <h2>Register</h2>
  <input v-model="name" placeholder="Name"><br>
  <input v-model="phone" placeholder="Phone"><br>
  <input v-model="email" placeholder="Email"><br>
  <input v-model="password" type="password" placeholder="Password"><br>
  <input v-model="address" placeholder="Delivery Address"><br>
  <button @click="register">Register</button>
  <p>{{ message }}</p>

  <h2>Login</h2>
  <input v-model="loginEmail" placeholder="Email"><br>
  <input v-model="loginPassword" type="password" placeholder="Password"><br>
  <button @click="login">Login</button>
  <p>{{ loginMessage }}</p>
</template>