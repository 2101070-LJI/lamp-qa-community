<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h(APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
</head>
<body>
<nav class="navbar">
    <div class="container">
        <a href="<?= BASE_URL ?>/" class="brand"><?= h(APP_NAME) ?></a>
        <div class="nav-links">
            <?php if (isLoggedIn()): ?>
                <span>안녕하세요, <strong><?= h($_SESSION['username']) ?></strong>님</span>
                <a href="<?= BASE_URL ?>/questions/create">질문하기</a>
                <a href="<?= BASE_URL ?>/profile">프로필</a>
                <a href="<?= BASE_URL ?>/logout">로그아웃</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/login">로그인</a>
                <a href="<?= BASE_URL ?>/register">회원가입</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<main class="container">
<?php
$flash = flashMessage();
if ($flash): ?>
    <div class="alert alert-success"><?= h($flash) ?></div>
<?php endif; ?>
