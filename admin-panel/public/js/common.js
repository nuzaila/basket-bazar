// ====== Backend address (unchanged backend) ======
      const BASE_URL = '/api';

// If product pictures don't show, open /products in your browser, look at the "image" value,
// and change this to match where your backend serves its uploaded pictures.
      const IMAGE_BASE = '/api/uploads/';;

function productImage(name) {
  if (!name) return 'images/placeholder.png';
  if (name.startsWith('http')) return name;
  return IMAGE_BASE + name;
}

function formatDate(d) {
  if (!d) return '';
  const dt = new Date(d);
  if (isNaN(dt)) return d;
  return dt.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
}

function renderHeader(activeTab) {
  document.getElementById('header').innerHTML = `
    <div class="topbar">
      <button class="back" onclick="history.back()">‹</button>
      <!-- IMAGE: put your logo at images/logo.png -->
      <img src="images/logo.png" alt="logo">
      <span class="title">Basket Bazar</span>
    </div>
    <div class="searchrow">
      <button class="menu-btn" id="menuBtn">☰</button>
      <div class="searchbox">
        <input type="text" id="topSearch" placeholder="Search Product">
        🔍
      </div>
      <!-- IMAGE: images/user-icon.png -->
      <img src="images/user-icon.png" alt="user">
      <!-- IMAGE: images/cart-icon.png -->
      <img src="images/cart-icon.png" alt="cart">
      <div class="dropdown" id="dropdown">
        <a href="dashboard.html">Dashboard</a>
        <a href="products.html">Manage Products</a>
        <a href="offers.html">Manage Offers</a>
        <a href="login.html">Logout</a>
      </div>
    </div>
    <div class="nav">
      <a href="dashboard.html" class="${activeTab === 'home' ? 'active' : ''}">Home</a>
      <a href="products.html" class="${activeTab === 'products' ? 'active' : ''}">Categories</a>
      <a href="offers.html" class="${activeTab === 'offers' ? 'active' : ''}">Offers</a>
      <a href="#">About</a>
      <a href="#">Contact us</a>
    </div>`;

  document.getElementById('menuBtn').onclick = () =>
    document.getElementById('dropdown').classList.toggle('open');

  // Pressing Enter in the top search goes to Products page and searches
  document.getElementById('topSearch').addEventListener('keydown', e => {
    if (e.key === 'Enter') {
      location.href = 'products.html?search=' + encodeURIComponent(e.target.value);
    }
  });
}