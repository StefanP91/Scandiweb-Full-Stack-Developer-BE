<?php

namespace App\Models;

use App\Config\Database;
use Exception;
use mysqli_sql_exception;

class ProductAttribute extends Database
{
    public function getAttributeForProduct(string $productId): array
    {
        try {                
            $query = "SELECT * FROM attributes WHERE product_id = ?";
            
            $stmt = $this->connection->prepare($query);

            if (!$stmt) {
                error_log("Failed to prepare statement: " . $this->connection->error);
                return [];
            }

            $stmt->bind_param("s", $productId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $attributes = [];
            
            while ($row = $result->fetch_assoc()) {
                $attributes[] = [
                    'id' => $row['name'],
                    'name' => $row['name'],
                    'items' => $this->getAttributeItems($row['id'], $row['name']),
                    'type' => $row['type'],
                    'typename' => $row['typename']
                ];
            }
            
            return $attributes;
            
        } catch (mysqli_sql_exception $error) {
            error_log("SQL ERROR in getAttributeForProduct: " . $error->getMessage());
            return [];
        } catch (Exception $error) {
            error_log("ERROR in getAttributeForProduct: " . $error->getMessage());
            return [];
        }
    }
    
    private function getAttributeItems(int $attributeId, string $attributeName): array
    {
        try {
            $query = "SELECT * FROM attribute_items WHERE attribute_id = ?";
            $stmt = $this->connection->prepare($query);

            if (!$stmt) {
                error_log("Failed to prepare statement: " . $this->connection->error);
                return [];
            }
            
            $stmt->bind_param("i", $attributeId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $items = [];

            while ($row = $result->fetch_assoc()) {
                $items[] = [
                    'id' => $row['value'], 
                    'value' => $row['value'],
                    'displayValue' => $row['displayValue'],
                    'typename' => $row['typename']
                ];
            }
            
            return $items;
            
        } catch (Exception $error) {
            error_log("ERROR in getAttributeItems: " . $error->getMessage());
            return [];
        }
    }
}
