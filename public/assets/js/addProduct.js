$("#name").change(function(){
    if($('#name').val()!=''){
        $('#nameEmpty').hide();
    }
    else{
        $('#nameEmpty').show();
    }
});
$("#model").change(function(){
    if($('#model').val()!=''){
        $('#modelEmpty').hide();
    }
    else{
        $('#modelEmpty').show();
    }
});
$("#color").change(function(){
    if($('#color').val()!=''){
        $('#colorEmpty').hide();
    }
    else{
        $('#colorEmpty').show();
    }
});
$("#colorHexCode").change(function(){
    if($('#colorHexCode').val()!=''){
        $('#colorHexCodeEmpty').hide();
    }
    else{
        $('#colorHexCodeEmpty').show();
    }
});
$("#price").change(function(){
    if($('#price').val()!=''){
        $('#priceEmpty').hide();
    }
    else{
        $('#priceEmpty').show();
    }
});
$("#title").change(function(){
    if($('#title').val()!=''){
        $('#titleEmpty').hide();
    }
    else{
        $('#titleEmpty').show();
    }
});
$("#addSizeFrontButton").click(function(){    
    $("#addSizeFront").replaceWith( ` 
    <div id="addSizeFront"></div>
    <div class="col-xs-1" style="border: 1px solid black; padding: 1%; margin: 1%">
    <input type="text" class="form-control" name="size[]" required=""/>
    <input type="text" class="form-control" name="quantity[]" required=""/>
    <input type="text" class="form-control" name="sizeSantimeters[]" required=""/>
    </div>` );
});
$("#addSizeBackButton").click(function(){    
    $("#addSizeBack").replaceWith( `<div class="col-xs-1" style="border: 1px solid black; padding: 1%; margin: 1%">
    <input type="text" class="form-control" name="size[]" required=""/>
    <input type="text" class="form-control" name="quantity[]" required=""/>
    <input type="text" class="form-control" name="sizeSantimeters[]" required=""/>
    </div>
  <div id="addSizeBack"></div>` );
});
$("#addPictureButton").click(function(){
    $("#addPicture").replaceWith( `<li style="margin: 1%">
    <input name="file[]" type="file" onchange="readURL(this);"/>
    <img id="blah" style='height: 60px' src="http://placehold.it/180" alt="Начална снимка"/>
</li>
<li id="addPicture">
</li>` );
});
