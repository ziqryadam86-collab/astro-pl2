<?php
// 1. Sumber Utama
$pageUrl = "https://www.kds.tw/tv/sports-tv-live-streaming/astro-pl-2/";
$ch = curl_init($pageUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// Kita menyamar jadi iPhone supaya link m3u8 senang keluar
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Mobile/15E148 Safari/604.1");
$html = curl_exec($ch);
curl_close($ch);

// 2. Cari link m3u8 (Teknik Sniper)
$live_link = "";
if (preg_match('/https?:\/\/[^"\']+\.m3u8[^"\']*/', $html, $matches)) {
    $live_link = stripslashes($matches[0]);
}

// 3. Bina fail m3u8 ikut format yang anda nak
$m3u8_content = "#EXTM3U\n#EXT-X-INDEPENDENT-SEGMENTS\n";

if (!empty($live_link) && strpos($live_link, 'http') !== false) {
    // Template kualiti yang anda minta
    $qualities = [
        "546239,RESOLUTION=426x240",
        "1568726,RESOLUTION=854x480",
        "4370178,RESOLUTION=1280x720",
        "7171631,RESOLUTION=1920x1080"
    ];

    foreach ($qualities as $q) {
        $m3u8_content .= "#EXT-X-STREAM-INF:BANDWIDTH=" . $q . ",FRAME-RATE=60\n";
        $m3u8_content .= $live_link . "\n";
    }
} else {
    // Link Backup sementara jika KDS block robot GitHub
    $m3u8_content .= "#EXT-X-STREAM-INF:BANDWIDTH=1280000,RESOLUTION=1280x720\n";
    $m3u8_content .= "https://tglmp.com/live/pl2.m3u8\n";
}

file_put_contents("astro.m3u8", $m3u8_content);
echo "Update Selesai!";
?>
