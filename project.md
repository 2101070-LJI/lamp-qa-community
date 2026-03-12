# LAMP 커뮤니티 Q&A 포럼 프로젝트

## 1. 프로젝트 개요

| 항목 | 내용 |
|------|------|
| 프로젝트명 | LAMP Q&A 커뮤니티 |
| 목적 | LAMP 스택 학습 및 실습 (Stack Overflow 스타일 Q&A 사이트 구현) |
| 기술스택 | Linux, Apache, MySQL, PHP (LAMP) |
| 개발환경 | Ubuntu, Apache 2.4, MySQL 8.0, PHP 8.x |

---

## 2. 핵심 기능 및 우선순위

| 순위 | 기능 | 설명 |
|------|------|------|
| P1 | 회원 관리 | 회원가입, 로그인, 로그아웃 (세션 기반) |
| P1 | 질문 CRUD | 질문 작성/수정/삭제/조회 |
| P1 | 답변 CRUD | 답변 작성/수정/삭제 |
| P2 | 투표 시스템 | 질문/답변 추천/비추천 |
| P2 | 베스트 답변 채택 | 질문 작성자가 답변 채택 |
| P2 | 태그 시스템 | 질문에 태그 분류 및 필터링 |

---

## 3. DB 설계 (MySQL)

### users
```sql
CREATE TABLE users (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    username    VARCHAR(50) UNIQUE NOT NULL,
    email       VARCHAR(100) UNIQUE NOT NULL,
    password    VARCHAR(255) NOT NULL,         -- bcrypt 해시
    points      INT DEFAULT 0,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

### questions
```sql
CREATE TABLE questions (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT NOT NULL,
    title       VARCHAR(255) NOT NULL,
    body        TEXT NOT NULL,
    view_count  INT DEFAULT 0,
    vote_count  INT DEFAULT 0,
    is_solved   TINYINT(1) DEFAULT 0,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

### answers
```sql
CREATE TABLE answers (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    question_id  INT NOT NULL,
    user_id      INT NOT NULL,
    body         TEXT NOT NULL,
    vote_count   INT DEFAULT 0,
    is_accepted  TINYINT(1) DEFAULT 0,
    created_at   DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at   DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (question_id) REFERENCES questions(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

### votes
```sql
CREATE TABLE votes (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    user_id      INT NOT NULL,
    target_type  ENUM('question', 'answer') NOT NULL,
    target_id    INT NOT NULL,
    value        TINYINT NOT NULL,              -- 1: 추천, -1: 비추천
    created_at   DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_vote (user_id, target_type, target_id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

### tags
```sql
CREATE TABLE tags (
    id    INT AUTO_INCREMENT PRIMARY KEY,
    name  VARCHAR(50) UNIQUE NOT NULL
);
```

### question_tags
```sql
CREATE TABLE question_tags (
    question_id  INT NOT NULL,
    tag_id       INT NOT NULL,
    PRIMARY KEY (question_id, tag_id),
    FOREIGN KEY (question_id) REFERENCES questions(id),
    FOREIGN KEY (tag_id) REFERENCES tags(id)
);
```

### notifications
```sql
CREATE TABLE notifications (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT NOT NULL,
    message     VARCHAR(255) NOT NULL,
    is_read     TINYINT(1) DEFAULT 0,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

---

## 4. 파일/디렉토리 구조 (MVC 패턴)

```
project1/
├── config/
│   ├── database.php        # PDO DB 연결
│   └── config.php          # 환경설정 (상수 정의)
├── controllers/
│   ├── AuthController.php  # 회원가입, 로그인, 로그아웃
│   ├── QuestionController.php
│   ├── AnswerController.php
│   ├── VoteController.php
│   └── ProfileController.php
├── models/
│   ├── User.php
│   ├── Question.php
│   ├── Answer.php
│   ├── Vote.php
│   └── Tag.php
├── views/
│   ├── auth/
│   │   ├── login.php
│   │   └── register.php
│   ├── questions/
│   │   ├── index.php       # 질문 목록
│   │   ├── show.php        # 질문 상세 + 답변
│   │   ├── create.php      # 질문 작성 폼
│   │   └── edit.php        # 질문 수정 폼
│   └── profile/
│       └── show.php
├── public/
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   └── main.js
│   └── images/
├── includes/
│   ├── header.php          # 공통 헤더
│   ├── footer.php          # 공통 푸터
│   └── functions.php       # 공통 유틸 함수
├── .htaccess               # URL 라우팅 (mod_rewrite)
├── index.php               # 진입점 (프론트 컨트롤러)
└── project.md
```

---

## 5. 개발 단계 (Phase)

| Phase | 내용 | 기간 |
|-------|------|------|
| Phase 1 | 개발 환경 설정 + 회원 기능 (가입/로그인/로그아웃/세션) | 2주 |
| Phase 2 | 질문/답변 CRUD + 페이지네이션 | 2주 |
| Phase 3 | 투표 시스템 + 베스트 답변 채택 + 태그 | 1주 |
| Phase 4 | 검색 기능 + 사용자 프로필/포인트 | 1주 |
| Phase 5 | 알림 시스템 + 관리자 기능 | 1주 |

---

## 6. 학습 포인트

- **PHP 세션/쿠키 관리**: `session_start()`, `$_SESSION`, 로그인 상태 유지
- **MySQL PDO 사용법**: `PDO`, `prepare()`, `execute()`, `fetch()`
- **SQL Injection 방지**: Prepared Statements 사용 (바인딩 파라미터)
- **XSS 방지**: 출력 시 `htmlspecialchars()` 적용
- **Apache .htaccess URL 라우팅**: `mod_rewrite`로 클린 URL 구현
- **비밀번호 보안**: `password_hash()` / `password_verify()` (bcrypt)
- **CSRF 방지**: 폼에 토큰 삽입 및 검증
