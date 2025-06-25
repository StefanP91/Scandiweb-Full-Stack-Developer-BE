<?php

namespace App\Models\Orders;

use App\Models\Abstracts\Order;

class StandardOrder extends Order
{
    public function __construct(
        $id,
        string $orderNumber,
        float $total,
        string $currency,
        string $createdAt
    ) {
        parent::__construct($id, $orderNumber, $total, $currency, $createdAt);
    }

    public function getOrderSummary(): string
    {
        return "Order #{$this->orderNumber}: {$this->total} {$this->currency}";
    }
}