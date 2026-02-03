<?php
// Guna sumber alternatif (OKSTREAM / MHdtvWorld)
$url = "https://mhdworld.com/live/astro-premier-league-2/";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 10; SM-G960U) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Mobile Safari/537.36');
$response = curl_exec($ch);
curl_close($ch);

// Cari link m3u8 yang ada token
preg_match('/https?:\/\/[^"\']+\.m3u8[^"\']*/', $response, $matches);
$live_link = isset($matches[0]) ? $matches[0] : "";

$m3u8_content = "#EXTM3U\n";
$m3u8_content .= "#EXTINF:-1,Astro Premier League 2\n";

if (!empty($live_link)) {
    // Bersihkan link daripada simbol pelik
    $live_link = stripslashes($live_link);
    $m3u8_content .= $live_link . "\n";
    echo "BERJAYA! Jumpa link baru: " . $live_link;
} else {
    // Kalau semua gagal, kita terpaksa guna link statik yang masih aktif (jika ada)
    $m3u8_content .= "https://stream.tglmp.com/hls/pl2.m3u8\n";
    echo "Gagal cari link baru, guna backup stream.";
}

file_put_contents("astro.m3u8", $m3u8_content);
?>
