<?php
declare(strict_types=1);

namespace App\Service;

use App\Model\Category;
use App\Model\Product;

final class CatalogService
{
    /** @var Product[] */
    private array $products = [];
    /** @var Category[] */
    private array $categories = [];

    public function __construct(string $productsJsonPath, string $categoriesJsonPath)
    {
        $this->loadCategories($categoriesJsonPath);
        $this->loadProducts($productsJsonPath);
    }

    private function loadCategories(string $path): void
    {
        if (!file_exists($path)) {
            throw new \RuntimeException("Файл категорий не найден: {$path}");
        }
        $data = json_decode((string)file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
        foreach ($data as $item) {
            $this->categories[] = new Category((int)$item['id'], (string)$item['name']);
        }
    }

    private function loadProducts(string $path): void
    {
        if (!file_exists($path)) {
            throw new \RuntimeException("Файл товаров не найден: {$path}");
        }
        $data = json_decode((string)file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
        foreach ($data as $item) {
            $this->products[] = new Product(
                (int)$item['id'],
                (string)$item['name'],
                (float)$item['price'],
                (int)$item['category_id'],
                $item['description'] ?? null
            );
        }
    }

    /** @return Product[] */
    public function getProducts(?int $categoryId = null): array
    {
        if ($categoryId === null || $categoryId === 0) {
            return $this->products;
        }
        return array_values(array_filter($this->products, fn(Product $p) => $p->matchesCategory($categoryId)));
    }

    /** @return Category[] */
    public function getCategories(): array
    {
        return $this->categories;
    }

    public function getAveragePrice(?int $categoryId = null): float
    {
        $list = $this->getProducts($categoryId);
        if (empty($list)) {
            return 0.0;
        }
        $total = array_reduce($list, fn(float $sum, Product $p) => $sum + $p->getPrice(), 0.0);
        return $total / count($list);
    }
}