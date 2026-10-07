<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const API = '/backend'
const router = useRouter()

const q = ref('')
const cats = ref([])
const offers = ref([])

const slug = (s) =>
  (s || '')
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/^-|-$/g, '')

const fixProduct = (p) => ({
  id: p.product_id ?? p.id,
  name: p.product_name ?? p.name ?? '',
  price: Number(p.price ?? 0),
  discount: 0,
  old: 0
})

const fixCat = (c) => ({
  id: c.category_id ?? c.id,
  name: c.category_name ?? c.name ?? ''
})

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

function search() {
  router.push({
    path: '/category',
    query: {
      search: q.value
    }
  })
}

onMounted(async () => {
  try {
    const categoryData = await getJSON(
      API + '/categories'
    )

    cats.value = categoryData.map(fixCat)

    const products = await getJSON(
      API + '/products'
    )

    let promotions = []

    try {
      promotions = await getJSON(
        API + '/promotions'
      )
    } catch (error) {
      console.log('Promotions error:', error)
    }

    const discountMap = {}

    promotions.forEach((promotion) => {
      discountMap[promotion.product_id] =
        Number(
          promotion.discount_percentage ??
          promotion.discount ??
          0
        )
    })

    offers.value = products
      .map(fixProduct)
      .map((product) => {
        const discount =
          discountMap[product.id]

        if (discount) {
          product.old = product.price

          product.price = Math.round(
            product.price *
            (1 - discount / 100)
          )

          product.discount = discount
        }

        return product
      })
      .filter(
        (product) => product.discount > 0
      )

  } catch (error) {
    console.error(
      'HOME ERROR:',
      error
    )
  }
})

async function addToCart(id) {
  try {
    const formData = new FormData()

    formData.append('customer_id', 1)
    formData.append('product_id', id)
    formData.append('quantity', 1)

    const response = await fetch(
      API + '/add-to-cart',
      {
        method: 'POST',
        body: formData
      }
    )

    alert(await response.text())

  } catch (error) {
    console.error(
      'Add to cart error:',
      error
    )

    alert('Could not add product to cart.')
  }
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


  <form
    class="search"
    @submit.prevent="search"
  >
    <input
      v-model="q"
      placeholder="Search Products"
    />
  </form>


  <nav class="menu">

    <router-link
      to="/"
      class="on"
    >
      Home
    </router-link>

    <router-link to="/category">
      Categories
    </router-link>

    <a href="#offers">
      Offers
    </a>

    <router-link to="/about">
      About
    </router-link>

    <router-link to="/contact">
      Contact us
    </router-link>

  </nav>


  <main class="wrap">

    <section class="hero">

      <div>

        <h1>
          Fresh groceries delivered
          to your door
        </h1>

        <p>
          Akkaraipattu, Addalaichenai, Palamunai
        </p>

        <router-link
          class="btn white"
          to="/category"
        >
          Shop now
        </router-link>

      </div>

      <img
        src="/images/banner.png"
        alt="Basket Bazar Banner"
      />

    </section>


    <div class="dots">
      <i></i>
      <i></i>
      <i></i>
    </div>


    <h2 class="sec">
      Shop By Category
    </h2>

    <div class="cats">

      <div
        v-for="c in cats"
        :key="c.id"
        class="cat"
        @click="
          router.push({
            path: '/category',
            query: { cat: c.id }
          })
        "
      >

        <img
          :src="
            '/images/categories/' +
            slug(c.name) +
            '.png'
          "
          :alt="c.name"
        />

        <span>
          {{ c.name }}
        </span>

      </div>


      <div
        class="cat"
        @click="router.push('/category')"
      >

        <img
          src="/images/categories/more.png"
          alt="More"
        />

        <span>More</span>

      </div>

    </div>


    <h2
      id="offers"
      class="sec"
    >
      Special Offers
    </h2>


    <div class="grid">

      <div
        v-for="p in offers"
        :key="p.id"
        class="card"
        @click="
          router.push({
            path: '/product',
            query: { id: p.id }
          })
        "
      >

        <span class="badge">
          {{ p.discount }}%
        </span>

        <img
          :src="
            '/images/products/' +
            slug(p.name) +
            '.jpg'
          "
          :alt="p.name"
        />

        <h4>
          {{ p.name }}
        </h4>

        <div class="price">
          Rs {{ p.price }}
          <s>Rs {{ p.old }}</s>
        </div>

        <button
          class="btn sm"
          @click.stop="addToCart(p.id)"
        >
          Add Cart
        </button>

      </div>

    </div>


    <p
      v-if="!offers.length"
      class="no-offers"
    >
      No offers right now.
    </p>

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

.btn {
  background: #3a7d0a;
  color: #fff;
  border: 2px solid #3a7d0a;
  border-radius: 6px;
  padding: 10px 18px;
  font-weight: 600;
  cursor: pointer;
  display: inline-block;
  text-decoration: none;
}

.btn.white {
  background: #fff;
  color: #3a7d0a;
}

.btn.sm {
  padding: 3px 14px;
  font-size: 0.78rem;
  border-radius: 4px;
}

.hero {
  background: #3a7d0a;
  color: #fff;
  border-radius: 14px;
  padding: 28px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 20px;
}

.hero h1 {
  font-size: 1.9rem;
  font-weight: 500;
  max-width: 340px;
  margin: 0;
}

.hero p {
  font-size: 0.85rem;
  margin: 8px 0 16px;
}

.hero img {
  width: 260px;
  max-width: 45%;
  height: 170px;
  object-fit: cover;
  border-radius: 8px;
}

.dots {
  display: flex;
  gap: 10px;
  justify-content: center;
  margin: 14px 0;
}

.dots i {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background: #ddd;
}

.dots i:first-child {
  background: #3a7d0a;
}

.sec {
  font-size: 1.15rem;
  font-weight: 600;
  margin: 22px 0 12px;
}

.cats {
  display: flex;
  flex-wrap: wrap;
  gap: 22px;
}

.cat {
  width: 100px;
  text-align: center;
  font-size: 0.78rem;
  cursor: pointer;
}

.cat img {
  width: 80px;
  height: 80px;
  object-fit: cover;
  display: block;
  margin: 0 auto 4px;
}

.grid {
  display: grid;
  grid-template-columns:
    repeat(auto-fill, minmax(180px, 1fr));
  gap: 22px;
}

.card {
  position: relative;
  text-align: center;
  cursor: pointer;
}

.card img {
  width: 100%;
  height: 150px;
  object-fit: contain;
}

.card h4 {
  font-weight: 500;
  font-size: 0.95rem;
  margin: 6px 0 0;
}

.price {
  color: #3a7d0a;
  font-size: 0.9rem;
  margin: 2px 0 6px;
}

.price s {
  color: #999;
  font-size: 0.75rem;
}

.badge {
  position: absolute;
  top: 4px;
  left: 4px;
  background: #f57c00;
  color: #fff;
  font-size: 0.72rem;
  padding: 2px 7px;
  border-radius: 3px;
}

.no-offers {
  text-align: center;
  color: #777;
}

</style>