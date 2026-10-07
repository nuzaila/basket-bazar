<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const API = '/backend'

const route = useRoute()
const router = useRouter()

const categories = ref([])
const products = ref([])
const loading = ref(true)

const slug = (text) =>
  (text || '')
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/^-|-$/g, '')

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

onMounted(async () => {
  try {
    categories.value = await getJSON(
      API + '/categories'
    )

    const allProducts = await getJSON(
      API + '/products'
    )

    const selectedCategory =
      route.query.cat

    if (selectedCategory) {
      products.value = allProducts.filter(
        (product) =>
          String(product.category_id) ===
          String(selectedCategory)
      )
    } else {
      products.value = allProducts
    }

  } catch (error) {
    console.error(
      'CATEGORY ERROR:',
      error
    )
  } finally {
    loading.value = false
  }
})

function openProduct(product) {
  router.push({
    path: '/product',
    query: {
      id: product.product_id
    }
  })
}
</script>


<template>

  <header class="top">

    <router-link
      class="brand"
      to="/"
    >
      <img
        src="/images/logo.png"
        alt="Basket Bazar Logo"
      />

      <span>Basket Bazaar</span>
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


  <form class="search">

    <input
      placeholder="Search Products"
    />

  </form>


  <nav class="menu">

    <router-link to="/">
      Home
    </router-link>

    <router-link
      to="/category"
      class="active"
    >
      Categories
    </router-link>

    <a href="/">
      Offers
    </a>

    <router-link to="/about">
      About
    </router-link>

    <router-link to="/contact">
      Contact us
    </router-link>

  </nav>


  <main class="page">

    <h1>
      Categories
    </h1>


    <!-- CATEGORY BUTTONS -->

    <div class="category-list">

      <button
        v-for="category in categories"
        :key="category.category_id"
        @click="
          router.push({
            path: '/category',
            query: {
              cat: category.category_id
            }
          })
        "
      >

        {{ category.category_name }}

      </button>

    </div>


    <!-- LOADING -->

    <p v-if="loading">
      Loading products...
    </p>


    <!-- PRODUCTS -->

    <div
      v-else
      class="products"
    >

      <div
        v-for="product in products"
        :key="product.product_id"
        class="product-card"
        @click="openProduct(product)"
      >

        <img
          :src="
            '/images/products/' +
            slug(product.product_name) +
            '.jpg'
          "
          :alt="product.product_name"
        />

        <h3>
          {{ product.product_name }}
        </h3>

        <p class="description">
          {{ product.description }}
        </p>

        <p class="price">
          Rs {{ product.price }}
        </p>

        <button
          class="view-button"
          @click.stop="openProduct(product)"
        >
          View Product
        </button>

      </div>

    </div>

  </main>

</template>


<style scoped>

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
  object-fit: cover;
  border-radius: 50%;
}

.icons {
  display: flex;
  gap: 15px;
}

.icons a {
  text-decoration: none;
  font-size: 23px;
}

.search {
  max-width: 960px;
  margin: 8px auto;
  padding: 0 20px;
}

.search input {
  width: 100%;
  box-sizing: border-box;
  padding: 13px;
  border: 1px solid #ddd;
  border-radius: 10px;
}

.menu {
  display: flex;
  justify-content: space-between;
  max-width: 960px;
  margin: 10px auto 20px;
  padding: 0 20px;
}

.menu a {
  text-decoration: none;
  color: #333;
}

.menu .active {
  color: #3a7d0a;
  font-weight: 600;
}

.page {
  max-width: 1000px;
  margin: auto;
  padding: 0 20px 40px;
}

.page h1 {
  color: #3a7d0a;
}

.category-list {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin-bottom: 30px;
}

.category-list button {
  border: 1px solid #3a7d0a;
  background: white;
  color: #3a7d0a;
  padding: 9px 15px;
  border-radius: 6px;
  cursor: pointer;
}

.category-list button:hover {
  background: #3a7d0a;
  color: white;
}

.products {
  display: grid;
  grid-template-columns:
    repeat(auto-fill, minmax(190px, 1fr));
  gap: 25px;
}

.product-card {
  border: 1px solid #eee;
  border-radius: 10px;
  padding: 15px;
  text-align: center;
  cursor: pointer;
  background: white;
}

.product-card img {
  width: 100%;
  height: 160px;
  object-fit: contain;
}

.product-card h3 {
  margin: 8px 0;
}

.description {
  color: #777;
  font-size: 13px;
}

.price {
  color: #3a7d0a;
  font-weight: 600;
}

.view-button {
  background: #3a7d0a;
  color: white;
  border: none;
  border-radius: 5px;
  padding: 8px 15px;
  cursor: pointer;
}

</style>