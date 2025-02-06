<?php
// mysql 연결
$conn = mysqli_connect(
  'localhost',
  'root',
  '1111');  // 비밀번호 1111

// 연결 확인
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// 데이터베이스 선택
$db_name = 'userInfo';
mysqli_select_db($conn, $db_name);

// 세션 시작 (로그인된 사용자 정보 확인용)
session_start();

// 디버깅용: 세션 상태 출력
// var_dump($_SESSION);

if (!isset($_SESSION['userID'])) {
    // 세션이 설정되지 않은 경우 로그인 페이지로 이동
    header('Location: index.php');
    exit;
}

$userID = $_SESSION['userID'];

// 사용자 정보 가져오기
$sql_get_user = "SELECT userName, userNick, userPhone, userID FROM users WHERE userID = '$userID'";
$result = mysqli_query($conn, $sql_get_user);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result); // 사용자 정보 배열로 가져오기
} else {
    die("계정 정보를 가져올 수 없습니다");
}

mysqli_close($conn);
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>계정 정보 - NETFLIX</title>
</head>
<body>
    <h1>계정 정보</h1>
    <div class="info">
        <p><?= htmlspecialchars($user['userName']) ?>님의 계정 정보</p>
        <p>별명: <?= htmlspecialchars($user['userNick']) ?></p>
        <p>전화번호: <?= htmlspecialchars($user['userPhone']) ?></p>
        <p>아이디: <?= htmlspecialchars($user['userID']) ?></p>
    </div>
    <div class="actions">
        <a href="edit_account.php">계정 정보 수정</a>
        <a href="delete_account.php" class="delete">회원 탈퇴</a>
    </div>
    <p><a href="logout.php">로그아웃</a></p>
</body>
</html>
