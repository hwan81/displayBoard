<?php
session_start();

// 로그인되어 있지 않으면 로그인 페이지로 리디렉션
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}
$current_user_id = $_SESSION['user_id'];
$current_username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>콘텐츠 관리 시스템</title>
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ko.js"></script>
    <script src="./js/tinymce/tinymce.min.js" referrerpolicy="origin"></script>    

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <style>
        /* Custom styles for better responsive behavior */
        html, body {
            height: 100%;
            margin: 0;
        }
        @media (max-width: 768px) {
            html, body {
                height: auto; /* Allow body to grow in mobile view */
            }
        }
    </style>
</head>
<body class="bg-gray-100">

    <div class="flex flex-col md:flex-row h-full">
        <!-- Left Sidebar: Calendar -->
        <aside class="w-full md:w-96 bg-white shadow-lg p-4 md:p-6 flex flex-col flex-shrink-0">
            <header class="mb-4">
                <h1 class="text-xl md:text-2xl font-bold text-gray-900">콘텐츠 관리</h1>
                <p class="text-sm text-gray-500">날짜를 선택하여 콘텐츠를 관리하세요.</p>
            </header>
            
            <div id="calendar-container" class="mb-4">
                <input type="text" id="calendar" class="hidden items-center">
            </div>

            <!-- API Settings Form -->
            <div class="mt-4 pt-4 border-t">
                <h3 class="text-lg font-semibold mb-3">나이스 급식 API 설정</h3>
                <form id="apiSettingsForm" class="space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="apiAreaCode" class="block text-sm font-medium text-gray-600">지역코드</label>
                            <input type="text" id="apiAreaCode" name="api_area_code" maxlength="3" class="w-full p-2 mt-1 border rounded-md text-sm" placeholder="예: J10">
                        </div>
                        <div>
                            <label for="apiSchoolCode" class="block text-sm font-medium text-gray-600">행정표준코드</label>
                            <input type="text" id="apiSchoolCode" name="api_school_code" maxlength="7" class="w-full p-2 mt-1 border rounded-md text-sm" placeholder="예: 7530229">
                        </div>
                    </div>
                    <div class="flex items-center mt-2">
                        <input type="checkbox" id="hideMealInfo" name="hide_meal_info" class="mr-2">
                        <label for="hideMealInfo" class="text-sm text-gray-700 select-none">급식정보 표시하지 않기</label>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="bg-green-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-green-700 text-sm">
                            API 정보 저장
                        </button>
                    </div>
                </form>
                <p class="text-xs text-gray-500 mt-2">
                    <a href="https://open.neis.go.kr/portal/data/service/selectServicePage.do?page=1&rows=10&sortColumn=&sortDirection=&infId=OPEN17320190722180924242823&infSeq=1" target="_blank" class="text-blue-600 hover:underline">학교정보 확인하기</a>
                </p>
            </div>

            <div class="mt-auto pt-4 border-t">
                 <div class="mb-4">
                    <h3 class="text-md font-semibold">선택된 날짜</h3>
                    <p id="selected-date" class="text-lg text-blue-600 font-bold mt-1">날짜를 선택하세요</p>
                </div>
                <button id="logout-btn" class="w-full bg-red-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-red-700 transition-colors">
                    로그아웃
                </button>
            </div>
        </aside>

        <!-- Right Main Content -->
        <main class="flex-1 p-3 md:p-4 overflow-y-auto">
            <header class="flex flex-col md:flex-row justify-between md:items-center mb-6 gap-4">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800">
                    <span id="content-date-display" class="text-blue-600">날짜</span> 콘텐츠
                </h2>
                <div class="flex flex-wrap gap-2">
                    <button id="new-slide-btn" class="flex items-center justify-center flex-grow md:flex-grow-0 bg-green-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-green-700 transition-colors text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        새 슬라이드
                    </button>
                    <button id="show-today-slides-btn" class="flex items-center justify-center flex-grow md:flex-grow-0 bg-blue-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-blue-700 transition-colors text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                        </svg>
                        오늘의 슬라이드 (<span id="slide-count">0</span>)
                    </button>
                    <button id="show-all-slides-btn" class="flex items-center justify-center flex-grow md:flex-grow-0 bg-indigo-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-indigo-700 transition-colors text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                        </svg>
                        모든 슬라이드
                    </button>
                    <a href="#" id="display-link" target="_blank" class="flex items-center justify-center flex-grow md:flex-grow-0 text-center bg-purple-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-purple-700 transition-colors text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.27 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                        </svg>
                        디스플레이 보기
                    </a>
                </div>
            </header>

            <!-- Section for adding/editing content -->
            <section id="editor-section" class="bg-white p-4 md:p-6 rounded-lg shadow-md mb-8">
                <h3 id="editor-title" class="text-xl font-semibold mb-4">새 슬라이드 추가</h3>
                <form id="slide-form">
                    <div class="space-y-4">
                        <div>
                            <label for="slide-content" class="block text-sm font-medium text-gray-700 mb-1">슬라이드 내용</label>
                            <textarea id="slide-content"></textarea>
                        </div>
                        <div class="flex flex-col md:flex-row md:items-end md:space-x-4 space-y-4 md:space-y-0">
                            <div class="flex-shrink-0">
                                <label class="block text-sm font-medium text-gray-700 mb-1">기간 설정</label>
                                <div class="flex space-x-4 p-2 border rounded-md bg-gray-50 h-[42px] items-center">
                                    <label class="flex items-center">
                                        <input type="radio" name="date-range" value="today" class="form-radio">
                                        <span class="ml-2 text-sm">오늘하루</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="date-range" value="week" class="form-radio">
                                        <span class="ml-2 text-sm">오늘부터 일주일</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="date-range" value="custom" class="form-radio">
                                        <span class="ml-2 text-sm">기간설정</span>
                                    </label>
                                </div>
                            </div>
                            <div class="flex-1">
                                <label for="start-date" class="block text-sm font-medium text-gray-700 mb-1">게시 시작일</label>
                                <input type="date" id="start-date" class="w-full p-2 border rounded-md">
                            </div>
                            <div class="flex-1">
                                <label for="end-date" class="block text-sm font-medium text-gray-700 mb-1">게시 종료일</label>
                                <input type="date" id="end-date" class="w-full p-2 border rounded-md">
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
                            <div>
                                <label for="slide-duration" class="block text-sm font-medium text-gray-700 mb-1">표시 시간 (초)</label>
                                <input type="number" id="slide-duration" class="w-full md:w-40 p-2 border rounded-md" value="10">
                            </div>
                            <div class="flex justify-end space-x-2 mt-2 md:mt-0">
                                <button id="cancel-edit-btn" type="button" class="hidden bg-gray-500 text-white font-bold py-2 px-4 rounded-md hover:bg-gray-600 text-sm">
                                    취소
                                </button>
                                <button id="add-slide-btn" type="submit" class="bg-blue-600 text-white font-bold py-2 px-4 rounded-md hover:bg-blue-700 text-sm">
                                    저장하기
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </section>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let selectedDate = null;
            const currentUserId = <?php echo $current_user_id; ?>;
            const currentUsername = "<?php echo $current_username; ?>";
            let editMode = {
                active: false,
                slideId: null
            };

            // --- DOM Elements ---
            const editorSection = document.getElementById('editor-section');
            const editorTitle = document.getElementById('editor-title');
            const slideForm = document.getElementById('slide-form');
            const displayLink = document.getElementById('display-link');
            const apiSettingsForm = document.getElementById('apiSettingsForm');
            const addSlideBtn = document.getElementById('add-slide-btn');
            const cancelEditBtn = document.getElementById('cancel-edit-btn');
            const logoutBtn = document.getElementById('logout-btn');
            const newSlideBtn = document.getElementById('new-slide-btn');
            const showTodaySlidesBtn = document.getElementById('show-today-slides-btn');
            const showAllSlidesBtn = document.getElementById('show-all-slides-btn');
            const slidesModal = document.getElementById('slides-modal');
            const closeSlidesModalBtn = document.getElementById('close-slides-modal-btn');
            const modalSlideList = document.getElementById('modal-slide-list');
            const slideCountSpan = document.getElementById('slide-count');
            const dateRangeRadios = document.querySelectorAll('input[name="date-range"]');

            // --- Helper Functions ---
            const updateSlideCount = async (date) => {
                try {
                    const url = date ? `php/get_slides.php?date=${date}` : 'php/get_slides.php';
                    const response = await fetch(url);
                    const result = await response.json();
                    if (result.status === 'success') {
                        slideCountSpan.textContent = result.data.length;
                    } else {
                        slideCountSpan.textContent = '0';
                    }
                } catch (error) {
                    console.error('Failed to fetch slide count:', error);
                    slideCountSpan.textContent = '0';
                }
            };

            const showToast = (message, type = 'success') => {
                const backgroundColor = type === 'success' ? "linear-gradient(to right, #00b09b, #96c93d)" : "linear-gradient(to right, #ff5f6d, #ffc371)";
                Toastify({
                    text: message,
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "center",
                    stopOnFocus: true,
                    style: {
                        background: backgroundColor,
                    },
                }).showToast();
            };

            const formatDate = (date, format = 'Y-m-d') => {
                const d = new Date(date);
                const year = d.getFullYear();
                const month = String(d.getMonth() + 1).padStart(2, '0');
                const day = String(d.getDate()).padStart(2, '0');
                if (format === 'Y-m-d') return `${year}-${month}-${day}`;
                return `${year}년 ${month}월 ${day}일`;
            };

            const setDateRange = (rangeType) => {
                const startDateInput = document.getElementById('start-date');
                const endDateInput = document.getElementById('end-date');
                const today = new Date();

                if (rangeType === 'today') {
                    startDateInput.value = formatDate(today);
                    endDateInput.value = formatDate(today);
                } else if (rangeType === 'week') {
                    const oneWeekLater = new Date();
                    oneWeekLater.setDate(today.getDate() + 6); // Today + 6 days = 7 days total
                    startDateInput.value = formatDate(today);
                    endDateInput.value = formatDate(oneWeekLater);
                }
            };

            const setEditMode = (active, slideId = null) => {
                editMode.active = active;
                editMode.slideId = slideId;

                if (active) {
                    editorTitle.textContent = '슬라이드 수정';
                    addSlideBtn.textContent = '수정하기';
                    addSlideBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                    addSlideBtn.classList.add('bg-yellow-500', 'hover:bg-yellow-600');
                    cancelEditBtn.classList.remove('hidden');
                    document.querySelector('input[name="date-range"][value="custom"]').checked = true;
                } else {
                    editorTitle.textContent = '새 슬라이드 추가';
                    addSlideBtn.textContent = '저장하기';
                    addSlideBtn.classList.remove('bg-yellow-500', 'hover:bg-yellow-600');
                    addSlideBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');
                    cancelEditBtn.classList.add('hidden');
                    tinymce.get('slide-content').setContent('');
                    document.getElementById('slide-duration').value = 10;
                    document.querySelector('input[name="date-range"][value="week"]').checked = true;
                    setDateRange('week');
                }
            };

            // --- API & Data Fetching ---
            const fetchApiSettings = async () => {
                try {
                    const response = await fetch('php/api_settings.php');
                    const result = await response.json();
                    if (result.status === 'success' && result.data) {
                        document.getElementById('apiAreaCode').value = result.data.api_area_code || '';
                        document.getElementById('apiSchoolCode').value = result.data.api_school_code || '';
                        document.getElementById('hideMealInfo').checked = result.data.hide_meal_info == 1;
                    }
                } catch (error) {
                    console.error('API 설정을 불러오지 못했습니다.', error);
                }
            };

            const fetchSlides = async (date, targetList) => {
                if (!targetList) return;
                targetList.innerHTML = '<li>로딩 중...</li>';
                try {
                    const url = date ? `php/get_slides.php?date=${date}` : 'php/get_slides.php';
                    const response = await fetch(url);
                    const result = await response.json();
                    if (result.status === 'success') {
                        renderSlides(result.data, targetList);
                    } else {
                        targetList.innerHTML = `<li>${result.message}</li>`;
                    }
                } catch (error) {
                    targetList.innerHTML = '<li>슬라이드를 불러오는 중 오류가 발생했습니다.</li>';
                }
            };
            
            const fetchSingleSlide = async (slideId) => {
                try {
                    const response = await fetch(`php/get_slide.php?slide_id=${slideId}`);
                    const result = await response.json();
                    if (result.status === 'success') {
                        tinymce.get('slide-content').setContent(result.data.content);
                        document.getElementById('slide-duration').value = result.data.duration;
                        document.getElementById('start-date').value = result.data.start_date;
                        document.getElementById('end-date').value = result.data.end_date;
                        setEditMode(true, slideId);
                        editorSection.scrollIntoView({ behavior: 'smooth' });
                    } else {
                        showToast(result.message, 'error');
                    }
                } catch (error) {
                    showToast('슬라이드 정보를 가져오지 못했습니다.', 'error');
                }
            };

            // --- Rendering ---
            const renderSlides = (slides, targetList) => {
                targetList.innerHTML = '';
                if (slides.length === 0) {
                    targetList.innerHTML = '<li class="text-center text-gray-500 p-4">해당 날짜에 등록된 슬라이드가 없습니다.</li>';
                    return;
                }
                slides.forEach((slide, index) => {
                    const li = document.createElement('li');
                    li.className = 'flex flex-col md:flex-row justify-between items-start md:items-center p-3 bg-gray-50 rounded-md border gap-3';
                    li.innerHTML = `
                        <div class="flex-1 w-full overflow-hidden">
                            <p class="font-medium text-gray-800 text-sm">슬라이드 ${index + 1} <span class="text-xs text-gray-500">(${slide.start_date} ~ ${slide.end_date})</span></p>
                            <div class="text-sm text-gray-600 mt-2 p-2 bg-white border rounded-md truncate">${slide.content}</div>
                        </div>
                        <div class="flex items-center justify-between w-full md:w-auto">
                            <p class="font-semibold text-sm">${slide.duration}초</p>
                            <div class="ml-4 space-x-2">
                                <button class="text-sm text-blue-500 hover:underline btn-edit" data-id="${slide.id}">수정</button>
                                <button class="text-sm text-red-500 hover:underline btn-delete" data-id="${slide.id}">삭제</button>
                            </div>
                        </div>
                    `;
                    targetList.appendChild(li);
                });
            };

            // --- Form Submissions ---
            const handleSlideFormSubmit = async (e) => {
                e.preventDefault();
                const content = tinymce.get('slide-content').getContent();
                const duration = document.getElementById('slide-duration').value;
                const startDate = document.getElementById('start-date').value;
                const endDate = document.getElementById('end-date').value;

                if (!content || !startDate || !endDate) {
                    showToast('슬라이드 내용, 시작일, 종료일을 모두 입력해주세요.', 'error');
                    return;
                }

                const formData = new FormData();
                formData.append('content', content);
                formData.append('duration', duration);
                formData.append('start_date', startDate);
                formData.append('end_date', endDate);

                let url = editMode.active ? 'php/update_slide.php' : 'php/add_slide.php';
                if(editMode.active) formData.append('slide_id', editMode.slideId);

                try {
                    const response = await fetch(url, { method: 'POST', body: formData });
                    const result = await response.json();
                    showToast(result.message, response.ok ? 'success' : 'error');
                    if (response.ok) {
                        setEditMode(false);
                        updateSlideCount(selectedDate);
                    }
                } catch (error) {
                    showToast('작업 중 오류가 발생했습니다.', 'error');
                }
            };

            const deleteSlide = async (slideId) => {
                if (!confirm('정말로 이 슬라이드를 삭제하시겠습니까?')) return;
                const formData = new FormData();
                formData.append('slide_id', slideId);
                try {
                    const response = await fetch('php/delete_slide.php', { method: 'POST', body: formData });
                    const result = await response.json();
                    showToast(result.message, response.ok ? 'success' : 'error');
                    if (response.ok) {
                        if (editMode.active && editMode.slideId == slideId) {
                            setEditMode(false);
                        }
                        updateSlideCount(selectedDate);
                        // If modal is open, refresh it
                        if (!slidesModal.classList.contains('hidden')) {
                            fetchSlides(selectedDate, modalSlideList);
                        }
                    }
                } catch (error) {
                    showToast('슬라이드 삭제 중 오류 발생', 'error');
                }
            };

            // --- Initializations ---
            const calendar = flatpickr("#calendar", {
                inline: true,
                dateFormat: "Y-m-d",
                defaultDate: "today",                
                // locale: "ko_KR", // Set locale to Korean
                onChange: (selectedDates, dateStr) => {
                    selectedDate = dateStr;
                    const formattedDateStr = formatDate(dateStr, 'Y년 m월 d일');
                    document.getElementById('selected-date').textContent = formattedDateStr;
                    document.getElementById('content-date-display').textContent = formattedDateStr;
                    setEditMode(false);
                    updateSlideCount(selectedDate);
                }
            });

            tinymce.init({
                selector: 'textarea#slide-content',
                // language: 'ko',                
                 license_key: 'gpl',
                 extended_valid_elements: 'script[src|type|language]',
                 valid_children: "+body[script]",
                plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                toolbar: 'undo redo | fontfamily fontsize forecolor backcolor | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
            
                font_size_formats: "12pt 14pt 18pt 24pt 36pt 48pt 60pt 78pt 100pt",
                color_map: [
                    '000000', 'Black',
                    'FFFFFF', 'White',
                    'FF0000', 'Red',
                    '00FF00', 'Green',
                    '0000FF', 'Blue',
                    'FFFF00', 'Yellow',
                    'FF00FF', 'Magenta',
                    '00FFFF', 'Cyan',
                    '808080', 'Gray',
                    'FFA500', 'Orange'
                ],

                height: 600,
                menubar: false,
                placeholder: '여기에 슬라이드 내용을 입력하세요...'
            });

            // --- Event Listeners ---
            dateRangeRadios.forEach(radio => {
                radio.addEventListener('change', (e) => {
                    setDateRange(e.target.value);
                });
            });

            logoutBtn.addEventListener('click', async () => {
                if (confirm('로그아웃 하시겠습니까?')) {
                    try {
                        const response = await fetch('php/logout.php', { method: 'POST' });
                        const result = await response.json();
                        if (result.status === 'success') {
                            window.location.href = 'login.html';
                        } else {
                            showToast('로그아웃에 실패했습니다.', 'error');
                        }
                    } catch (error) {
                        showToast('오류가 발생했습니다.', 'error');
                    }
                }
            });

            slideForm.addEventListener('submit', handleSlideFormSubmit);
            cancelEditBtn.addEventListener('click', () => setEditMode(false));
            newSlideBtn.addEventListener('click', () => {
                setEditMode(false);
                editorSection.scrollIntoView({ behavior: 'smooth' });
            });

            showTodaySlidesBtn.addEventListener('click', () => {
                slidesModal.classList.remove('hidden');
                document.querySelector('#slides-modal h2').textContent = '오늘의 슬라이드 목록';
                fetchSlides(selectedDate, modalSlideList);
            });

            showAllSlidesBtn.addEventListener('click', () => {
                slidesModal.classList.remove('hidden');
                document.querySelector('#slides-modal h2').textContent = '모든 슬라이드 목록';
                fetchSlides(null, modalSlideList);
            });

            closeSlidesModalBtn.addEventListener('click', () => {
                slidesModal.classList.add('hidden');
            });

            slidesModal.addEventListener('click', (e) => {
                const target = e.target.closest('.btn-delete, .btn-edit');
                if (!target) return;

                const slideId = target.dataset.id;
                if (target.classList.contains('btn-delete')) {
                    deleteSlide(slideId);
                }
                if (target.classList.contains('btn-edit')) {
                    fetchSingleSlide(slideId);
                    slidesModal.classList.add('hidden'); // Close modal after editing
                }
            });
            apiSettingsForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = new FormData(apiSettingsForm);
                try {
                    const response = await fetch('php/api_settings.php', { method: 'POST', body: formData });
                    const result = await response.json();
                    showToast(result.message, response.ok ? 'success' : 'error');
                } catch (error) {
                    showToast('API 정보 저장 중 오류가 발생했습니다.', 'error');
                }
            });

            // --- Initial Load ---
            const initialLoad = async () => {
                displayLink.href = `display.html?username=${currentUsername}`;
                await fetchApiSettings();
                // Manually trigger the calendar's onChange event to load the initial slide count for today.
                const initialDate = calendar.selectedDates[0] || new Date();
                const dateStr = formatDate(initialDate);
                calendar.config.onChange[0]([initialDate], dateStr, calendar);
                
                // Set initial date range
                document.querySelector('input[name="date-range"][value="week"]').checked = true;
                setDateRange('week');
            };

            initialLoad();

        });
    </script>

    <!-- Slides Modal -->
    <div id="slides-modal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-4 rounded-lg shadow-xl w-11/12 md:w-4/5 lg:w-3/4 h-5/6 flex flex-col">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">등록된 슬라이드 목록</h2>
                <button id="close-slides-modal-btn" class="text-gray-500 hover:text-gray-700 text-3xl leading-none">&times;</button>
            </div>
            <div id="modal-slide-list" class="flex-1 overflow-y-auto space-y-3 p-2 border rounded-md bg-gray-50">
                <!-- Slides will be loaded here -->
            </div>
        </div>
    </div>
</body>
</html>
