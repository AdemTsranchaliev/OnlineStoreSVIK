$(document).ready(function () {
    if (!localStorage.getItem('cart') || localStorage.getItem('cart') == '[]') {
        window.location.href = "/shoppingCart";
    }

    renderCartCheckout();
    $('#order').val(localStorage.getItem('cart'));
    nameValidate();
    emailValidate();
    surnameValidate();
    deliverValidate();
    populatedPlaceValidate();
    zipCodeValidate();
    phoneValidate();
});

function renderCartCheckout() {

    let sum = 0;
    if (!localStorage.getItem('cart') || localStorage.getItem('cart') == '[]') {
        let text = ` <h5 style="width: 100%">Няма добавени продукти</h5>`;
        $('#cart-content').html(text);
    }
    else {
        let item = JSON.parse(localStorage.getItem('cart'));

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

$('input:radio[name="shipping"]').change(
    function () {
        if (this.checked && this.value == 'office') {
            $('#wayOfDelivery').val('Доставка до офис');
            $('#officeShipping').show();
            $('#addressShipping').hide();
        }
    });

    function populatedPlaceValidate() {
        $('#office').change(
            function () {
                if ($('#office').val().lenght <= 0 || !$('#office').val()) {
                    $('#populatedPlaceRequired').show();
                    $('#office').addClass("not-valid");
                }
                else {
                    $('#populatedPlaceRequired').hide();
                    $('#office').removeClass("not-valid");
                }
            }
        );
    }
$('input:radio[name="shipping"]').change(
    function () {
        if (this.checked && this.value == 'address') {
            $('#wayOfDelivery').val('Доставка до личен адрес');
            $('#addressShipping').show();
            $('#officeShipping').hide();
        }
    });


function nameValidate() {
    $('#name').change(
        function () {
            if ($('#name').val().lenght <= 0 || !$('#name').val()) {
                $('#nameRequired').show();
                $('#name').addClass("not-valid");
            }
            else {
                $('#nameRequired').hide();
                $('#name').removeClass("not-valid");
            }
        }
    );
}

function surnameValidate() {
    $('#surname').change(
        function () {
            if ($('#surname').val().lenght <= 0 || !$('#surname').val()) {
                $('#surnameRequired').show();
                $('#surname').addClass("not-valid");
            }
            else {
                $('#surnameRequired').hide();
                $('#surname').removeClass("not-valid");
            }
        }
    );
}

function emailValidate() {
    $('#email').change(
        function () {
            if ($('#email').val().lenght <= 0 || !$('#email').val()) {
                $('#emailRequired').show();
                $('#email').addClass("not-valid");
            }
            else {
                $('#emailRequired').hide();
                $('#email').removeClass("not-valid");
            }
        }
    );
}

function deliverValidate() {
    $('#deliver').change(
        function () {
            if ($("#deliver option:selected").text().lenght <= 0 || !$('#deliver').val()) {
                $('#deliverRequired').show();
                $('#deliver').addClass("not-valid");
            }
            else {
                $('#deliverRequired').hide();
                $('#deliver').removeClass("not-valid");
            }
        }
    );
}

function populatedPlaceValidate() {
    $('#myInput').change(
        function () {
            if ($('#myInput').val().lenght <= 0 || !$('#myInput').val()) {
                $('#populatedPlaceRequired').show();
                $('#myInput').addClass("not-valid");
            }
            else {
                $('#populatedPlaceRequired').hide();
                $('#myInput').removeClass("not-valid");
            }
        }
    );
}
function zipCodeValidate() {
    $('#zipCode').change(
        function () {
            if ($('#zipCode').val().lenght <= 0 || !$('#zipCode').val()) {
                $('#zipCodeRequired').show();
                $('#zipCode').addClass("not-valid");
            }
            else {
                $('#zipCodeRequired').hide();
                $('#zipCode').removeClass("not-valid");
            }
        }
    );
}
function phoneValidate() {
    $('#phone').change(
        function () {
            if ($('#phone').val().lenght <= 0 || !$('#phone').val()) {
                $('#phoneRequired').show();
                $('#phone').addClass("not-valid");
            }
            else {
                $('#phoneRequired').hide();
                $('#phone').removeClass("not-valid");
            }
        }
    );
}
function wayOfDeliveryValidate() {
    $('#wayOfDelivery').change(
        function () {
            if ($('#wayOfDelivery').val().lenght <= 0 || !$('#wayOfDelivery').val()) {
                $('#wayOfDeliveryRequired').show();
            }
            else {
                $('#wayOfDeliveryRequired').hide();
            }
        }
    );
}
function officeValidate() {
    $('#office').change(
        function () {
            if ($('#wayOfDelivery').val() == "Доставка до офис") {
                if ($("#office").text().lenght <= 0 || !$('#office').val()) {
                    $('#officeRequired').show();
                    $('#office').addClass("not-valid");
                }
                else {
                    $('#officeRequired').hide();
                    $('#office').removeClass("not-valid");
                }
            }
        }
    );
}

$("#checkoutButton").click(function (e) {
    e.preventDefault();

    let counter = 0;

    if ($('#name').val().lenght <= 0 || !$('#name').val()) {
        $('#nameRequired').show();
        $('#name').addClass("not-valid");
    }
    else {
        counter++;
        $('#nameRequired').hide();
        $('#name').removeClass("not-valid");
    }
    if ($('#surname').val().lenght <= 0 || !$('#surname').val()) {
        $('#surnameRequired').show();
        $('#surname').addClass("not-valid");
    }
    else {
        counter++;
        $('#surnameRequired').hide();
        $('#surname').removeClass("not-valid");
    }
    if ($('#email').val().lenght <= 0 || !$('#email').val()) {
        $('#emailRequired').show();
        $('#email').addClass("not-valid");
    }
    else {
        counter++;
        $('#emailRequired').hide();
        $('#email').removeClass("not-valid");
    }
    if ($("#deliver option:selected").text().lenght <= 0 || !$("#deliver option:selected").text()) {
        $('#deliverRequired').show();
        $('#deliver').addClass("not-valid");
    }
    else {
        counter++;
        $('#deliverRequired').hide();
        $('#deliver').removeClass("not-valid");
    }
    if ($('#myInput').val().lenght <= 0 || !$('#myInput').val()) {
        $('#populatedPlaceRequired').show();
        $('#myInput').addClass("not-valid");
    }
    else {
        counter++;
        $('#populatedPlaceRequired').hide();
        $('#myInput').removeClass("not-valid");
    }
    if ($('#zipCode').val().lenght <= 0 || !$('#zipCode').val()) {
        $('#zipCodeRequired').show();
        $('#zipCode').addClass("not-valid");
    }
    else {
        counter++;
        $('#zipCodeRequired').hide();
        $('#zipCode').removeClass("not-valid");
    }
    if ($('#phone').val().lenght <= 0 || !$('#phone').val()) {
        $('#phoneRequired').show();
        $('#phone').addClass("not-valid");
    }
    else {
        counter++;
        $('#phoneRequired').hide();
        $('#phone').removeClass("not-valid");
    }
    if ($('#wayOfDelivery').val().lenght <= 0 || !$('#wayOfDelivery').val()) {
        $('#wayOfDeliveryRequired').show();
    }
    else {
        counter++;

        $('#wayOfDeliveryRequired').hide();
    }
    if ($('#terms-condition').is(":checked")) {
        $("#terms-conditionRequired").hide();
        counter++;
    }
    else {
        $("#terms-conditionRequired").show();
    }
    if ($('#wayOfDelivery').val() == 'Доставка до личен адрес') {
        if ($('#address').val().lenght <= 0 || !$('#address').val()) {
            $('#addressRequired').show();
            $('#address').addClass("not-valid");
        }
        else {
            counter++;
            $('#addressRequired').hide();
            $('#address').removeClass("not-valid");
        }
    }
    else if ($('#wayOfDelivery').val() == 'Доставка до офис') {

        if($("#office").val().lenght <= 0||!$("#office").val()){
            $("#officeRequired").show(); 
            $('#office').addClass("not-valid");
        }
        else {
            counter++;
            $('#officeRequired').hide();
            $('#office').removeClass("not-valid");
        }
    }

    if(counter==10)
    {
        $('#checkout').submit();
        localStorage.setItem('cart', '[]');
    }

});