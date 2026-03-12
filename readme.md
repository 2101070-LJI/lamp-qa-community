# Build Errors

## 2026-03-12

### [1] PHP Fatal Error - QuestionController.php 미존재
```
Warning: require_once(/home/lee/project1/controllers/QuestionController.php): Failed to open stream: No such file or directory in /home/lee/project1/index.php on line 18
Fatal error: Uncaught Error: Failed opening required '/home/lee/project1/controllers/QuestionController.php' (include_path='.:/usr/share/php') in /home/lee/project1/index.php:18
```

### [2] MySQL root 접근 거부
```
ERROR 1698 (28000): Access denied for user 'root'@'localhost'
```

### [3] Apache 심볼릭 링크 403 Forbidden
```
[core:error] [pid 10837] [client ::1:42270] AH00037: Symbolic link not allowed or link target not accessible: /var/www/html/project1
[core:error] [pid 10838] [client ::1:33674] AH00037: Symbolic link not allowed or link target not accessible: /var/www/html/project1
```

### [4] npm ENOENT - package.json 미존재
```
npm ERR! code ENOENT
npm ERR! syscall open
npm ERR! path /home/lee/project1/package.json
npm ERR! errno -2
npm ERR! enoent ENOENT: no such file or directory, open '/home/lee/project1/package.json'
```

### [5] Playwright run.js 모듈 경로 오류
```
Error: Cannot find module '/run.js'
    at Module._resolveFilename (node:internal/modules/cjs/loader:1134:15)
    at Module._load (node:internal/modules/cjs/loader:975:27)
    at Function.executeUserEntryPoint [as runMain] (node:internal/modules/run_main:128:12)
    at node:internal/main/run_main_module:28:49 {
  code: 'MODULE_NOT_FOUND',
  requireStack: []
}
```

### [6] Playwright 테스트 실패 - HTML5 유효성 검사로 인한 타임아웃
```
❌ 빈 폼 제출 시 에러 표시: page.waitForSelector: Timeout 30000ms exceeded.
Call log:
  - waiting for locator('.alert-error') to be visible
```
