<?php
// 1. Ambil source code halaman Astro PL 2 (atau TV3)
$pageUrl = "https://www.kds.tw/tv/sports-tv-live-streaming/astro-pl-2/";
$ch = curl_init($pageUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36");
$html = curl_exec($ch);
curl_close($ch);

// 2. Cari pautan m3u8 menggunakan Regex yang lebih agresif
// Ia akan cari link yang ada .m3u8 walaupun terselip dalam JavaScript
preg_match('/https?:\/\/[^"\']+\.m3u8[^"\']*/', $html, $matches);
$live_link = isset($matches[0]) ? stripslashes($matches[0]) : "";

// 3. Bina kandungan fail astro.m3u8 ikut format yang anda minta
$m3u8_content = "#EXTM3U\n";
$m3u8_content .= "#EXT-X-INDEPENDENT-SEGMENTS\n";

if (!empty($live_link)) {
    // Susun format Bitrate/Resolusi secara manual mengikut template anda
    // 240p
    $m3u8_content .= '#EXT-X-STREAM-INF:BANDWIDTH=546239,CODECS="mp4a.40.5,avc1.4d4015",RESOLUTION=426x240,FRAME-RATE=30,VIDEO-RANGE=SDR,CLOSED-CAPTIONS=NONE' . "\n";
    $m3u8_content .= $live_link . "\n";
    
    // 480p
    $m3u8_content .= '#EXT-X-STREAM-INF:BANDWIDTH=1568726,CODECS="mp4a.40.2,avc1.4d401f",RESOLUTION=854x480,FRAME-RATE=30,VIDEO-RANGE=SDR,CLOSED-CAPTIONS=NONE' . "\n";
    $m3u8_content .= $live_link . "\n";

    // 720p 60fps
    $m3u8_content .= '#EXT-X-STREAM-INF:BANDWIDTH=4370178,CODECS="mp4a.40.2,avc1.4d4020",RESOLUTION=1280x720,FRAME-RATE=60,VIDEO-RANGE=SDR,CLOSED-CAPTIONS=NONE' . "\n";
    $m3u8_content .= $live_link . "\n";

    // 1080p 60fps
    $m3u8_content .= '#EXT-X-STREAM-INF:BANDWIDTH=7171631,CODECS="mp4a.40.2,avc1.64002a",RESOLUTION=1920x1080,FRAME-RATE=60,VIDEO-RANGE=SDR,CLOSED-CAPTIONS=NONE' . "\n";
    $m3u8_content .= $live_link . "\n";

    echo "Status: BERJAYA. Link dijumpai dan format disusun.";
} else {
    // Jika gagal, beritahu robot supaya jangan kosongkan fail
    $m3u8_content .= "# ERROR: Link tidak dijumpai pada waktu ini. Sila semak sumber KDS.\n";
    echo "Status: GAGAL. Website mungkin menggunakan proteksi baru.";
}

// 4. Simpan ke fail astro.m3u8
file_put_contents("astro.m3u8", $m3u8_content);
?>
