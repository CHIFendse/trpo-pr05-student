<?php
declare(strict_types=1);

namespace App\Model;

final class Product
{
    public function __construct(
        private int $id,
        private string $name,
        private float $price,
        private int $categoryId,
        private ?string $description = null
    ) {
        if ($this->id <= 0) {
            throw new \InvalidArgumentException('ID товара должен быть положительным');
        }
        if (trim($this->name) === '') {
            throw new \InvalidArgumentException('Название товара не может быть пустым');
        }
        if ($this->price < 0) {
            throw new \InvalidArgumentException('Цена не может быть отрицательной');
        }
    }

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getPrice(): float { return $this->price; }
    public function getCategoryId(): int { return $this->categoryId; }
    public function getDescription(): string { return $this->description ?? 'Описание отсутствует'; }

    // Методы предметной области
    public function getFormattedPrice(): string
    {
        return number_format($this->price, 2, '.', ' ') . ' ₽';
    }

    public function isExpensive(float $threshold = 5000.0): bool
    {
        return $this->price >= $threshold;
    }

    public function matchesCategory(int $categoryId): bool
    {
        return $this->categoryId === $categoryId;
    }

    public function getShortDescription(int $limit = 50): string
    {
        $desc = $this->getDescription();
        return mb_strlen($desc) > $limit ? mb_substr($desc, 0, $limit) . '...' : $desc;
    }
}