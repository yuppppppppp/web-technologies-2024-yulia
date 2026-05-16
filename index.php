<?php
$pdo = new PDO('sqlite:' . __DIR__ . '/menu.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec('CREATE TABLE IF NOT EXISTS menu_items (
    id INTEGER PRIMARY KEY,
    parent_id INTEGER,
    title TEXT NOT NULL,
    sort_order INTEGER NOT NULL
)');

if (!$pdo->query('SELECT COUNT(*) FROM menu_items')->fetchColumn()) {
    $items = [
        [1, null, 'Каталог товаров', 1],
        [2, 1, 'Мойки', 1],
        [3, 2, 'Ulgran', 1],
        [4, 3, 'Smth', 1],
        [5, 3, 'Smth', 2],
        [6, 2, 'Vigro Mramor', 2],
        [7, 2, 'Handmade', 3],
        [8, 7, 'Smth', 1],
        [9, 7, 'Smth', 2],
        [10, 2, 'Vigro Glass', 4],
        [11, 1, 'Фильтры', 2],
        [12, 11, 'Ulgran', 1],
        [13, 12, 'Smth', 1],
        [14, 12, 'Smth', 2],
        [15, 11, 'Vigro Mramor', 2],
    ];

    $stmt = $pdo->prepare('INSERT INTO menu_items VALUES (?, ?, ?, ?)');
    foreach ($items as $item) {
        $stmt->execute($item);
    }
}

$rows = $pdo->query('SELECT * FROM menu_items ORDER BY parent_id, sort_order')->fetchAll(PDO::FETCH_ASSOC);
$menu = [];

foreach ($rows as $row) {
    $row['children'] = [];
    $menu[$row['id']] = $row;
}

foreach ($menu as &$item) {
    if ($item['parent_id']) {
        $menu[$item['parent_id']]['children'][] = &$item;
    }
}
unset($item);

function renderMenu(array $items, int $parentId = 0, int $level = 0): string
{
    $html = '';
    $indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level);

    foreach ($items as $item) {
        if ((int) $item['parent_id'] !== $parentId) {
            continue;
        }

        $title = htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8');
        $id = 'menu-' . $item['id'];

        if ($item['children']) {
            $html .= '<div>' . $indent . '<span onclick="toggleMenu(\'' . $id . '\')">📁 ' . $title . '</span></div>';
            $html .= '<div id="' . $id . '">' . renderMenu($item['children'], (int) $item['id'], $level + 1) . '</div>';
        } else {
            $html .= '<div>' . $indent . '📁 ' . $title . '</div>';
        }
    }

    return $html;
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Практика 20</title>
</head>
<body>
    <?= renderMenu($menu) ?>

    <script>
        function toggleMenu(id) {
            const block = document.getElementById(id);
            block.hidden = !block.hidden;
        }
    </script>
</body>
</html>
