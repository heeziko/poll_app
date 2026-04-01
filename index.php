<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>홈페이지 방문 피드백</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="survey-container">
        <header class="survey-header">
            <div class="logo-area"><i class="fas fa-poll"></i></div>
            <h1>홈페이지 방문 피드백</h1>
            <p>소중한 시간 내어 피드백 주셔서 감사합니다.<br>여러분의 의견은 더 나은 사이트를 만드는 데 큰 도움이 됩니다.</p>
        </header>

        <form action="submit.php" method="POST" id="poll-form">
            <!-- 1. Source -->
            <div class="form-section">
                <h3><span class="step-num">1</span>어떤 경로로 이 사이트를 방문하셨나요?</h3>
                <div class="radio-group">
                    <label class="radio-card">
                        <input type="radio" name="visit_source" value="검색 엔진" required>
                        <div class="card-content">
                            <i class="fas fa-search"></i>
                            <span>검색 엔진</span>
                        </div>
                    </label>
                    <label class="radio-card">
                        <input type="radio" name="visit_source" value="SNS">
                        <div class="card-content">
                            <i class="fab fa-instagram"></i>
                            <span>SNS</span>
                        </div>
                    </label>
                    <label class="radio-card">
                        <input type="radio" name="visit_source" value="지인 추천">
                        <div class="card-content">
                            <i class="fas fa-user-friends"></i>
                            <span>지인 추천</span>
                        </div>
                    </label>
                    <label class="radio-card">
                        <input type="radio" name="visit_source" value="포트폴리오">
                        <div class="card-content">
                            <i class="fas fa-briefcase"></i>
                            <span>포트폴리오</span>
                        </div>
                    </label>
                    <label class="radio-card">
                        <input type="radio" name="visit_source" value="기타">
                        <div class="card-content">
                            <i class="fas fa-ellipsis-h"></i>
                            <span>기타</span>
                        </div>
                    </label>
                </div>
                <div id="other-source-field" class="hidden">
                    <input type="text" name="other_visit_source" placeholder="기타 경로를 입력해 주세요">
                </div>
            </div>

            <!-- 2. Purpose -->
            <div class="form-section">
                <h3><span class="step-num">2</span>어떤 정보를 찾으러 오셨나요? (중복 선택 가능)</h3>
                <div class="checkbox-group">
                    <label class="check-item"><input type="checkbox" name="purpose[]" value="바이브 코딩 학습 자료"><span>바이브 코딩 학습 자료</span></label>
                    <label class="check-item"><input type="checkbox" name="purpose[]" value="디자인/일러스트 작품"><span>디자인/일러스트 작품</span></label>
                    <label class="check-item"><input type="checkbox" name="purpose[]" value="강의/워크샵 정보"><span>강의/워크샵 정보</span></label>
                    <label class="check-item"><input type="checkbox" name="purpose[]" value="교수/전문가 프로필"><span>교수/전문가 프로필</span></label>
                    <label class="check-item"><input type="checkbox" name="purpose[]" value="프로젝트 협업 문의"><span>프로젝트 협업 문의</span></label>
                    <label class="check-item"><input type="checkbox" name="purpose[]" value="그냥 둘러보기"><span>그냥 둘러보기</span></label>
                </div>
            </div>

            <!-- 3. Impression -->
            <div class="form-section">
                <h3><span class="step-num">3</span>사이트의 첫인상은 어떠셨나요?</h3>
                <div class="option-list">
                    <label class="option-item"><input type="radio" name="first_impression" value="😍 매우 인상적이고 독창적" required><span>😍 매우 인상적이고 독창적</span></label>
                    <label class="option-item"><input type="radio" name="first_impression" value="😊 깔끔하고 전문적"><span>😊 깔끔하고 전문적</span></label>
                    <label class="option-item"><input type="radio" name="first_impression" value="😐 평범하지만 무난함"><span>😐 평범하지만 무난함</span></label>
                    <label class="option-item"><input type="radio" name="first_impression" value="😕 약간 혼란스러움"><span>😕 약간 혼란스러움</span></label>
                    <label class="option-item"><input type="radio" name="first_impression" value="😞 개선이 많이 필요함"><span>😞 개선이 많이 필요함</span></label>
                </div>
            </div>

            <!-- 4. Ease of use -->
            <div class="form-section">
                <h3><span class="step-num">4</span>원하는 정보를 찾기 쉬웠나요?</h3>
                <div class="option-list">
                    <label class="option-item"><input type="radio" name="search_ease" value="매우 쉬웠다" required><span>매우 쉬웠다 (직관적)</span></label>
                    <label class="option-item"><input type="radio" name="search_ease" value="쉬웠다"><span>쉬웠다 (조금 탐색 필요)</span></label>
                    <label class="option-item"><input type="radio" name="search_ease" value="보통이다"><span>보통이다</span></label>
                    <label class="option-item"><input type="radio" name="search_ease" value="어려웠다"><span>어려웠다 (구조 파악 힘듦)</span></label>
                    <label class="option-item"><input type="radio" name="search_ease" value="찾지 못했다"><span>찾지 못했다</span></label>
                </div>
            </div>

            <!-- 5. Content -->
            <div class="form-section">
                <h3><span class="step-num">5</span>가장 인상 깊었거나 유용했던 콘텐츠는?</h3>
                <p class="instruction">예: 바이브 코딩 튜토리얼, 프로젝트 갤러리, 블로그 글 제목 등 구체적으로 적어주세요.</p>
                <textarea name="useful_content" placeholder="답변을 입력해 주세요" rows="4"></textarea>
            </div>

            <!-- 6. Features -->
            <div class="form-section">
                <h3><span class="step-num">6</span>이 사이트에 추가되었으면 하는 기능이나 콘텐츠는?</h3>
                <div class="checkbox-group">
                    <label class="check-item"><input type="checkbox" name="desired_features[]" value="온라인 코딩 에디터"><span>온라인 코딩 에디터/플레이그라운드</span></label>
                    <label class="check-item"><input type="checkbox" name="desired_features[]" value="강의 일정/신청"><span>강의 일정/신청 시스템</span></label>
                    <label class="check-item"><input type="checkbox" name="desired_features[]" value="커뮤니티/댓글"><span>커뮤니티/댓글 기능</span></label>
                    <label class="check-item"><input type="checkbox" name="desired_features[]" value="작품 다운로드/공유"><span>작품 다운로드/공유 기능</span></label>
                    <label class="check-item"><input type="checkbox" name="desired_features[]" value="뉴스레터 구독"><span>뉴스레터 구독</span></label>
                </div>
                <div class="input-field">
                    <span>기타사항: </span>
                    <input type="text" name="other_feature" placeholder="추가하고 싶은 기능...">
                </div>
            </div>

            <!-- 7. NPS -->
            <div class="form-section">
                <h3><span class="step-num">7</span>이 사이트를 다른 사람에게 추천하실 의향이 있나요?</h3>
                <div class="rating-container">
                    <div class="rating-labels">
                        <span>절대 아니다</span>
                        <span>보통</span>
                        <span>적극 추천</span>
                    </div>
                    <div class="rating-options">
                        <?php for($i=0; $i<=10; $i++): ?>
                        <label class="rating-box">
                            <input type="radio" name="recommend_score" value="<?php echo $i; ?>" required>
                            <span><?php echo $i; ?></span>
                        </label>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>

            <div class="submit-area">
                <button type="submit" class="submit-btn">피드백 제출하기 <i class="fas fa-paper-plane"></i></button>
            </div>
        </form>

        <footer class="survey-footer">
            <p>🙏 소중한 피드백은 사이트 개선에 직접 반영됩니다.</p>
            <div class="contact-info">
                <span>heeziko@kakao.com</span>
            </div>
        </footer>
    </div>

    <script src="scripts.js"></script>
</body>
</html>
