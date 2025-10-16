let cart = JSON.parse(localStorage.getItem('cart')) || [];
const shippingFee = 10000;

function renderCart() {
    const cartItemsContainer = document.getElementById('cart-items');
    if (!cartItemsContainer) return; 

    cartItemsContainer.innerHTML = '';
    let subtotal = 0;

    cart.forEach(item => {
        const itemElement = document.createElement('div');
        itemElement.classList.add('cart-item');
        
        const itemTotal = item.price * item.quantity;
        subtotal += itemTotal;

    itemElement.innerHTML = `
        <div class="item-details">
            <h3>${item.name}</h3>
            <p>Harga: Rp${(item.price || 0).toLocaleString('id-ID')}</p>
            <div class="item-quantity">
                <input type="number" value="${item.quantity}" min="1" data-name="${item.name}" class="quantity-input">
            </div>
        </div>
        <div class="item-actions">
            <div class="item-total">Rp${itemTotal.toLocaleString('id-ID')}</div>
            <button class="delete-item-button" data-name="${item.name}">❌</button>
        </div>
    `;
        cartItemsContainer.appendChild(itemElement);
    });

    document.getElementById('subtotal').textContent = `Rp${subtotal.toLocaleString('id-ID')}`;
    document.getElementById('total').textContent = `Rp${(subtotal + shippingFee).toLocaleString('id-ID')}`;
    
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', (e) => {
            const name = e.target.dataset.name;
            const newQuantity = parseInt(e.target.value);
            
            let item = cart.find(item => item.name === name);
            if (item && newQuantity > 0) {
                item.quantity = newQuantity;
            } else if (newQuantity <= 0) {
                cart = cart.filter(item => item.name !== name);
            }
            localStorage.setItem('cart', JSON.stringify(cart));
            renderCart();
        });
    });

    document.querySelectorAll('.delete-item-button').forEach(button => {
        button.addEventListener('click', (e) => {
            const name = e.target.dataset.name;
            if (confirm(`Hapus "${name}" dari keranjang?`)) {
                cart = cart.filter(item => item.name !== name);
                localStorage.setItem('cart', JSON.stringify(cart));
                renderCart();
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', renderCart);

document.addEventListener('DOMContentLoaded', () => {
    const clearButton = document.getElementById('clear-cart-button');
    if (clearButton) {
        clearButton.addEventListener('click', () => {
            if (confirm("Apakah Anda yakin ingin menghapus semua item di keranjang?")) {
                cart = [];
                localStorage.removeItem('cart'); 
                renderCart(); 
            }
        });
    }

    const checkoutButton = document.getElementById('checkout-button');
    if (checkoutButton) { 
        checkoutButton.addEventListener('click', () => {
            if (cart.length > 0) {
                localStorage.setItem('cart', JSON.stringify(cart));
                window.location.href = 'payment.php';
            } else {
                alert('Keranjang Anda kosong. Silakan tambahkan produk terlebih dahulu.');
            }
        });
    }
});