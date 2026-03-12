# LAMP Q&A 커뮤니티 - 현재 완성 현황

> 기준일: 2026-03-12

---

## 완성된 기능 목록

### P1 - 핵심 기능 (전부 완료)

#### 회원 관리
| 기능 | 메서드 | 완료 |
|------|--------|------|
| 로그인 폼 | `AuthController::showLogin()` | ✅ |
| 로그인 처리 | `AuthController::login()` | ✅ |
| 회원가입 폼 | `AuthController::showRegister()` | ✅ |
| 회원가입 처리 | `AuthController::register()` | ✅ |
| 로그아웃 | `AuthController::logout()` | ✅ |

#### 질문 CRUD
| 기능 | 메서드 | 완료 |
|------|--------|------|
| 질문 목록 (페이지네이션) | `QuestionController::index()` | ✅ |
| 질문 상세 조회 + 조회수 증가 | `QuestionController::show()` | ✅ |
| 질문 작성 폼 | `QuestionController::create()` | ✅ |
| 질문 저장 | `QuestionController::store()` | ✅ |
| 질문 수정 폼 | `QuestionController::edit()` | ✅ |
| 질문 수정 처리 | `QuestionController::update()` | ✅ |
| 질문 삭제 | `QuestionController::delete()` | ✅ |

#### 답변 CRUD
| 기능 | 메서드 | 완료 |
|------|--------|------|
| 답변 작성 | `AnswerController::store()` | ✅ |
| 답변 수정 폼 | `AnswerController::edit()` | ✅ |
| 답변 수정 처리 | `AnswerController::update()` | ✅ |
| 답변 삭제 | `AnswerController::delete()` | ✅ |

---

### P2 - 부가 기능 (전부 완료)

#### 투표 시스템
| 기능 | 메서드 | 완료 |
|------|--------|------|
| 질문/답변 추천·비추천 | `VoteController::vote()` | ✅ |
| 자기 글 투표 방지 | VoteController 내 user_id 검증 | ✅ |
| 중복 투표 방지 | votes 테이블 UNIQUE KEY | ✅ |

#### 베스트 답변 채택
| 기능 | 메서드 | 완료 |
|------|--------|------|
| 답변 채택 (질문 작성자만) | `AnswerController::accept()` | ✅ |
| 질문 해결 표시 | `Question::markSolved()` | ✅ |

#### 태그 시스템
| 기능 | 메서드 | 완료 |
|------|--------|------|
| 질문 작성 시 태그 등록 | `Tag::attachToQuestion()` | ✅ |
| 질문 수정 시 태그 갱신 | `Tag::attachToQuestion()` | ✅ |
| 태그별 질문 필터링 | `QuestionController::index()` + `?tag=` | ✅ |
| 인기 태그 목록 표시 | `Tag::getAll()` | ✅ |

#### 포인트 시스템
| 이벤트 | 포인트 | 완료 |
|--------|--------|------|
| 답변 작성 | +5 | ✅ |
| 베스트 답변 채택 | +15 | ✅ |

#### 사용자 프로필
| 기능 | 메서드 | 완료 |
|------|--------|------|
| 내 질문 목록 | `ProfileController::show()` | ✅ |
| 내 답변 목록 | `ProfileController::show()` | ✅ |

---

### 보안 구현 현황 (완료)

| 항목 | 구현 방법 | 완료 |
|------|-----------|------|
| SQL Injection 방지 | PDO Prepared Statements | ✅ |
| XSS 방지 | `htmlspecialchars()` | ✅ |
| CSRF 방지 | 폼 토큰 생성/검증 (`verifyCsrfToken()`) | ✅ |
| 비밀번호 암호화 | `password_hash()` bcrypt | ✅ |
| 세션 보안 | `httponly=true`, `samesite=Lax` | ✅ |
| 권한 체크 | `requireLogin()`, 작성자 일치 검증 | ✅ |

---

## 구현된 라우트 목록

| Method | Path | 기능 |
|--------|------|------|
| GET | `/` | 질문 목록 (페이지네이션, 태그 필터) |
| GET | `/login` | 로그인 폼 |
| POST | `/login` | 로그인 처리 |
| GET | `/register` | 회원가입 폼 |
| POST | `/register` | 회원가입 처리 |
| ANY | `/logout` | 로그아웃 |
| GET | `/questions/create` | 질문 작성 폼 |
| POST | `/questions/create` | 질문 저장 |
| GET | `/questions/{id}` | 질문 상세 |
| GET | `/questions/{id}/edit` | 질문 수정 폼 |
| POST | `/questions/{id}/edit` | 질문 수정 처리 |
| POST | `/questions/{id}/delete` | 질문 삭제 |
| POST | `/questions/{id}/answers` | 답변 작성 |
| GET | `/answers/{id}/edit` | 답변 수정 폼 |
| POST | `/answers/{id}/edit` | 답변 수정 처리 |
| POST | `/answers/{id}/delete` | 답변 삭제 |
| POST | `/answers/{id}/accept` | 베스트 답변 채택 |
| POST | `/vote/{type}/{id}` | 투표 (question\|answer) |
| GET | `/profile` | 내 프로필 |

