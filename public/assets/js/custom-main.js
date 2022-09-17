$(document).ready(function () {
    
    renderCart();
});

function renderCart() {

    let sum = 0;
    if (!localStorage.getItem('cart') || localStorage.getItem('cart') == '[]') {
        let text = ` <h5 style="width: 100%">Няма добавени продукти</h5>`;
        $('#cart').html(text);
        $('#cartCount').html(0);
    }
    else {
        let item = JSON.parse(localStorage.getItem('cart'));

        let products = '';

        item.forEach(element => {
            sum += element.price * element.quantity;
            products += `
        <div class="product product-cart">
            <figure class="product-media">
                <a href="/singleProduct/${element.id}">
                    <img src="/assets/images/small/${element.imageId}.jpg" alt="product" width="80" height="88"/>
                </a>
                <button class="btn btn-link btn-close" onclick='removeFromCart(${element.id},${element.size})'>
                    <i class="fas fa-times"></i>
                    <span class="sr-only">Close</span>
                </button>
            </figure>
            <div class="product-detail">
                <a href="/singleProduct/${element.id}" class="product-name">${element.title}</a>
                <div class="price-box">
                    <span class="product-quantity">${element.size}(${element.quantity})</span>
                    <span class="product-price">${Math.round(element.price * 100) / 100} лв</span>
                </div>
            </div>
        </div>`;
        });
   

        let text = `
	    	<div class="products scrollable">
                ${products}
	    		<!-- End of Cart Product -->
	    	</div>
	    	<!-- End of Products  -->
	    	<div class="cart-total">
	    		<label>Общо:</label>
	    		<span class="price">${Math.round(sum * 100) / 100} лв</span>
	    	</div>
	    	<!-- End of Cart Total -->
	    	<div class="cart-action">
	    		<a href="/shoppingCart" class="btn btn-dark btn-link">Преглед на кошница</a>
	    		<a href="/checkout" class="btn btn-dark">
	    			<span>Към поръчка</span>
	    		</a>
	    	</div>
	    	<!-- End of Cart Action -->
        `;

        $('#cart').html(text);
        $('#cartCount').html(item.length);
    }
}

function removeFromCart(id, size) {
    let items = JSON.parse(localStorage.getItem('cart'));
    let index = items.findIndex(x => x.id == id && x.size == size);
    items.splice(index, 1);
    console.log(index);
    localStorage.setItem('cart', JSON.stringify(items));
    renderCart();
}