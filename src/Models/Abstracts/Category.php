<?php

namespace App\Models\Abstracts;

abstract class Category
{
    protected string $name;
    protected string $typename;

    public function __construct(string $name, string $typename)
    {
        $this->name = $name;
        $this->typename = $typename;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTypeName(): string
    {
        return $this->typename;
    }

    abstract public function getDisplayInfo(): string;
}