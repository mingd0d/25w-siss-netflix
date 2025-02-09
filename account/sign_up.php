<?php
// mysql 연결
$conn = mysqli_connect(
  'localhost',
  'root',
  '1111');  // 비밀번호 1111

// 연결 확인
if (!$conn) {
    die("연결 오류: " . mysqli_connect_error());
}

// 데이터베이스 선택
$db_name = 'userInfo';
mysqli_select_db($conn, $db_name);

// 회원가입 처리
$signup_error = '';
$id_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $userName = mysqli_real_escape_string($conn, $_POST['userName']);
  $userID = mysqli_real_escape_string($conn, $_POST['userID']);
  $userPW = mysqli_real_escape_string($conn, $_POST['userPW']);
  $userNick = mysqli_real_escape_string($conn, $_POST['userNick']);
  $userPhone = mysqli_real_escape_string($conn, $_POST['userPhone']);

  // 아이디 중복 체크
  $sql_check = "SELECT * FROM users WHERE userID='$userID'";
  $result = mysqli_query($conn, $sql_check);

  if (mysqli_num_rows($result) > 0) {
    $id_error = "사용할 수 없는 아이디입니다";
  } else {
    // 데이터 삽입
    $sql_insert = "
    INSERT INTO users (userName, userID, userPW, userNick, userPhone)
    VALUES ('$userName', '$userID', '$userPW', '$userNick', '$userPhone')";

    if (mysqli_query($conn, $sql_insert)) {
      // 회원가입 성공 시 로그인 페이지로 리다이렉트
      header('Location: ../index.php'); // 상위 폴더의 index.php로 이동
      exit; // 리다이렉트 후 코드 실행 중단
    } else {
      $signup_error = "오류: " . mysqli_error($conn);
    }
  }
}

mysqli_close($conn);
?>

<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>회원 가입 - NETFLIX</title>
    <link rel="stylesheet" href="../css/style.css">
  </head>
  <body>
    <div class="form-container">
      <div class="info">
        <h2>회원 가입</h2>
        <p>회원 정보를 입력해주세요</p>
      </div>

      <form id="input-form" action="sign_up.php" method="post">
        <div class="input-field">
          <div>
            <input type="text" id="userName" name="userName" placeholder="이름" required>
          </div>  
          <div>
            <input type="text" id="userID" name="userID" placeholder="아이디" required>
            <span class="error-message" id="userID-message"><?php echo $id_error; ?></span>
          </div>  
          <div>
            <input type="password" id="userPW" name="userPW" placeholder="비밀번호" required>
          </div>  
          <div>
            <input type="password" id="userPWCheck" name="userPWCheck" placeholder="비밀번호 확인" required>
            <span id="password-message" class="invalid"></span>
          </div>  
          <div>
            <input type="text" id="userNick" name="userNick" placeholder="별명" required>
          </div>  
          <div>
            <input type="text" id="userPhone" name="userPhone" placeholder="전화번호" required>
          </div>  
        </div>

        <div>
          <input type="submit" value="등록" id="submit-btn" class="submit-btn" disabled>
        </div>
        <p class="error-message"><?= htmlspecialchars($signup_error) ?></p>
      </form>

      <p class="additional-links">
        계정이 있으신가요? <a href="../index.php">로그인</a>하기
      </p>

      <script>
        const password = document.getElementById('userPW');
        const passwordCheck = document.getElementById('userPWCheck');
        const message = document.getElementById('password-message');
        const submitBtn = document.getElementById('submit-btn');
        const userIDInput = document.getElementById('userID');
        const userIDMessage = document.getElementById('userID-message');
        
        let isUserIDValid = false;

        // 아이디 중복 확인 함수
        userIDInput.addEventListener('input', function () {
          const userID = userIDInput.value.trim();
          if (userID === '') {
            userIDMessage.textContent = '';
            isUserIDValid = false;
            checkFormValidity();
            return;
          }

          // 서버에서 아이디 중복 검사
          const xhr = new XMLHttpRequest();
          xhr.open('POST', 'check_id.php', true); // 현재 폴더의 check_id.php로 요청
          xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
          xhr.onload = function () {
            if (xhr.status === 200) {
              if (xhr.responseText.includes('사용할 수 없는 아이디')) {
                userIDMessage.textContent = xhr.responseText;
                userIDMessage.style.color = 'red';
                isUserIDValid = false;
              } else {
                userIDMessage.textContent = xhr.responseText;
                userIDMessage.style.color = 'green';
                isUserIDValid = true;
              }
              checkFormValidity();
            }
          };
          xhr.send('checkUserID=' + encodeURIComponent(userID));
        });

        // 비밀번호 확인
        function checkPasswords() {
          if (password.value === passwordCheck.value && password.value !== '') {
            message.textContent = '비밀번호가 일치합니다';
            message.className = 'valid';
          } else {
            message.textContent = '비밀번호가 일치하지 않습니다';
            message.className = 'invalid';
          }
          checkFormValidity();
        }

        password.addEventListener('input', checkPasswords);
        passwordCheck.addEventListener('input', checkPasswords);

        // 모든 입력값 검증
        function checkFormValidity() {
          let allFilled = true;
          const inputs = document.querySelectorAll('input[required]');
          inputs.forEach(input => {
            if (input.value.trim() === '') {
              allFilled = false;
            }
          });

          if (allFilled && isUserIDValid && password.value === passwordCheck.value) {
            submitBtn.disabled = false;
          } else {
            submitBtn.disabled = true;
          }
        }

        const inputs = document.querySelectorAll('input[required]');
        inputs.forEach(input => {
          input.addEventListener('input', checkFormValidity);
        });
      </script>
    </div>
  </body>
</html>