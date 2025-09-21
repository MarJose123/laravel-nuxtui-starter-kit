<?php
/*
 * File: updater.php
 * Last Modified: 8/12/25, 4:17 PM
 *
 * Copyright (c) 2025 Marjose Darang. - All Rights Reserved
 *
 */


function getLatestReleaseInfo() {
    $url = "https://api.github.com/repos/axllent/mailpit/releases/latest";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'mailpit-updater script v1'); // GitHub API requires a User-Agent

    $response = curl_exec($ch);
    curl_close($ch);

    if (!$response) {
        die("Failed to fetch release info.\n");
    }

    return json_decode($response, true);
}

function downloadFile($url, $destination): void
{
    $file = fopen($destination, 'w');
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_FILE, $file);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'mailpit-updater script v1');
    curl_exec($ch);
    curl_close($ch);
    fclose($file);
}

function extractMailpitExe($zipPath, $targetDir): void
{
    // First, try to stop any running mailpit process
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        // Windows
        exec('taskkill /F /IM mailpit.exe 2>NUL');
    } else {
        // macOS and Linux
        exec('killall mailpit 2>/dev/null');
    }
    sleep(2);

    $zip = new ZipArchive();
    if ($zip->open($zipPath) === TRUE) {
        // Check if the file exists in the zip
        if ($zip->locateName('mailpit.exe') === false) {
            $zip->close();
            die("❌  mailpit.exe not found in zip file.\n");
        }

        // Create a target directory if it doesn't exist
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // Extract the specific file
        if (!$zip->extractTo($targetDir, 'mailpit.exe')) {
            $zip->close();
            die("❌  Failed to extract mailpit.exe.\n");
        }

        $zip->close();
        echo "✅  Extracted Mailpit\n";
    } else {
        die("❌  Failed to open zip file.\n");
    }
}

function getLocalVersion($exePath): ?string
{
    if (!file_exists($exePath)) {
        return null;
    }

    $output = [];
    exec("\"$exePath\" version", $output);
    if (!empty($output)) {
        // Extract version like "v1.27.2" using regex
        if (preg_match('/v\d+\.\d+\.\d+/', $output[0], $matches)) {
            return $matches[0];
        }
    }
    return null;
}

// Main script logic
$release = getLatestReleaseInfo();
$latestVersion = $release['tag_name'];
echo "🔍  Latest version on GitHub: $latestVersion\n";

$zipAsset = null;
foreach ($release['assets'] as $asset) {
    if (str_contains($asset['name'], 'mailpit-windows-amd64.zip')) {
        $zipAsset = $asset['browser_download_url'];
        break;
    }
}

if (!$zipAsset) {
    die("❌  Could not find Windows x64 zip asset in latest release.\n");
}

$scriptDir = __DIR__;
$exePath = "$scriptDir/mailpit.exe";
$zipPath = "$scriptDir/mailpit-windows-amd64.zip";

$currentVersion = getLocalVersion($exePath);
if ($currentVersion) {
    echo "📦  Local Mailpit version: $currentVersion\n";
} else {
    echo "⚠️  Mailpit not found locally or version not detected.\n";
}

if (!$currentVersion || stripos($currentVersion, $latestVersion) === false) {
    echo "⬇️  Downloading and updating to $latestVersion...\n";
    downloadFile($zipAsset, $zipPath);
    extractMailpitExe($zipPath, $scriptDir);
    unlink($zipPath); // Cleanup zip file
    echo "✅  Update complete.\n";
} else {
    echo "✔️  Already up-to-date.\n";
}
