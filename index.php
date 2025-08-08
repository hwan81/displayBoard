<?php
// 디버깅 정보 추가
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// 한국 시간대로 설정
date_default_timezone_set('Asia/Seoul');

// 디버깅을 위한 로그
error_log("index.php 접속 - " . date('Y-m-d H:i:s'));
error_log("세션 상태: " . (isset($_SESSION['user_id']) ? "로그인됨 (ID: " . $_SESSION['user_id'] . ")" : "로그인되지 않음"));

// 세션 정보가 없으면 login.html로 리디렉션
if (!isset($_SESSION['user_id'])) {
    error_log("로그인되지 않음 - login.html로 리디렉션");
    header("Location: login.html");
    exit;
}

// 세션 정보가 있으면 admin.php로 리디렉션
error_log("로그인됨 - admin.php로 리디렉션");
header("Location: admin.php");
exit;
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>디지털 사이니지 시스템</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        @import url('https://cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/static/pretendard.css');
        
        body {
            font-family: 'Pretendard', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f4f6;
            border-top: 4px solid #3b82f6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 text-center fade-in">
        <!-- 로고/아이콘 영역 -->
        <div class="mb-6">
            <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">디지털 사이니지 시스템</h1>
            <p class="text-gray-600">Digital Signage Management System</p>
        </div>
        
        <!-- 로딩 영역 -->
        <div class="mb-6">
            <div class="loading-spinner mx-auto mb-4"></div>
            <p class="text-gray-600 text-sm">세션 확인 중...</p>
        </div>
        
        <!-- 시스템 정보 -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <div class="text-gray-500">버전</div>
                    <div class="font-semibold text-gray-800">v1.0.0</div>
                </div>
                <div>
                    <div class="text-gray-500">상태</div>
                    <div class="font-semibold text-green-600 flex items-center justify-center">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                        활성
                    </div>
                </div>
            </div>
        </div>
        
        <!-- 수동 이동 링크 -->
        <div class="text-center">
            <p class="text-gray-500 text-sm mb-2">자동 이동이 되지 않나요?</p>
            <div class="space-y-2">
                <a href="admin.php" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium w-full justify-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    관리자 페이지로 이동
                </a>
                <a href="login.html" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors text-sm font-medium w-full justify-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m0 0v3a5 5 0 0 0 5 5l4 0"/>
                    </svg>
                    로그인 페이지로 이동
                </a>
            </div>
        </div>
    </div>

    <script>
        // 이 스크립트는 PHP 리디렉션이 실패할 경우의 대비책입니다
        setTimeout(() => {
            // PHP에서 이미 리디렉션했다면 이 코드는 실행되지 않습니다
            console.log('PHP 리디렉션이 실행되지 않은 경우의 대비책');
        }, 3000);
    </script>
</body>
</html>
