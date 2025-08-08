# 📦 디지털 사이니지 시스템 설치 가이드

이 문서는 디지털 사이니지 시스템을 설치하는 방법을 단계별로 안내합니다.

## 🎯 설치 옵션

1. **XAMPP를 이용한 로컬 설치** (개발/테스트용)
2. **웹 호스팅 업체를 통한 설치** (운영용)

---

## 🖥️ 방법 1: XAMPP를 이용한 로컬 설치

### 1단계: XAMPP 설치

1. [XAMPP 공식 웹사이트](https://www.apachefriends.org/)에서 다운로드
2. 설치 시 다음 구성 요소 선택:
   - ✅ Apache
   - ✅ MySQL
   - ✅ PHP
   - ✅ phpMyAdmin

### 2단계: 파일 배치

1. 다운로드한 ZIP 파일을 압축 해제
2. 압축 해제된 폴더를 `C:\xampp\htdocs\digital-signage`로 복사
   ```
   C:\xampp\htdocs\digital-signage\
   ├── index.php
   ├── admin.php
   ├── display.html
   ├── php/
   └── js/
   ```

### 3단계: XAMPP 실행

1. XAMPP Control Panel 실행
2. Apache와 MySQL 서비스 시작
   - Apache: `Start` 버튼 클릭
   - MySQL: `Start` 버튼 클릭

### 4단계: 데이터베이스 설정

1. 웹 브라우저에서 `http://localhost/phpmyadmin` 접속
2. 새 데이터베이스 생성:
   - 데이터베이스 이름: `digital_signage`
   - 문자 집합: `utf8mb4_unicode_ci`
3. 생성된 데이터베이스 선택 후 `가져오기` 탭 클릭
4. `tables.sql` 파일 업로드 및 실행

### 5단계: 설정 파일 생성

1. `php/db_config.example.php`를 복사하여 `php/db_config.php` 생성
2. `php/db_config.php` 파일 수정:
   ```php
   <?php
   define('DB_SERVER', 'localhost');
   define('DB_USERNAME', 'root');           // XAMPP 기본값
   define('DB_PASSWORD', '');               // XAMPP 기본값 (빈 문자열)
   define('DB_NAME', 'digital_signage');
   define('NEIS_API_KEY', 'YOUR_API_KEY');  // 나이스 API 키 (선택사항)
   ?>
   ```

### 6단계: 시스템 접속

- **관리자 페이지**: `http://localhost/digital-signage/`
- **디스플레이 화면**: `http://localhost/digital-signage/display.html`

---

## 🌐 방법 2: 웹 호스팅 업체를 통한 설치

### 호스팅 업체 요구사항 확인

다음 사양을 지원하는 호스팅 업체를 선택하세요:

#### 필수 사양
- **웹 서버**: Apache 2.4+ 또는 Nginx 1.18+
- **PHP**: 7.4 이상 (8.0+ 권장)
- **데이터베이스**: MySQL 5.7+ 또는 MariaDB 10.3+
- **PHP 확장**: MySQLi, JSON
- **기타**: `.htaccess` 지원, `mod_rewrite` 활성화

#### 추천 호스팅 업체 (한국)
- **카페24**: PHP, MySQL 지원, 저렴한 가격
- **가비아**: 안정적인 서비스, 기술 지원
- **후이즈**: 개발자 친화적, 다양한 PHP 버전 지원
- **닷홈**: 무료 호스팅도 제공

### 1단계: 파일 업로드

1. **FTP 클라이언트 사용** (FileZilla, WinSCP 등)
   - 호스팅 업체에서 제공한 FTP 정보로 접속
   - 압축 해제된 모든 파일을 웹 루트 디렉토리에 업로드
   ```
   public_html/ (또는 www/, htdocs/)
   ├── index.php
   ├── admin.php
   ├── display.html
   ├── php/
   └── js/
   ```

2. **웹 호스팅 파일 매니저 사용**
   - 호스팅 업체 제공 웹 인터페이스 사용
   - ZIP 파일 업로드 후 서버에서 직접 압축 해제

### 2단계: 데이터베이스 생성

1. 호스팅 업체의 관리 패널 접속 (cPanel, DirectAdmin 등)
2. **MySQL 데이터베이스** 메뉴 선택
3. 새 데이터베이스 생성:
   - 데이터베이스 이름: `your_username_digital_signage`
   - 문자 집합: `utf8mb4_unicode_ci`
4. 데이터베이스 사용자 생성 및 권한 부여
5. **phpMyAdmin** 또는 **MySQL 가져오기**를 통해 `tables.sql` 실행

### 3단계: 설정 파일 수정

1. `php/db_config.example.php`를 복사하여 `php/db_config.php` 생성
2. 호스팅 업체에서 제공한 데이터베이스 정보로 수정:
   ```php
   <?php
   define('DB_SERVER', 'localhost');        // 또는 호스팅 업체 제공 주소
   define('DB_USERNAME', 'your_db_user');   // 데이터베이스 사용자명
   define('DB_PASSWORD', 'your_db_pass');   // 데이터베이스 비밀번호
   define('DB_NAME', 'your_db_name');       // 데이터베이스 이름
   define('NEIS_API_KEY', 'YOUR_API_KEY');  // 나이스 API 키 (선택사항)
   ?>
   ```

### 4단계: 파일 권한 설정

다음 디렉토리/파일의 권한을 확인하세요:
- `php/` 디렉토리: 755
- `php/db_config.php`: 644
- 기타 PHP 파일: 644
- HTML/JS 파일: 644

### 5단계: 시스템 접속

- **관리자 페이지**: `https://yourdomain.com/`
- **디스플레이 화면**: `https://yourdomain.com/display.html`

---

## 🔧 초기 설정

### 1. 관리자 계정 생성
1. `https://yourdomain.com/register.html` 접속
2. 관리자 계정 정보 입력:
   - 사용자명, 비밀번호
   - 학교명
   - 나이스 API 정보 (선택사항)

### 2. 나이스 API 키 발급 (급식 정보 사용 시)
1. [나이스 교육정보 개방포털](https://open.neis.go.kr/) 접속
2. 회원가입 및 로그인
3. **Open API** → **API 키 발급** 메뉴
4. 발급받은 키를 `db_config.php`에 입력

### 3. 학교 정보 설정
1. 관리자 페이지 로그인
2. **학교 정보 설정**에서 다음 정보 입력:
   - **지역코드**: 나이스 API 지역코드 (예: J10)
   - **학교코드**: 나이스 API 학교코드 (예: 7531000)

---

## 🚨 문제 해결

### 자주 발생하는 문제

#### 1. "데이터베이스 연결 실패"
- `db_config.php` 설정 정보 확인
- 데이터베이스 서버 상태 확인
- 호스팅 업체 데이터베이스 서비스 상태 확인

#### 2. "페이지를 찾을 수 없음" (404 오류)
- `.htaccess` 파일이 업로드되었는지 확인
- 호스팅 업체에서 `mod_rewrite` 지원 여부 확인
- 파일 권한 설정 확인

#### 3. "급식 정보 로딩 실패"
- 나이스 API 키 유효성 확인
- 지역코드, 학교코드 정확성 확인
- 인터넷 연결 상태 확인

#### 4. "슬라이드가 표시되지 않음"
- 관리자 페이지에서 슬라이드 등록 여부 확인
- 슬라이드 게시 기간 설정 확인
- 브라우저 캐시 삭제 후 재시도

### 기술 지원

추가 도움이 필요하시면:
1. 호스팅 업체 기술 지원팀 문의
2. GitHub Issues 페이지 활용
3. PHP 오류 로그 확인 (`/logs/` 디렉토리)

---

## 🔒 보안 강화 (선택사항)

### 1. HTTPS 설정
- 호스팅 업체에서 SSL 인증서 설치
- HTTP에서 HTTPS로 자동 리디렉션 설정

### 2. 파일 접근 제한
```apache
# .htaccess 파일에 추가
<Files "db_config.php">
    Order Allow,Deny
    Deny from all
</Files>
```

### 3. 정기 백업
- 데이터베이스 정기 백업 설정
- 파일 백업 스케줄 설정

---

## 📱 사용 방법

설치가 완료되면 [README.md](README.md) 파일의 사용 방법을 참고하여 시스템을 운용하세요.

**🎉 설치 완료! 디지털 사이니지 시스템을 활용해보세요!**
