<?php
session_start();
$conn = mysqli_connect('localhost', 'root', '1111', 'review_system');

if (!$conn) {
    die("DB 연결 실패: " . mysqli_connect_error());
}

// 로그인 확인
if (!isset($_SESSION['userID'])) {
    die("로그인이 필요합니다.");
}

$userID = $_SESSION['userID']; // 현재 로그인한 사용자 ID

// GET 방식으로 genre 값 가져오기
$genre = isset($_GET['genre']) ? $conn->real_escape_string($_GET['genre']) : '';

// POST 방식으로 리뷰 등록 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['star'], $_POST['comment'])) {
    $genre = $conn->real_escape_string($_POST['genre']);
    $star = intval($_POST['star']);
    $comment = $conn->real_escape_string($_POST['comment']);

    $sql = "INSERT INTO reviews (genre, star, comment, user_id) VALUES ('$genre', '$star', '$comment', '$userID')";
    
    if ($conn->query($sql)) {
        // 중복 실행 방지
        header("Location: review.php?genre=" . urlencode($genre));
        exit();
    } else {
        echo "리뷰 등록 실패: " . $conn->error;
    }
}

// 해당 genre의 리뷰 목록 조회
$sql = "SELECT * FROM reviews WHERE genre = '$genre'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>리뷰 페이지</title>
    <link rel="stylesheet" href="../s.css">
</head>
<style>
    h1{
        margin-left:10px;
    }
    h1>a{
        margin-left:80%;
    }
    h3{
        color:white;
    }
    .text{
        color:white;
    }
    .review_content{
        background-color:rgb(67, 67, 67);
        width: 60%;
        margin-left:20%;
        text-align:center;
        border-radius: 5px;
        padding:3px;
    }
    .btn{
        margin-left:80%;
        margin-top:-50px;
        display:flex;
        flex-direction: column;
    }
    li{
        color:white;
    }
    form>label{
        font-weight:bold;
    }
    button{
        font-weight:bold;
        color:rgb(67, 67, 67);
    }
    .reviews>p{
        font-weight:bold;
        color:rgb(175, 175, 175);;
    }
</style>
<body>
    <h1>리뷰 작성
        <a href="../content/content.php?genre=<?=urlencode($genre)?>">|◀</a></h1> <!-- 현재 페이지에서 받은 값 포함시킴 -->
    <hr>
    <div class="review_content">
        <h2>리뷰를 남겨주세요!</h2>
        <div class="text">
        <form method="POST" action="">
            <input type="hidden" name="genre" value="<?= htmlspecialchars($genre) ?>"> <!-- 뒤로가기 기능에 필요 -->
            <label for="star">별점:</label>
            <select name="star" id="star" required>
                <option value="1">1점</option>
                <option value="2">2점</option>
                <option value="3">3점</option>
                <option value="4">4점</option>
                <option value="5">5점</option>
            </select>
            <br><br>            
            <label for="comment">감상평을 작성해주세요</label><br>
            <textarea name="comment" id="comment" rows="4" cols="50" required></textarea>
            <br><br>
            <button type="submit">리뷰 등록</button>
        </form>
        </div>
    </div>

    <h2>등록된 리뷰</h2>
    <?php if ($result && $result->num_rows > 0): ?>
    <ul>
        <?php while ($row = $result->fetch_assoc()): ?>
            <li>
                <div class="reviews">
                    <p><?= htmlspecialchars($row['user_id']) ?></p>
                    <strong>별점:</strong> <?= htmlspecialchars($row['star']) ?>점<br>
                    <strong>댓글:</strong> <?= nl2br(htmlspecialchars($row['comment'])) ?>
                </div>
            </li>
            <?php if ($row['user_id'] === $userID): ?> <!-- 본인만 수정/삭제 가능 -->
                <div class="btn">
                    <a href="edit_review.php?id=<?= $row['id'] ?>"><button type="button">수정</button></a>
                    <a href="delete_review.php?id=<?= $row['id'] ?>&genre=<?= urlencode($genre) ?>" onclick="return confirm('정말 삭제하시겠습니까?');">
                        <button type="button">삭제</button>
                    </a>
                </div>
            <?php endif; ?>
            <hr>
        <?php endwhile; ?>
    </ul>
    <?php else: ?>
        <h3>아직 등록된 리뷰가 없습니다</h3>
    <?php endif; ?>
    <?php $conn->close(); ?>
</body>
</html>