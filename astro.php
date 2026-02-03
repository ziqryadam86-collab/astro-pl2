<?php
// 1. Ambil data dari website sumber
$url = "https://www.kds.tw/tv/sports-tv-live-streaming/astro-pl-2/";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36');
$response = curl_exec($ch);
curl_close($ch);

// 2. Teknik Sniper: Cari link m3u8 yang tersembunyi
// Kita cari apa-apa yang nampak macam link .m3u8
preg_match_all('/https?:\/\/[^"\']+\.m3u8[^"\']*/', $response, $matches);

// Ambil link pertama yang dijumpai
$live_link = "";
if (!empty($matches[0])) {
    foreach ($matches[0] as $link) {
        if (strpos($link, 'astro') !== false || strpos($link, 'stream') !== false) {
            $live_link = $link;
            break;
        }
    }
    // Jika masih tak jumpa yang spesifik, ambil saja yang pertama ada .m3u8
    if (empty($live_link)) { $live_link = $matches[0][0]; }
}

// 3. Bina kandungan fail M3U8
$m3u8_content = "#EXTM3U\n";
$m3u8_content .= "#EXT-X-STREAM-INF:BANDWIDTH=1280000,RESOLUTION=1280x720\n";

if (!empty($live_link)) {
    // Bersihkan link daripada simbol pelik
    $live_link = stripslashes($live_link);
    $m3u8_content .= $live_link . "\n";
    echo "BERJAYA! Link dijumpai: " . $live_link;
} else {
    // Jika buntu, kita guna link alternatif (Direct Stream)
    $backup_link = "https://tglmp.com/live/pl2.m3u8"; 
    $m3u8_content .= $backup_link . "\n";
    echo "Menggunakan link backup.";
}

// 4. Simpan ke fail
file_put_contents("astro.m3u8", $m3u8_content);
?>
