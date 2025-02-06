<!-- delete_review.php
리뷰 삭제 -->
<?php
$conn = mysqli_connect('localhost', 'root', '1111', 'review_system');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "DELETE FROM reviews WHERE id = $id";
if ($conn->query($sql)) {
    echo "리뷰가 성공적으로 삭제되었습니다!";
    header("Location: review.php?genre=" . urlencode($_GET['genre']));
    exit;
} else {
    echo "삭제 실패: " . $conn->error;
}
?>