<?php
$basePath = realpath(__DIR__ . '/../../public/assets/images');
if ($basePath === false) {
    throw new RuntimeException('Images directory missing.');
}
if (!is_dir($basePath)) {
    mkdir($basePath, 0775, true);
}

function pngChunk(string $type, string $data): string
{
    return pack('N', strlen($data)) . $type . $data . pack('N', crc32($type . $data));
}

function blend(array $start, array $end, float $ratio): array
{
    $ratio = max(0, min(1, $ratio));
    return [
        (int) round($start[0] * (1 - $ratio) + $end[0] * $ratio),
        (int) round($start[1] * (1 - $ratio) + $end[1] * $ratio),
        (int) round($start[2] * (1 - $ratio) + $end[2] * $ratio),
    ];
}

function makeImage(int $width, int $height, callable $pixelGenerator, string $path): void
{
    $raw = '';
    for ($y = 0; $y < $height; $y++) {
        $raw .= chr(0);
        for ($x = 0; $x < $width; $x++) {
            [$r, $g, $b] = $pixelGenerator($x, $y, $width, $height);
            $raw .= chr($r) . chr($g) . chr($b);
        }
    }

    $header = "\x89PNG\r\n\x1a\n";
    $ihdr = pack('NNCCCCC', $width, $height, 8, 2, 0, 0, 0);
    $png = $header . pngChunk('IHDR', $ihdr) . pngChunk('IDAT', gzcompress($raw, 9)) . pngChunk('IEND', '');

    file_put_contents($path, $png);
}

$heroStart = [79, 70, 229];
$heroEnd = [20, 184, 166];
makeImage(1400, 900, function (int $x, int $y, int $w, int $h) use ($heroStart, $heroEnd) {
    $ratio = ($x / max($w - 1, 1)) * 0.35 + ($y / max($h - 1, 1)) * 0.65;
    $base = blend($heroStart, $heroEnd, $ratio);
    $circleDist = sqrt(($x - 340) ** 2 + ($y - 440) ** 2);
    if ($circleDist < 220) {
        $boost = 1 - ($circleDist / 220);
        $base = [
            min(255, $base[0] + (int) (90 * $boost)),
            min(255, $base[1] + (int) (70 * $boost)),
            min(255, $base[2] + (int) (70 * $boost)),
        ];
    }
    if ($x > 860 && $x < 1320 && $y > 220 && $y < 660) {
        $base = [min(255, $base[0] + 35), min(255, $base[1] + 35), min(255, $base[2] + 35)];
    }
    $lineY = (int) (($x * 0.5) + 220);
    if ($y > $lineY - 12 && $y < $lineY + 12) {
        $base = [min(255, $base[0] + 80), min(255, $base[1] + 80), min(255, $base[2] + 80)];
    }
    return $base;
}, $basePath . '/hero.png');

$bannerStart = [20, 184, 166];
$bannerEnd = [14, 116, 144];
makeImage(1400, 500, function (int $x, int $y, int $w, int $h) use ($bannerStart, $bannerEnd) {
    $ratio = ($x / max($w - 1, 1)) * 0.2 + ($y / max($h - 1, 1)) * 0.8;
    $base = blend($bannerStart, $bannerEnd, $ratio);
    for ($i = 0; $i < 5; $i++) {
        $cx = 160 + $i * 220;
        $cy = 260;
        $dist = sqrt(($x - $cx) ** 2 + ($y - $cy) ** 2);
        if ($dist > 140 && $dist < 175) {
            $base = [min(255, $base[0] + 65), min(255, $base[1] + 65), min(255, $base[2] + 65)];
        }
    }
    return $base;
}, $basePath . '/dashboard-banner.png');

$emptyBg = [248, 250, 252];
$emptyCard = [226, 232, 240];
$emptyLine = [148, 163, 184];
makeImage(900, 600, function (int $x, int $y) use ($emptyBg, $emptyCard, $emptyLine) {
    $color = $emptyBg;
    if ($x > 140 && $x < 760 && $y > 200 && $y < 440) {
        $color = $emptyCard;
    }
    if ($y > 240 && $y < 260 && $x > 200 && $x < 700) {
        $color = $emptyLine;
    }
    if (($x > 220 && $x < 430 && $y > 280 && $y < 410) || ($x > 470 && $x < 700 && $y > 280 && $y < 360)) {
        $color = [255, 255, 255];
    }
    return $color;
}, $basePath . '/empty-state.png');

$categoryPalettes = [
    'category-music.png' => [[79, 70, 229], [139, 92, 246]],
    'category-programming.png' => [[20, 184, 166], [14, 165, 233]],
    'category-design.png' => [[99, 102, 241], [244, 114, 182]],
    'category-wellness.png' => [[56, 189, 248], [16, 185, 129]],
];

foreach ($categoryPalettes as $file => $palette) {
    makeImage(320, 320, function (int $x, int $y, int $w, int $h) use ($palette) {
        $ratio = ($x / max($w - 1, 1)) * 0.5 + ($y / max($h - 1, 1)) * 0.5;
        $base = blend($palette[0], $palette[1], $ratio);
        if ($x > 60 && $x < 260 && $y > 60 && $y < 260) {
            $base = [min(255, $base[0] + 25), min(255, $base[1] + 25), min(255, $base[2] + 25)];
        }
        if ($y > 110 && $y < 115 || $y > 150 && $y < 155 || $y > 190 && $y < 195) {
            $base = [255, 255, 255];
        }
        return $base;
    }, $basePath . '/' . $file);
}

$postPalettes = [
    'post_guitar_1.png' => [[244, 114, 182], [251, 191, 36]],
    'post_react_1.png' => [[59, 130, 246], [129, 140, 248]],
    'post_ux_1.png' => [[45, 212, 191], [16, 185, 129]],
];

foreach ($postPalettes as $file => $palette) {
    makeImage(1200, 800, function (int $x, int $y, int $w, int $h) use ($palette) {
        $ratio = $y / max($h - 1, 1);
        $base = blend($palette[0], $palette[1], $ratio);
        if ($y % 160 < 18) {
            $base = [255, 255, 255];
        }
        return $base;
    }, $basePath . '/' . $file);
}

$profilePalettes = [
    'profile-alice.png' => [[239, 68, 68], [248, 113, 113]],
    'profile-ben.png' => [[59, 130, 246], [96, 165, 250]],
];

foreach ($profilePalettes as $file => $palette) {
    makeImage(400, 400, function (int $x, int $y, int $w, int $h) use ($palette) {
        $ratio = $y / max($h - 1, 1);
        $base = blend($palette[0], $palette[1], $ratio);
        $dist = sqrt(($x - 200) ** 2 + ($y - 200) ** 2);
        if ($dist < 160) {
            $base = [255, 255, 255];
        }
        return $base;
    }, $basePath . '/' . $file);
}

echo "Procedural PNG assets generated in {$basePath}" . PHP_EOL;
