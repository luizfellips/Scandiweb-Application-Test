<?php
namespace App\Models;

class ProductFactory
{
    protected static $productMap = [
        'DVD' => DVD::class,
        'Book' => Book::class,
        'Furniture' => Furniture::class

    ];

    //this method generates a product based on it's product type.
    public static function GenerateProduct($sku, $name, $price, $product_type, $attributes)
    {
        $product = self::$productMap[$product_type];
        return new $product($sku, $name, $price, ...array_values($attributes));
    }


    //this method saves the product to the database.
    public static function Save($product)
    {

        $connection = \Module\Connection::getConnection();
        $query = "INSERT INTO Products(sku,name,price,product_type) values (:sku,:name,:price,:product_type)";
        $stmt = $connection->prepare($query);
        $stmt->bindValue(':sku', $product->getSku());
        $stmt->bindValue(':name', $product->getName());
        $stmt->bindValue(':price', $product->getPrice());
        $stmt->bindValue(':product_type', $product->getProductType());
        $stmt->execute();

        //Variable columns and values count
        $attribute_columns = "SKU" . "," . (implode(",", array_keys($product->getAttributes())));
        $values_array = array_merge([$product->getSku()], array_values($product->getAttributes()));
        $placeholders = implode(',', array_fill(0, count($values_array), '?'));

        $second_query = "INSERT INTO Products_Attributes (" . $attribute_columns . ")  values (" . $placeholders . ")";
        $second_stmt = $connection->prepare($second_query);
        $second_stmt->execute($values_array);

    }

    //This method receives a product object and deletes it based on the SKU.
    public static function Delete($product)
    {
        $conn = \Module\Connection::getConnection();

        $stmt = $conn->prepare('DELETE from products where SKU = :sku');
        $stmt->bindValue(":sku", $product->getSku());
        $stmt->execute();

        $stmt = $conn->prepare('DELETE from products_attributes where SKU = :sku');
        $stmt->bindValue(":sku", $product->getSku());
        $stmt->execute();
    }

    
    
    // This method will transform a StdClass instance from a product into it's respective product.
    public static function PrepareProduct($product)
    {
        unset($product->ID);
        if (isset($product->dimensions)) {
            $dimensions = explode("x", $product->dimensions);
            unset($product->dimensions);
            $product->height = $dimensions[0];
            $product->width = $dimensions[1];
            $product->length = $dimensions[2];
        }
        $attributes = array_filter(
            array(
                'size' => $product->size,
                'weight' => $product->weight,
                'height' => $product->height,
                'width' => $product->width,
                'length' => $product->length
            )
        );

        return ProductFactory::GenerateProduct(
            $product->sku,
            $product->name,
            $product->price,
            $product->product_type,
            $attributes
        );
    }
}