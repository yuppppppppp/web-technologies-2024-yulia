<?php
declare(strict_types=1);

const DB_FILE = __DIR__ . '/menu.sqlite';

function getConnection(): PDO
{
    $pdo = new PDO('sqlite:' . DB_FILE);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS menu_items (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            parent_id INTEGER NULL,
            title TEXT NOT NULL,
            sort_order INTEGER NOT NULL,
            FOREIGN KEY (parent_id) REFERENCES menu_items(id)
        )'
    );

    return $pdo;
}

function seedMenu(PDO $pdo): void
{
    $count = (int) $pdo->query('SELECT COUNT(*) FROM menu_items')->fetchColumn();
    if ($count > 0) {
        return;
    }

    $items = [
        ['id' => 1, 'parent_id' => null, 'title' => 'Каталог товаров', 'sort_order' => 1],
        ['id' => 2, 'parent_id' => 1, 'title' => 'Мойки', 'sort_order' => 1],
        ['id' => 3, 'parent_id' => 2, 'title' => 'Ulgran', 'sort_order' => 1],
        ['id' => 4, 'parent_id' => 3, 'title' => 'Smth', 'sort_order' => 1],
        ['id' => 5, 'parent_id' => 3, 'title' => 'Smth', 'sort_order' => 2],
        ['id' => 6, 'parent_id' => 2, 'title' => 'Vigro Mramor', 'sort_order' => 2],
        ['id' => 7, 'parent_id' => 2, 'title' => 'Handmade', 'sort_order' => 3],
        ['id' => 8, 'parent_id' => 7, 'title' => 'Smth', 'sort_order' => 1],
        ['id' => 9, 'parent_id' => 7, 'title' => 'Smth', 'sort_order' => 2],
        ['id' => 10, 'parent_id' => 2, 'title' => 'Vigro Glass', 'sort_order' => 4],
        ['id' => 11, 'parent_id' => 1, 'title' => 'Фильтры', 'sort_order' => 2],
        ['id' => 12, 'parent_id' => 11, 'title' => 'Ulgran', 'sort_order' => 1],
        ['id' => 13, 'parent_id' => 12, 'title' => 'Smth', 'sort_order' => 1],
        ['id' => 14, 'parent_id' => 12, 'title' => 'Smth', 'sort_order' => 2],
        ['id' => 15, 'parent_id' => 11, 'title' => 'Vigro Mramor', 'sort_order' => 2],
    ];

    $stmt = $pdo->prepare(
        'INSERT INTO menu_items (id, parent_id, title, sort_order)
         VALUES (:id, :parent_id, :title, :sort_order)'
    );

    foreach ($items as $item) {
        $stmt->execute($item);
    }
}

function getMenuTree(PDO $pdo): array
{
    $rows = $pdo
        ->query('SELECT id, parent_id, title FROM menu_items ORDER BY parent_id, sort_order, title')
        ->fetchAll(PDO::FETCH_ASSOC);

    $tree = [];
    $items = [];

    foreach ($rows as $row) {
        $row['children'] = [];
        $items[(int) $row['id']] = $row;
    }

    foreach ($items as $id => &$item) {
        if ($item['parent_id'] === null) {
            $tree[] = &$item;
            continue;
        }

        $parentId = (int) $item['parent_id'];
        if (isset($items[$parentId])) {
            $items[$parentId]['children'][] = &$item;
        }
    }
    unset($item);

    return $tree;
}

function renderMenu(array $items, int $level = 0): string
{
    $html = '';
    $indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level);

    foreach ($items as $item) {
        $hasChildren = $item['children'] !== [];
        $childrenId = 'children-' . (int) $item['id'];
        $title = htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8');

        if ($hasChildren) {
            $html .= '<div>' . $indent;
            $html .= '<span onclick="toggleMenu(\'' . $childrenId . '\')">&#128193; ' . $title . '</span>';
            $html .= '</div>';
            $html .= '<div id="' . $childrenId . '">' . renderMenu($item['children'], $level + 1) . '</div>';
        } else {
            $html .= '<div>' . $indent . '&#128193; ' . $title . '</div>';
        }
    }

    return $html;
}

$error = '';
$menuTree = [];

try {
    $pdo = getConnection();
    seedMenu($pdo);
    $menuTree = getMenuTree($pdo);
} catch (Throwable $exception) {
    $error = 'Не удалось подключиться к базе данных. Проверьте, что в PHP включено расширение SQLite.';
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Практика 20</title>
</head>
<body>
<main>
    <?php if ($error !== ''): ?>
        <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php else: ?>
        <nav aria-label="Каталог товаров">
            <?= renderMenu($menuTree) ?>
        </nav>
    <?php endif; ?>
</main>
<script>
    function toggleMenu(id) {
        const block = document.getElementById(id);
        block.hidden = !block.hidden;
    }
</script>
</body>
</html>
