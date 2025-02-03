<!-- review.php
리뷰 작성 -->
<?php
session_start(); // 세션 시작
$conn = mysqli_connect('localhost', 'root', '1110', 'review_system');

if (!isset($_SESSION['userID'])) {
    die("로그인이 필요합니다."); // 로그인 안 하면 리뷰 작성 불가
}

$userID = $_SESSION['userID']; // 현재 로그인한 사용자 ID
$genre = $conn->real_escape_string($_POST['genre']);
$star = intval($_POST['star']);
$comment = $conn->real_escape_string($_POST['comment']);

$sql = "INSERT INTO reviews (genre, star, comment, user_id) VALUES ('$genre', '$star', '$comment', '$userID')";

if ($conn->query($sql)) {
    echo "<h3>리뷰가 성공적으로 등록되었습니다!</h3>";
} else {
    echo "리뷰 등록 실패: " . $conn->error;
}

// 현재 장르의 리뷰 목록 조회
$sql = "SELECT * FROM reviews WHERE genre = '$genre'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>리뷰 페이지</title>
    <link rel="stylesheet" href="s.css">
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
</style>
<body>
    <h1>리뷰 작성
        <a href="content.php?genre=<?=urlencode($genre)?>">|◀</a></h1> <!-- 현재 페이지에서 받은 값 포함시킴 -->
    <hr>
    <div class="review_content">
        <h2>리뷰를 남겨주세요!</h2>
        <div class="text">
        <form method="POST" action="">
            <input type="hidden" name="genre" value="<?= htmlspecialchars($genre) ?>">
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
            </div>
        </form>
    </div>
    <h2>등록된 리뷰</h2>
    <?php if ($result && $result->num_rows > 0): ?>
    <ul>
        <?php while ($row = $result->fetch_assoc()): ?>
            <li>
                <div class="reviews">
                    <strong>별점:</strong> <?= htmlspecialchars($row['star']) ?>점<br>
                    <strong>댓글:</strong> <?= nl2br(htmlspecialchars($row['comment'])) ?>
                </div>
            </li>

            <!-- 디버깅 메시지 -->
            <p>디버깅: 리뷰 작성자 ID = <?= $row['user_id'] ?>, 로그인한 유저 ID = <?= $userID ?></p>

            <?php if ($userID !== null && $row['user_id'] === $userID): ?> <!-- 본인만 수정/삭제 가능 -->
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
    <h3>아직 등록된 리뷰가 없습니다.</h3>
<?php endif; ?>
<?php $conn->close(); ?>