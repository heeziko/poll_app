# 📊 POLL - 프리미엄 설문조사 & 대시보드 애플리케이션

![PHP](https://img.shields.io/badge/PHP-7.4+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Chart.js](https://img.shields.io/badge/Chart.js-4.0-FF6384?style=for-the-badge&logo=chartdotjs&logoColor=white)

사용자의 피드백을 수집하고, 실시간으로 시각화된 통계 데이터를 제공하는 깔끔하고 모던한 설문조사 웹 애플리케이션입니다.

## ✨ 주요 기능

- **모던 설문 폼**: 직관적이고 반응성이 뛰어난 UI/UX 기반의 피드백 수집 폼.
- **다이나믹 대시보드**: Chart.js를 사용한 5가지 핵심 지표 시각화.
  - 추천 점수 분포 (Bar)
  - 유입 경로 분석 (Doughnut)
  - 방문 목적 통계 (Horizontal Bar)
  - 희망 추가 기능 순위 (Horizontal Bar)
  - 검색/탐색 편의성 분포 (Doughnut)
- **보안 강화**: XSS 방지 필터링 및 SQL Injection 방지를 위한 Prepared Statements 적용.
- **관리자 시스템**: 비밀번호 보호 기반의 관리자 세션 처리 및 상세 피드백 모달 뷰.

## 🛠 기술 스택

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7 / 8.0
- **Frontend**: Vanilla JS, Chart.js, FontAwesome, Google Fonts (Inter, Outfit)
- **Styling**: Vanilla CSS (Custom Dashboard Theme)

## 🚀 시작하기 (설치 방법)

### 1. 전제 조건
- PHP가 설치된 웹 서버 (Apache / Nginx)
- MySQL 데이터베이스 서버

### 2. 데이터베이스 설정
`schema.sql` 파일을 실행하여 데이터베이스와 테이블을 생성합니다.
```bash
mysql -u [username] -p < schema.sql
```

### 3. 프로젝트 복사 및 설정
1. 프로젝트를 웹 서버 루트 디렉토리에 복사합니다.
2. `config.sample.php`를 `config.php`로 복사합니다.
   ```bash
   cp config.sample.php config.php
   ```
3. `config.php` 파일을 열어 자신의 DB 환경에 맞게 수정합니다.
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'your_user');
   define('DB_PASS', 'your_pass');
   define('DB_NAME', 'POLL');
   define('ADMIN_PASS', 'admin_password_here');
   ```

## 📁 프로젝트 구조

```text
poll_app/
├── admin.php          # 관리자 통계 대시보드
├── config.php         # 환경 설정 (비공개)
├── config.sample.php  # 환경 설정 템플릿
├── index.php          # 설문조사 메인 화면
├── schema.sql         # DB 테이블 스키마
├── scripts.js         # 프론트엔드 공통 로직
├── styles.css         # 전체 스타일링 (모던 대시보드 포함)
└── submit.php         # 피드백 제출 처리 라이브러리
```

## 🔒 보안 정보

- **Config 보호**: `config.php`는 비밀번호를 포함하므로 `.gitignore`에 등록되어 있습니다. Git에 커밋되지 않도록 주의하세요.
- **데이터 검증**: 모든 사용자 입력값은 PHP의 `htmlspecialchars`와 JS의 `escapeHtml`을 통해 필터링된 후 출력됩니다.
- **SQL 보안**: 모든 DB 쿼리는 `mysqli_stmt` 수준에서 파라미터 바인딩을 통해 실행됩니다.

## 📄 라이선스

본 프로젝트는 자유롭게 수정 및 배포가 가능합니다.

---
**Created by Antigravity AI**
