<?php 
// mysql 연결
$conn = mysqli_connect(
    'localhost',
    'root',
    '1111');  // 비밀번호 1111

// 연결 확인
if (!$conn) {
    die("연결 실패: " . mysqli_connect_error());
}

// 데이터베이스 생성 (없으면 생성)
$db_name = 'userInfo';
$sql_create_db = "CREATE DATABASE IF NOT EXISTS $db_name";
if (!mysqli_query($conn, $sql_create_db)) {
    die("오류(데이터베이스 생성): " . mysqli_error($conn));
}

// 데이터베이스 선택
mysqli_select_db($conn, $db_name);

// 테이블 생성 (없으면 생성)
$sql_create_user_table = "
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    userName VARCHAR(255) NOT NULL,
    userID VARCHAR(25) NOT NULL UNIQUE,
    userPW VARCHAR(255) NOT NULL,
    userNick VARCHAR(25) NOT NULL,
    userPhone VARCHAR(15) NOT NULL,
    created DATETIME DEFAULT CURRENT_TIMESTAMP
)";
if (!mysqli_query($conn, $sql_create_user_table)) {
    die("테이블 오류(users): " . mysqli_error($conn));
}

// 세션 시작 (로그인 정보를 저장하기 위함)
session_start();

// 로그인 처리
$login_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = mysqli_real_escape_string($conn, $_POST['userID']);
    $userPW = mysqli_real_escape_string($conn, $_POST['userPW']);

    $sql = "SELECT * FROM users WHERE userID = '$userID'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if ($userPW === $row['userPW']) {
            // 로그인 성공 - 세션에 사용자 정보 저장
            $_SESSION['userID'] = $row['userID'];
            $_SESSION['userName'] = $row['userName'];
            $_SESSION['userNick'] = $row['userNick'];

            // genre 폴더의 genre.php로 리다이렉트
            header("Location: genre/genre.php");
            exit();
        } else {
            $login_error = "비밀번호가 일치하지 않습니다";
        }
    } else {
        $login_error = "존재하지 않는 아이디입니다";
    }
}

mysqli_close($conn);
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Login - NETFLIX</title>
    </head>
    <body>
        <h1><a href="index.php">NETFLIX</a></h1>
        <h2>로그인</h2>
        <p>NETFLIX 계정으로 로그인하세요</p>
        
        <form action="index.php" method="post">
            <p>
                <input type="text" id="userID" name="userID" placeholder="아이디" required>
            </p>
            <p>
                <input type="password" id="userPW" name="userPW" placeholder="비밀번호" required>
            </p>
            <p>
                <input type="submit" value="로그인">
            </p>
            <p style="color: red;"><?= htmlspecialchars($login_error) ?></p>
        </form>
        <p>
            아직 계정이 없으신가요?
            <br>
            <a href="account/sign_up.php">회원 가입</a>
        </p>
        <p>
            계정 정보를 찾고 계신가요?
            <br>
            <a href="account/find_id.php">아이디 찾기</a> 혹은 <a href="account/reset_pw.php">비밀번호 재설정</a>
        </p>
    </body>
</html>
