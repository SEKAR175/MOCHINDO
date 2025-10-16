let cart = JSON.parse(localStorage.getItem('cart')) || [];

function addToCart(name, price) {
    let item = cart.find(item => item.name === name);
    if (item) {
        item.quantity++;
    } else {
        cart.push({ name: name, price: price, quantity: 1 });
    }
    
    localStorage.setItem('cart', JSON.stringify(cart));
    alert(`${name} telah ditambahkan ke keranjang!`);
}

document.querySelectorAll('.add-to-cart-button').forEach(button => {
    button.addEventListener('click', (e) => {
        const productCard = e.target.closest('.product-card');
        const name = productCard.querySelector('h2').innerText;
        const price = parseInt(productCard.querySelector('.price').dataset.price);
        addToCart(name, price);
    });
});