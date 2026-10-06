<script setup>
import { ref } from 'vue'

const name = ref('')
const contactInfo = ref('')
const message = ref('')
const resultMessage = ref('')

async function submitInquiry() {
  const formData = new FormData()
  formData.append('name', name.value)
  formData.append('contact_info', contactInfo.value)
  formData.append('message', message.value)

  const response = await fetch('http://localhost:8080/submit-inquiry', {
    method: 'POST',
    body: formData
  })
  resultMessage.value = await response.text()
}
</script>

<template>
  <h2>Contact Us</h2>

  <h3>Send Us a Message</h3>
  <input v-model="name" placeholder="Enter Your Name"><br>
  <input v-model="contactInfo" placeholder="Enter Your Phone Number"><br>
  <textarea v-model="message" placeholder="Type your message here..."></textarea><br>
  <button @click="submitInquiry">Send</button>
  <p>{{ resultMessage }}</p>

  <h3>Contact Info</h3>
  <p>📍 Pottuvil Road, Akkaraipattu, Sri Lanka</p>
  <p>📞 077 665 1516</p>
</template>