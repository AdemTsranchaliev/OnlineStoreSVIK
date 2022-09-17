$(document).ready(function () {
    let sum = 0;

    let item = JSON.parse($('#orderJson').val());
    console.log(item);
    let products = '';

    item.forEach(element => {
        sum += element.price * element.quantity;
        products += `
        <tr>
            <td class="product-name">
                <img class="product-quantity" src='/assets/images/small/${element.imageId}.jpg' style='height: 60px'>
            </td>
            <td class="product-name">${element.title}
                <span class="product-quantity">×&nbsp;${element.size}(${element.quantity})</span>
            </td>
            <td class="product-price">${(Math.round(parseFloat(element.quantity * element.price) * 100) / 100)} лв</td>
        </tr>
    `;
    });

    text = `
    ${products}
    <tr class="summary-subtotal">
		<td>
			<h4 class="summary-subtitle">Доставка:</h4>
		</td>
        <td>
		</td>
		<td class="summary-subtotal-price">
        `;

        if (sum >= 100) {
            text += `Безплатна доставка`;
        }
        else {
            text += `Поема се от клиента.`;
        }

        text += `
    </td>
	</tr>
	<tr class="summary-subtotal">
		<td>
			<h4 class="summary-subtitle">Метод на плащане:</h4>
		</td>
        <td>
        </td>
		<td class="summary-subtotal-price">НАЛОЖЕН ПЛАТЕЖ</td>
	</tr>
	<tr class="summary-subtotal">
		<td>
			<h4 class="summary-subtitle">Общо:</h4>
		</td>
        <td>
        </td>
		<td>
			<p class="summary-total-price">${(Math.round(parseFloat(sum) * 100) / 100)}</p>
		</td>
	</tr>
    `;

    $('#orderContent').html(text);

});