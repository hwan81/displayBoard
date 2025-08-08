# 🚀 배포 준비 체크리스트

ZIP 파일로 배포하기 전에 다음 사항들을 확인하세요.

## ✅ 필수 확인 사항

### 1. 보안 파일 제거
- [ ] `php/db_config.php` 파일이 제외되었는지 확인
- [ ] `.env`, `.env.local` 등 환경 설정 파일 제외
- [ ] 디버그 파일들 (`phptest.php`, `index_debug.php`) 제외

### 2. 예시 파일 포함
- [ ] `php/db_config.example.php` 파일 포함
- [ ] `tables.sql` 파일 포함
- [ ] `README.md` 및 `INSTALL.md` 파일 포함

### 3. 필수 파일 확인
- [ ] `index.php` (메인 진입점)
- [ ] `admin.php` (관리자 페이지)
- [ ] `display.html` (디스플레이 화면)
- [ ] `login.html`, `register.html`
- [ ] `php/` 디렉토리의 모든 PHP 파일
- [ ] `js/` 디렉토리의 라이브러리 파일들
- [ ] `.htaccess` 파일

### 4. 문서 확인
- [ ] README.md에 프로젝트 설명 포함
- [ ] INSTALL.md에 설치 가이드 포함
- [ ] 라이선스 정보 포함
- [ ] 사용 방법 안내 포함

## 📁 배포 파일 구조

ZIP 파일에 포함되어야 할 디렉토리 구조:

```
digital-signage-system/
├── README.md                   # 프로젝트 소개
├── INSTALL.md                  # 설치 가이드
├── LICENSE                     # 라이선스 (선택)
├── .htaccess                   # Apache 설정
├── index.php                   # 메인 진입점
├── admin.php                   # 관리자 페이지
├── display.html               # 디스플레이 화면
├── login.html                 # 로그인 페이지
├── register.html              # 회원가입 페이지
├── tables.sql                 # 데이터베이스 스키마
├── php/                       # PHP 백엔드 파일들
│   ├── db_config.example.php  # 데이터베이스 설정 예제 (필수)
│   ├── login.php
│   ├── register.php
│   ├── add_slide.php
│   ├── get_slides.php
│   ├── get_display_data.php
│   ├── get_meal.php
│   └── ... (기타 PHP 파일들)
└── js/                        # JavaScript 라이브러리
    └── tinymce/              # TinyMCE 에디터
        ├── tinymce.min.js
        ├── icons/
        ├── plugins/
        ├── skins/
        └── themes/
```

## 🚫 배포에서 제외할 파일들

다음 파일들은 `.gitignore`에 포함되어 있으며 배포 시 제외해야 합니다:

```
# 보안 설정 파일
php/db_config.php

# 디버그 파일
phptest.php
index_debug.php

# IDE 설정
.idea/
.vscode/
*.swp
*.swo

# 로그 파일
*.log
error.log

# 운영체제 파일
.DS_Store
Thumbs.db

# 임시 파일
*.tmp
*.temp

# 백업 파일
*.backup
*_backup.*
index_backup.html

# 환경별 설정
.env
.env.local
.env.production

# Git 관련
.git/
.gitignore
```

## 📦 ZIP 파일 생성 방법

### Windows에서:
```powershell
# PowerShell에서 실행
Compress-Archive -Path ".\*" -DestinationPath "digital-signage-system.zip" -Exclude @("php\db_config.php", "phptest.php", "index_debug.php", ".git", ".idea")
```

### Linux/Mac에서:
```bash
# 터미널에서 실행
zip -r digital-signage-system.zip . -x "php/db_config.php" "phptest.php" "index_debug.php" ".git/*" ".idea/*"
```

## 📋 배포 후 사용자 안내사항

ZIP 파일과 함께 다음 정보를 제공하세요:

### 시스템 요구사항
- PHP 7.4 이상
- MySQL 5.7 이상 또는 MariaDB 10.3 이상
- Apache 2.4 이상 또는 Nginx 1.18 이상

### 설치 순서
1. INSTALL.md 파일 읽기
2. 호스팅 환경 또는 XAMPP 설정
3. 데이터베이스 생성 및 tables.sql 실행
4. db_config.php 설정 파일 생성
5. 관리자 계정 생성

### 지원 정보
- GitHub Issues: 문제 신고 및 질문
- 설치 가이드: INSTALL.md
- 사용 방법: README.md

## 🔍 최종 검증

배포 전 다음을 확인하세요:

1. **테스트 환경에서 ZIP 파일 압축 해제 후 설치 테스트**
2. **모든 기능이 정상 작동하는지 확인**
3. **문서의 정확성 검증**
4. **보안 파일이 포함되지 않았는지 재확인**

✅ **모든 항목이 확인되면 배포 준비 완료!**
