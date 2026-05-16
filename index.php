<?php
declare(strict_types=1);

$pageTitle = 'Лабораторная работа';

//Задание 1 Вывод чисел от 0 до 10 через цикл do...while
function getNumbersDescription(): array
{
    $result = [];
    $number = 0;

    do {
        if ($number === 0) {
            $result[] = "{$number} - это ноль.";
        } elseif ($number % 2 === 0) {
            $result[] = "{$number} - четное число.";
        } else {
            $result[] = "{$number} - нечетное число.";
        }
        $number++;
    } while ($number <= 10);
    return $result;
}

//Задание 2 Массив областей и городов
$regions = [
    'Московская область' => ['Москва', 'Зеленоград', 'Клин', 'Коломна', 'Красногорск'],
    'Ленинградская область' => ['Санкт-Петербург', 'Всеволожск', 'Павловск', 'Кронштадт'],
    'Рязанская область' => ['Рязань', 'Касимов', 'Скопин', 'Сасово', 'Кораблино'],
];

//Задание 3 Функция транслитерации строк
function transliterate(string $text): string
{
    $letters = [
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
        'е' => 'e', 'ё' => 'e', 'ж' => 'zh', 'з' => 'z', 'и' => 'i',
        'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
        'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
        'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'ts', 'ч' => 'ch',
        'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y', 'ь' => '',
        'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
    ];
    return strtr($text, $letters);
}

//Задание 4 Динамическое меню с вложенными подменю
$menu = [
    ['title' => 'Главная', 'url' => '#task-1'],
    ['title' => 'Задания', 'url' => '#', 'children' => [
        ['title' => 'Числа', 'url' => '#task-1'],
        ['title' => 'Области', 'url' => '#task-2'],
        ['title' => 'Транслитерация', 'url' => '#task-3'],
        ['title' => 'Города на К', 'url' => '#task-6'],
    ]],
    ['title' => 'Контакты', 'url' => '#contacts'],
];

//Задание 5 Рендер меню через простой движок из функции
function renderMenu(array $items): string
{
    $html = '<ul class="menu">';

    foreach ($items as $item) {
        $title = htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8');
        $url = htmlspecialchars($item['url'], ENT_QUOTES, 'UTF-8');

        $html .= '<li>';
        $html .= "<a href=\"{$url}\">{$title}</a>";

        if (!empty($item['children'])) {
            $html .= renderMenu($item['children']);
        }
        $html .= '</li>';
    }
    return $html . '</ul>';
}

//Задание 6 Вывод городов, начинающихся с буквы К
function getCitiesByFirstLetter(array $regions, string $letter): array
{
    $result = [];

    foreach ($regions as $region => $cities) {
        foreach ($cities as $city) {
            if (strpos($city, $letter) === 0) {
                $result[$region][] = $city;
            }
        }
    }
    return $result;
}

$citiesWithK = getCitiesByFirstLetter($regions, 'К');
$transliterationText = 'Лабораторая работа';
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="./src/assets/styles/style.css">
</head>
<body>
<header class="header">
    <nav class="nav">
        <?= renderMenu($menu) ?>
    </nav>
</header>

<main class="main">
    <h1><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></h1>

    <section id="task-1" class="task">
        <h2>Задание 1</h2>
        <?php foreach (getNumbersDescription() as $line): ?>
            <p><?= htmlspecialchars($line, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endforeach; ?>
    </section>

    <section id="task-2" class="task">
        <h2>Задание 2</h2>
        <?php foreach ($regions as $region => $cities): ?>
            <h3><?= htmlspecialchars($region, ENT_QUOTES, 'UTF-8') ?>:</h3>
            <p><?= htmlspecialchars(implode(', ', $cities), ENT_QUOTES, 'UTF-8') ?>.</p>
        <?php endforeach; ?>
    </section>

    <section id="task-3" class="task">
        <h2>Задание 3</h2>
        <p>Исходная строка: <?= htmlspecialchars($transliterationText, ENT_QUOTES, 'UTF-8') ?></p>
        <p>Результат: <?= htmlspecialchars(transliterate($transliterationText), ENT_QUOTES, 'UTF-8') ?></p>
    </section>

    <section id="task-4" class="task">
        <h2>Задания 4 и 5</h2>
        <p>Меню сверху страницы сформировано из массива и выведено функцией renderMenu</p>
    </section>

    <section id="task-6" class="task">
        <h2>Задание 6</h2>
        <?php foreach ($citiesWithK as $region => $cities): ?>
            <h3><?= htmlspecialchars($region, ENT_QUOTES, 'UTF-8') ?>:</h3>
            <p><?= htmlspecialchars(implode(', ', $cities), ENT_QUOTES, 'UTF-8') ?>.</p>
        <?php endforeach; ?>
    </section>
</main>
</body>
</html>
