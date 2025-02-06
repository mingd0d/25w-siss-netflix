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

// 아이디 찾기 처리
$find_id_error = '';
$found_id = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userName = mysqli_real_escape_string($conn, $_POST['userName']);
    $userPhone = mysqli_real_escape_string($conn, $_POST['userPhone']);

    $sql_find_id = "
    SELECT userID 
    FROM users 
    WHERE userName = '$userName' AND userPhone = '$userPhone'";

    $result = mysqli_query($conn, $sql_find_id);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $found_id = "입력하신 정보로 등록된 아이디입니다: " . htmlspecialchars($row['userID']);
    } else {
        $find_id_error = "해당 정보로 등록된 계정이 없습니다";
    }
}

mysqli_close($conn);
?>

<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Find User ID - NETFLIX</title>
  </head>
  <body>
    <h1>아이디 찾기</h1>
    <p>등록된 회원 정보를 입력해주세요</p>

    <form action="find_id.php" method="post">
      <p>
        <input type="text" id="userName" name="userName" placeholder="이름" required>
      </p>
      <p>
        <input type="text" id="userPhone" name="userPhone" placeholder="전화번호" required>
      </p>
      <p>
        <input type="submit" value="아이디 찾기">
      </p>
    </form>

    <p style="color: green;"><?= htmlspecialchars($found_id) ?></p>
    <p style="color: red;"><?= htmlspecialchars($find_id_error) ?></p>
    
    <p><a href="../index.php">로그인</a> 화면으로 돌아가기</p>
  </body>
</html>
