<?php

namespace App\Repositories;

use App\Config\Database;
use App\Models\Orders\StandardOrder;
use Exception;
use mysqli_sql_exception;

class OrderRepository extends Database
{
    public function __construct()
    {
        parent::__construct();
    }

    public function createOrder(array $items, string $currency): ?StandardOrder
    {
        try {
            if (empty($items) || !is_array($items)) {
                throw new Exception('Invalid items data');
            }

            $this->connection->begin_transaction();
            
            $total = 0;
            foreach ($items as $item) {
                if (!isset($item['price']) || !isset($item['quantity'])) {
                    throw new Exception('Missing price or quantity in item');
                }
                $total += ($item['price'] * $item['quantity']);
            }
            
            $orderNumber = 'ORD-' . strtoupper(uniqid());
            $date = date('Y-m-d H:i:s');
            
            $stmt = $this->connection->prepare("INSERT INTO orders (order_number, total, currency, created_at) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sdss", $orderNumber, $total, $currency, $date);
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to create order: " . $stmt->error);
            }
            
            $orderId = $this->connection->insert_id;
            
            // Save order items and attributes
            foreach ($items as $item) {
                if (isset($item['productId'])) {
                    $itemStmt = $this->connection->prepare(
                        "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)"
                    );
                    $productId = $item['productId'];
                    $quantity = $item['quantity'];
                    $price = $item['price'];
                    $itemStmt->bind_param("isid", $orderId, $productId, $quantity, $price);
                    
                    if (!$itemStmt->execute()) {
                        throw new Exception("Failed to save order item: " . $itemStmt->error);
                    }

                    $orderItemId = $this->connection->insert_id;
                    
                    // Save selected attributes if present
                    if (isset($item['selectedAttributes']) && is_array($item['selectedAttributes'])) {
                        foreach ($item['selectedAttributes'] as $attr) {
                            if (isset($attr['name']) && isset($attr['value'])) {
                                $attrStmt = $this->connection->prepare(
                                    "INSERT INTO order_item_attributes (order_item_id, name, value) VALUES (?, ?, ?)"
                                );
                                $attrStmt->bind_param("iss", $orderItemId, $attr['name'], $attr['value']);
                                
                                if (!$attrStmt->execute()) {
                                    throw new Exception("Failed to save attribute: " . $attrStmt->error);
                                }
                            }
                        }
                    }
                }
            }
                    
            $this->connection->commit();
            
            $order = new StandardOrder($orderId, $orderNumber, (float)$total, $currency, $date);
            
            return $order;
        } catch (mysqli_sql_exception $error) {
            if ($this->connection && $this->connection->connect_error === false) {
            $this->connection->rollback();
            }
            error_log("SQL error in createOrder: " . $error->getMessage());
            return null;
        } catch (Exception $error) {
            if ($this->connection && $this->connection->connect_error === false) {
            $this->connection->rollback();
            }
            error_log("Error in createOrder: " . $error->getMessage());
            return null;
        }
    }
}

