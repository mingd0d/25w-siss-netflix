<!-- content.php
콘텐츠 썸네일  -->
<?php
$genre = isset($_GET['genre']) ? $_GET['genre'] : '';

// 영화별 내용 설정
$contents = [
    'movie1' => [
        'title' => '발레리나',
        'description' => '경호원 출신 ‘옥주’(전종서)가 소중한 친구 ‘민희’(박유림)를 죽음으로 몰아간 ‘최프로’(김지훈)를 쫓으며 펼치는 아름답고 무자비한 감성 액션 복수극',
        'image' => '../image/nmovie1.png',
        'link' => 'https://youtu.be/e2HpU8X2dLM?si=3p9Xd3RrH3n_zccR'
    ],
    'movie2' => [
        'title' => '전,란',
        'description' => '왜란이 일어난 혼란의 시대, 함께 자란 조선 최고 무신 집안의 아들 ‘종려’(박정민)와 그의 몸종 ‘천영’(강동원)이 ‘선조’(차승원)의 최측근 무관과 의병으로 적이 되어 다시 만나는 이야기를 그린 영화',
        'image' => '../image/nmovie2.png',
        'link' => 'https://youtu.be/5nG3QbNqbEU?si=5lp3aribZfyaqUGi'
    ],
    'movie3' => [
        'title' => '크로스',
        'description' => '어느 날 결혼생활에 극한의 미션이 떨어진다면? 아내에게 과거를 숨긴 채 베테랑 주부로 살아가는 전직 요원. 뜻하지 않게 위험한 임무에 얽히게 되는데, 어느새 형사인 아내까지 함께 휘말리고 만다.',
        'image' => '../image/nmovie3.png',
        'link' => 'https://youtu.be/eKfKGQr4vZw?si=Q0E675py0s7yHybi'
    ],
    'drama1' => [
        'title' => '더 글로리',
        'description' => '유년 시절 폭력으로 영혼까지 부서진 한 여자가 온 생을 걸어 치밀하게 준비한 처절한 복수와 그 소용돌이에 빠져드는 이들의 이야기를 그린 넷플릭스 시리즈',
        'image' => '../image/ndrama1.png',
        'link' => 'https://youtu.be/YQtgHb8tkYM?si=SUWXA9f6QObE7FJq'
    ],
    'drama2' => [
        'title' => '마스크걸',
        'description' => '외모 콤플렉스를 가진 평범한 직장인 김모미가 밤마다 마스크로 얼굴을 가린 채 인터넷 방송 BJ로 활동하면서 의도치 않은 사건에 휘말리며 벌어지는 이야기로, 김모미의 파란만장한 일대기를 그린 넷플릭스 시리즈',
        'image' => '../image/ndrama2.png',
        'link' => 'https://youtu.be/IOWo0QruiS8?si=oybI2UHzN3w5kNBu'
    ],
    'drama3' => [
        'title' => '오징어 게임 2',
        'description' => '복수를 다짐하고 다시 돌아와 게임에 참가하는 ‘기훈’(이정재)과 그를 맞이하는 ‘프론트맨’(이병헌)의 치열한 대결, 그리고 다시 시작되는 진짜 게임을 담은 이야기',
        'image' => '../image/ndrama3.png',
        'link' => 'https://youtu.be/EN1KhHulIJ0?si=4K81BohOVG_UoGXf'
    ],
    'anima1' => [
        'title' => '너에게 닿기를',
        'description' => '음산한 분위기 탓에 친구들과 어울리지 못하는 사와코. 하지만 밝고 쾌활한 동급생이 먼저 다가와 준 그날 이후, 사와코의 생활도 점점 빛이 나기 시작한다.',
        'image' => '../image/nanima1.png',
        'link' => 'https://youtu.be/x_jRbrFjLsY?si=95U-laGsNyN88NwJ'
    ],
    'anima2' => [
        'title' => '사카모토 데이즈',
        'description' => '한때 최강의 킬러라 불렸던 사카모토 타로. 사랑에 빠져 은퇴하지만 과거가 그의 발목을 잡기 시작한다. 이제, 사카모토는 사랑하는 가족을 지키기 위해 싸워야만 하는데.',
        'image' => '../image/nanima2.png',
        'link' => 'https://youtu.be/4ZX5rg6aRek?si=Chjg9VL6FrwoEBnF'
    ],
    'anima3' => [
        'title' => '스파이 패밀리',
        'description' => '스파이 남자, 킬러 여자, 초능력자 소녀가 위장 가족이 되어 펼치는 홈 코미디 애니메이션',
        'image' => '../image/nanima3.png',
        'link' => 'https://youtu.be/kca1wvko9mU?si=iTh62x5xQyPGiWNc'
    ]
];

// 해당 genre에 맞는 정보 출력
if (array_key_exists($genre, $contents)) {
    $print = $contents[$genre];
    echo "<img src='{$print['image']}' alt='{$print['title']}'>";
    echo "<h2 class='play'><a href='{$print['link']}'>▶ 재생</a></h2>";
    echo "<h2 class='phptext'>줄거리</h2>";
    echo "<h4 class='phptext'>{$print['description']}</h4>";
} else {
    echo "<h1 class='phptext'>선택을 잘못하셨습니다.</h1>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>콘텐츠</title>
    <link rel="stylesheet" href="../s.css">
</head>
<style>
    img{
        width:100%;
        height:65%;
        padding:0px;
        position:relative;
    }
    .play{
        margin-top:-40px;
        margin-left:10px;
        background:white;
        width:150px;
        border-radius: 5px;
        text-align:center;
        position:absolute;
    }
    .play>a{
        color:black;
    }
    .play:hover{
        background:rgb(212, 212, 212);
    }
    body>h3{
        margin-top:50px;
        display:flex;
        justify-content: space-around;
    }
    body>h3> a:hover{
        color:rgb(170, 170, 170);
    }
</style>
<body>
    <h3>
        <a href="../home/home.php?genre=<?= htmlspecialchars(preg_replace('/[0-9]/', '', $genre)) ?>">목록</a> 
        <a href="../review/review.php?genre=<?php echo $genre; ?>" class="review">리뷰 보기</a>
    </h3>
</body>
</html>