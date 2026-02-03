<?php
// Senarai User-Agent untuk elak kena block
$ua = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36";

$url = "https://www.kds.tw/tv/sports-tv-live-streaming/astro-pl-2/";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_USERAGENT, $ua);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
$html = curl_exec($ch);
curl_close($ch);

// Cari link m3u8 menggunakan Regex
preg_match('/https?:\/\/[^"\']+\.m3u8[^"\']*/', $html, $matches);
$live_link = isset($matches[0]) ? stripslashes($matches[0]) : "";

// Bina fail M3U8
$m3u8 = "#EXTM3U\n#EXT-X-INDEPENDENT-SEGMENTS\n";

if (!empty($live_link) && strpos($live_link, 'http') !== false) {
    // Format Bitrate yang anda mahu
    $bitrates = [
        "546239,RESOLUTION=426x240",
        "1568726,RESOLUTION=854x480",
        "4370178,RESOLUTION=1280x720",
        "7171631,RESOLUTION=1920x1080"
    ];
    foreach ($bitrates as $b) {
        $m3u8 .= "#EXT-X-STREAM-INF:BANDWIDTH=" . $b . ",FRAME-RATE=60\n" . $live_link . "\n";
    }
    echo "BERJAYA: Jumpa link!";
} else {
    // JIKA GAGAL, GUNA LINK BACKUP (Penting!)
    $backup = "https://tglmp.com/live/pl2.m3u8";
    $m3u8 .= "#EXT-X-STREAM-INF:BANDWIDTH=1280000,RESOLUTION=1280x720\n" . $backup . "\n";
    echo "GUNA BACKUP: Website asal sorok link.";
}

file_put_contents("astro.m3u8", $m3u8);
?>
