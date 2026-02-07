<?php
// Handle AJAX POST submissions to save feedback
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    
    // Cek jika ini request AJAX
    $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
              strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    
    if (!$isAjax) {
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
        exit;
    }
    
    try {
        $pdo = include __DIR__ . '/db.php';

        // Collect input safely
        $nama_lengkap = isset($_POST['nama_lengkap']) ? trim($_POST['nama_lengkap']) : null;
        $acara = isset($_POST['acara']) ? trim($_POST['acara']) : null;
        $manfaat = isset($_POST['manfaat']) ? trim($_POST['manfaat']) : null;
        $pendapat_panitia = isset($_POST['pendapat_panitia']) ? trim($_POST['pendapat_panitia']) : null;
        $kesan = isset($_POST['kesan']) ? trim($_POST['kesan']) : null;
        $kritik = isset($_POST['kritik']) ? trim($_POST['kritik']) : null;
        $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : null;

        // Validasi input wajib
        if (empty($nama_lengkap) || empty($acara) || empty($manfaat) || empty($rating)) {
            throw new Exception('Harap lengkapi semua field wajib');
        }

        $ip = null;
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;

        $sql = "INSERT INTO feedback (nama_lengkap, acara, manfaat, pendapat_panitia, kesan, kritik, ip_address, user_agent, rating) 
                VALUES (:nama_lengkap, :acara, :manfaat, :pendapat_panitia, :kesan, :kritik, :ip_address, :user_agent, :rating)";

        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            ':nama_lengkap' => $nama_lengkap,
            ':acara' => $acara,
            ':manfaat' => $manfaat,
            ':pendapat_panitia' => $pendapat_panitia,
            ':kesan' => $kesan,
            ':kritik' => $kritik,
            ':ip_address' => $ip,
            ':user_agent' => $user_agent,
            ':rating' => $rating,
        ]);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Feedback berhasil disimpan!']);
        } else {
            throw new Exception('Gagal menyimpan ke database');
        }
        exit;
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Peserta - STEP UP</title>
    <link rel="icon" type="image/png" href="https://imgur.com/oQNRfoA.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Nunito', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f0fdf4 0%, #f0f9ff 100%);
            min-height: 100vh;
        }
        
        .form-section {
            animation: fadeIn 0.8s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .card-glow {
            box-shadow: 0 10px 30px rgba(72, 187, 120, 0.15);
        }
        
        .card-glow:hover {
            box-shadow: 0 15px 40px rgba(72, 187, 120, 0.2);
            transform: translateY(-5px);
            transition: all 0.3s ease;
        }
        
        .form-input:focus {
            box-shadow: 0 0 0 3px rgba(72, 187, 120, 0.2);
        }
        
        .radio-card {
            transition: all 0.2s ease;
            border: 2px solid #e5e7eb;
        }
        
        .radio-card:hover {
            border-color: #48bb78;
            background-color: #f0fff4;
        }
        
        .radio-card.selected {
            border-color: #48bb78;
            background-color: #f0fff4;
            box-shadow: 0 5px 15px rgba(72, 187, 120, 0.1);
        }
        
        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: center;
            gap: 8px;
        }

        .star-rating input {
            display: none;
        }

        .star-rating label {
            font-size: 1.75rem;
            color: #d1d5db;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .star-rating input:checked ~ label,
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: #fbbf24;
        }

        @media (min-width: 640px) {
            .star-rating label {
                font-size: 2.5rem;
            }
        }
        
        textarea::-webkit-scrollbar {
            width: 8px;
        }
        
        textarea::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        textarea::-webkit-scrollbar-thumb {
            background: #48bb78;
            border-radius: 10px;
        }
        
        textarea::-webkit-scrollbar-thumb:hover {
            background: #38a169;
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-submit:hover:not(:disabled) {
            background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(56, 161, 105, 0.3);
        }
        
        .btn-submit:active:not(:disabled) {
            transform: translateY(-1px);
        }
        
        .btn-submit::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(30deg);
            transition: all 0.5s ease;
        }
        
        .btn-submit:hover:not(:disabled)::after {
            transform: rotate(30deg) translate(50%, 50%);
        }
        
        .success-animation {
            animation: successPop 0.6s ease-out;
        }
        
        @keyframes successPop {
            0% { transform: scale(0.8); opacity: 0; }
            70% { transform: scale(1.05); }
            100% { transform: scale(1); opacity: 1; }
        }
        
        .section-title {
            position: relative;
            display: inline-block;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #48bb78, #38a169);
            border-radius: 2px;
        }
        
        .floating-element {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }
        
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(72, 187, 120, 0.4); }
            70% { box-shadow: 0 0 0 15px rgba(72, 187, 120, 0); }
            100% { box-shadow: 0 0 0 0 rgba(72, 187, 120, 0); }
        }
        
        .btn-submit:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }
        
        .btn-submit:disabled:hover {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%) !important;
            transform: none !important;
            box-shadow: none !important;
        }
        
        .btn-submit:disabled::after {
            display: none !important;
        }
        
        .error-message {
            display: none;
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        .error-message.show {
            display: block;
        }

        /* Style khusus untuk input nama */
        #nama_lengkap {
            transition: all 0.3s ease;
        }

        #nama_lengkap:focus {
            border-color: #48bb78;
            box-shadow: 0 0 0 3px rgba(72, 187, 120, 0.2);
        }

        /* Success message */
        #nama-success {
            display: none;
            color: #10b981;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        #nama-success:not(.hidden) {
            display: flex;
        }

        /* Animasi shake */
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        /* Responsive untuk nama input */
        @media (max-width: 640px) {
            #nama_lengkap {
                padding-right: 90px;
            }
            #namaCounter {
                font-size: 0.75rem;
            }
        }
    </style>
</head>
<body class="text-gray-800">
    <!-- Header Navigation -->
    <nav class="fixed w-full z-50 bg-white/95 backdrop-blur-sm shadow-sm transition-all duration-300" id="navbar">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <!-- Logo -->
            <a href="#" class="flex items-center gap-3 text-2xl font-extrabold text-green-600 tracking-wider">
                <div class="w-10 h-10 rounded-full overflow-hidden flex items-center justify-center bg-green-100 p-1">
                    <img 
                        src="https://imgur.com/oQNRfoA.png" 
                        alt="STEP UP Logo"
                        class="w-full h-full object-cover"
                    >
                </div>
                <span>STEP UP</span>
            </a>

            <!-- Menu Desktop -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="index.php" class="bg-green-600 text-white px-6 py-2 rounded-full hover:bg-green-700 transition shadow-md transform hover:scale-105 font-bold flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i>
                    Kembali Ke Home
                </a>
            </div>

            <!-- Tombol Hamburger Mobile -->
            <button id="mobile-menu-btn" class="md:hidden text-green-700 focus:outline-none text-2xl">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>

        <!-- Menu Mobile -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-green-100 absolute w-full shadow-lg">
            <div class="flex flex-col px-6 py-4 space-y-4">
                <a href="index.php" class="mobile-link text-green-600 font-bold bg-green-50 p-3 rounded-lg text-center flex items-center justify-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i>
                    Kembali ke Home
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-24 pb-16 px-4 md:px-6">
        <div class="container mx-auto max-w-4xl">
            <!-- Header -->
            <div class="text-center mb-10 px-4">
                <div class="inline-block p-2.5 bg-green-50 rounded-xl mb-4">
                    <i class="fa-solid fa-comment-dots text-2xl text-green-600"></i>
                </div>

                <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-3 tracking-tight">
                    Feedback Peserta
                </h1>

                <p class="text-base text-gray-500 max-w-lg mx-auto leading-relaxed">
                    Suaramu sangat berarti! Berikan feedback jujur agar acara STEP UP ke depan bisa lebih keren lagi.
                </p>
            </div>

            <!-- Form Container -->
            <div class="form-section">
                <div class="bg-white rounded-3xl shadow-2xl card-glow overflow-hidden border border-green-100">
                    <!-- Form Header -->
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 border-b border-green-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                                    <i class="fa-solid fa-clipboard-check text-2xl text-green-600"></i>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-black text-gray-900">Formulir Feedback</h2>
                                    <p class="text-gray-600">Isi dengan jujur ya!</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Content -->
                    <form id="feedbackForm" class="p-6 md:p-10 space-y-10" novalidate>
                        <!-- Error Message -->
                        <div id="errorMessage" class="hidden bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                            <div class="flex items-center">
                                <i class="fa-solid fa-circle-exclamation text-red-500 mr-3 text-xl"></i>
                                <div>
                                    <h4 class="font-bold text-red-800">Terjadi Kesalahan</h4>
                                    <p id="errorText" class="text-red-600"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Section 0: Nama Lengkap -->
                        <section class="space-y-4">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-r from-green-500 to-emerald-500 text-white flex items-center justify-center font-bold shadow-md">
                                    <i class="fa-solid fa-user text-sm"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">Nama Lengkap</h3>
                                    <p class="text-sm text-gray-600">Biar kami tahu siapa kamu 😊</p>
                                </div>
                            </div>
                            
                            <div class="relative group">
                                <div class="relative">
                                    <div class="flex items-center">
                                        <div class="absolute left-4 z-10">
                                            <i class="fa-solid fa-user text-green-600 text-lg"></i>
                                        </div>
                                        
                                        <input 
                                            type="text" 
                                            id="nama_lengkap" 
                                            name="nama_lengkap" 
                                            placeholder="Masukkan nama lengkap kamu..."
                                            maxlength="50"
                                            autocomplete="name"
                                            class="w-full pl-12 pr-24 py-4 text-gray-800 bg-white rounded-xl border-2 border-gray-200 focus:border-green-500 focus:ring-4 focus:ring-green-100 outline-none transition-all duration-300 form-input placeholder-gray-400"
                                        >
                                        
                                        <div class="absolute right-4 flex items-center space-x-3">
                                            <div class="flex items-center space-x-2">
                                                <div id="namaIndicator" class="w-2 h-2 rounded-full bg-green-500 opacity-0 transition-opacity duration-300"></div>
                                                <span id="namaCounter" class="text-sm text-gray-500 font-medium">0/50</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Micro helper text -->
                                    <div class="mt-2 px-1">
                                        <div id="nama-error" class="error-message text-sm text-red-500 flex items-center">
                                            <i class="fa-solid fa-circle-exclamation mr-1.5"></i>
                                            <span>Harap isi nama lengkap</span>
                                        </div>
                                        <div id="nama-success" class="hidden text-sm text-green-600 flex items-center">
                                            <i class="fa-solid fa-circle-check mr-1.5"></i>
                                            <span>Nama sudah terisi!</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Fun tip -->
                                <div class="mt-3 flex items-center text-sm text-gray-500">
                                    <i class="fa-solid fa-lightbulb mr-2 text-green-500"></i>
                                    <span>Isi dengan nama asli kamu ya, biar lebih personal!</span>
                                </div>
                            </div>
                        </section>

                        <!-- Section 1: Bagaimana acara yang diselenggarakan -->
                        <section class="space-y-4">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 rounded-full bg-green-600 text-white flex items-center justify-center font-bold">
                                    1
                                </div>
                                <h3 class="text-xl font-bold text-gray-900">Bagaimana acara yang diselenggarakan oleh kakak-kakak? *</h3>
                            </div>
                            <p class="text-gray-600 mb-6">Pilih satu opsi yang paling sesuai dengan pengalamanmu</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <?php
                                $acaraOptions = [
                                    ['value' => 'Sangat Baik', 'icon' => 'face-grin-stars', 'color' => 'green', 'title' => 'Sangat Baik & Terorganisir', 'desc' => 'Acara berjalan lancar dan sangat terstruktur'],
                                    ['value' => 'Baik', 'icon' => 'face-smile', 'color' => 'blue', 'title' => 'Baik', 'desc' => 'Acara cukup baik dan bermanfaat'],
                                    ['value' => 'Cukup', 'icon' => 'face-meh', 'color' => 'yellow', 'title' => 'Cukup Baik', 'desc' => 'Acara biasa saja, ada beberapa hal yang bisa ditingkatkan'],
                                    ['value' => 'Kurang', 'icon' => 'face-frown', 'color' => 'red', 'title' => 'Kurang Memuaskan', 'desc' => 'Banyak hal yang perlu diperbaiki']
                                ];
                                
                                foreach ($acaraOptions as $option) {
                                    echo '
                                    <label class="radio-card p-5 rounded-xl cursor-pointer relative overflow-hidden">
                                        <input type="radio" name="acara" value="' . $option['value'] . '" class="hidden peer acara-radio">
                                        <div class="flex items-center gap-4">
                                            <div class="relative">
                                                <div class="w-10 h-10 rounded-full bg-' . $option['color'] . '-100 flex items-center justify-center peer-checked:bg-' . $option['color'] . '-500 peer-checked:text-white transition-all duration-300">
                                                    <i class="fa-solid fa-' . $option['icon'] . ' text-' . $option['color'] . '-600 peer-checked:text-white text-xl transition-all duration-300"></i>
                                                </div>
                                                <div class="absolute -top-1 -right-1 w-5 h-5 bg-' . $option['color'] . '-500 rounded-full border-2 border-white flex items-center justify-center opacity-0 peer-checked:opacity-100 transform scale-0 peer-checked:scale-100 transition-all duration-300">
                                                    <i class="fa-solid fa-check text-white text-xs"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-gray-900 peer-checked:text-' . $option['color'] . '-700 transition-colors duration-300">' . $option['title'] . '</h4>
                                                <p class="text-sm text-gray-600 mt-1">' . $option['desc'] . '</p>
                                            </div>
                                        </div>
                                        <div class="absolute inset-0 border-2 border-' . $option['color'] . '-500 rounded-xl opacity-0 peer-checked:opacity-100 transition-all duration-300 pointer-events-none"></div>
                                        <div class="absolute inset-0 bg-' . $option['color'] . '-500/5 opacity-0 peer-checked:opacity-100 transition-all duration-300 pointer-events-none"></div>
                                    </label>';
                                }
                                ?>
                            </div>
                            <div id="acara-error" class="error-message text-sm text-red-500 mt-2">
                                Harap pilih penilaian untuk acara
                            </div>
                        </section>

                        <!-- Section 2: Apakah acara bermanfaat -->
                        <section class="space-y-4">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 rounded-full bg-green-600 text-white flex items-center justify-center font-bold">
                                    2
                                </div>
                                <h3 class="text-xl font-bold text-gray-900">Apakah acara ini bermanfaat buat kamu? *</h3>
                            </div>
                            <p class="text-gray-600 mb-6">Pilih tingkat manfaat yang kamu rasakan</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <?php
                                $manfaatOptions = [
                                    ['value' => 'Sangat Bermanfaat', 'icon' => 'brain', 'color' => 'green', 'title' => 'Sangat Bermanfaat', 'desc' => 'Jadi tambah ilmu!'],
                                    ['value' => 'Bermanfaat', 'icon' => 'thumbs-up', 'color' => 'blue', 'title' => 'Bermanfaat', 'desc' => 'Memberikan wawasan baru'],
                                    ['value' => 'Kurang Bermanfaat', 'icon' => 'handshake-slash', 'color' => 'red', 'title' => 'Kurang Bermanfaat', 'desc' => 'Materi kurang relevan']
                                ];
                                
                                foreach ($manfaatOptions as $option) {
                                    echo '
                                    <label class="radio-card p-5 rounded-xl cursor-pointer flex items-center gap-4 relative overflow-hidden">
                                        <input type="radio" name="manfaat" value="' . $option['value'] . '" class="hidden peer manfaat-radio">
                                        <div class="relative">
                                            <div class="w-12 h-12 rounded-full bg-' . $option['color'] . '-100 flex items-center justify-center peer-checked:bg-' . $option['color'] . '-500 peer-checked:text-white transition-all duration-300">
                                                <i class="fa-solid fa-' . $option['icon'] . ' text-' . $option['color'] . '-600 peer-checked:text-white text-xl transition-all duration-300"></i>
                                            </div>
                                            <div class="absolute -top-1 -right-1 w-5 h-5 bg-' . $option['color'] . '-500 rounded-full border-2 border-white flex items-center justify-center opacity-0 peer-checked:opacity-100 transform scale-0 peer-checked:scale-100 transition-all duration-300">
                                                <i class="fa-solid fa-check text-white text-xs"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-900 peer-checked:text-' . $option['color'] . '-700 transition-colors duration-300">' . $option['title'] . '</h4>
                                            <p class="text-sm text-gray-600 mt-1">' . $option['desc'] . '</p>
                                        </div>
                                        <div class="absolute inset-0 border-2 border-' . $option['color'] . '-500 rounded-xl opacity-0 peer-checked:opacity-100 transition-all duration-300 pointer-events-none"></div>
                                        <div class="absolute inset-0 bg-' . $option['color'] . '-500/5 opacity-0 peer-checked:opacity-100 transition-all duration-300 pointer-events-none"></div>
                                    </label>';
                                }
                                ?>
                            </div>
                            <div id="manfaat-error" class="error-message text-sm text-red-500 mt-2">
                                Harap pilih tingkat manfaat acara
                            </div>
                        </section>

                        <!-- Section 3: Pendapat tentang panitia -->
                        <section class="space-y-4">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 rounded-full bg-green-600 text-white flex items-center justify-center font-bold">
                                    3
                                </div>
                                <h3 class="text-xl font-bold text-gray-900">Bagaimana kakak-kakak panitia menurut kamu? *</h3>
                            </div>
                            <p class="text-gray-600 mb-6">Ceritakan pengalamanmu dengan panitia acara (diketik)</p>
                            
                            <div class="relative">
                                <textarea 
                                    id="pendapat_panitia" 
                                    name="pendapat_panitia" 
                                    rows="5" 
                                    placeholder="Tuliskan pendapatmu tentang kakak-kakak panitia. Apakah mereka ramah, membantu, dan profesional?"
                                    maxlength="500"
                                    class="w-full px-5 py-4 text-gray-800 bg-gray-50 rounded-2xl border-2 border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition-all duration-300 form-input resize-none required-textarea"
                                ></textarea>
                                <div class="absolute bottom-3 right-3 text-sm text-gray-500">
                                    <i class="fa-solid fa-pen-to-square mr-1"></i>
                                    <span id="charCountPanitia">0</span>/500 karakter
                                </div>
                            </div>
                            <div id="pendapat-error" class="error-message text-sm text-red-500 mt-2">
                                Harap isi pendapat tentang panitia
                            </div>
                        </section>

                        <!-- Section 4: Kesan dan Pesan -->
                        <section class="space-y-4">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 rounded-full bg-green-600 text-white flex items-center justify-center font-bold">
                                    4
                                </div>
                                <h3 class="text-xl font-bold text-gray-900">Kesan dan Pesan *</h3>
                            </div>
                            <p class="text-gray-600 mb-6">Ceritakan apa yang paling berkesan dari acara ini</p>
                            
                            <div class="relative">
                                <textarea 
                                    id="kesan" 
                                    name="kesan" 
                                    rows="5" 
                                    placeholder="Apa hal yang paling berkesan untukmu dari acara ini? Bagaimana perasaanmu selama mengikuti acara?"
                                    maxlength="500"
                                    class="w-full px-5 py-4 text-gray-800 bg-gray-50 rounded-2xl border-2 border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition-all duration-300 form-input resize-none required-textarea"
                                ></textarea>
                                <div class="absolute bottom-3 right-3 text-sm text-gray-500">
                                    <i class="fa-solid fa-heart mr-1"></i>
                                    <span id="charCountKesan">0</span>/500 karakter
                                </div>
                            </div>
                            <div id="kesan-error" class="error-message text-sm text-red-500 mt-2">
                                Harap isi kesan dan pesan
                            </div>
                        </section>

                        <!-- Section 5: Kritik dan Saran -->
                        <section class="space-y-4">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 rounded-full bg-green-600 text-white flex items-center justify-center font-bold">
                                    5
                                </div>
                                <h3 class="text-xl font-bold text-gray-900">Kritik dan Saran *</h3>
                            </div>
                            <p class="text-gray-600 mb-6">Berikan masukan untuk perbaikan acara ke depan</p>
                            
                            <div class="relative">
                                <textarea 
                                    id="kritik" 
                                    name="kritik" 
                                    rows="5" 
                                    placeholder="Apa yang bisa ditingkatkan dari acara ini? Saran apa yang ingin kamu berikan untuk acara STEP UP selanjutnya?"
                                    maxlength="500"
                                    class="w-full px-5 py-4 text-gray-800 bg-gray-50 rounded-2xl border-2 border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-200 outline-none transition-all duration-300 form-input resize-none required-textarea"
                                ></textarea>
                                <div class="absolute bottom-3 right-3 text-sm text-gray-500">
                                    <i class="fa-solid fa-lightbulb mr-1"></i>
                                    <span id="charCountKritik">0</span>/500 karakter
                                </div>
                            </div>
                            <div id="kritik-error" class="error-message text-sm text-red-500 mt-2">
                                Harap isi kritik dan saran
                            </div>
                        </section>

                        <!-- Section 6: Rating Bintang -->
                        <section class="space-y-4">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 rounded-full bg-green-600 text-white flex items-center justify-center font-bold">
                                    6
                                </div>
                                <h3 class="text-xl font-bold text-gray-900">Rating Keseluruhan *</h3>
                            </div>
                            <p class="text-gray-600 mb-6">Berikan rating untuk keseluruhan acara</p>
                            
                            <div class="bg-gradient-to-r from-green-50 to-amber-50 p-8 rounded-2xl border border-green-100">
                                <div class="text-center mb-8">
                                    <h4 class="text-2xl font-black text-gray-900 mb-2">Kasih Bintang Dong! ⭐</h4>
                                    <p class="text-gray-600">Seberapa puas kamu dengan acara STEP UP ini?</p>
                                </div>
                                
                                <div class="star-rating mb-6">
                                    <input type="radio" id="star5" name="rating" value="5" class="rating-radio">
                                    <label for="star5" title="Sempurna (5 Bintang)"><i class="fa-solid fa-star"></i></label>
                                    <input type="radio" id="star4" name="rating" value="4">
                                    <label for="star4" title="Bagus (4 Bintang)"><i class="fa-solid fa-star"></i></label>
                                    <input type="radio" id="star3" name="rating" value="3">
                                    <label for="star3" title="Lumayan (3 Bintang)"><i class="fa-solid fa-star"></i></label>
                                    <input type="radio" id="star2" name="rating" value="2">
                                    <label for="star2" title="Kurang (2 Bintang)"><i class="fa-solid fa-star"></i></label>
                                    <input type="radio" id="star1" name="rating" value="1">
                                    <label for="star1" title="Buruk (1 Bintang)"><i class="fa-solid fa-star"></i></label>
                                </div>
                                
                                <div class="text-center">
                                    <div id="ratingText" class="text-lg font-semibold text-gray-700 mb-1">Pilih bintang di atas</div>
                                    <div id="ratingDesc" class="text-sm text-gray-500">Klik pada bintang untuk memberikan rating</div>
                                </div>
                            </div>
                            <div id="rating-error" class="error-message text-sm text-red-500 mt-2">
                                Harap berikan rating
                            </div>
                        </section>

                        <!-- Submit Button -->
                        <div class="pt-6">
                            <button id="submitBtn" type="submit" class="btn-submit w-full py-5 rounded-2xl text-white font-black text-lg flex items-center justify-center gap-3">
                                <i class="fa-solid fa-paper-plane"></i>
                                Kirim Feedback Saya
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                            
                            <p class="text-center text-gray-500 text-sm mt-4">
                                <i class="fa-solid fa-shield-check mr-1"></i>
                                wajib diisi semua ya!
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Success Message (Hidden by Default) -->
    <div id="successMessage" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-10 text-center success-animation">
            <div class="floating-element">
                <div class="w-24 h-24 rounded-full bg-gradient-to-r from-green-100 to-emerald-100 flex items-center justify-center mx-auto mb-6">
                    <i class="fa-solid fa-circle-check text-5xl text-green-600"></i>
                </div>
            </div>
            
            <h3 class="text-3xl font-black text-gray-900 mb-4">Terima Kasih! 🎉</h3>
            <p class="text-gray-600 mb-8">
                Feedback kamu sudah berhasil dikirim. Masukan dari peserta seperti kamu sangat berarti untuk kami dan akan membantu meningkatkan kualitas acara STEP UP ke depannya.
            </p>
            
            <div class="space-y-4">
                <a href="index.php" class="block bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-bold py-4 rounded-xl transition transform hover:-translate-y-1 shadow-lg">
                    <i class="fa-solid fa-house mr-2"></i>
                    Kembali ke Homepage
                </a>
                <button id="newFeedback" class="block w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-4 rounded-xl transition">
                    <i class="fa-solid fa-plus mr-2"></i>
                    Kirim Feedback Lagi
                </button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-10 md:py-12 border-t-4 border-green-600">
        <div class="container mx-auto px-4 md:px-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                <!-- Logo dan informasi -->
                <div class="flex flex-col items-center lg:items-start text-center lg:text-left">
                    <a href="#" class="flex items-center gap-3 text-2xl font-extrabold text-green-600 tracking-wider mb-4">
                        <span class="w-12 h-12 rounded-full overflow-hidden flex items-center justify-center bg-green-100 p-1">
                            <img 
                                src="https://imgur.com/oQNRfoA.png" 
                                alt="STEP UP Logo"
                                class="w-full h-full object-cover"
                            >
                        </span>
                        <span>STEP UP</span>
                    </a>
                    <p class="text-sm text-gray-400 mb-4 max-w-xs lg:max-w-none">
                        Organisasi Event Lingkungan Sekolah. Membangun kesadaran sejak dini.
                    </p>
                    <a href="mailto:stepupenv.official@gmail.com" class="text-sm hover:text-green-500 transition flex items-center gap-2">
                        <i class="fa-solid fa-envelope"></i>
                        stepupenv.official@gmail.com
                    </a>
                </div>

                <!-- Media Sosial -->
                <div class="flex flex-col items-center">
                    <h3 class="text-lg font-semibold text-green-500 mb-4">Ikuti Kami</h3>
                    <div class="flex space-x-6 text-2xl">
                        <a href="https://www.instagram.com/step_upsmkbl?igsh=MmpyZmNxOHl0dDVl" 
                           class="hover:text-green-500 transition transform hover:-translate-y-1" 
                           target="_blank" 
                           aria-label="Instagram STEP UP">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                    </div>
                    <div class="mt-6 text-sm text-gray-500 text-center">
                        <p>SMK Budi Luhur</p>
                        <p class="text-xs mt-1">Ciptakan perubahan, mulai dari sekolah</p>
                    </div>
                </div>

                <!-- Quick info -->
                <div class="flex flex-col items-center lg:items-end text-center lg:text-right">
                    <h4 class="text-lg font-semibold text-gray-200 mb-3">Info Singkat</h4>
                    <p class="text-sm text-gray-400 max-w-sm">Bergabunglah dalam gerakan ramah lingkungan, dari workshop hingga aksi nyata di sekolah dan komunitas.</p>
                </div>
            </div>

            <div class="border-t border-gray-800 my-8"></div>

            <div class="text-center">
                <p class="text-xs text-gray-600">
                    © 2026 STEP UP Organization. Step Up Today, Save the Future.!
                </p>
                <p class="text-xs text-gray-700 mt-2">
                    #StepUpForEnvironment
                </p>
            </div>
        </div>
    </footer>

    <script>
// ============================================
// FIXED JAVASCRIPT - SEMUA FUNGSI BEKERJA
// ============================================

// Mobile Menu Toggle
const mobileMenuBtn = document.getElementById('mobile-menu-btn');
const mobileMenu = document.getElementById('mobile-menu');

mobileMenuBtn.addEventListener('click', () => {
    mobileMenu.classList.toggle('hidden');
});

// Close mobile menu when clicking outside
document.addEventListener('click', (e) => {
    if (!mobileMenuBtn.contains(e.target) && !mobileMenu.contains(e.target)) {
        mobileMenu.classList.add('hidden');
    }
});

// Radio card selection
const radioCards = document.querySelectorAll('.radio-card');
radioCards.forEach(card => {
    const input = card.querySelector('input[type="radio"]');
    
    input.addEventListener('change', () => {
        // Remove selected class from all cards in same group
        const name = input.name;
        document.querySelectorAll(`input[name="${name}"]`).forEach(inp => {
            inp.closest('.radio-card')?.classList.remove('selected');
        });
        // Add selected class to current card
        card.classList.add('selected');
    });
    
    card.addEventListener('click', (e) => {
        if (e.target !== input) {
            input.checked = true;
            input.dispatchEvent(new Event('change'));
        }
    });
});

// ============================================
// NAMA LENGKAP VALIDATION (STANDALONE)
// ============================================
const namaLengkap = document.getElementById('nama_lengkap');
const namaCounter = document.getElementById('namaCounter');
const namaIndicator = document.getElementById('namaIndicator');
const namaError = document.getElementById('nama-error');
const namaSuccess = document.getElementById('nama-success');

function validateNamaLengkap() {
    const value = namaLengkap.value.trim();
    const length = value.length;
    
    // Update counter
    namaCounter.textContent = `${length}/50`;
    
    // Update color based on length
    if (length === 0) {
        namaCounter.style.color = '#9ca3af';
        namaIndicator.style.opacity = '0';
        namaError.classList.remove('show');
        namaSuccess.classList.add('hidden');
    } else if (length > 50) {
        namaCounter.style.color = '#ef4444';
        namaIndicator.style.backgroundColor = '#ef4444';
        namaIndicator.style.opacity = '1';
        namaError.classList.add('show');
        namaError.querySelector('span').textContent = 'Nama terlalu panjang (maksimal 50 karakter)';
        namaSuccess.classList.add('hidden');
    } else {
        namaCounter.style.color = '#10b981';
        namaIndicator.style.backgroundColor = '#10b981';
        namaIndicator.style.opacity = '1';
        namaError.classList.remove('show');
        namaSuccess.classList.remove('hidden');
    }
}

// Event listeners untuk nama
namaLengkap.addEventListener('input', validateNamaLengkap);
namaLengkap.addEventListener('blur', validateNamaLengkap);

// ============================================
// CHARACTER COUNTERS UNTUK TEXTAREA
// ============================================
const pendapatPanitia = document.getElementById('pendapat_panitia');
const kesan = document.getElementById('kesan');
const kritik = document.getElementById('kritik');
const charCountPanitia = document.getElementById('charCountPanitia');
const charCountKesan = document.getElementById('charCountKesan');
const charCountKritik = document.getElementById('charCountKritik');

function updateCharCount(textarea, counter) {
    const count = textarea.value.length;
    counter.textContent = count;
    
    if (count > 450) {
        counter.style.color = '#f87171';
    } else if (count > 300) {
        counter.style.color = '#fbbf24';
    } else {
        counter.style.color = '#6b7280';
    }
}

pendapatPanitia.addEventListener('input', () => updateCharCount(pendapatPanitia, charCountPanitia));
kesan.addEventListener('input', () => updateCharCount(kesan, charCountKesan));
kritik.addEventListener('input', () => updateCharCount(kritik, charCountKritik));

// ============================================
// RATING SYSTEM
// ============================================
const ratingInputs = document.querySelectorAll('.star-rating input');
const ratingText = document.getElementById('ratingText');
const ratingDesc = document.getElementById('ratingDesc');

const ratingDescriptions = {
    1: { text: "Buruk", desc: "Sangat tidak puas dengan acara" },
    2: { text: "Kurang", desc: "Ada banyak hal yang perlu ditingkatkan" },
    3: { text: "Lumayan", desc: "Cukup baik, tapi bisa lebih baik lagi" },
    4: { text: "Bagus", desc: "Puas dengan acara yang diselenggarakan" },
    5: { text: "Sempurna", desc: "Sangat puas! Acara luar biasa" }
};

ratingInputs.forEach(input => {
    input.addEventListener('change', () => {
        const value = parseInt(input.value);
        const desc = ratingDescriptions[value];
        ratingText.textContent = `${value} Bintang - ${desc.text}`;
        ratingDesc.textContent = desc.desc;
    });
});

// ============================================
// FORM COMPONENTS
// ============================================
const form = document.getElementById('feedbackForm');
const errorMessage = document.getElementById('errorMessage');
const errorText = document.getElementById('errorText');
const successMessage = document.getElementById('successMessage');
const newFeedbackBtn = document.getElementById('newFeedback');
const submitBtn = document.getElementById('submitBtn');
const navbar = document.getElementById('navbar');

// ============================================
// UPDATED FORM VALIDATION (INCLUDES NAMA)
// ============================================
function setupFormValidation() {
    // Array semua field yang wajib diisi (Sekarang termasuk nama)
    const requiredFields = [
        { 
            type: 'text', 
            name: 'nama_lengkap', 
            errorId: 'nama-error',
            validator: function(value) {
                const trimmed = value.trim();
                return trimmed.length > 0 && trimmed.length <= 50;
            }
        },
        { type: 'radio', name: 'acara', errorId: 'acara-error' },
        { type: 'radio', name: 'manfaat', errorId: 'manfaat-error' },
        { type: 'textarea', name: 'pendapat_panitia', errorId: 'pendapat-error' },
        { type: 'textarea', name: 'kesan', errorId: 'kesan-error' },
        { type: 'textarea', name: 'kritik', errorId: 'kritik-error' },
        { type: 'radio', name: 'rating', errorId: 'rating-error' }
    ];

    // Function to check if a field is valid
    function isFieldValid(field) {
        if (field.type === 'text') {
            const input = document.querySelector(`input[name="${field.name}"]`);
            if (!input) return false;
            
            if (field.validator) {
                return field.validator(input.value);
            }
            return input.value.trim().length > 0;
        } else if (field.type === 'radio') {
            return !!form.querySelector(`input[name="${field.name}"]:checked`);
        } else if (field.type === 'textarea') {
            const textarea = document.querySelector(`textarea[name="${field.name}"]`);
            return textarea && textarea.value.trim().length > 0;
        }
        return false;
    }

    // Function to validate entire form
    function validateForm() {
        let allValid = true;
        
        requiredFields.forEach(field => {
            const isValid = isFieldValid(field);
            const errorElement = document.getElementById(field.errorId);
            
            if (errorElement) {
                if (isValid) {
                    errorElement.classList.remove('show');
                } else {
                    errorElement.classList.add('show');
                    allValid = false;
                }
            } else {
                if (!isValid) allValid = false;
            }
        });
        
        // Update submit button state
        submitBtn.disabled = !allValid;
        
        return allValid;
    }

    // Function to check field and update error on the fly
    function checkField(field) {
        const isValid = isFieldValid(field);
        const errorElement = document.getElementById(field.errorId);
        
        if (errorElement) {
            if (isValid) {
                errorElement.classList.remove('show');
            } else {
                errorElement.classList.add('show');
            }
        }
        
        // Update submit button
        submitBtn.disabled = !validateForm();
    }

    // Add event listeners to all required fields
    requiredFields.forEach(field => {
        if (field.type === 'radio') {
            const radios = form.querySelectorAll(`input[name="${field.name}"]`);
            radios.forEach(radio => {
                radio.addEventListener('change', () => checkField(field));
            });
        } else if (field.type === 'textarea') {
            const textarea = document.querySelector(`textarea[name="${field.name}"]`);
            if (textarea) {
                textarea.addEventListener('input', () => checkField(field));
                textarea.addEventListener('blur', () => checkField(field));
            }
        } else if (field.type === 'text') {
            const input = document.querySelector(`input[name="${field.name}"]`);
            if (input) {
                input.addEventListener('input', () => {
                    checkField(field);
                    validateNamaLengkap(); // Also update nama visual validation
                });
                input.addEventListener('blur', () => checkField(field));
            }
        }
    });

    // Also check on any form input (as backup)
    form.addEventListener('input', () => {
        validateForm();
    });

    // Initial validation
    setTimeout(() => {
        validateForm();
        validateNamaLengkap();
    }, 300);
}

// ============================================
// FORM SUBMISSION HANDLER (FIXED)
// ============================================
form.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    // Validate all required fields
    const namaValue = namaLengkap.value.trim();
    const isNamaValid = namaValue.length > 0 && namaValue.length <= 50;
    
    const acaraChecked = !!form.querySelector('input[name="acara"]:checked');
    const manfaatChecked = !!form.querySelector('input[name="manfaat"]:checked');
    const ratingChecked = !!form.querySelector('input[name="rating"]:checked');
    const pendapatValue = pendapatPanitia.value.trim();
    const kesanValue = kesan.value.trim();
    const kritikValue = kritik.value.trim();
    
    // Check if all fields are valid
    if (!isNamaValid || !acaraChecked || !manfaatChecked || !ratingChecked || 
        pendapatValue === '' || kesanValue === '' || kritikValue === '') {
        
        errorText.textContent = "Harap lengkapi semua field yang wajib diisi";
        errorMessage.classList.remove('hidden');
        
        // Show individual error messages for empty fields
        if (!isNamaValid) {
            namaError.classList.add('show');
        }
        if (!acaraChecked) {
            document.getElementById('acara-error').classList.add('show');
        }
        if (!manfaatChecked) {
            document.getElementById('manfaat-error').classList.add('show');
        }
        if (!ratingChecked) {
            document.getElementById('rating-error').classList.add('show');
        }
        if (pendapatValue === '') {
            document.getElementById('pendapat-error').classList.add('show');
        }
        if (kesanValue === '') {
            document.getElementById('kesan-error').classList.add('show');
        }
        if (kritikValue === '') {
            document.getElementById('kritik-error').classList.add('show');
        }
        
        // Scroll to first error
        form.scrollIntoView({ behavior: 'smooth', block: 'start' });
        
        setTimeout(() => {
            errorMessage.classList.add('hidden');
        }, 5000);
        return;
    }
    
    // Show loading on button
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mengirim...';
    submitBtn.disabled = true;
    
    try {
        const formData = new FormData(form);
        
        const response = await fetch('', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const result = await response.json();
        
        if (result.success) {
            // Show success message
            successMessage.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Reset form
            form.reset();
            
            // Reset visual states
            radioCards.forEach(card => card.classList.remove('selected'));
            updateCharCount(pendapatPanitia, charCountPanitia);
            updateCharCount(kesan, charCountKesan);
            updateCharCount(kritik, charCountKritik);
            validateNamaLengkap(); // Reset nama validation
            ratingText.textContent = "Pilih bintang di atas";
            ratingDesc.textContent = "Klik pada bintang untuk memberikan rating";
            
            // Hide all error messages
            document.querySelectorAll('.error-message').forEach(el => {
                el.classList.remove('show');
            });
            
        } else {
            throw new Error(result.message || 'Gagal mengirim feedback');
        }
        
    } catch (error) {
        console.error('Error:', error);
        errorText.textContent = `Gagal mengirim feedback: ${error.message}`;
        errorMessage.classList.remove('hidden');
        
        setTimeout(() => {
            errorMessage.classList.add('hidden');
        }, 5000);
        
    } finally {
        submitBtn.innerHTML = originalText;
        // Re-enable validation
        setTimeout(setupFormValidation, 100);
    }
});

// ============================================
// NEW FEEDBACK BUTTON
// ============================================
newFeedbackBtn.addEventListener('click', () => {
    successMessage.classList.add('hidden');
    document.body.style.overflow = 'auto';
    
    // Reset form
    form.reset();
    
    // Reset visual states
    radioCards.forEach(card => card.classList.remove('selected'));
    updateCharCount(pendapatPanitia, charCountPanitia);
    updateCharCount(kesan, charCountKesan);
    updateCharCount(kritik, charCountKritik);
    validateNamaLengkap();
    ratingText.textContent = "Pilih bintang di atas";
    ratingDesc.textContent = "Klik pada bintang untuk memberikan rating";
    
    // Hide all error messages
    document.querySelectorAll('.error-message').forEach(el => {
        el.classList.remove('show');
    });
    
    // Scroll to top of form
    form.scrollIntoView({ behavior: 'smooth', block: 'start' });
    
    // Re-initialize validation
    setTimeout(setupFormValidation, 100);
});

// ============================================
// NAVBAR SCROLL EFFECT
// ============================================
window.addEventListener('scroll', () => {
    if (window.scrollY > 20) {
        navbar.classList.add('shadow-md', 'bg-white/95');
    } else {
        navbar.classList.remove('shadow-md');
    }
});

// ============================================
// INITIALIZE EVERYTHING WHEN PAGE LOADS
// ============================================
document.addEventListener('DOMContentLoaded', () => {
    // Initialize counters
    updateCharCount(pendapatPanitia, charCountPanitia);
    updateCharCount(kesan, charCountKesan);
    updateCharCount(kritik, charCountKritik);
    validateNamaLengkap();
    
    // Setup form validation
    setupFormValidation();
    
    // Add tooltip for rating stars
    const starLabels = document.querySelectorAll('.star-rating label');
    starLabels.forEach(label => {
        label.addEventListener('mouseenter', () => {
            const value = label.getAttribute('for').replace('star', '');
            const desc = ratingDescriptions[value];
            if (desc) {
                ratingText.textContent = `${value} Bintang - ${desc.text}`;
                ratingDesc.textContent = desc.desc;
            }
        });
    });
});
    </script>
</body>
</html>