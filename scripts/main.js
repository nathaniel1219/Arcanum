function openModal(index) {
  document.getElementById(`modal-${index}`).classList.remove('hidden');
}

function closeModal(index) {
  document.getElementById(`modal-${index}`).classList.add('hidden');
}

function showToast(message) {
  const toast = document.getElementById('toast');
  toast.textContent = message;
  toast.classList.remove('hidden');
  toast.classList.add('opacity-100');
  setTimeout(() => {
    toast.classList.add('hidden');
    toast.classList.remove('opacity-100');
  }, 3000);
}

function addToCart(productId) {
  fetch('add_to_cart.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: `product_id=${productId}`
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      showToast('✅ ' + data.message);
    } else {
      showToast('❌ ' + data.message);
    }
  })
  .catch(error => {
    showToast('❌ Failed to add to cart');
  });
}
