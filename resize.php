<?php
$sourcePath = __DIR__ . '/public/images/colt.png';
$info = getimagesize($sourcePath);
echo "Original size: " . $info[0] . "x" . $info[1] . "\n";

// We want to create a webp version that is much smaller, or just resize it
$image = imagecreatefrompng($sourcePath);
imagepalettetotruecolor($image);
imagealphablending($image, true);
imagesavealpha($image, true);

// Resize to 256x256 max to preserve some quality for high DPI displays
$maxWidth = 256;
$maxHeight = 256;

$ratio = min($maxWidth / $info[0], $maxHeight / $info[1]);
if ($ratio < 1) {
    $newWidth = $info[0] * $ratio;
    $newHeight = $info[1] * $ratio;
} else {
    $newWidth = $info[0];
    $newHeight = $info[1];
}

$resized = imagecreatetruecolor($newWidth, $newHeight);
imagealphablending($resized, false);
imagesavealpha($resized, true);
$transparent = imagecolorallocatealpha($resized, 255, 255, 255, 127);
imagefilledrectangle($resized, 0, 0, $newWidth, $newHeight, $transparent);

imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $info[0], $info[1]);

// Save as WebP for optimal compression
$targetPath = __DIR__ . '/public/images/colt.webp';
imagewebp($resized, $targetPath, 80);

echo "Saved optimized image to: $targetPath\n";
echo "New size: " . filesize($targetPath) . " bytes\n";
