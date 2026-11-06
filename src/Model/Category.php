<?php
declare(strict_types=1);

namespace App\Model;

final class Category
{
    public function __construct(
        private int $id,
        private string $name
    ) {
        if ($this->id <= 0) {
            throw new \InvalidArgumentException('ID категории должен быть положительным числом');
        }
        if (trim($this->name) === '') {
            throw new \InvalidArgumentException('Название категории не может быть пустым');
        }
    }

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
}