<script setup>
defineProps(['orderMessage'])
</script>

<template>
  <h2>Thank You!</h2>
  <p>Your order has been placed successfully.</p>
  <p>{{ orderMessage }}</p>
</template>