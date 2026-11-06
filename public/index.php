<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/Model/Category.php';
require_once __DIR__ . '/../src/Model/Product.php';
require_once __DIR__ . '/../src/Service/CatalogService.php';

use App\Service\CatalogService;

$productsFile = __DIR__ . '/../data/products.json';
$categoriesFile = __DIR__ . '/../data/categories.json';

$service = new CatalogService($productsFile, $categoriesFile);

$selectedCategory = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$products = $service->getProducts($selectedCategory);
$categories = $service->getCategories();
$avgPrice = $service->getAveragePrice($selectedCategory);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Практическая работа №5 — Каталог</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <header class="header">
        <h1>Каталог товаров (ООП Модель)</h1>
    </header>

    <section class="card filter-card">
        <form method="GET" action="">
            <label for="category">Категория:</label>
            <select name="category" id="category" onchange="this.form.submit()">
                <option value="0">Все категории</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat->getId() ?>" <?= $selectedCategory === $cat->getId() ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat->getName()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
        <p class="stats">Найдено товаров: <strong><?= count($products) ?></strong> | Средняя цена: <strong><?= number_format($avgPrice, 2, '.', ' ') ?> ₽</strong></p>
    </section>

    <main class="grid">
        <?php if (empty($products)): ?>
            <p>Товары не найдены.</p>
        <?php else: ?>
            <?php foreach ($products as $prod): ?>
                <article class="card product-card">
                    <h3><?= htmlspecialchars($prod->getName()) ?></h3>
                    <p class="desc"><?= htmlspecialchars($prod->getShortDescription()) ?></p>
                    <p class="price <?= $prod->isExpensive() ? 'expensive' : '' ?>"><?= htmlspecialchars($prod->getFormattedPrice()) ?></p>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
</div>
</body>
</html>