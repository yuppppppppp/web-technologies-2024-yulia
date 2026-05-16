<?php

//Задание 1 Объявляем две целочисленные переменные и проверяем их знаки
$a = 8;
$b = -3;

echo "Задание 1\n";
echo "\$a = {$a}, \$b = {$b}\n";

if ($a >= 0 && $b >= 0) {
    echo "Оба числа положительные. Разность: " . ($a - $b) . "\n\n";
} elseif ($a < 0 && $b < 0) {
    echo "Оба числа отрицательные. Произведение: " . ($a * $b) . "\n\n";
} else {
    echo "Числа разных знаков. Сумма: " . ($a + $b) . "\n\n";
}

//Задание 2 Присваиваем $a значение от 0 до 15 и через switch выводим числа до 15
$a = 7;

echo "Задание 2\n";
echo "\$a = {$a}\n";

switch ($a) {
    case 0:
        echo "0 ";
    case 1:
        echo "1 ";
    case 2:
        echo "2 ";
    case 3:
        echo "3 ";
    case 4:
        echo "4 ";
    case 5:
        echo "5 ";
    case 6:
        echo "6 ";
    case 7:
        echo "7 ";
    case 8:
        echo "8 ";
    case 9:
        echo "9 ";
    case 10:
        echo "10 ";
    case 11:
        echo "11 ";
    case 12:
        echo "12 ";
    case 13:
        echo "13 ";
    case 14:
        echo "14 ";
    case 15:
        echo "15\n\n";
        break;
}

//Задание 3 Реализуем 4 арифметические операции в виде функций с return
function add($arg1, $arg2)
{
    return $arg1 + $arg2;
}

function subtract($arg1, $arg2)
{
    return $arg1 - $arg2;
}

function multiply($arg1, $arg2)
{
    return $arg1 * $arg2;
}

function divide($arg1, $arg2)
{
    if ($arg2 == 0) {
        return 'На ноль делить нельзя';
    }

    return $arg1 / $arg2;
}

//Задание 4 Выбираем операцию по строке и используем функции из задания 3
function mathOperation($arg1, $arg2, $operation)
{
    switch ($operation) {
        case 'add':
            return add($arg1, $arg2);
        case 'subtract':
            return subtract($arg1, $arg2);
        case 'multiply':
            return multiply($arg1, $arg2);
        case 'divide':
            return divide($arg1, $arg2);
        default:
            return 'Неизвестная операция';
    }
}

$arg1 = 12;
$arg2 = 4;

echo "Задание 3\n";
echo "{$arg1} + {$arg2} = " . add($arg1, $arg2) . "\n";
echo "{$arg1} - {$arg2} = " . subtract($arg1, $arg2) . "\n";
echo "{$arg1} * {$arg2} = " . multiply($arg1, $arg2) . "\n";
echo "{$arg1} / {$arg2} = " . divide($arg1, $arg2) . "\n\n";

echo "Задание 4\n";
echo "Сложение: " . mathOperation($arg1, $arg2, 'add') . "\n";
echo "Вычитание: " . mathOperation($arg1, $arg2, 'subtract') . "\n";
echo "Умножение: " . mathOperation($arg1, $arg2, 'multiply') . "\n";
echo "Деление: " . mathOperation($arg1, $arg2, 'divide') . "\n\n";

//Задание 6 Рекурсивно возводим число в степень
function power($val, $pow)
{
    if ($pow === 0) {
        return 1;
    }

    if ($pow < 0) {
        return 1 / power($val, -$pow);
    }

    return $val * power($val, $pow - 1);
}

echo "Задание 6\n";
echo "power(2, 8) = " . power(2, 8) . "\n";
echo "power(5, -2) = " . power(5, -2) . "\n";
