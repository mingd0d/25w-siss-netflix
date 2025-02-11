<!-- edit_review.php
리뷰 수정 -->
<?php
$conn = mysqli_connect('localhost', 'root', '1111', 'review_system');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT * FROM reviews WHERE id = $id";
$result = $conn->query($sql);
$review = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $star = intval($_POST['star']);
    $comment = $conn->real_escape_string($_POST['comment']);

    $update_sql = "UPDATE reviews SET star = $star, comment = '$comment' WHERE id = $id";
    if ($conn->query($update_sql)) {
        echo "리뷰가 성공적으로 수정되었습니다!";
        header("Location: review.php?genre=" . urlencode($review['genre']));
        exit;
    } else {
        echo "수정 실패: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>리뷰 수정</title>
    <link rel="stylesheet" href="../css/s.css">
</head>
<style>
    .text{
        color:white;
        font-weight:bold;
        margin-left:10px;
        margin-top:30px;
    }
    h1{
        margin-left:10px;
    }
</style>
<body>
    <h1>리뷰 수정</h1>
    <hr>
    <form method="POST" action="">
        <div class="text">
            <label for="star">별점:</label>
            <select name="star" id="star" required>
                <option value="1" <?= $review['star'] == 1 ? 'selected' : '' ?>>1점</option>
                <option value="2" <?= $review['star'] == 2 ? 'selected' : '' ?>>2점</option>
                <option value="3" <?= $review['star'] == 3 ? 'selected' : '' ?>>3점</option>
                <option value="4" <?= $review['star'] == 4 ? 'selected' : '' ?>>4점</option>
                <option value="5" <?= $review['star'] == 5 ? 'selected' : '' ?>>5점</option>
            </select>
            <br><br>
            <label for="comment">감상평:</label><br>
            <textarea name="comment" id="comment" rows="4" cols="50" required><?= htmlspecialchars($review['comment']) ?></textarea>
            <br><br>
            <button type="submit">완료</button>
        </div>
    </form>
</body>
</html>
