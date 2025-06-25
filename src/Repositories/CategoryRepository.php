<?php

namespace App\Repositories;

use App\Config\DatabaseInterface;
use App\Models\Abstracts\Category;
use App\Models\Categories\AllCategory;
use App\Models\Categories\ClothesCategory;
use App\Models\Categories\TechCategory;
use Exception;
use mysqli_sql_exception;

class CategoryRepository extends DatabaseInterface
{
    private array $categoryInstances;

    public function __construct()
    {
        parent::__construct();

        $this->categoryInstances = [
            'all' => new AllCategory(),
            'tech' => new TechCategory(),
            'clothes' => new ClothesCategory()
        ];
    }

    public function getAllCategories(): array
    {
        try {
            $query = "SELECT * FROM categories";
            $result = mysqli_query($this->connection, $query);
        
            if (!$result) {
                error_log("Database error: " . mysqli_error($this->connection));
                return [];
            }
        
            $categories = [];
            
            while ($row = mysqli_fetch_assoc($result)) {
                $categories[] = [
                    'name' => $row['name'],
                    'typename' => $row['typename']
                ];
            }
        
            return $categories;
        } catch (mysqli_sql_exception $error) {
            error_log("SQL error in getAllCategories: " . $error->getMessage());
            return [];
        } catch (Exception $error) {
            error_log("Error in getAllCategories: " . $error->getMessage());
            return [];
        }
    }
}
