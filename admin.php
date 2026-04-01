<?php
require_once 'config.php';
session_start();

// Handle Login
if (isset($_POST['password'])) {
    if ($_POST['password'] === ADMIN_PASS) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        $login_error = "비밀번호가 틀렸습니다.";
    }
}

// Handle Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

// If NOT logged in, show Login Form
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>관리자 로그인 - POLL</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="center-content">
    <div class="result-card login-card">
        <div class="logo-area" style="font-size: 2rem;"><i class="fas fa-lock"></i></div>
        <h2>관리자 로그인</h2>
        <p>통계 데이터를 보려면 비밀번호를 입력하세요.</p>
        <form action="admin.php" method="POST" class="login-form">
            <input type="password" name="password" placeholder="비밀번호" required autofocus>
            <?php if (isset($login_error)): ?>
                <div class="error-msg" style="color: #ef4444; font-size: 0.8rem; margin-top: 0.5rem;"><?php echo $login_error; ?></div>
            <?php endif; ?>
            <button type="submit" class="submit-btn" style="padding: 1rem 2rem; margin-top: 1.5rem; font-size: 1rem;">로그인</button>
        </form>
    </div>
</body>
</html>
<?php 
    exit;
}

$conn = get_db_connection();

// Stats
$totalRes = $conn->query("SELECT COUNT(*) FROM responses")->fetch_row()[0];
$avgScore = $conn->query("SELECT AVG(recommend_score) FROM responses")->fetch_row()[0];

// Source dist
$sourceRes = $conn->query("SELECT visit_source, COUNT(*) as cnt FROM responses GROUP BY visit_source");
$sources = [];
while($row = $sourceRes->fetch_assoc()) $sources[] = $row;

// Recent list
$listRes = $conn->query("SELECT * FROM responses ORDER BY created_at DESC LIMIT 50");
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POLL Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="admin-body">
    <div class="admin-container">
        <header class="admin-header">
            <div>
                <h1>POLL <span class="badge">ADMIN</span></h1>
                <p>사이트 방문자 피드백 통계 및 관리</p>
            </div>
            <div class="actions">
                <a href="index.php" class="preview-btn" style="margin-right:0.5rem"><i class="fas fa-eye"></i> 설문 보기</a>
                <a href="?logout=1" class="preview-btn" style="color: #ef4444; border-color: #fca5a5"><i class="fas fa-sign-out-alt"></i> 로그아웃</a>
            </div>
        </header>

        <section class="stats-row">
            <div class="stat-card">
                <i class="fas fa-users-viewfinder"></i>
                <div class="stat-data">
                    <span class="label">Total Responses</span>
                    <span class="value"><?php echo $totalRes; ?></span>
                </div>
            </div>
            <div class="stat-card">
                <i class="fas fa-star-half-stroke"></i>
                <div class="stat-data">
                    <span class="label">Avg Recommend Score</span>
                    <span class="value"><?php echo number_format($avgScore, 1); ?> / 10</span>
                </div>
            </div>
        </section>

        <section class="main-grid">
            <div class="chart-panel card">
                <h3>방문 경로 통계</h3>
                <div class="source-list">
                    <?php foreach($sources as $s): ?>
                        <div class="source-item">
                            <span class="name"><?php echo $s['visit_source']; ?></span>
                            <div class="bar-container">
                                <div class="bar" style="width: <?php echo ($totalRes > 0 ? ($s['cnt']/$totalRes)*100 : 0); ?>%"></div>
                            </div>
                            <span class="count"><?php echo $s['cnt']; ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="list-panel card">
                <h3>최근 피드백 목록 (최근 50건)</h3>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>날짜</th>
                                <th>방문경로</th>
                                <th>첫인상</th>
                                <th>추천점수</th>
                                <th>인상깊은 콘텐츠</th>
                                <th>동작</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $listRes->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo date('m-d H:i', strtotime($row['created_at'])); ?></td>
                                    <td><?php echo $row['visit_source']; ?></td>
                                    <td><?php echo mb_strimwidth($row['first_impression'], 0, 15, '..'); ?></td>
                                    <td><span class="score-badge"><?php echo $row['recommend_score']; ?></span></td>
                                    <td><?php echo mb_strimwidth($row['useful_content'], 0, 30, '..'); ?></td>
                                    <td><button class="view-btn icon-btn" onclick="viewDetail(<?php echo htmlspecialchars(json_encode($row)); ?>)"><i class="fas fa-search-plus"></i></button></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <!-- Detail Modal (Simple Alert for demo) -->
    <script>
    function viewDetail(data) {
        let msg = `[상세 정보]\n\n` +
                  `일시: ${data.created_at}\n` +
                  `방문경로: ${data.visit_source} (${data.other_visit_source})\n` +
                  `방문목적: ${data.purpose}\n` +
                  `첫인상: ${data.first_impression}\n` +
                  `탐색 편의성: ${data.search_ease}\n` +
                  `유용한 콘텐츠: ${data.useful_content}\n` +
                  `추가 기능: ${data.desired_features} (${data.other_feature})\n` +
                  `추천 점수: ${data.recommend_score}`;
        alert(msg);
    }
    </script>
</body>
</html>
<?php $conn->close(); ?>
