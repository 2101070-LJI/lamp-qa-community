# LAMP Q&A 커뮤니티

Stack Overflow 스타일의 Q&A 포럼을 LAMP 스택으로 구현한 프로젝트입니다.

---

## 기술 스택

| 구성 | 버전 |
|------|------|
| OS | Linux (Ubuntu) |
| Web Server | Apache 2.4 (mod_rewrite) |
| Database | MySQL 8.0 |
| Backend | PHP 8.x |
| Architecture | MVC 패턴 (프론트 컨트롤러) |

---

## 프로젝트 구조

```
project1/
├── config/
│   ├── database.php        # PDO DB 연결 (싱글턴)
│   └── config.php          # 환경설정 상수
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
│   ├── auth/               # 로그인, 회원가입 뷰
│   ├── questions/          # 질문 목록, 상세, 작성, 수정
│   └── profile/            # 프로필 뷰
├── includes/
│   ├── header.php
│   ├── footer.php
│   └── functions.php       # 공통 유틸 함수
├── public/
│   ├── css/style.css
│   └── js/main.js
├── .htaccess               # URL 라우팅 (mod_rewrite)
└── index.php               # 프론트 컨트롤러
```

---

## 구현 기능

### P1 - 핵심 기능

#### 회원 관리
- 회원가입 / 로그인 / 로그아웃
- PHP 세션 기반 인증 (`session_start`, `$_SESSION`)
- bcrypt 비밀번호 암호화 (`password_hash` / `password_verify`)

#### 질문 CRUD
- 질문 목록 조회 (페이지네이션)
- 질문 상세 조회 (조회수 자동 증가)
- 질문 작성 / 수정 / 삭제 (작성자 본인만)

#### 답변 CRUD
- 답변 작성 / 수정 / 삭제 (작성자 본인만)

---

### P2 - 부가 기능

#### 투표 시스템
- 질문 / 답변 추천(+1) / 비추천(-1)
- 자기 글 투표 방지
- 중복 투표 방지 (`UNIQUE KEY` 제약)

#### 베스트 답변 채택
- 질문 작성자가 답변 채택 가능
- 채택 시 질문 `is_solved` 표시

#### 태그 시스템
- 질문 작성 / 수정 시 태그 등록 (쉼표 구분)
- 태그별 질문 필터링 (`?tag=tagname`)
- 인기 태그 목록 사이드바 표시

#### 포인트 시스템
| 이벤트 | 포인트 |
|--------|--------|
| 답변 작성 | +5 |
| 베스트 답변 채택 | +15 |

#### 사용자 프로필
- 내가 작성한 질문 목록 (최근 10개)
- 내가 작성한 답변 목록 (최근 10개)

---

## 보안

| 항목 | 구현 방법 |
|------|-----------|
| SQL Injection 방지 | PDO Prepared Statements + 바인딩 파라미터 |
| XSS 방지 | 출력 시 `htmlspecialchars()` 적용 |
| CSRF 방지 | 폼 토큰 생성 및 검증 (`verifyCsrfToken()`) |
| 비밀번호 보안 | `password_hash()` bcrypt |
| 세션 보안 | `httponly=true`, `samesite=Lax` |
| 권한 체크 | `requireLogin()`, 작성자 본인 검증 |

---

## DB 설계

```
users         회원 정보 (username, email, password, points)
questions     질문 (title, body, view_count, vote_count, is_solved)
answers       답변 (body, vote_count, is_accepted)
votes         투표 내역 (target_type: question|answer, value: 1|-1)
tags          태그 (name)
question_tags 질문-태그 다대다 관계
notifications 알림 (message, is_read)
```

---

## 라우트

| Method | Path | 기능 |
|--------|------|------|
| GET | `/` | 질문 목록 |
| GET/POST | `/login` | 로그인 |
| GET/POST | `/register` | 회원가입 |
| ANY | `/logout` | 로그아웃 |
| GET/POST | `/questions/create` | 질문 작성 |
| GET | `/questions/{id}` | 질문 상세 |
| GET/POST | `/questions/{id}/edit` | 질문 수정 |
| POST | `/questions/{id}/delete` | 질문 삭제 |
| POST | `/questions/{id}/answers` | 답변 작성 |
| GET/POST | `/answers/{id}/edit` | 답변 수정 |
| POST | `/answers/{id}/delete` | 답변 삭제 |
| POST | `/answers/{id}/accept` | 베스트 답변 채택 |
| POST | `/vote/{type}/{id}` | 투표 |
| GET | `/profile` | 내 프로필 |

---

## 개발 단계

| Phase | 내용 |
|-------|------|
| Phase 1 | 개발 환경 설정 + 회원 기능 (가입/로그인/로그아웃/세션) |
| Phase 2 | 질문/답변 CRUD + 페이지네이션 |
| Phase 3 | 투표 시스템 + 베스트 답변 채택 + 태그 + 포인트 |
| Phase 4 | 사용자 프로필 |

---

## 학습 포인트

- **PHP 세션/쿠키 관리** - `session_start()`, `$_SESSION`, 로그인 상태 유지
- **MySQL PDO** - `prepare()`, `execute()`, `fetch()`, 싱글턴 패턴
- **SQL Injection 방지** - Prepared Statements 바인딩 파라미터
- **XSS 방지** - 출력 시 `htmlspecialchars()` 적용
- **Apache URL 라우팅** - `.htaccess` + `mod_rewrite` 클린 URL
- **비밀번호 보안** - `password_hash()` / `password_verify()` bcrypt
- **CSRF 방지** - 폼 토큰 삽입 및 검증
