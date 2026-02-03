<?php
$url = "https://www.kds.tw/tv/sports-tv-live-streaming/astro-pl-2/";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36');
$response = curl_exec($ch);
curl_close($ch);

preg_match_all('/https?:\/\/[^"\']+\.m3u8[^"\']*/', $response, $matches);

$live_link = "";
if (!empty($matches[0])) {
    foreach ($matches[0] as $link) {
        if (strpos($link, 'astro') !== false || strpos($link, 'stream') !== false) {
            $live_link = stripslashes($link);
            break;
        }
    }
}

// JIKA MASIH GAGAL, GUNA LINK ALTERNATIF YANG LEBIH STABIL
if (empty($live_link)) {
    $live_link = "https://tglmp.com/live/pl2.m3u8";
}

$m3u8_content = "#EXTM3U\n";
// KITA TAMBAH USER-AGENT DALAM M3U8 SUPAYA VLC/IPTV PLAYER BOLEH LEPAS
$m3u8_content .= '#EXTINF:-1 tvg-id="AstroPL2" tvg-name="Astro Premier League 2" group-title="SPORTS",Astro Premier League 2' . "\n";
$m3u8_content .= '#EXTVLCOPT:http-user-agent="Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36"' . "\n";
$m3u8_content .= $live_link . "|User-Agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36\n";

file_put_contents("astro.m3u8", $m3u8_content);
echo "Kemaskini selesai dengan User-Agent.";
?>
