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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Outfit:wght@600;700&display=swap" rel="stylesheet">
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

// --- Data Fetching & Processing ---

// 1. Basic Stats
$totalRes = $conn->query("SELECT COUNT(*) FROM responses")->fetch_row()[0];
$avgScore = $conn->query("SELECT AVG(recommend_score) FROM responses")->fetch_row()[0];
$todayRes = $conn->query("SELECT COUNT(*) FROM responses WHERE DATE(created_at) = CURDATE()")->fetch_row()[0];

// 2. Fetch ALL responses for detailed processing
$allResponses = $conn->query("SELECT * FROM responses ORDER BY created_at DESC");
$responseData = [];
while($row = $allResponses->fetch_assoc()) {
    $responseData[] = $row;
}

// Analytics Storage
$sourceCounts = [];
$scoreDistribution = array_fill(1, 10, 0); // 1 to 10
$purposeCounts = [];
$easeCounts = [];
$featureCounts = [];

foreach($responseData as $resp) {
    // Sources
    $src = $resp['visit_source'] ?: '기타';
    $sourceCounts[$src] = ($sourceCounts[$src] ?? 0) + 1;

    // Scores
    $score = intval($resp['recommend_score']);
    if ($score >= 1 && $score <= 10) $scoreDistribution[$score]++;

    // Purposes (JSON)
    $purposes = json_decode($resp['purpose'], true) ?: [];
    foreach($purposes as $p) {
        $purposeCounts[$p] = ($purposeCounts[$p] ?? 0) + 1;
    }

    // Features (JSON)
    $features = json_decode($resp['desired_features'], true) ?: [];
    foreach($features as $f) {
        $featureCounts[$f] = ($featureCounts[$f] ?? 0) + 1;
    }

    // Search Ease
    $ease = $resp['search_ease'] ?: '미지정';
    $easeCounts[$ease] = ($easeCounts[$ease] ?? 0) + 1;
}

// Prepare data for Chart.js
$chartData = [
    'sources' => [
        'labels' => array_keys($sourceCounts),
        'data' => array_values($sourceCounts)
    ],
    'scores' => [
        'labels' => array_keys($scoreDistribution),
        'data' => array_values($scoreDistribution)
    ],
    'purposes' => [
        'labels' => array_keys($purposeCounts),
        'data' => array_values($purposeCounts)
    ],
    'features' => [
        'labels' => array_keys($featureCounts),
        'data' => array_values($featureCounts)
    ],
    'ease' => [
        'labels' => array_keys($easeCounts),
        'data' => array_values($easeCounts)
    ]
];

// Recent list (reuse some of $responseData)
$recentList = array_slice($responseData, 0, 50);

?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POLL Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="admin-body">
    <div class="admin-container">
        <header class="admin-header">
            <div class="brand">
                <div class="logo-circle"><i class="fas fa-chart-line"></i></div>
                <div>
                    <h1>POLL <span class="badge">DASHBOARD</span></h1>
                    <p>사용자 피드백 데이터 분석</p>
                </div>
            </div>
            <div class="actions">
                <a href="index.php" class="nav-btn"><i class="fas fa-external-link-alt"></i> 설문 보기</a>
                <a href="?logout=1" class="nav-btn logout-btn"><i class="fas fa-sign-out-alt"></i> 로그아웃</a>
            </div>
        </header>

        <!-- Stats Overview Cards -->
        <section class="stats-overview">
            <div class="overview-stat-card">
                <div class="icon-box"><i class="fas fa-users"></i></div>
                <div class="data">
                    <span class="label">총 응답 수</span>
                    <span class="value"><?php echo number_format($totalRes); ?></span>
                </div>
            </div>
            <div class="overview-stat-card">
                <div class="icon-box score"><i class="fas fa-star"></i></div>
                <div class="data">
                    <span class="label">평균 추천 점수</span>
                    <span class="value"><?php echo number_format($avgScore, 1); ?> <small>/ 10</small></span>
                </div>
            </div>
            <div class="overview-stat-card">
                <div class="icon-box trend"><i class="fas fa-bolt"></i></div>
                <div class="data">
                    <span class="label">오늘의 새로운 응답</span>
                    <span class="value"><?php echo number_format($todayRes); ?></span>
                </div>
            </div>
        </section>

        <!-- Charts Grid -->
        <section class="charts-grid">
            <div class="chart-card">
                <div class="card-header">
                    <h3><i class="fas fa-bullseye"></i> 추천 점수 분포</h3>
                    <p>사용자들이 부여한 1-10점 분포도입니다.</p>
                </div>
                <div class="chart-body">
                    <canvas id="scoreChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <div class="card-header">
                    <h3><i class="fas fa-share-nodes"></i> 유입 경로 분석</h3>
                    <p>방문자들이 사이트를 알게 된 경로입니다.</p>
                </div>
                <div class="chart-body">
                    <canvas id="sourceChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <div class="card-header">
                    <h3><i class="fas fa-compass"></i> 방문 목적 (중복 선택)</h3>
                    <p>사용자들이 주로 어떤 목적으로 방문했는지 보여줍니다.</p>
                </div>
                <div class="chart-body">
                    <canvas id="purposeChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <div class="card-header">
                    <h3><i class="fas fa-lightbulb"></i> 희망 추가 기능</h3>
                    <p>사용자들이 가장 선호하는 우선순위 기능입니다.</p>
                </div>
                <div class="chart-body">
                    <canvas id="featureChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <div class="card-header">
                    <h3><i class="fas fa-search-location"></i> 검색/탐색 편의성</h3>
                    <p>사용자들이 평가한 사이트 이용 만족도입니다.</p>
                </div>
                <div class="chart-body">
                    <canvas id="easeChart"></canvas>
                </div>
            </div>
        </section>

        <!-- Recent Responses Table -->
        <section class="table-section card">
            <div class="section-header">
                <h3><i class="fas fa-list-ul"></i> 최근 피드백 목록 (최근 50건)</h3>
                <button class="export-btn"><i class="fas fa-download"></i> CSV 내보내기 (준비중)</button>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>날짜</th>
                            <th>유입경로</th>
                            <th>추천점수</th>
                            <th>첫인상 요약</th>
                            <th>동작</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($recentList as $row): ?>
                            <tr>
                                <td class="date-td"><?php echo date('Y-m-d H:i', strtotime($row['created_at'])); ?></td>
                                <td><span class="tag"><?php echo htmlspecialchars($row['visit_source']); ?></span></td>
                                <td><span class="score-badge score-<?php echo floor($row['recommend_score']/2); ?>"><?php echo (int)$row['recommend_score']; ?></span></td>
                                <td class="text-summary"><?php echo htmlspecialchars(mb_strimwidth($row['first_impression'], 0, 50, '...')); ?></td>
                                <td>
                                    <button class="view-detail-btn" onclick='viewDetail(<?php echo json_encode($row, JSON_HEX_APOS | JSON_HEX_QUOT); ?>)'>
                                        <i class="fas fa-search"></i> 상세보기
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <!-- Modal for Individual Detail -->
    <div id="detailModal" class="modal-overlay hidden">
        <div class="modal-content">
            <div class="modal-header">
                <h3>피드백 상세 내역</h3>
                <button class="close-modal" onclick="closeModal()"><i class="fas fa-times"></i></button>
            </div>
            <div id="detailBody" class="modal-body">
                <!-- Injected via JS -->
            </div>
        </div>
    </div>

    <script>
        // Data from PHP
        const stats = <?php echo json_encode($chartData); ?>;

        // Chart.js Default Configs
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.color = '#64748b';

        // 1. Score Chart
        new Chart(document.getElementById('scoreChart'), {
            type: 'bar',
            data: {
                labels: stats.scores.labels,
                datasets: [{
                    label: '응답 수',
                    data: stats.scores.data,
                    backgroundColor: 'rgba(79, 70, 229, 0.6)',
                    borderColor: 'rgb(79, 70, 229)',
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { display: false } },
                    x: { grid: { display: false } }
                }
            }
        });

        // 2. Source Chart
        new Chart(document.getElementById('sourceChart'), {
            type: 'doughnut',
            data: {
                labels: stats.sources.labels,
                datasets: [{
                    data: stats.sources.data,
                    backgroundColor: [
                        '#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#6366f1'
                    ],
                    borderWidth: 0,
                    hoverOffset: 15
                }]
            },
            options: {
                cutout: '70%',
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
                }
            }
        });

        // 3. Purpose Chart (Horizontal Bar)
        new Chart(document.getElementById('purposeChart'), {
            type: 'bar',
            data: {
                labels: stats.purposes.labels,
                datasets: [{
                    label: '선택 횟수',
                    data: stats.purposes.data,
                    backgroundColor: 'rgba(16, 185, 129, 0.6)',
                    borderColor: 'rgb(16, 185, 129)',
                    borderWidth: 1,
                    indexAxis: 'y',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { beginAtZero: true, grid: { display: true, color: '#f1f5f9' } },
                    y: { grid: { display: false } }
                }
            }
        });

        // 4. Feature Chart (Horizontal Bar)
        new Chart(document.getElementById('featureChart'), {
            type: 'bar',
            data: {
                labels: stats.features.labels,
                datasets: [{
                    label: '선택 횟수',
                    data: stats.features.data,
                    backgroundColor: 'rgba(245, 158, 11, 0.6)',
                    borderColor: 'rgb(245, 158, 11)',
                    borderWidth: 1,
                    indexAxis: 'y',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { beginAtZero: true },
                    y: { grid: { display: false } }
                }
            }
        });

        // 5. Ease Chart
        new Chart(document.getElementById('easeChart'), {
            type: 'doughnut',
            data: {
                labels: stats.ease.labels,
                datasets: [{
                    data: stats.ease.data,
                    backgroundColor: ['#ef4444', '#f59e0b', '#10b981', '#3b82f6', '#8b5cf6'],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '60%',
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } }
                }
            }
        });

        // Detail View Logic
        function escapeHtml(text) {
            if (!text) return '';
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
        }

        function viewDetail(data) {
            const body = document.getElementById('detailBody');
            const purposes = JSON.parse(data.purpose || '[]');
            const features = JSON.parse(data.desired_features || '[]');
            
            body.innerHTML = `
                <div class="detail-grid">
                    <div class="detail-item full">
                        <span class="label">작성 시간</span>
                        <span class="val">${escapeHtml(data.created_at)}</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">유입 경로</span>
                        <span class="val">${escapeHtml(data.visit_source)} ${data.other_visit_source ? '('+escapeHtml(data.other_visit_source)+')' : ''}</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">추천 점수</span>
                        <span class="val score-val">${escapeHtml(data.recommend_score)} / 10</span>
                    </div>
                    <div class="detail-item full">
                        <span class="label">방문 목적</span>
                        <div class="tags-row">${purposes.map(p => `<span class="tag">${escapeHtml(p)}</span>`).join('')}</div>
                    </div>
                    <div class="detail-item full">
                        <span class="label">첫인상</span>
                        <div class="text-box">${escapeHtml(data.first_impression) || '없음'}</div>
                    </div>
                    <div class="detail-item">
                        <span class="label">검색 편의성</span>
                        <span class="val">${escapeHtml(data.search_ease)}</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">유용한 콘텐츠</span>
                        <span class="val">${escapeHtml(data.useful_content)}</span>
                    </div>
                    <div class="detail-item full">
                        <span class="label">요청 기능</span>
                        <div class="tags-row">${features.map(f => `<span class="tag gold">${escapeHtml(f)}</span>`).join('')} ${data.other_feature ? `<span class="tag gold">${escapeHtml(data.other_feature)}</span>` : ''}</div>
                    </div>
                </div>
            `;
            document.getElementById('detailModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('detailModal').classList.add('hidden');
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>
