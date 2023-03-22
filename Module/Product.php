<?php
namespace Module;

class Product
{
    public static $attributes = [
        'size' => ['label' => 'Size', 'suffix' => 'MB'],
        'weight' => ['label' => 'Weight', 'suffix' => 'KG'],
        'dimensions' => ['label' => 'Dimension', 'suffix' => ''],
    ];
    public static function getProducts(): array
    {
        $conn = Connection::getConnection();
        $query = "select t1.ID, t1.sku, t1.name,t1.price, t1.product_type, t2.size,t2.weight, concat(t2.length,'x',t2.width,'x',t2.height) as dimensions
        from products as t1
        inner join products_attributes t2 on t1.sku = t2.sku order by ID;";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $products = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $products = array_map(function ($item) {
            return array_filter(
                $item,
                function ($value) {
                    return !is_null($value);
                }
            );
        }, $products);
        return $products;
    }
}