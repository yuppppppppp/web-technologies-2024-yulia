<?php
declare(strict_types=1);

const IMAGE_DIR = __DIR__ . '/uploads/images';
const THUMB_DIR = __DIR__ . '/uploads/thumbs';
const IMAGE_URL = 'uploads/images';
const THUMB_URL = 'uploads/thumbs';
const MAX_SIZE = 5 * 1024 * 1024;

function makeDir(string $dir): void
{
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

function logRequest(): void
{
    $logFile = __DIR__ . '/log.txt';
    file_put_contents($logFile, date('Y-m-d H:i:s') . PHP_EOL, FILE_APPEND | LOCK_EX);

    $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false || count($lines) < 10) {
        return;
    }

    $number = 0;
    while (file_exists(__DIR__ . "/log{$number}.txt")) {
        $number++;
    }

    rename($logFile, __DIR__ . "/log{$number}.txt");
}

function getImages(string $dir): array
{
    $images = [];
    foreach (scandir($dir) ?: [] as $file) {
        $path = $dir . '/' . $file;
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        if (is_file($path) && in_array($ext, ['jpg', 'jpeg', 'png', 'gif'], true)) {
            $images[] = $file;
        }
    }

    sort($images, SORT_NATURAL | SORT_FLAG_CASE);
    return $images;
}

function resizeImage(string $sourcePath, string $targetPath, string $mime, int $maxWidth, int $maxHeight): bool
{
    $size = getimagesize($sourcePath);
    if ($size === false) {
        return false;
    }

    [$width, $height] = $size;
    $ratio = min($maxWidth / $width, $maxHeight / $height, 1);
    $newWidth = (int) round($width * $ratio);
    $newHeight = (int) round($height * $ratio);

    if ($mime === 'image/jpeg') {
        $source = imagecreatefromjpeg($sourcePath);
    } elseif ($mime === 'image/png') {
        $source = imagecreatefrompng($sourcePath);
    } else {
        $source = imagecreatefromgif($sourcePath);
    }

    if ($source === false) {
        return false;
    }

    $image = imagecreatetruecolor($newWidth, $newHeight);
    if ($mime !== 'image/jpeg') {
        imagealphablending($image, false);
        imagesavealpha($image, true);
    }

    imagecopyresampled($image, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    if ($mime === 'image/jpeg') {
        $result = imagejpeg($image, $targetPath, 90);
    } elseif ($mime === 'image/png') {
        $result = imagepng($image, $targetPath, 6);
    } else {
        $result = imagegif($image, $targetPath);
    }

    imagedestroy($source);
    imagedestroy($image);

    return $result;
}

function uploadImage(array $file): string
{

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return 'Файл не загрузился :(';
    }

    if ($file['size'] > MAX_SIZE) {
        return 'Размер файла должен быть не больше 5 МБ :(';
    }

    $info = getimagesize($file['tmp_name']);
    if ($info === false) {
        return 'Можно загружать только изображения :(';
    }

    $mime = $info['mime'];
    $extensions = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
    ];

    if (!isset($extensions[$mime])) {
        return 'Разрешены только JPG, PNG и GIF.';
    }

    $fileName = uniqid('image_', true) . '.' . $extensions[$mime];
    $imagePath = IMAGE_DIR . '/' . $fileName;
    $thumbPath = THUMB_DIR . '/' . $fileName;

    if (!resizeImage($file['tmp_name'], $imagePath, $mime, 1200, 900)) {
        return 'Не удалось сохранить изображение :(';
    }

    if (!resizeImage($imagePath, $thumbPath, $mime, 220, 160)) {
        unlink($imagePath);
        return 'Не удалось создать миниатюру :(';
    }

    return '';
}

function renderGallery(string $dir): string
{
    $images = getImages($dir);
    if ($images === []) {
        return '<p class="empty">Изображений пока нет</p>';
    }

    $html = '<div class="gallery">';
    foreach ($images as $image) {
        $file = rawurlencode($image);
        $alt = htmlspecialchars(pathinfo($image, PATHINFO_FILENAME), ENT_QUOTES, 'UTF-8');
        $bigImage = IMAGE_URL . '/' . $file;
        $thumb = is_file(THUMB_DIR . '/' . $image) ? THUMB_URL . '/' . $file : $bigImage;

        $html .= '<a class="gallery__item" href="' . $bigImage . '" target="_blank" data-full="' . $bigImage . '">';
        $html .= '<img src="' . $thumb . '" alt="' . $alt . '" width="220">';
        $html .= '</a>';
    }

    return $html . '</div>';
}

makeDir(IMAGE_DIR);
makeDir(THUMB_DIR);
logRequest();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $error = uploadImage($_FILES['image']);
    if ($error === '') {
        header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
        exit;
    }
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Лабораторная работа 19</title>
    <link rel="stylesheet" href="./src/assets/styles/style.css">
</head>
<body>
<main class="page">
    <h1>Фотогалерея человека с отличным вкусом)</h1>

    <form class="upload-form" action="" method="post" enctype="multipart/form-data">
        <label for="image">Новое изображение</label>
        <div class="upload-form__row">
            <input id="image" type="file" name="image" accept="image/jpeg,image/png,image/gif" required>
            <button type="submit">Загрузить</button>
        </div>
        <p>JPG, PNG или GIF до 5 МБ.</p>
        <?php if ($error !== ''): ?>
            <p class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
    </form>

    <?= renderGallery(IMAGE_DIR) ?>
</main>

<div class="viewer" id="viewer">
    <button class="viewer__close" type="button" aria-label="Закрыть">x</button>
    <img class="viewer__image" src="" alt="">
</div>

<script>
    const viewer = document.getElementById('viewer');
    const viewerImage = viewer.querySelector('.viewer__image');
    const closeButton = viewer.querySelector('.viewer__close');

    document.querySelectorAll('.gallery__item').forEach((link) => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            viewerImage.src = link.dataset.full;
            viewer.classList.add('viewer--open');
        });
    });

    function closeViewer() {
        viewer.classList.remove('viewer--open');
        viewerImage.src = '';
    }

    closeButton.addEventListener('click', closeViewer);
    viewer.addEventListener('click', (event) => {
        if (event.target === viewer) {
            closeViewer();
        }
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeViewer();
        }
    });
</script>
</body>
</html>
