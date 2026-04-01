<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = get_db_connection();

    $visit_source = $_POST['visit_source'] ?? '';
    $other_visit_source = $_POST['other_visit_source'] ?? '';
    $purpose = isset($_POST['purpose']) ? json_encode($_POST['purpose'], JSON_UNESCAPED_UNICODE) : '[]';
    $first_impression = $_POST['first_impression'] ?? '';
    $search_ease = $_POST['search_ease'] ?? '';
    $useful_content = $_POST['useful_content'] ?? '';
    $desired_features = isset($_POST['desired_features']) ? json_encode($_POST['desired_features'], JSON_UNESCAPED_UNICODE) : '[]';
    $other_feature = $_POST['other_feature'] ?? '';
    $recommend_score = intval($_POST['recommend_score'] ?? 0);

    $stmt = $conn->prepare("INSERT INTO responses (visit_source, other_visit_source, purpose, first_impression, search_ease, useful_content, desired_features, other_feature, recommend_score) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssi", $visit_source, $other_visit_source, $purpose, $first_impression, $search_ease, $useful_content, $desired_features, $other_feature, $recommend_score);

    if ($stmt->execute()) {
        $success = true;
    } else {
        $success = false;
        $error = $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>제출 완료</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="center-content">
    <div class="result-card">
        <?php if (isset($success) && $success): ?>
            <div class="success-icon"><i class="fas fa-check-circle"></i></div>
            <h2>피드백이 성공적으로 제출되었습니다!</h2>
            <p>소중한 의견 주셔서 진심으로 감사합니다. 더 좋은 서비스를 만드는 밑거름으로 잘 활용하겠습니다.</p>
            <a href="index.php" class="back-btn">다시 작성하기</a>
        <?php else: ?>
            <div class="error-icon"><i class="fas fa-exclamation-circle"></i></div>
            <h2>죄송합니다. 오류가 발생했습니다.</h2>
            <p><?php echo $error ?? '서버 오류가 발생했습니다. 잠시 후 다시 시도해 주세요.'; ?></p>
            <a href="index.php" class="back-btn">돌아가기</a>
        <?php endif; ?>
    </div>
</body>
</html>
