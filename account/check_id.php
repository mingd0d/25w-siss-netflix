<?php
// mysql 연결
$conn = mysqli_connect('localhost', 'root', '1111');
if (!$conn) {
    die("연결 오류: " . mysqli_connect_error());
}

$db_name = 'userInfo';
mysqli_select_db($conn, $db_name);

if (isset($_POST['checkUserID'])) {
    $userID = mysqli_real_escape_string($conn, $_POST['checkUserID']);
    $sql_check = "SELECT * FROM users WHERE userID = '$userID'";
    $result = mysqli_query($conn, $sql_check);

    if (mysqli_num_rows($result) > 0) {
        echo "사용할 수 없는 아이디입니다";
    } else {
        echo "사용 가능한 아이디입니다";
    }
}

mysqli_close($conn);
?>
