<?php
$conn = mysqli_connect('localhost', 'root', '1110', 'review_system');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// GET 요청으로 장르 값 가져오기
$genre = isset($_GET['genre']) ? $_GET['genre'] : 'movie';  // 기본값 movie

// 검색어 처리
$query = isset($_GET['query']) ? $_GET['query'] : '';

// 해당 장르의 모든 세부 장르(movie1, movie2 등) 가져오기
$sql = "SELECT filename, title, genre FROM images WHERE genre LIKE '$genre%'";
$result = mysqli_query($conn, $sql);

$items = [];
while ($row = mysqli_fetch_assoc($result)) {
    $items[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>홈</title>
    <link rel="stylesheet" href="../s.css">
    <style>
        h1 {
            color: red;
        }
        h2>a:hover {
            color: rgb(170, 170, 170);
        }
        header {
            width: 600px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .sort {
            width: 150px;
            display: flex;
            justify-content: space-between;
        }
        main {
            width: 1400px;
            display: flex;
            justify-content: space-evenly;
            flex-wrap: wrap;
        }
        main>a {
            margin: 20px;
        }
        .text {
            width: 990px;
            margin-left: 200px;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        img:hover {
            opacity: 70%;
        }
        .search {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }
        form>input {
            width: 1000px;
            height: 30px;
            text-align: center;
            background: rgb(43, 43, 43);
            font-weight: bold;
            font-size: 20px;
            color: white;
        }
        form>button {
            height: 35px;
            background: rgb(74, 74, 74);
            color: rgb(179, 179, 179);
            font-weight: bold;
        }
        h4 {
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <h1>Netflix</h1>
        <h2><a href="../account/logout.php">로그아웃</a></h2>
        <h2><a href="../account/account_info.php">계정정보</a></h2>
        <h2><a href="../genre/genre.php">장르선택</a></h2>
    </header>

    <!-- 검색창 -->
    <div class="search">
        <form method="GET" action="">
            <input type="hidden" name="genre" value="<?= htmlspecialchars($genre) ?>">
            <input type="text" name="query" placeholder="제목을 입력하세요" value="<?= htmlspecialchars($query) ?>" required>
            <button type="submit">검색</button>
        </form>
    </div>

    <!-- 정렬 옵션 -->
    <div class="sort">
        <h2>정렬</h2>
        <h2>|</h2>
        <h2><a href="sort.php">별점순</a></h2>
    </div>

    <!-- 콘텐츠 출력 -->
    <main>
        <?php foreach ($items as $item): 
            $isVisible = ($query === '' || stripos($item['title'], $query) !== false);
        ?>
            <a href="../content/content.php?genre=<?= htmlspecialchars($item['genre']) ?>" class="<?= htmlspecialchars($item['genre']) ?>" <?= !$isVisible ? "style='visibility: hidden;'" : "" ?>>
                <img src="<?= htmlspecialchars($item['filename']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
            </a>
        <?php endforeach; ?>
    </main>

    <!-- 콘텐츠 제목 출력 -->
    <div class="text">
        <?php foreach ($items as $item): 
            $isVisible = ($query === '' || stripos($item['title'], $query) !== false);
        ?>
            <h2 <?= !$isVisible ? "style='visibility: hidden;'" : "" ?>><?= htmlspecialchars($item['title']) ?></h2>
        <?php endforeach; ?>
    </div>
</body>
</html>

<?php mysqli_close($conn); ?>
