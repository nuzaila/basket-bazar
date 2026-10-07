<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const q = ref('')
const search = () => router.push({ path: '/categories', query: { search: q.value } })
</script>

<template>
  <!-- ===== HEADER ===== -->
  <header class="top">
    <router-link class="brand" to="/home">
      <!-- PASTE IMAGE: logo  ->  public/images/logo.png -->
      <img src="/images/logo.png" alt="logo" />
      Basket Bazaar
    </router-link>
    <div class="icons">
      <router-link to="/login"><svg viewBox="0 0 24 24"><circle cx="12" cy="9" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/><circle cx="12" cy="12" r="10"/></svg></router-link>
      <router-link to="/cart"><svg viewBox="0 0 24 24"><path d="M2 3h3l3 12h11l2-8H6"/><circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/></svg></router-link>
    </div>
  </header>
  <form class="search" @submit.prevent="search"><input v-model="q" placeholder="Search Products" /></form>
  <nav class="menu">
    <router-link to="/home">Home</router-link>
    <router-link to="/categories">Categories</router-link>
    <router-link :to="{ path: '/home', hash: '#offers' }">Offers</router-link>
    <router-link to="/about" class="on">About</router-link>
    <router-link to="/contact">Contact us</router-link>
  </nav>

  <!-- ===== PAGE ===== -->
  <main class="wrap">
    <div class="abt-hero">
      Basket
      <!-- PASTE IMAGE: vegetable shelves photo (middle of green banner)  ->  public/images/about-shelves.jpg -->
      <img src="/images/about-shelves.jpg" alt="shelves" />
      Bazaar
    </div>
    <div class="tag">Trust in every basket</div>

    <div class="feat">
      <div><svg viewBox="0 0 24 24"><circle cx="12" cy="9" r="6"/><path d="M8 14l-2 8 6-3 6 3-2-8"/></svg>Quality Products</div>
      <div><svg viewBox="0 0 24 24"><path d="M3 3h8l10 10-8 8L3 11z"/><circle cx="8" cy="8" r="1.5"/></svg>Great Offers</div>
      <div><svg viewBox="0 0 24 24"><path d="M2 3h3l3 12h11l2-8H6"/><circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/></svg>Easy Shopping</div>
      <div><svg viewBox="0 0 24 24"><circle cx="9" cy="8" r="3"/><circle cx="17" cy="9" r="2.5"/><path d="M3 20c0-4 3-6 6-6s6 2 6 6M15 15c4 0 6 2 6 5"/></svg>Friendly Service</div>
    </div>

    <div class="story">
      <div>
        <h3>Our Story</h3>
        Basket Bazar is a local supermarket located in Akkaraipattu, Sri Lanka, providing groceries, fresh produce,
        beverages, household essentials, and personal care products to the local community.
      </div>
      <!-- PASTE IMAGE: inside of the store photo  ->  public/images/store-inside.jpg -->
      <img src="/images/store-inside.jpg" alt="store" />
    </div>

    <h3 class="offer-title">What we Offer?</h3>
    <div class="offer">
      <!-- PASTE IMAGE: heart-shaped fruits & veg  ->  public/images/offer-fresh.jpg -->
      <img src="/images/offer-fresh.jpg" alt="fresh" />
      <!-- PASTE IMAGE: groceries photo  ->  public/images/offer-grocery.jpg -->
      <img src="/images/offer-grocery.jpg" alt="grocery" />
      <!-- PASTE IMAGE: beverages photo  ->  public/images/offer-beverages.jpg -->
      <img src="/images/offer-beverages.jpg" alt="beverages" />
    </div>
    <div style="text-align:center"><router-link class="btn" to="/categories">SHOP NOW</router-link></div>
  </main>
</template>

<style scoped>
.top { display:flex; align-items:center; justify-content:space-between; padding:10px 20px; max-width:1000px; margin:0 auto; }
.brand { display:flex; align-items:center; gap:12px; color:#3a7d0a; font-size:1.25rem; font-weight:500; text-decoration:none; }
.brand img { width:44px; height:44px; border-radius:50%; object-fit:cover; }
.icons { display:flex; gap:12px; }
.icons svg { width:26px; height:26px; stroke:#1f2937; fill:none; stroke-width:1.8; }
.search { max-width:960px; margin:6px auto; padding:0 20px; }
.search input { width:100%; box-sizing:border-box; padding:13px 16px; border:1px solid #d9d9d9; border-radius:12px; font-size:.95rem; }
.menu { display:flex; justify-content:space-between; max-width:960px; margin:8px auto 16px; padding:0 20px; font-size:.9rem; }
.menu a { color:#1f2937; text-decoration:none; padding-bottom:3px; border-bottom:2px solid transparent; }
.menu a.on { color:#3a7d0a; border-color:#3a7d0a; }
.wrap { max-width:1000px; margin:0 auto; padding:0 20px 40px; }
.btn { background:#3a7d0a; color:#fff; border:2px solid #3a7d0a; border-radius:6px; padding:10px 18px; font-weight:600; text-decoration:none; display:inline-block; }
.abt-hero { background:#3a7d0a; border-radius:14px; padding:14px; display:flex; align-items:center; gap:16px; color:#fff; font-size:1.5rem; font-weight:500; }
.abt-hero img { flex:1; min-width:0; height:200px; object-fit:cover; border-radius:10px; }
.tag { background:#e4f2d6; color:#3a7d0a; text-align:center; font-size:1.3rem; border-radius:14px; padding:22px; margin:16px 0; }
.feat { display:flex; justify-content:space-around; margin:26px 0; font-size:.78rem; text-align:center; }
.feat svg { width:44px; height:44px; stroke:#3a7d0a; fill:none; stroke-width:1.6; display:block; margin:0 auto 4px; }
.story { display:flex; gap:16px; border:1px solid #d9d9d9; padding:16px; font-size:.82rem; }
.story img { width:140px; height:130px; object-fit:cover; }
.story h3 { color:#3a7d0a; margin:0 0 6px; }
.offer-title { text-align:center; margin:18px 0 8px; font-size:1rem; }
.offer { display:flex; justify-content:center; gap:20px; margin:12px 0 22px; }
.offer img { width:100px; height:70px; object-fit:cover; }
</style>