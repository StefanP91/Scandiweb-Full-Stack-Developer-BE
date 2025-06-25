<?php

namespace App\Models\Abstracts;

abstract class Order
{
    protected $id;
    protected string $orderNumber;
    protected float $total;
    protected string $currency;
    protected string $createdAt;

    public function __construct($id ,string $orderNumber ,float $total ,string $currency ,string $createdAt)
    {
        $this->id = $id;
        $this->orderNumber = $orderNumber;
        $this->total = $total;
        $this->currency = $currency;
        $this->createdAt = $createdAt;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getOrderNumber(): string
    {
        return $this->orderNumber;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    abstract public function getOrderSummary(): string;
}