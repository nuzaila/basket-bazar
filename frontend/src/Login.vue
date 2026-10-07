<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const API = 'http://localhost:8080'
const router = useRouter()

const mode = ref('login')        // 'login' or 'register'
const showPw = ref(false)
const msg = ref('')

// login fields
const email = ref('')
const password = ref('')

// register fields
const name = ref('')
const phone = ref('')
const regEmail = ref('')
const regPassword = ref('')
const address = ref('')

async function login() {
  msg.value = ''
  if (!email.value || !password.value) { msg.value = 'Please enter your email and password.'; return }
  const f = new FormData()
  f.append('email', email.value)      // your backend expects the "email" field
  f.append('password', password.value)
  const r = await fetch(API + '/login', { method: 'POST', body: f })
  const text = await r.text()
  msg.value = text
  // go to Home unless the backend's message looks like an error
  if (r.ok && !/invalid|wrong|incorrect|fail|not found|error/i.test(text)) {
    setTimeout(() => router.push('/home'), 700)
  }
}

async function register() {
  msg.value = ''
  if (!name.value || !phone.value || !regEmail.value || !regPassword.value || !address.value) {
    msg.value = 'Please fill in all the fields.'
    return
  }
  const f = new FormData()
  f.append('name', name.value)
  f.append('phone', phone.value)
  f.append('email', regEmail.value)
  f.append('password', regPassword.value)
  f.append('delivery_address', address.value)
  const r = await fetch(API + '/register', { method: 'POST', body: f })
  msg.value = await r.text()
}

function switchMode(m) { mode.value = m; msg.value = '' }
</script>

<template>
  <div class="login">
    <!-- PASTE IMAGE: round Basket Bazaar logo  ->  public/images/logo.png -->
    <img class="logo" src="/images/logo.png" alt="Basket Bazaar" />

    <!-- ===== LOGIN ===== -->
    <template v-if="mode === 'login'">
      <h1>Welcome to Basket Bazaar</h1>
      <p class="sub">Please sign in to continue</p>

      <div class="field">
        <span class="ico">👤</span>
        <input v-model="email" placeholder="Email or Phone Number" />
      </div>
      <div class="field">
        <span class="ico">🔒</span>
        <input v-model="password" :type="showPw ? 'text' : 'password'" placeholder="Password" @keyup.enter="login" />
        <span class="eye" @click="showPw = !showPw">{{ showPw ? '🙈' : '👁' }}</span>
      </div>
      <div class="forgot">Forgot Password?</div>

      <button class="btn full" @click="login">Login</button>
      <div class="msg">{{ msg }}</div>
      <div class="or">OR</div>
      <button class="btn out full" @click="switchMode('register')">Create New Account</button>

      <div class="admin">Login as Admin? <router-link to="/admin">Click here</router-link></div>
    </template>

    <!-- ===== CREATE ACCOUNT ===== -->
    <template v-else>
      <h1>Create New Account</h1>
      <p class="sub">Join Basket Bazaar</p>

      <div class="field"><input v-model="name" placeholder="Full Name" /></div>
      <div class="field"><input v-model="phone" placeholder="Phone Number" /></div>
      <div class="field"><input v-model="regEmail" placeholder="Email" /></div>
      <div class="field"><input v-model="regPassword" type="password" placeholder="Password" /></div>
      <div class="field"><input v-model="address" placeholder="Delivery Address" /></div>

      <button class="btn full" @click="register">Create Account</button>
      <div class="msg">{{ msg }}</div>
      <div class="or">OR</div>
      <button class="btn out full" @click="switchMode('login')">Back to Login</button>
    </template>
  </div>
</template>

<style scoped>
.login { max-width:380px; margin:40px auto; text-align:center; padding:0 20px; font-family:'Inter',system-ui,sans-serif; color:#1f2937; }
.logo { width:100px; height:100px; border-radius:50%; object-fit:cover; }
h1 { font-size:1.5rem; margin:22px 0 6px; }
.sub { color:#6b7280; margin:0 0 28px; }
.field { position:relative; margin-bottom:14px; }
.field input { width:100%; box-sizing:border-box; padding:12px 40px; border:1px solid #888; border-radius:6px; font-size:.95rem; }
.ico { position:absolute; left:12px; top:11px; }
.eye { position:absolute; right:12px; top:11px; cursor:pointer; }
.forgot { text-align:right; font-size:.78rem; color:#3a7d0a; font-weight:600; margin:-4px 0 14px; }
.btn { background:#3a7d0a; color:#fff; border:2px solid #3a7d0a; border-radius:6px; padding:11px 18px; font-size:.95rem; font-weight:600; cursor:pointer; }
.btn.out { background:#fff; color:#3a7d0a; }
.btn.full { display:block; width:100%; }
.msg { font-size:.85rem; color:#3a7d0a; margin:10px 0; min-height:1em; }
.or { margin:10px 0; font-size:.85rem; }
.admin { margin-top:26px; font-size:.85rem; color:#6b7280; }
.admin a { color:#3a7d0a; font-weight:600; text-decoration:underline; }
</style>