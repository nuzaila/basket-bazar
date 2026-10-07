<template>
  <nav>
    <router-link to="/">Home</router-link> |
    <router-link to="/login">Login</router-link> |
    <router-link to="/category">Category</router-link> |
    <router-link to="/product">Product Details</router-link> |
    <router-link to="/cart">My Cart</router-link> |
    <router-link to="/about">About</router-link> |
    <router-link to="/contact">Contact</router-link>
  </nav>

  <hr>

  <router-view />
</template>