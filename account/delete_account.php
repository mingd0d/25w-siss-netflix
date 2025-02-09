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

// 비밀번호 확인 및 탈퇴 처리
$delete_error = '';
$delete_success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $userPW = mysqli_real_escape_string($conn, $_POST['userPW']);

    // 비밀번호 확인
    $sql_check_pw = "SELECT userPW FROM users WHERE userID = '$userID'";
    $result = mysqli_query($conn, $sql_check_pw);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if ($userPW === $row['userPW']) {
            // 비밀번호가 일치하면 회원 탈퇴
            $sql_delete_user = "DELETE FROM users WHERE userID = '$userID'";
            if (mysqli_query($conn, $sql_delete_user)) {
                session_destroy(); // 세션 삭제
                $delete_success = "회원 탈퇴 완료";
                echo "<script>
                    setTimeout(function() {
                        window.location.href = 'index.php';
                    }, 3000);
                </script>";
            } else {
                $delete_error = "회원 탈퇴 중 오류가 발생했습니다";
            }
        } else {
            $delete_error = "비밀번호가 일치하지 않습니다";
        }
    } else {
        $delete_error = "사용자 정보를 확인할 수 없습니다";
    }
}

mysqli_close($conn);
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>회원 탈퇴 - NETFLIX</title>
    <link rel="stylesheet" href="../css/style.css">

</head>

<body>
    <h1><a href="../genre/genre.php">NETFLIX</a></h1>

    <div class="form-container">
        <div class="info">
            <h2>회원 탈퇴</h2>
            <p>탈퇴를 위해 비밀번호를 입력해주세요</p>
        </div>

        <form id="input-form" action="delete_account.php" method="post">
            <input type="hidden" name="action" value="delete">
            <p>
                <input type="password" id="userPW" name="userPW" placeholder="비밀번호" required>
            </p>
            <p class="delete-message">
                <span>정말 탈퇴하시겠습니까?</span>
                <br>
                <span>지금까지 작성한 모든 리뷰가 삭제됩니다</span>
            </p>
            <button type="submit" id="delete-btn" disabled>탈퇴하기</button>

            <p class="success-message"><?= htmlspecialchars($delete_success) ?></p>
            <p class="error-message"><?= htmlspecialchars($delete_error) ?></p>
        </form>

        <p class="additional-links">
            <a href="account_info.php">계정 정보</a>로 돌아가기
        </p>
    </div>

    <script>
        const userPWInput = document.getElementById('userPW');
        const deleteBtn = document.getElementById('delete-btn');

        userPWInput.addEventListener('input', function () {
            if (userPWInput.value.trim() !== '') {
                deleteBtn.disabled = false;
                deleteBtn.classList.add('enabled');
            } else {
                deleteBtn.disabled = true;
                deleteBtn.classList.remove('enabled');
            }
        });
    </script>
</body>
</html>
