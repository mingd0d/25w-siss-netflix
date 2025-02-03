<!-- 별점순 정렬 미완성 -->
<?php
require('home.php');
?>
<?php
$conn = mysqli_connect('localhost', 'root', '1110', 'review_system');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 영화 데이터 배열
$movies = [
    ['title' => '발레리나', 'image' => './movie1.png', 'genre' => 'movie1'],
    ['title' => '전,란', 'image' => './movie2.png', 'genre' => 'movie2'],
    ['title' => '크로스', 'image' => './movie3.png', 'genre' => 'movie3']
];

// 별점 데이터 가져오기
$starData = [];
foreach ($movies as $movie) {
    $genre = $movie['genre'];
    $query = "SELECT AVG(star) as avg_star FROM reviews WHERE genre = '$genre'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $starData[$genre] = $row['avg_star'] ?? null; // 별점이 없으면 null
}

// 별점순 정렬
usort($movies, function ($a, $b) use ($starData) {
    $starA = $starData[$a['genre']] ?? -1; // 별점이 없으면 -1로 처리
    $starB = $starData[$b['genre']] ?? -1;
    return $starB <=> $starA; // 높은 별점순으로 정렬
});

// 검색어 처리
$query = isset($_GET['query']) ? $_GET['query'] : '';

// 영화 출력
foreach ($movies as $movie) {
    $isVisible = ($query === '' || stripos($movie['title'], $query) !== false);
    $avgStar = isset($starData[$movie['genre']]) ? number_format($starData[$movie['genre']], 1) : '등록된 별점이 없습니다'; // 소수점 아래 1자리 출력
    echo "<a href='content.php?genre={$movie['genre']}' class='{$movie['genre']}'>
            <img src='{$movie['image']}' alt='{$movie['title']}'>
          </a>";
    echo "<h4>{$movie['title']} (⭐ {$avgStar})</h4>";
}
$conn->close();
?>
        </div>
    </body>
</html>