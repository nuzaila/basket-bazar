<script setup>
import { useRoute } from 'vue-router'
const route = useRoute()
</script>

<template>
  <h2>Thank You!</h2>
  <p>Your order has been placed successfully.</p>
  <p>{{ route.query.message }}</p>
</template>