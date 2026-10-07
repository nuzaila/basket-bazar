<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const API = '/backend'

const route = useRoute()
const router = useRouter()

const product = ref(null)
const loading = ref(true)
const quantity = ref(1)
const errorMessage = ref('')


// This reads JSON and ignores the extra DebugBar data
async function getJSON(url) {
  const response = await fetch(url)
  const text = await response.text()

  const firstBracket = text.indexOf('[')

  if (firstBracket === -1) {
    throw new Error('No JSON data received')
  }

  const jsonText = text.substring(firstBracket)

  let depth = 0
  let insideString = false
  let escaped = false
  let endPosition = -1

  for (let i = 0; i < jsonText.length; i++) {
    const character = jsonText[i]

    if (insideString) {
      if (escaped) {
        escaped = false
      } else if (character === '\\') {
        escaped = true
      } else if (character === '"') {
        insideString = false
      }

      continue
    }

    if (character === '"') {
      insideString = true
      continue
    }

    if (character === '[') {
      depth++
    }

    if (character === ']') {
      depth--

      if (depth === 0) {
        endPosition = i + 1
        break
      }
    }
  }

  if (endPosition === -1) {
    throw new Error('Incomplete JSON response')
  }

  return JSON.parse(
    jsonText.substring(0, endPosition)
  )
}


// Load product
onMounted(async () => {
  try {
    const products = await getJSON(
      API + '/products'
    )

    const productId = route.query.id

    if (productId) {
      product.value = products.find(
        item =>
          String(item.product_id) ===
          String(productId)
      )
    }

    // If no product ID was given,
    // show the first product
    if (!product.value) {
      product.value = products[0]
    }

  } catch (error) {

    console.error(
      'PRODUCT DETAILS ERROR:',
      error
    )

    errorMessage.value =
      'Unable to load product.'

  } finally {

    loading.value = false
  }
})


function increaseQuantity() {
  quantity.value++
}


function decreaseQuantity() {
  if (quantity.value > 1) {
    quantity.value--
  }
}


// Add product to cart
async function addToCart() {

  if (!product.value) {
    return
  }

  try {

    const formData = new FormData()

    formData.append(
      'customer_id',
      '1'
    )

    formData.append(
      'product_id',
      product.value.product_id
    )

    formData.append(
      'quantity',
      quantity.value
    )

    await fetch(
      API + '/add-to-cart',
      {
        method: 'POST',
        body: formData
      }
    )

    alert('Product added to cart!')

  } catch (error) {

    console.error(
      'ADD TO CART ERROR:',
      error
    )

    alert(
      'Could not add product to cart.'
    )
  }
}


function goToCart() {
  router.push('/cart')
}


function goHome() {
  router.push('/')
}
</script>


<template>

  <!-- HEADER -->

  <header class="top">

    <router-link
      class="brand"
      to="/"
    >

      <!-- 🖼️ YOUR FIGMA BASKET BAZAR LOGO -->

      <img
        src="/images/logo.png"
        alt="Basket Bazar Logo"
      />

      <span>
        Basket Bazaar
      </span>

    </router-link>


    <div class="icons">

      <router-link to="/login">
        👤
      </router-link>

      <router-link to="/cart">
        🛒
      </router-link>

    </div>

  </header>


  <!-- SEARCH -->

  <form class="search">

    <input
      type="text"
      placeholder="Search Products"
    />

  </form>


  <!-- MENU -->

  <nav class="menu">

    <router-link to="/">
      Home
    </router-link>

    <router-link to="/category">
      Categories
    </router-link>

    <a href="#">
      Offers
    </a>

    <router-link to="/about">
      About
    </router-link>

    <router-link to="/contact">
      Contact us
    </router-link>

  </nav>


  <!-- MAIN -->

  <main class="page">

    <p
      v-if="loading"
      class="loading"
    >
      Loading product...
    </p>


    <p
      v-else-if="errorMessage"
      class="error"
    >
      {{ errorMessage }}
    </p>


    <div
      v-else-if="product"
      class="product-details"
    >

      <!-- PRODUCT IMAGE -->

      <div class="image-section">

        <!-- 🖼️ PRODUCT IMAGE
             Put your actual product image
             inside public/images/products/
        -->

        <img
          :src="
            '/images/products/' +
            product.product_name
              .toLowerCase()
              .replace(/[^a-z0-9]+/g, '-') +
            '.jpg'
          "
          :alt="product.product_name"
          class="product-image"
        />

      </div>


      <!-- PRODUCT INFORMATION -->

      <div class="information">

        <p class="category">
          {{ product.category_name || 'Fresh Product' }}
        </p>


        <h1>
          {{ product.product_name }}
        </h1>


        <p class="description">
          {{ product.description }}
        </p>


        <h2 class="price">
          Rs {{ product.price }}
        </h2>


        <p class="stock">
          In Stock
        </p>


        <!-- QUANTITY -->

        <div class="quantity-area">

          <span>
            Quantity
          </span>

          <div class="quantity-box">

            <button
              @click="decreaseQuantity"
            >
              −
            </button>

            <span>
              {{ quantity }}
            </span>

            <button
              @click="increaseQuantity"
            >
              +
            </button>

          </div>

        </div>


        <!-- BUTTONS -->

        <div class="buttons">

          <button
            class="cart-button"
            @click="addToCart"
          >
            Add to Cart
          </button>


          <button
            class="buy-button"
            @click="goToCart"
          >
            Buy Now
          </button>

        </div>


        <button
          class="back-button"
          @click="goHome"
        >
          ← Back to Home
        </button>

      </div>

    </div>


    <p
      v-else
      class="error"
    >
      Product not found.
    </p>

  </main>

</template>


<style scoped>

* {
  box-sizing: border-box;
}


/* HEADER */

.top {
  display: flex;
  align-items: center;
  justify-content: space-between;

  max-width: 1000px;

  margin: auto;

  padding: 12px 20px;
}


.brand {
  display: flex;
  align-items: center;

  gap: 10px;

  text-decoration: none;

  color: #3a7d0a;

  font-size: 20px;

  font-weight: 600;
}


.brand img {
  width: 45px;
  height: 45px;

  object-fit: contain;
}


.icons {
  display: flex;

  gap: 15px;
}


.icons a {
  text-decoration: none;

  font-size: 23px;
}


/* SEARCH */

.search {
  max-width: 960px;

  margin: 8px auto;

  padding: 0 20px;
}


.search input {
  width: 100%;

  padding: 13px;

  border: 1px solid #ddd;

  border-radius: 10px;

  outline: none;
}


/* MENU */

.menu {
  display: flex;

  justify-content: space-between;

  max-width: 960px;

  margin: 10px auto 30px;

  padding: 0 20px;
}


.menu a {
  text-decoration: none;

  color: #333;
}


.menu a:hover {
  color: #3a7d0a;
}


/* PAGE */

.page {
  max-width: 1000px;

  margin: auto;

  padding: 20px;
}


/* PRODUCT */

.product-details {
  display: grid;

  grid-template-columns:
    1fr 1fr;

  gap: 60px;

  align-items: center;
}


/* IMAGE */

.image-section {
  display: flex;

  justify-content: center;

  align-items: center;

  min-height: 400px;
}


.product-image {
  width: 100%;

  max-width: 420px;

  height: 400px;

  object-fit: contain;
}


/* INFORMATION */

.information {
  padding: 20px;
}


.category {
  color: #3a7d0a;

  font-size: 14px;

  font-weight: 600;

  margin-bottom: 10px;
}


.information h1 {
  font-size: 36px;

  margin: 10px 0;
}


.description {
  color: #666;

  line-height: 1.6;

  margin: 20px 0;
}


.price {
  color: #3a7d0a;

  font-size: 28px;

  margin: 20px 0;
}


.stock {
  color: #3a7d0a;

  font-weight: 600;
}


/* QUANTITY */

.quantity-area {
  display: flex;

  align-items: center;

  gap: 20px;

  margin: 25px 0;
}


.quantity-box {
  display: flex;

  align-items: center;

  border: 1px solid #ddd;

  border-radius: 6px;

  overflow: hidden;
}


.quantity-box button {
  border: none;

  background: white;

  width: 38px;

  height: 38px;

  font-size: 20px;

  cursor: pointer;
}


.quantity-box span {
  width: 40px;

  text-align: center;
}


/* BUTTONS */

.buttons {
  display: flex;

  gap: 12px;

  margin-top: 20px;
}


.cart-button,
.buy-button {
  border: none;

  padding: 13px 25px;

  border-radius: 6px;

  cursor: pointer;

  font-size: 15px;

  font-weight: 600;
}


.cart-button {
  background: white;

  color: #3a7d0a;

  border: 1px solid #3a7d0a;
}


.buy-button {
  background: #3a7d0a;

  color: white;
}


.cart-button:hover {
  background: #3a7d0a;

  color: white;
}


.buy-button:hover {
  background: #2f6508;
}


/* BACK */

.back-button {
  margin-top: 25px;

  background: none;

  border: none;

  color: #3a7d0a;

  cursor: pointer;

  font-size: 14px;
}


/* LOADING / ERROR */

.loading {
  text-align: center;

  padding: 60px;

  color: #777;
}


.error {
  text-align: center;

  padding: 60px;

  color: red;
}


/* MOBILE */

@media (max-width: 700px) {

  .product-details {
    grid-template-columns: 1fr;

    gap: 20px;
  }


  .product-image {
    height: 300px;
  }


  .information h1 {
    font-size: 28px;
  }


  .menu {
    gap: 15px;

    overflow-x: auto;
  }

}

</style>