$(document).ready(function() {

    $("#delete-product-btn").click(function(){
        var selected_products = $('.delete-checkbox:checked').map(function(){
            return $(this).val();
        }).get();
        

         $.ajax({
            url: "delete-selected.php",
            method: "POST",
            data: {selected_products: selected_products},
            success: function(response){
                location.reload();
            }
        })
    })
   
        
})