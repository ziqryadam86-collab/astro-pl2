<?php
// 1. Ambil data dari website sumber
$url = "https://www.kds.tw/tv/sports-tv-live-streaming/astro-pl-2/";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
$response = curl_exec($ch);
curl_close($ch);

// 2. Cari link m3u8 guna Regex (Cara PHP lebih tepat)
preg_match('/https?:\/\/[^"\']+\.m3u8[^"\']*/', $response, $matches);
$live_link = isset($matches[0]) ? $matches[0] : "";

// 3. Bina kandungan fail M3U8
$m3u8_content = "#EXTM3U\n";
$m3u8_content .= "#EXT-X-STREAM-INF:BANDWIDTH=1280000,RESOLUTION=1280x720\n";

if (!empty($live_link)) {
    $m3u8_content .= $live_link . "\n";
    echo "Link dijumpai: " . $live_link;
} else {
    $m3u8_content .= "http://link-tidak-dijumpai.m3u8\n";
    echo "Ralat: Link tidak dijumpai!";
}

// 4. Simpan ke fail astro.m3u8 supaya GitHub boleh baca
file_put_contents("astro.m3u8", $m3u8_content);
?>

