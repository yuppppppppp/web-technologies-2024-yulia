<?php
date_default_timezone_set('Asia/Yekaterinburg');

$pageTitle = 'Лабораторная работа 16';
$pageHeading = 'Привет!';
$currentYear = date('Y');

function getPluralForm(int $number, array $forms): string
{
    $lastTwoDigits = $number % 100;
    $lastDigit = $number % 10;

    if ($lastTwoDigits >= 11 && $lastTwoDigits <= 14) {
        return $forms[2];
    }

    if ($lastDigit === 1) {
        return $forms[0];
    }

    if ($lastDigit >= 2 && $lastDigit <= 4) {
        return $forms[1];
    }

    return $forms[2];
}

function getCurrentTimeText(): string
{
    $hours = (int) date('G');
    $minutes = (int) date('i');

    $hourText = getPluralForm($hours, ['час', 'часа', 'часов']);
    $minuteText = getPluralForm($minutes, ['минута', 'минуты', 'минут']);

    return "{$hours} {$hourText} {$minutes} {$minuteText}";
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="./src/assets/styles/style.css">
</head>
<body>
<main class="main">
    <section class="main-content">
        <h1><?= htmlspecialchars($pageHeading, ENT_QUOTES, 'UTF-8') ?></h1>
        <p>Текущий год: <?= htmlspecialchars($currentYear, ENT_QUOTES, 'UTF-8') ?></p>
        <p>Текущее время: <?= htmlspecialchars(getCurrentTimeText(), ENT_QUOTES, 'UTF-8') ?></p>
    </section>
</main>
</body>
</html>
