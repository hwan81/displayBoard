<?php
require_once 'db_config.php';

header('Content-Type: application/json');

$area_code = $_GET['area_code'] ?? '';
$school_code = $_GET['school_code'] ?? '';

if (empty($area_code) || empty($school_code)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => '지역코드와 학교코드가 필요합니다.']);
    exit;
}

// 한국 시간대로 설정
date_default_timezone_set('Asia/Seoul');

// 오늘 날짜 (YYYYMMDD 형식)
$meal_date = date('Ymd');

// API URL 생성
$url = "https://open.neis.go.kr/hub/mealServiceDietInfo";
$params = [
    'KEY' => NEIS_API_KEY,
    'Type' => 'json',
    'ATPT_OFCDC_SC_CODE' => $area_code,
    'SD_SCHUL_CODE' => $school_code,
    'MLSV_YMD' => $meal_date
];
$request_url = $url . '?' . http_build_query($params);

// cURL 초기화
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $request_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // SSL 검증 비활성화 (로컬 테스트용)

// API 실행
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// API 요청 URL 및 응답 내용 콘솔에 출력 (디버깅용)
error_log("NEIS API Request URL: " . $request_url);
error_log("NEIS API Response: " . $response);

if ($http_code == 200) {
    $data = json_decode($response, true);

    // API 응답에서 INFO-200 오류 코드 확인
    if (isset($data['RESULT']['CODE']) && $data['RESULT']['CODE'] === 'INFO-200') {
        echo json_encode(['status' => 'success', 'menu' => '급식정보가 없습니다.', 'date' => $meal_date]);
        exit;
    }

    if (isset($data['mealServiceDietInfo'][1]['row'])) {
        $meal_rows = $data['mealServiceDietInfo'][1]['row'];
        $meal_menu = [];
        foreach ($meal_rows as $row) {
            // <br/> 태그를 기준으로 각 음식 항목 분리
            $items = explode('<br/>', $row['DDISH_NM']);
            $cleaned_items = [];
            foreach ($items as $item) {
                // 숫자, 괄호 및 괄호 안의 내용 제거
                $cleaned_item = preg_replace('/[0-9\.]+|\s*\([^)]*\)/', '', $item);
                $cleaned_item = trim($cleaned_item);
                if (!empty($cleaned_item)) {
                    $cleaned_items[] = $cleaned_item;
                }
            }
            $meal_menu[] = implode(', ', $cleaned_items);
        }
        echo json_encode(['status' => 'success', 'menu' => implode(', ', $meal_menu), 'date' => $meal_date]);
    } else {
        // API에서 데이터가 없는 경우
        echo json_encode(['status' => 'success', 'menu' => '급식정보가 없습니다.', 'date' => $meal_date]);
    }
} else {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'API 호출에 실패했습니다.']);
}
?>
