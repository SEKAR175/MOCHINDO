document.addEventListener('DOMContentLoaded', () => {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const shippingFee = 10000;
    const itemsContainer = document.getElementById('payment-items-container');
    const subtotalEl = document.getElementById('payment-subtotal');
    const totalEl = document.getElementById('payment-total');

    let subtotal = 0;

    cart.forEach(item => {
        const itemTotal = item.price * item.quantity;
        subtotal += itemTotal;

        const itemDiv = document.createElement('div');
        itemDiv.classList.add('payment-item');
        itemDiv.innerHTML = `
            <span>${item.name} (x${item.quantity})</span>
            <span>Rp${itemTotal.toLocaleString('id-ID')}</span>
        `;
        itemsContainer.appendChild(itemDiv);
    });

    subtotalEl.textContent = `Rp${subtotal.toLocaleString('id-ID')}`;
    totalEl.textContent = `Rp${(subtotal + shippingFee).toLocaleString('id-ID')}`;


});