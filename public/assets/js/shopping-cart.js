$(document).ready(function () {

    class Product {
        constructor(id, imageId, size, quantity, price, title) {
            this.id = id;
            this.imageId = imageId;
            this.size = size;
            this.quantity = quantity;
            this.price = price;
            this.title = title;
        }
    }

    renderCartCustom();
});

function renderCartCustom() {
    console.log(localStorage.getItem('cart'));
    if (!localStorage.getItem('cart') || localStorage.getItem('cart') == '[]') {
        let text = ` <h5 style="width: 100%">Няма добавени продукти</h5>`;
        $('#content').html(text);
        $('#total').html('');
        $('#totalTwo').html('');
        $('#buttonOrder').hide();
        $('#shipping-price').html('');
    }
    else {
        $('#buttonOrder').show();

        let item = JSON.parse(localStorage.getItem('cart'));

        products = '';
        total = 0;

            item.forEach(element => {
                total += element.price * element.quantity;
                products += `
            <tr>
            <td class="product-thumbnail">
                <figure>
                    <a href="product-simple.html">
                        <img src="assets/images/small/${element.imageId}.jpg" width="100" height="100"
                            alt="product">
                    </a>
                </figure>
            </td>
            <td class="product-name">
                <div class="product-name-section">
                    <a href="product-simple.html">${element.title}</a>
                </div>
            </td>
            <td class="product-subtotal">
                <span class="amount">${Math.round(element.price * 100) / 100} лв</span>
            </td>
            <td class="product-subtotal">
                    <span class="amount">${element.size}(${element.quantity})</span>
            </td>
            <td class="product-price">
                <span class="amount">${Math.round(element.price * element.quantity * 100) / 100} лв</span>
            </td>
            <td class="product-close">
                <a href="#" class="product-remove" title="Remove this product" onclick='removeFromCart(${element.id},${element.size})'>
                    <i class="fas fa-times"></i>
                </a>
            </td>
        </tr>`;

            });
       
        if(total>=100){
            $('#shipping-price').html(`
                Безплатна доставка
            `);
        }
        else{
            $('#shipping-price').html(`
                Поема се от клиента. (Остават ${(Math.round(parseFloat(100-total) * 100) / 100)} лв. до безплатна доставка)
            `);
        }   

        $('#content').html(products);
        $('#total').html((Math.round(parseFloat(total) * 100) / 100).toString() + ' лв');
        $('#totalTwo').html((Math.round(parseFloat(total) * 100) / 100).toString() + ' лв');

    }
}
function removeFromCart(id, size) {
    let items = JSON.parse(localStorage.getItem('cart'));
    let index = items.findIndex(x => x.id == id && x.size == size);
    items.splice(index, 1);
    console.log(index);
    localStorage.setItem('cart', JSON.stringify(items));
    renderCartCustom();
    renderCart();
}

