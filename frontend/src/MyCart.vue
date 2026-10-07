<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const API = 'http://localhost:8080'
const CUSTOMER_ID = 1
const DELIVERY_FEE = 100
const router = useRouter()
const q = ref('')
const items = ref([])
const fullName = ref('')
const phone = ref('')
const address = ref('')
const city = ref('Akkaraipattu')
let prods = []

const slug = (s) => (s || '').toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '')
const search = () => router.push({ path: '/categories', query: { search: q.value } })
const hideImg = (e) => { e.target.style.visibility = 'hidden' }

async function loadCart() {
  const raw = await (await fetch(API + '/cart?customer_id=' + CUSTOMER_ID)).json()
  // If your backend uses different field names, change them here:
  items.value = raw.map((c) => {
    const base = prods.find((p) => (p.product_id ?? p.id) == c.product_id) || {}
    return {
      cid: c.cart_item_id ?? c.cartitem_id ?? c.id,
      name: c.name ?? c.product_name ?? base.product_name ?? base.name ?? '',
      price: Number(c.price ?? base.price ?? 0),
      qty: Number(c.quantity ?? 1),
    }
  })
}

const subtotal = computed(() => items.value.reduce((s, i) => s + i.price * i.qty, 0))
const total = computed(() => (items.value.length ? subtotal.value + DELIVERY_FEE : 0))

async function changeQty(item, newQty) {
  if (newQty < 1) return
  const f = new FormData()
  f.append('quantity', newQty)
  await fetch(API + '/update-cart-item/' + item.cid, { method: 'POST', body: f })
  loadCart()
}

async function removeItem(item) {
  await fetch(API + '/remove-cart-item/' + item.cid)
  loadCart()
}

async function placeOrder() {
  if (!fullName.value || !phone.value || !address.value) return alert('Please fill in your name, phone and address.')
  const f = new FormData()
  f.append('customer_id', CUSTOMER_ID)
  f.append('delivery_address', `${fullName.value}, ${phone.value}, ${address.value}, ${city.value}`)
  const r = await fetch(API + '/place-order', { method: 'POST', body: f })
  sessionStorage.setItem('orderMsg', await r.text())
  router.push('/confirmation')
}

onMounted(async () => {
  prods = await (await fetch(API + '/products')).json()
  loadCart()
})
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
    <router-link to="/about">About</router-link>
    <router-link to="/contact">Contact us</router-link>
  </nav>

  <!-- ===== PAGE ===== -->
  <main class="wrap">
    <h2 class="title">My Cart</h2>
    <table class="cart">
      <thead><tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th></tr></thead>
      <tbody>
        <tr v-for="i in items" :key="i.cid">
          <td>
            <!-- PASTE IMAGES: same product photos ->  public/images/products/
                 apple.jpg, chocolate.jpg ... (file name = product name in lowercase) -->
            <img :src="'/images/products/' + slug(i.name) + '.jpg'" @error="hideImg" />{{ i.name }}
          </td>
          <td>Rs.{{ i.price }}</td>
          <td>
            <div class="qty">
              <button @click="changeQty(i, i.qty - 1)">-</button>
              <input :value="i.qty" readonly style="width:36px" />
              <button @click="changeQty(i, i.qty + 1)">+</button>
            </div>
          </td>
          <td>Rs.{{ i.price * i.qty }} <a href="#" title="Remove" @click.prevent="removeItem(i)">✕</a></td>
        </tr>
        <tr v-if="!items.length"><td colspan="4">Your cart is empty.</td></tr>
      </tbody>
      <tfoot><tr><td colspan="3">Subtotal</td><td>Rs.{{ subtotal }}</td></tr></tfoot>
    </table>

    <div class="form">
      <h3>Delivery Details</h3>
      <label>Full Name</label><input v-model="fullName" placeholder="Enter your name" />
      <label>Phone Number</label><input v-model="phone" placeholder="Enter phone number" />
      <label>Address</label><input v-model="address" placeholder="Enter Address" />
      <label>City</label>
      <select v-model="city">
        <option>Akkaraipattu</option><option>Addalaichenai</option><option>Palamunai</option>
      </select>
      <div class="note">We deliver to Akkaraipattu, Addalaichenai and Palamunai only.</div>
    </div>

    <div class="summary">
      <b>Order Summary</b>
      <div><span>Item({{ items.length }})</span><span>Rs.{{ subtotal }}</span></div>
      <div><span>Delivery Fee</span><span>Rs. {{ DELIVERY_FEE }}</span></div>
      <div class="tot"><span>Total</span><span>Rs.{{ total }}</span></div>
    </div>
    <div style="text-align:center"><button class="btn" @click="placeOrder">Place the Order</button></div>
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
.wrap { max-width:1000px; margin:0 auto; padding:0 20px 40px; }
.btn { background:#3a7d0a; color:#fff; border:2px solid #3a7d0a; border-radius:6px; padding:10px 34px; font-weight:600; cursor:pointer; }
.title { font-size:1.5rem; font-weight:500; margin:10px 0; }
.cart { width:100%; border:1px solid #444; border-radius:8px; border-collapse:separate; border-spacing:0; font-size:.9rem; }
.cart th, .cart td { padding:10px 12px; text-align:left; border-bottom:1px solid #ccc; }
.cart img { width:44px; height:44px; object-fit:contain; vertical-align:middle; margin-right:10px; }
.qty { display:inline-flex; align-items:center; gap:6px; }
.qty button { width:26px; height:26px; border:1px solid #d9d9d9; background:#fff; cursor:pointer; border-radius:3px; }
.qty input { height:26px; text-align:center; border:1px solid #d9d9d9; border-radius:3px; }
.form { max-width:420px; margin:20px auto; }
.form h3 { text-align:center; font-weight:500; font-size:.95rem; }
.form label { display:block; font-size:.8rem; margin:12px 0 4px; }
.form input, .form select { width:100%; box-sizing:border-box; padding:9px 10px; border:none; background:#e9e9e9; border-radius:3px; }
.note { font-size:.72rem; color:#6b7280; margin-top:6px; }
.summary { max-width:420px; margin:20px auto; border:1px solid #bbb; border-radius:6px; padding:12px 14px; font-size:.9rem; }
.summary div { display:flex; justify-content:space-between; margin:6px 0; }
.tot { border-top:1px solid #ccc; padding-top:8px; font-weight:700; }
.tot span:last-child { color:#3a7d0a; }
</style>