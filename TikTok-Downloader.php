<?php
// Check if 'link' parameter is missing
if (!isset($_GET['link'])) {
  header('Content-Type: application/json');
  echo json_encode(['error' => 'Missing link parameter']);
  exit();
}

$link = $_GET['link'];
$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => 'https://www.tikwm.com/api/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_POST => true,
  CURLOPT_POSTFIELDS => http_build_query(['url' => $link, 'hd' => 1]),
]);

$response = curl_exec($curl);
curl_close($curl);

$data = json_decode($response, true);

if (empty($data['data'])) {
  echo json_encode(['error' => 'Invalid TikTok URL']);
  exit();
}

// Simplified response
$result = [
  'username' => $data['data']['author']['unique_id'],
  'views' => $data['data']['play_count'],
  'likes' => $data['data']['digg_count'],
  'comments' => $data['data']['comment_count'],
  'thumbnail' => 'https://www.tikwm.com'.$data['data']['cover'],
  'downloads' => [
    'video_hd' => 'https://www.tikwm.com'.$data['data']['hdplay'],
    'video_sd' => 'https://www.tikwm.com'.$data['data']['wmplay'],
    'music' => 'https://www.tikwm.com'.$data['data']['music'] // Added music download
  ]
];

header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT);