<!-- genre.php
장르 선택 페이지 -->
<?php
$conn = mysqli_connect('localhost', 'root', '1111', 'review_system');

// 연결 오류 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT filename, genre, title FROM images";
$result = mysqli_query($conn, $sql);

$images = [];
while ($row = mysqli_fetch_assoc($result)) {
    $images[$row['genre']] = $row; // 장르별로 데이터 저장
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>장르선택</title>
    <link rel="stylesheet" href="../css/s.css">
</head>
<style>
    img{
        position:relative;
    }
    main>a:hover{
        opacity: 70%;
    }
    h1{
        position: absolute;
        top: 500px;
        left: 530px;
    }
    h2{
        position: absolute;
        top: 100px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 1;
        color: white;
        font-size: 24px;
        font-weight: bold;
        text-shadow: 2px 2px 4px black, -2px -2px 4px black, 2px -2px 4px black, -2px 2px 4px black;
    }
</style>
<body>
    <main>
        <?php if (isset($images['movie1'])): ?>
            <a href="../home/home.php?genre=movie" class="movie">
                <img src="./<?= htmlspecialchars($images['movie1']['filename']) ?>" alt="<?= htmlspecialchars($images['movie1']['title']) ?>">
                <h2>영화</h2>
            </a>
        <?php endif; ?>

        <?php if (isset($images['drama1'])): ?>
            <a href="../home/home.php?genre=drama" class="drama">
                <img src="./<?= htmlspecialchars($images['drama1']['filename']) ?>" alt="<?= htmlspecialchars($images['drama1']['title']) ?>">
                <h2>드라마</h2>
            </a>
        <?php endif; ?>

        <?php if (isset($images['anima1'])): ?>
            <a href="../home/home.php?genre=anima" class="anima">
                <img src="./<?= htmlspecialchars($images['anima1']['filename']) ?>" alt="<?= htmlspecialchars($images['anima1']['title']) ?>">
                <h2>애니메이션</h2>
            </a>
        <?php endif; ?>
    </main>
    <h1>원하는 장르를 선택하세요</h1>
</body>
</html>
<?php mysqli_close($conn); ?>
