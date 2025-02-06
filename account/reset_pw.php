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

// 단계 설정 기본값
$step = isset($_POST['step']) ? (int)$_POST['step'] : 1;

$reset_error = '';
$reset_success = '';
$userID = '';
$userPhone = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($step == 1) {
        // Step 1: ID와 전화번호 확인
        $userID = mysqli_real_escape_string($conn, $_POST['userID']);
        $userPhone = mysqli_real_escape_string($conn, $_POST['userPhone']);

        $sql_check_user = "
        SELECT * 
        FROM users 
        WHERE userID = '$userID' AND userPhone = '$userPhone'";
        
        $result = mysqli_query($conn, $sql_check_user);

        if ($result && mysqli_num_rows($result) > 0) {
            $step = 2; // 유효한 사용자 정보 확인, Step 2로 이동
        } else {
            $reset_error = "입력하신 정보와 일치하는 계정이 없습니다";
        }
    } elseif ($step == 2) {
        // Step 2: 비밀번호 재설정
        $userID = mysqli_real_escape_string($conn, $_POST['userID']);
        $userPhone = mysqli_real_escape_string($conn, $_POST['userPhone']);
        $newPW = mysqli_real_escape_string($conn, $_POST['newPW']);
        $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirmPassword']);

        if ($newPW !== $confirmPassword) {
            $reset_error = "비밀번호가 일치하지 않습니다";
        } else {
            // 비밀번호 업데이트
            $sql_update_password = "
            UPDATE users 
            SET userPW = '$newPW' 
            WHERE userID = '$userID' AND userPhone = '$userPhone'";

            if (mysqli_query($conn, $sql_update_password)) {
                $reset_success = "비밀번호 재설정 완료";
            } else {
                $reset_error = "비밀번호 재설정 오류: " . mysqli_error($conn);
            }
        }
    }
}

mysqli_close($conn);
?>

<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>비밀번호 재설정 - NETFLIX</title>
    <script>
      function checkPasswords() {
        const newPW = document.getElementById('newPW').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        const message = document.getElementById('passwordMessage');

        if (newPW && confirmPassword) {
          if (newPW === confirmPassword) {
            message.textContent = '비밀번호가 일치합니다';
          } else {
            message.textContent = '비밀번호가 일치하지 않습니다';
          }
        } else {
          message.textContent = ''; // 입력이 없으면 메시지 초기화
        }
      }
    </script>
  </head>
  <body>
    <h1>비밀번호 재설정</h1>

    <?php if ($step == 1): ?>

    <!-- 1단계: 아이디 및 전화번호 입력 -->
    <p>등록한 회원 정보를 입력해주세요</p>
    <form action="reset_pw.php" method="post">
      <input type="hidden" name="step" value="1">
      <p>
        <input type="text" id="userID" name="userID" placeholder="아이디" required>
      </p>
      <p>
        <input type="text" id="userPhone" name="userPhone" placeholder="전화번호" required>
      </p>
      <p>
        <input type="submit" value="계정 확인">
      </p>
    </form>
    <?php elseif ($step == 2): ?>

    <!-- 2단계: 비밀번호 재설정 -->
    <p>변경할 비밀번호를 입력해주세요</p>
    <form action="reset_pw.php" method="post">
      <input type="hidden" name="step" value="2">
      <input type="hidden" name="userID" value="<?= htmlspecialchars($userID) ?>">
      <input type="hidden" name="userPhone" value="<?= htmlspecialchars($userPhone) ?>">
      <p>
        <input type="password" id="newPW" name="newPW" placeholder="새 비밀번호" required oninput="checkPasswords()">
      </p>
      <p>
        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="새 비밀번호 확인" required oninput="checkPasswords()">
      </p>
      <p id="passwordMessage"></p>
      <p>
        <input type="submit" value="비밀번호 재설정">
      </p>
    </form>
    <?php endif; ?>

    <p><?= htmlspecialchars($reset_error) ?></p>
    <p><?= htmlspecialchars($reset_success) ?></p>
    <p><a href="../index.php">로그인</a> 화면으로 돌아가기</p>
  </body>
</html>
