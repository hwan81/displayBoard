# 🖥️ 디지털 사이니지 시스템 (Digital Signage System)

학교나 기관에서 사용할 수 있는 웹 기반 디지털 사이니지 관리 시스템입니다.

## ✨ 주요 기능

### 📋 관리자 기능
- **슬라이드 관리**: 공지사항, 이벤트 등 다양한 콘텐츠 슬라이드 생성/수정/삭제
- **일정 관리**: 캘린더를 통한 날짜별 콘텐츠 관리
- **기간 설정**: 슬라이드별 게시 시작일/종료일 설정
- **실시간 에디터**: TinyMCE 에디터를 통한 리치 텍스트 편집
- **나이스 API 연동**: 교육청 급식 정보 자동 연동

### 📺 디스플레이 기능
- **자동 슬라이드쇼**: 설정된 시간에 따른 자동 슬라이드 전환
- **실시간 시계**: 현재 날짜/시간 표시
- **급식 정보**: 나이스 API를 통한 실시간 급식 메뉴 표시
- **전체화면 모드**: 디스플레이 최적화를 위한 전체화면 지원
- **자동 새로고침**: 자정 넘어가면 자동 페이지 새로고침

## 🛠️ 기술 스택

- **Frontend**: HTML5, CSS3, JavaScript, TailwindCSS
- **Backend**: PHP 7.4+
- **Database**: MySQL/MariaDB
- **Libraries**: 
  - Swiper.js (슬라이드쇼)
  - TinyMCE (에디터)
  - Flatpickr (날짜 선택)
  - Toastify (알림)

## 📦 설치 방법

### 1. 저장소 클론
```bash
git clone https://github.com/your-username/digital-signage-system.git
cd digital-signage-system
```

### 2. 데이터베이스 설정
```sql
-- MySQL/MariaDB에서 실행
CREATE DATABASE your_database_name;
USE your_database_name;
SOURCE tables.sql;
```

### 3. 설정 파일 생성
```bash
# 데이터베이스 설정 파일 생성
cp php/db_config.example.php php/db_config.php
```

### 4. 설정 파일 수정
`php/db_config.php` 파일을 열어 실제 정보로 수정:

```php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'your_username');     // 데이터베이스 사용자명
define('DB_PASSWORD', 'your_password');     // 데이터베이스 비밀번호
define('DB_NAME', 'your_database');         // 데이터베이스 이름
define('NEIS_API_KEY', 'your_api_key');     // 나이스 API 키
```

### 5. 나이스 API 키 발급
1. [나이스 교육정보 개방포털](https://open.neis.go.kr/) 접속
2. 회원가입 및 API 키 발급
3. `db_config.php`에 API 키 입력

### 6. 웹 서버 설정
- Apache 또는 Nginx 설정
- PHP 7.4+ 및 MySQL 확장 모듈 활성화
- `mod_rewrite` 활성화 (Apache)

## 🚀 사용 방법

### 초기 설정
1. 웹 브라우저에서 `http://your-domain/register.html` 접속
2. 관리자 계정 생성
3. `http://your-domain/admin.php`에서 로그인

### 슬라이드 관리
1. 관리자 페이지에서 날짜 선택
2. "새 슬라이드" 버튼 클릭
3. 콘텐츠 작성 및 게시 기간 설정
4. 저장

### 디스플레이 사용
1. `http://your-domain/display.html?username=관리자아이디` 접속
2. 전체화면 버튼 클릭
3. 디지털 사이니지 화면 확인

## 📁 프로젝트 구조

```
digital-signage-system/
├── index.php              # 메인 진입점 (세션 체크 및 리디렉션)
├── admin.php              # 관리자 페이지
├── display.html           # 디스플레이 화면
├── login.html             # 로그인 페이지
├── register.html          # 회원가입 페이지
├── tables.sql             # 데이터베이스 스키마
├── php/                   # PHP 백엔드 파일들
│   ├── db_config.example.php  # 데이터베이스 설정 예제
│   ├── login.php              # 로그인 처리
│   ├── register.php           # 회원가입 처리
│   ├── add_slide.php          # 슬라이드 추가
│   ├── get_slides.php         # 슬라이드 목록 조회
│   ├── get_display_data.php   # 디스플레이 데이터 조회
│   ├── get_meal.php           # 급식 정보 조회
│   └── ...
└── js/                    # JavaScript 라이브러리
    └── tinymce/          # TinyMCE 에디터
```

## 🔧 환경 요구사항

- **웹 서버**: Apache 2.4+ 또는 Nginx 1.18+
- **PHP**: 7.4 이상 (8.0+ 권장)
- **데이터베이스**: MySQL 5.7+ 또는 MariaDB 10.3+
- **브라우저**: Chrome, Firefox, Safari, Edge (최신 버전)

## 📖 API 문서

### 나이스 교육정보 개방포털 API
- **급식 정보**: `/hub/mealServiceDietInfo`
- **지원 매개변수**: 지역코드, 학교코드, 급식일자
- **문서**: https://open.neis.go.kr/

## 🛡️ 보안 고려사항

- `db_config.php` 파일은 절대 공개 저장소에 업로드하지 마세요
- 정기적으로 비밀번호를 변경하세요
- HTTPS 사용을 권장합니다
- 데이터베이스 접근 권한을 최소화하세요

## 📝 라이센스

이 프로젝트는 MIT 라이센스 하에 배포됩니다. 자세한 내용은 `LICENSE` 파일을 참조하세요.

## 🙏 감사의 말

- [TinyMCE](https://www.tiny.cloud/) - 리치 텍스트 에디터
- [Swiper.js](https://swiperjs.com/) - 슬라이드쇼 라이브러리
- [TailwindCSS](https://tailwindcss.com/) - CSS 프레임워크
- [나이스 교육정보 개방포털](https://open.neis.go.kr/) - 급식 정보 API


---

**Made with ❤️ for Education**
