<?php

namespace App\Repositories;

use App\Config\Database;
use App\Models\Abstracts\Product;
use App\Models\Products\ClothingProduct;
use App\Models\Products\TechProduct;
use App\Models\ProductAttribute;
use Exception;
use mysqli_sql_exception;

class ProductRepository extends Database
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllProducts(): array
    {
        try {
            $query = "SELECT * FROM products";
            $result = mysqli_query($this->connection, $query);
            
            if (!$result) {
                error_log("Database error: " . mysqli_error($this->connection));
                return [];
            }
            
            $products = [];
            $attributeModel = new ProductAttribute();
            
            while ($row = mysqli_fetch_assoc($result)) {

                $product = [
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'inStock' => (bool)$row['inStock'],
                    'gallery' => $this->getProductGallery($row['id']),
                    'description' => $row['description'],
                    'category' => $row['category'],
                    'brand' => $row['brand'],
                    'prices' => $this->getProductPrices($row['id']),
                    'attributes' => $attributeModel->getAttributeForProduct($row['id']),
                    'typename' => $row['typename'] 
                ];
                
                $products[] = $product;
            }
            
            return $products;
        } catch (mysqli_sql_exception $error) {
            error_log("SQL error in getAllCategories: " . $error->getMessage());
            return [];
        } catch (Exception $error) {
            error_log("Error in getAllCategories: " . $error->getMessage());
            return [];
        }
    }
    
    public function getByCategory($category): array
    {
        try {
            $query = "SELECT * FROM products WHERE category = ?";
            $stmt = mysqli_prepare($this->connection, $query);
            
            if (!$stmt) {
                error_log("Failed to prepare statement: " . mysqli_error($this->connection));
                return [];
            }
            
            mysqli_stmt_bind_param($stmt, "s", $category);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            $products = [];
            $attributeModel = new ProductAttribute();
            
            while ($row = mysqli_fetch_assoc($result)) {

                $product = [
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'brand' => $row['brand'],
                    'inStock' => isset($row['inStock']) ? (bool)$row['inStock'] : true,
                    'category' => $row['category'],
                    'description' => $row['description'],
                    'gallery' => $this->getProductGallery($row['id']),
                    'prices' => $this->getProductPrices($row['id']),
                    'attributes' => $attributeModel->getAttributeForProduct($row['id']),
                    'typename' => $row['typename'] ?? 'Product'
                ];
                
                $products[] = $product;
            }
            
            mysqli_stmt_close($stmt);
            return $products;
        } catch (Exception $error) {
            error_log("Error in getByCategory: " . $error->getMessage());
            return [];
        }
    }
    
    private function getProductGallery($productId): array
    {
        try {
            $query = "SELECT image_url FROM product_gallery WHERE product_id = ?";
            $stmt = mysqli_prepare($this->connection, $query);
            
            if (!$stmt) {
                error_log("Failed to prepare gallery statement: " . mysqli_error($this->connection));
                return [];
            }
            
            mysqli_stmt_bind_param($stmt, "s", $productId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            $gallery = [];
            
            while ($row = mysqli_fetch_assoc($result)) {
                $gallery[] = $row['image_url'];
            }
            
            mysqli_stmt_close($stmt);
            return $gallery;
        } catch (Exception $error) {
            error_log("Error in getProductGallery: " . $error->getMessage());
            return [];
        }
    }
    
    private function getProductPrices($productId): array
    {
        try {
            $query = "SELECT * FROM prices WHERE product_id = ?";
            $stmt = mysqli_prepare($this->connection, $query);
            
            if (!$stmt) {
                error_log("Failed to prepare prices statement: " . mysqli_error($this->connection));
                return [];
            }

            mysqli_stmt_bind_param($stmt, "s", $productId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            $prices = [];
            
            while ($row = mysqli_fetch_assoc($result)) {
                $prices[] = [
                    'amount' => (float)$row['amount'],
                    'currency' => [
                        'label' => $row['currency_label'],
                        'symbol' => $row['currency_symbol'],
                        'typename' => 'Currency'
                    ],
                    'typename' => $row['typename'] ?? 'Price'
                ];
            }
            
            mysqli_stmt_close($stmt);
            return $prices;
        } catch (Exception $error) {
            error_log("Error in getProductPrices: " . $error->getMessage());
            return [];
        }
    }
}