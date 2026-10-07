<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const API = 'http://localhost:8080'
const router = useRouter()

// ================= SEARCH =================

const q = ref('')

function search() {
  router.push({
    path: '/category',
    query: { search: q.value }
  })
}

// ================= CONTACT FORM =================

const name = ref('')
const address = ref('')
const phone = ref('')
const message = ref('')

async function send() {
  if (!name.value || !phone.value || !message.value) {
    alert('Please enter your name, phone number and message.')
    return
  }

  const formData = new FormData()

  formData.append('name', name.value)
  formData.append(
    'contact_info',
    phone.value + ' | ' + address.value
  )
  formData.append('message', message.value)

  try {
    const response = await fetch(
      API + '/submit-inquiry',
      {
        method: 'POST',
        body: formData
      }
    )

    const result = await response.text()

    alert(result)

    name.value = ''
    address.value = ''
    phone.value = ''
    message.value = ''

  } catch (error) {
    console.error(error)
    alert('Unable to send your message. Please try again.')
  }
}
</script>


<template>

  <!-- ================= HEADER ================= -->

  <header class="top">

    <router-link class="brand" to="/">

      <!-- BASKET BAZAR LOGO -->
      <img
        src="/images/logo.png"
        alt="Basket Bazar Logo"
      />

      <span>Basket Bazaar</span>

    </router-link>


    <div class="icons">

      <!-- Login -->

      <router-link to="/login">

        <svg viewBox="0 0 24 24">
          <circle
            cx="12"
            cy="9"
            r="4"
          />

          <path
            d="M4 21c0-4 4-6 8-6s8 2 8 6"
          />

          <circle
            cx="12"
            cy="12"
            r="10"
          />
        </svg>

      </router-link>


      <!-- Cart -->

      <router-link to="/cart">

        <svg viewBox="0 0 24 24">

          <path
            d="M2 3h3l3 12h11l2-8H6"
          />

          <circle
            cx="9"
            cy="20"
            r="1.5"
          />

          <circle
            cx="18"
            cy="20"
            r="1.5"
          />

        </svg>

      </router-link>

    </div>

  </header>


  <!-- ================= SEARCH ================= -->

  <form
    class="search"
    @submit.prevent="search"
  >

    <input
      v-model="q"
      placeholder="Search Products"
    />

  </form>


  <!-- ================= NAVIGATION ================= -->

  <nav class="menu">

    <router-link to="/">
      Home
    </router-link>

    <router-link to="/category">
      Categories
    </router-link>

    <router-link
      :to="{ path: '/', hash: '#offers' }"
    >
      Offers
    </router-link>

    <router-link to="/about">
      About
    </router-link>

    <router-link
      to="/contact"
      class="on"
    >
      Contact us
    </router-link>

  </nav>


  <!-- ================= CONTACT PAGE ================= -->

  <main class="wrap">


    <!-- TITLE + IMAGE -->

    <div class="c-top">

      <h2>
        Contact us
      </h2>


      <!-- CONTACT IMAGE -->
      <img
        src="/images/contact-cart.jpg"
        alt="Shopping Cart"
      />

    </div>


    <!-- ================= MESSAGE FORM ================= -->

    <div class="form">

      <b>
        SEND US A MESSAGE
      </b>


      <input
        v-model="name"
        placeholder="Enter Your Name"
      />


      <input
        v-model="address"
        placeholder="Enter Your Address"
      />


      <input
        v-model="phone"
        placeholder="Enter Your Phone Number"
      />


      <textarea
        v-model="message"
        rows="4"
        placeholder="Type your message here....."
      ></textarea>


      <div class="send-area">

        <button
          type="button"
          class="btn sm"
          @click="send"
        >
          Send
        </button>

      </div>

    </div>


    <!-- ================= CONTACT INFO ================= -->

    <div class="info-box">

      <b>
        Contact Info
      </b>


      <div class="row">

        <span class="icon">
          📍
        </span>

        <span class="pill">
          6V82+VCQ BASKET BAZAAR, POTTUVIL ROAD, Akkaraipattu
        </span>

      </div>


      <div class="row">

        <span class="icon">
          🕒
        </span>

        <span class="pill">
          7am to 12am
        </span>

      </div>


      <div class="row">

        <span class="icon">
          📞
        </span>

        <span class="pill">
          0776651516
        </span>

      </div>


      <a
        class="map"
        href="https://www.google.com/maps/search/?api=1&query=6V82%2BVCQ+Akkaraipattu"
        target="_blank"
      >
        View on Google Maps
      </a>

    </div>

  </main>

</template>


<style scoped>

.top {
  display: flex;
  align-items: center;
  justify-content: space-between;

  padding: 10px 20px;

  max-width: 1000px;
  margin: 0 auto;
}


.brand {
  display: flex;
  align-items: center;

  gap: 12px;

  color: #3a7d0a;

  font-size: 1.25rem;
  font-weight: 500;

  text-decoration: none;
}


.brand img {
  width: 44px;
  height: 44px;

  border-radius: 50%;

  object-fit: cover;
}


.icons {
  display: flex;
  gap: 12px;
}


.icons svg {
  width: 26px;
  height: 26px;

  stroke: #1f2937;

  fill: none;

  stroke-width: 1.8;
}


.search {
  max-width: 960px;

  margin: 6px auto;

  padding: 0 20px;
}


.search input {
  width: 100%;

  box-sizing: border-box;

  padding: 13px 16px;

  border: 1px solid #d9d9d9;

  border-radius: 12px;

  font-size: 0.95rem;
}


.menu {
  display: flex;

  justify-content: space-between;

  max-width: 960px;

  margin: 8px auto 16px;

  padding: 0 20px;

  font-size: 0.9rem;
}


.menu a {
  color: #1f2937;

  text-decoration: none;

  padding-bottom: 3px;

  border-bottom: 2px solid transparent;
}


.menu a.on {
  color: #3a7d0a;

  border-color: #3a7d0a;
}


.wrap {
  max-width: 1000px;

  margin: 0 auto;

  padding: 0 20px 40px;
}


.c-top {
  display: flex;

  align-items: center;

  justify-content: center;

  gap: 30px;

  margin: 10px 0;
}


.c-top h2 {
  color: #3a7d0a;

  font-weight: 500;

  font-size: 1.5rem;
}


.c-top img {
  width: 200px;
  height: 90px;

  object-fit: cover;
}


.form {
  max-width: 520px;

  margin: 20px auto;
}


.form b {
  display: block;

  margin-bottom: 10px;
}


.form input,
.form textarea {
  display: block;

  width: 100%;

  box-sizing: border-box;

  margin-top: 8px;

  padding: 9px 10px;

  border: none;

  background: #e9e9e9;

  border-radius: 3px;

  font-family: inherit;
}


.form textarea {
  resize: vertical;
}


.send-area {
  text-align: right;
}


.btn {
  background: #3a7d0a;

  color: white;

  border: 2px solid #3a7d0a;

  border-radius: 6px;

  font-weight: 600;

  cursor: pointer;
}


.btn.sm {
  padding: 4px 18px;

  font-size: 0.8rem;

  margin-top: 8px;
}


.info-box {
  border: 1px solid #d9d9d9;

  border-radius: 6px;

  padding: 16px;

  max-width: 520px;

  margin: 20px auto;
}


.row {
  display: flex;

  align-items: center;

  gap: 14px;

  margin: 10px 0;
}


.icon {
  font-size: 18px;
}


.pill {
  background: #e4f2d6;

  border: 1px solid #888;

  border-radius: 4px;

  padding: 8px 12px;

  font-weight: 600;

  font-size: 0.85rem;
}


.map {
  display: inline-block;

  background: #e4f2d6;

  padding: 8px 16px;

  border-radius: 4px;

  font-weight: 600;

  font-size: 0.85rem;

  color: #1f2937;

  text-decoration: none;
}


@media (max-width: 600px) {

  .menu {
    gap: 12px;

    overflow-x: auto;

    justify-content: flex-start;
  }


  .c-top {
    flex-direction: column;

    gap: 10px;
  }


  .c-top img {
    width: 180px;
    height: 80px;
  }


  .row {
    align-items: flex-start;
  }


  .pill {
    flex: 1;
  }

}

</style>