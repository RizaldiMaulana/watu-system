// ...existing code...
const POS_BASE = 'http://localhost/pos-kasir-php/pos-kasir-php-master/api';

async function loginPos(username, password) {
  const r = await fetch(`${POS_BASE}/login_token.php`, {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({username, password})
  });
  const j = await r.json();
  if (j.ok) localStorage.setItem('pos_token', j.token);
  return j;
}

async function fetchProducts() {
  const r = await fetch(`${POS_BASE}/products.php`);
  return r.json();
}

async function addToPos(productId, qty=1, memberId=0) {
  const token = localStorage.getItem('pos_token');
  const r = await fetch(`${POS_BASE}/add_sale.php`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': 'Bearer ' + token
    },
    body: JSON.stringify({product_id: productId, qty, member_id: memberId})
  });
  return r.json();
}