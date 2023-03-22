<?php
include_once('../vendor/autoload.php');
use App\Models\ProductFactory;
if (isset($_POST['selected_products'])) {
    $selected_products = $_POST['selected_products'];
    foreach ($selected_products as $product) {
        $prepared_product = ProductFactory::PrepareProduct(json_decode($product));
        ProductFactory::Delete($prepared_product);
    }
}
?>