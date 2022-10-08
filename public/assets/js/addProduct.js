$(document).ready(function () {
    initSizes();
});


$("#name").change(function () {
    if ($('#name').val() != '') {
        $('#nameEmpty').hide();
    }
    else {
        $('#nameEmpty').show();
    }
});

$("#model").change(function () {
    if ($('#model').val() != '') {
        $('#modelEmpty').hide();
    }
    else {
        $('#modelEmpty').show();
    }
});

$("#color").change(function () {
    if ($('#color').val() != '') {
        $('#colorEmpty').hide();
    }
    else {
        $('#colorEmpty').show();
    }
});

$("#colorHexCode").change(function () {
    if ($('#colorHexCode').val() != '') {
        $('#colorHexCodeEmpty').hide();
    }
    else {
        $('#colorHexCodeEmpty').show();
    }
});

$("#price").change(function () {
    if ($('#price').val() != '') {
        $('#priceEmpty').hide();
    }
    else {
        $('#priceEmpty').show();
    }
});

$("#title").change(function () {
    if ($('#title').val() != '') {
        $('#titleEmpty').hide();
    }
    else {
        $('#titleEmpty').show();
    }
});

$("#addSizeFrontButton").click(function () {
    let freeIndex = document.getElementById('firstFreeSizeId').value;
    $("#addSizeFront").replaceWith(` 
    <div id="addSizeFront"></div>${getSizeColumnHtml(freeIndex)}`);
    document.getElementById('firstFreeSizeId').value = ++freeIndex;


});

$("#addSizeBackButton").click(function () {
    let freeIndex = document.getElementById('firstFreeSizeId').value;
    console.log(freeIndex);
    $("#addSizeBack").replaceWith(`${getSizeColumnHtml(freeIndex)}<div id="addSizeBack"></div>`);
    document.getElementById('firstFreeSizeId').value = ++freeIndex;
});

$("#addPictureButton").click(function () {
    $("#addPicture").replaceWith(`
    <li style="margin: 1%">
        <input name="file[]" type="file" onchange="readURL(this);"/>
        <img id="blah" style='height: 60px' src="http://placehold.it/180" alt="Начална снимка"/>
    </li>
    <li id="addPicture"></li>` );
});

function getSizeColumnHtml(id, size = 0, quantity = 0, sizeSantimeters = 0) {
    return `
    <div class="col-sm-2" style="border: 1px solid black; padding: 1%; margin: 1%" id='size-${id}'>
        <input type="text" class="form-control" value='${size}' name="size[]" />
        <input type="text" class="form-control" value='${quantity}' name="quantity[]"/>
        <input type="text" class="form-control" value='${sizeSantimeters}' name="sizeSantimeters[]"/>
        <button type='button' class='btn btn-danger' onclick='removeSize("size-${id}")'>X</button>
    </div>
    `
}
function initSizes() {
    let freeIndex = document.getElementById('firstFreeSizeId').value;

    let defaultSizes = [
        { size: 36, quantity: 2, sizeSantimeters: 23.5 },
        { size: 37, quantity: 2, sizeSantimeters: 24 },
        { size: 38, quantity: 2, sizeSantimeters: 25 },
        { size: 39, quantity: 2, sizeSantimeters: 25.5 },
        { size: 40, quantity: 2, sizeSantimeters: 26 },
    ];

    let result = '';
    defaultSizes.forEach(x => {
        result += getSizeColumnHtml(freeIndex, x.size, x.quantity, x.sizeSantimeters);
        freeIndex++;
    });
    document.getElementById('firstFreeSizeId').value = freeIndex;
    $("#addSizeBack").replaceWith(`${result}<div id="addSizeBack"></div>`);
}

function removeSize(id) {
    let sizeId = `#size-${id}`;
    $(sizeId).replaceWith("");
}