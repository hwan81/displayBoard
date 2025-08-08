-- --------------------------------------------------------
-- 데이터베이스: notice_board_db (예시)
-- --------------------------------------------------------

--
-- 테이블 구조 `users`
-- 여러 학교의 관리자 계정과 API 연동 코드를 저장합니다.
--
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL COMMENT '로그인 아이디',
  `password` VARCHAR(255) NOT NULL COMMENT '비밀번호 (해시하여 저장)',
  `school_name` VARCHAR(100) NOT NULL COMMENT '학교 이름',
  `email` VARCHAR(100) NOT NULL COMMENT '이메일 주소',
  `api_area_code` VARCHAR(10) DEFAULT NULL COMMENT '나이스 API 지역코드 (ATPT_OFCDC_SC_CODE)',
  `api_school_code` VARCHAR(20) DEFAULT NULL COMMENT '나이스 API 행정표준코드 (SD_SCHUL_CODE)',
  `hide_meal_info` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '급식정보 표시하지 않기 (0: 표시, 1: 표시안함)',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '가입일',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='사용자 정보 및 API 설정 테이블';

--
-- 테이블 구조 `slides`
-- 각 사용자의 날짜별 슬라이드 내용을 저장합니다.
--
CREATE TABLE IF NOT EXISTS `slides` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL COMMENT '사용자 ID (users.id)',
  `start_date` DATE NOT NULL DEFAULT (CURRENT_DATE) COMMENT '게시 시작일',
  `end_date` DATE NOT NULL DEFAULT '9999-12-31' COMMENT '게시 종료일',
  `content` MEDIUMTEXT NOT NULL COMMENT '슬라이드 HTML 내용',
  `duration` INT(11) NOT NULL DEFAULT 10 COMMENT '표시 시간 (초)',
  `slide_order` INT(11) NOT NULL DEFAULT 0 COMMENT '슬라이드 순서',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '생성일',
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '수정일',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `slides_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='슬라이드 콘텐츠 테이블';

--
-- `boards` 테이블은 `users` 테이블에 통합되었으므로 삭제됩니다.
-- DROP TABLE IF EXISTS `boards`;
--