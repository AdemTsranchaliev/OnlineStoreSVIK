$(document).ready(function () {
        renderCartCheckout()
     
});
function renderCartCheckout() {
    let json = document.getElementById('order').value;
    let sum = 0;
    if (!json || json== '[]') {
        let text = ` <h5 style="width: 100%">Няма добавени продукти</h5>`;
        $('#cart-content').html(text);
    }
    else {
        let item = JSON.parse(json);

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
				<td class="product-total text-body">${(Math.round(parseFloat(element.quantity * element.price) * 100) / 100)} лв</td>
			</tr>
        `;
        });


        let text = `
	    	${products}
            <tr class="sumnary-shipping shipping-row-last">
            <td colspan="2">
                <h4 class="summary-subtitle">Доставка</h4>
                <ul>                                    
                    <li>
                        <div class="custom-radio">
                         <div class="sample-icon" >
                             <i class="d-icon-truck"></i>
                             <span class="name" id="shipping-price">`;

        if (sum >= 100) {
            text += `Безплатна доставка`;
        }
        else {
            text += `Поема се от клиента. (Остават ${(Math.round(parseFloat(100 - sum) * 100) / 100)} лв. до безплатна доставка)`;
        }

        text += `
                             </span>
                          </div>
                        </div>
                    </li>					
    
                </ul>
            </td>
            <td class="pb-0">
            </td>
            <td class=" pt-0 pb-0">
            <td class="pt-0"></td>
            </td>
            </tr>
			
			<tr class="summary-total">
				<td class="pb-0">
					<h4 class="summary-subtitle">Общо за поръчка</h4>
				</td>
                <td class="pb-0">
				</td>
				<td class=" pt-0 pb-0">
					<p class="summary-total-price ls-s text-primary">${(Math.round(parseFloat(sum) * 100) / 100)} лв</p>
				</td>
			</tr>
        `;

        $('#cart-content').html(text);
    }
}