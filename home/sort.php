<?php
require('home.php');

$conn = mysqli_connect('localhost', 'root', '1111', 'review_system');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// GET 요청으로 장르 값 가져오기
$genre = isset($_GET['genre']) ? mysqli_real_escape_string($conn, $_GET['genre']) : 'movie'; // 기본값 movie

// 검색어 처리
$query = isset($_GET['query']) ? mysqli_real_escape_string($conn, $_GET['query']) : '';

// 해당 장르의 모든 세부 장르 가져오기
$sql = "SELECT filename, title, genre FROM images WHERE genre LIKE '$genre%'";
$result = mysqli_query($conn, $sql);

$movies = [];
while ($row = mysqli_fetch_assoc($result)) {
    $movies[] = $row;
}

// 별점 데이터 가져오기
$starData = [];

foreach ($movies as $movie) {
    $movieGenre = $movie['genre']; // 기존 $genre를 덮어쓰지 않도록 변경
    $query = "SELECT AVG(star) as avg_star FROM reviews WHERE genre = '$movieGenre'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $starData[$movieGenre] = $row['avg_star'] ?? null; // 별점이 없으면 null
}

// 별점순 정렬
usort($movies, function ($a, $b) use ($starData) {
    $starA = $starData[$a['genre']] ?? -1; // 별점이 없으면 -1로 처리
    $starB = $starData[$b['genre']] ?? -1;
    return $starB <=> $starA; // 높은 별점순으로 정렬
});

// 영화 출력
foreach ($movies as $movie) {
    $isVisible = ($query === '' || stripos($movie['title'], $query) !== false);
    $avgStar = isset($starData[$movie['genre']]) ? number_format($starData[$movie['genre']], 1) : '등록된 별점이 없습니다'; // 소수점 아래 1자리 출력
    
    echo "<a href='../content/content.php?genre={$movie['genre']}' class='{$movie['genre']}'>
            <img src='{$movie['filename']}' alt='{$movie['title']}'>
          </a>";
    echo "<h4>{$movie['title']} (⭐ {$avgStar})</h4>";
}

$conn->close();
?>
