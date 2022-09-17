$(document).ready(function () {

    $('#addToCard').prop('disabled', true);
    console.log('in');


});
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

$('#addToCard').on("click",
    function () {
        if (!$('#selectedSize').val() || $('#selectedSize').val() == '') {
            $('#sizeRequired').show();
        }
        else {

            $('#sizeRequired').hide();

            if (!localStorage.getItem('cart')) {
                localStorage.setItem('cart', '[]');
            }

            let items = JSON.parse(localStorage.getItem('cart'));

            if ((items.filter(e => e.id === $("#id").val()).length > 0) && (items.filter(e => e.size === $("#selectedSize").val()).length > 0)) {

                let indexOfProduct = items.findIndex(x => x.id == $("#id").val() && x.size == $("#selectedSize").val());
                let quantity = parseInt(items[indexOfProduct].quantity);

                quantity += parseInt($("#quantity").val());
                items[indexOfProduct].quantity = quantity.toString();
            }
            else {
                items.push(new Product($("#id").val(), $("#imageId").val(), $("#selectedSize").val(), $("#quantity").val(), $("#price").val(), $("#title").val()));
            }
            localStorage.setItem('cart', JSON.stringify(items));
            renderCart();
        }
    });

function setSelectedSize(size) {
    $('#addToCard').prop('disabled', false);
    $('#selectedSizeShow').html(size);
    $('#selectedSize').val(size);
}


let givenRating = 0;
function giveRating(number) {
    if (givenRating != 0) {
        $('#rank' + givenRating).removeClass("active");
    }
    givenRating = number;

    $('#rank' + number).addClass("active");
}

$('#reviewSubmitButton').on("click",
    function (e) {
        e.preventDefault();
        counter = 0;
        if ($('#reviewerName').val().length <= 0 || !$('#reviewerName').val()) {
            $('#reviewerNameRequired').show();
            $('#reviewerName').addClass("not-valid");
        }
        else {
            $('#reviewerNameRequired').hide();
            $('#reviewerName').removeClass("not-valid");
            counter++;
        }

        if ($('#reviewerEmail').val().length <= 0 || !$('#reviewerEmail').val()) {
            $('#reviewerEmailRequired').show();
            $('#reviewerEmail').addClass("not-valid");
        }
        else {
            $('#reviewerEmailRequired').hide();
            $('#reviewerEmail').removeClass("not-valid");
            counter++;
        }
        if (givenRating == 0) {
            $('#reviewerRatingRequired').show();
        }
        else {
            $('#reviewerRatingRequired').hide();
            counter++;
        }

        if (counter == 3) {
            console.log(1);
            $.ajax({
                url: '/addReview',
                type: 'POST',
                dataType: 'html',
                data: {
                    'reviewerRating': givenRating,
                    'reviewerComment': $('#reviewerComment').val(),
                    'productId': $("#productId").val(),
                    'name': $('#reviewerName').val(),
                    'email': $('#reviewerEmail').val()
                },
                async: true,
                success: function (data, status) {
                    $('#reviewerName').val('');
                    $('#reviewerEmail').val('');
                    $('#reviewerComment').val('');
                    
                    location.reload();
                },
                error: function (xhr, textStatus, errorThrown) {

                    alert("fail")

                }
            });
        }
    })
