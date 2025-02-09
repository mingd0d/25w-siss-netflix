<?php
// MySQL 연결
$conn = mysqli_connect(
    'localhost',
    'root',
    '1111' // 비밀번호 1111
);

// 연결 확인
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// 데이터베이스 선택
$db_name = 'userInfo';
mysqli_select_db($conn, $db_name);

// 세션 시작 (로그인된 사용자 정보 확인용)
session_start();

// 로그인된 사용자 확인
if (!isset($_SESSION['userID'])) {
    header('Location: index.php'); // 로그인 페이지로 리다이렉트
    exit();
}

$userID = $_SESSION['userID'];

// 사용자 정보 가져오기 (userName 포함)
$sql_get_user = "SELECT userName, userNick, userPhone FROM users WHERE userID = '$userID'";
$result = mysqli_query($conn, $sql_get_user);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
} else {
    die("사용자 정보를 가져올 수 없습니다");
}

// 업데이트 처리
$update_success = '';
$update_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userNick = mysqli_real_escape_string($conn, $_POST['userNick']);
    $userPhone = mysqli_real_escape_string($conn, $_POST['userPhone']);

    $sql_update = "UPDATE users SET userNick = '$userNick', userPhone = '$userPhone' WHERE userID = '$userID'";

    if (mysqli_query($conn, $sql_update)) {
        $update_success = "계정 정보가 변경되었습니다";
    } else {
        $update_error = "업데이트 중 오류가 발생했습니다: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>계정 정보 수정 - NETFLIX</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="form-container">
        <div class="info">
            <h2>계정 정보 수정</h2>
            <p><?= htmlspecialchars($user['userName']) ?>님의 계정 정보</p>
        </div>

        <form id="input-form" action="edit_account.php" method="post">
            <p>
                <input type="text" id="userNick" name="userNick" placeholder="별명" value="<?= htmlspecialchars($user['userNick']) ?>" required>
            </p>
            <p>
                <input type="text" id="userPhone" name="userPhone" placeholder="전화번호" value="<?= htmlspecialchars($user['userPhone']) ?>" required>
            </p>
            <p>
                <input type="submit" value="정보 수정">
            </p>
            <p class="success-message"><?= htmlspecialchars($update_success) ?></p>
            <p class="error-message"><?= htmlspecialchars($update_error) ?></p>
        </form>
        
        <p class="additional-links">
            <a href="reset_pw.php">비밀번호 재설정</a>
        </p>
        <p class="additional-links">
            <a href="account_info.php">계정 정보</a>로 돌아가기
        </p>
    </div>
</body>
</html>