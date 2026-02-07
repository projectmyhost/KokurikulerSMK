<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STEP UP - Generasi Peduli Lingkungan</title>
    <link rel="icon" type="image/png" href="https://imgur.com/oQNRfoA.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome untuk Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
        section {
            scroll-margin-top: 70px;
        }
        /* Custom scrollbar untuk textarea */
        textarea::-webkit-scrollbar {
            width: 8px;
        }
        textarea::-webkit-scrollbar-track {
            background: #f1f1f1; 
        }
        textarea::-webkit-scrollbar-thumb {
            background: #88c057; 
            border-radius: 4px;
        }
        textarea::-webkit-scrollbar-thumb:hover {
            background: #555; 
        }

        /* Logika CSS untuk Star Rating Interaktif */
        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: center;
            gap: 0.5rem;
        }
        .star-rating input {
            display: none; /* Sembunyikan radio button asli */
        }
        .star-rating label {
            cursor: pointer;
            color: #d1d5db; /* Warna abu-abu default (gray-300) */
            transition: color 0.2s;
            font-size: 2.5rem; /* Ukuran bintang */
        }
        /* Saat radio di-check, ubah warna label ini dan semua label setelahnya (DOM order) */
        .star-rating input:checked ~ label {
            color: #fbbf24; /* Warna kuning (amber-400) */
        }
        /* Efek Hover: Ubah warna saat mouse lewat */
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: #fcd34d; /* Warna kuning lebih terang (amber-300) */
        }

        /* Footer enhancements: responsive grid and icon hover */
        .footer-icon {
            transition: transform .18s ease, color .18s ease;
            color: inherit;
        }
        .footer-icon:hover {
            transform: translateY(-4px);
            color: #34d399; /* emerald-400 */
        }
        @media (min-width: 1024px) {
            .footer-grid {
                display: grid;
                grid-template-columns: 1fr 1fr 1fr;
                gap: 2rem;
                align-items: start;
            }
        }
        
    </style>
</head>
<body class="bg-green-50 text-gray-800">

    <!-- NAVBAR -->
    <nav class="fixed w-full z-50 bg-white/95 backdrop-blur-sm shadow-sm transition-all duration-300" id="navbar">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <!-- Logo -->
            <a href="#" class="flex items-center gap-2 text-2xl font-extrabold text-green-600 tracking-wider">
    <span class="w-10 h-10 rounded-full overflow-hidden flex items-center justify-center bg-green-100">
        <img 
            src="https://imgur.com/oQNRfoA.png" 
            alt="STEP UP Logo"
            class="w-full h-full object-cover"
        >
    </span>
    STEP UP
</a>


            <!-- Menu Desktop -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="#home" class="text-gray-600 hover:text-green-600 font-bold transition">Beranda</a>
                <a href="#about" class="text-gray-600 hover:text-green-600 font-bold transition">Tentang Kami</a>
                <a href="#visi-misi" class="text-gray-600 hover:text-green-600 font-bold transition">Visi & Misi</a>
                <a href="feedback.php" class="bg-green-600 text-white px-6 py-2 rounded-full hover:bg-green-700 transition shadow-md transform hover:scale-105 font-bold">
                    Isi Feedback
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
                <a href="#home" class="mobile-link text-gray-600 hover:text-green-600 font-bold">Beranda</a>
                <a href="#about" class="mobile-link text-gray-600 hover:text-green-600 font-bold">Tentang Kami</a>
                <a href="#visi-misi" class="mobile-link text-gray-600 hover:text-green-600 font-bold">Visi & Misi</a>
                <a href="feedback.php" class="mobile-link text-green-600 font-bold bg-green-50 p-2 rounded-lg text-center">Isi Feedback</a>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION / BANNER -->
    <section id="home" class="relative h-screen flex items-center justify-center bg-gray-900 text-white overflow-hidden">
        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" 
                 alt="Nature Background" class="w-full h-full object-cover opacity-60">
        </div>

        <div class="relative z-10 container mx-auto px-6 text-center">
            <span class="inline-block py-1 px-4 rounded-full bg-green-500/20 border border-green-400 text-green-300 text-sm font-bold mb-4 animate-pulse">
                Step Up Environtment 2026
            </span>
            <h1 class="text-4xl md:text-6xl font-extrabold leading-tight mb-6">
                Go Green bukan trend!<br><span class="text-green-400">tapi itu kebutuhan.</span>
            </h1>
            <p class="text-lg md:text-xl text-gray-200 mb-8 max-w-2xl mx-auto font-medium">
                STEP UP hadir mengajak siswa-siswi SMP untuk peduli lingkungan lewat aksi nyata, edukasi seru, dan kolaborasi positif.
            </p>
            <div class="flex flex-col md:flex-row gap-4 justify-center">
                <a href="#visi-misi" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-full transition shadow-lg transform hover:-translate-y-1 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-seedling"></i> Lihat Program
                </a>
                <a href="feedback.php" class="bg-white/10 backdrop-blur-sm border-2 border-white hover:bg-white hover:text-green-800 text-white font-bold py-3 px-8 rounded-full transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-comment-dots"></i> Beri Masukan
                </a>
            </div>
        </div>
        
        <!-- Decoration Wave Bottom -->
        <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none rotate-180">
            <svg class="relative block w-[calc(100%+1.3px)] h-[60px]" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="fill-green-50"></path>
            </svg>
        </div>
    </section>

    <!-- ABOUT SECTION -->
    <section id="about" class="py-20 bg-green-50">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Tentang STEP UP</h2>
            <div class="w-24 h-1.5 bg-green-500 mx-auto mb-8 rounded-full"></div>
            <p class="text-gray-600 max-w-3xl mx-auto leading-relaxed text-lg">
                Kami adalah tim pelajar SMK yang mengadakan event Go Green di sekolah SMP. Melalui
                penanaman tanaman, dan seminar yang seru, kami ingin menumbuhkan kesadaran lingkungan
                sejak dini, agar menghindari bencana di masa depan. Yuk, gabung dan jadi bagian dari
                perubahan positif untuk bumi kita tercinta!
            </p>
        </div>
    </section>

    <!-- VISI MISI SECTION (Z-PATTERN) -->
    <section id="visi-misi" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            
            <!-- Item 1: Visi (Gambar Kiri, Teks Kanan) -->
            <div class="flex flex-col md:flex-row items-center gap-12 mb-24">
                <!-- Gambar -->
                <div class="w-full md:w-1/2">
                    <div class="relative group">
                        <div class="absolute -inset-3 bg-green-400 rounded-2xl opacity-30 group-hover:opacity-50 transition duration-500 blur-xl"></div>
                        <img src="https://imgur.com/OdrL0vV.jpg" 
                             alt="Visi Lingkungan" 
                             class="relative rounded-2xl shadow-xl w-full object-cover h-72 md:h-96 transform transition duration-500 group-hover:scale-[1.02]">
                    </div>
                </div>
                <!-- Teks -->
                <div class="w-full md:w-1/2">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="bg-green-100 text-green-600 p-3 rounded-full">
                            <i class="fa-solid fa-globe text-2xl"></i>
                        </span>
                        <h3 class="text-3xl font-bold text-gray-800">Visi Kami</h3>
                    </div>
                    <p class="text-gray-600 leading-relaxed mb-6 text-lg">
                        Menumbuhkan kepedulian siswa terhadap lingkungan sejak dini melalui kegiatan Go Green yang edukatif, sederhana, dan bermanfaat bagi lingkungan sekolah.
                    </p>
                    <ul class="space-y-3">
                        <li class="flex items-center text-gray-700 bg-green-50 p-3 rounded-lg border border-green-100">
                            <i class="fa-solid fa-check-circle text-green-500 mr-3 text-xl"></i> Menciptakan lingkungan sekolah yang bersih dan hijau.
                        </li>
                        <li class="flex items-center text-gray-700 bg-green-50 p-3 rounded-lg border border-green-100">
                            <i class="fa-solid fa-check-circle text-green-500 mr-3 text-xl"></i> Gaya Hidup Minim Sampah
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Item 2: Misi (Teks Kiri, Gambar Kanan) -->
            <div class="flex flex-col md:flex-row-reverse items-center gap-12">
                <!-- Gambar -->
                <div class="w-full md:w-1/2">
                    <div class="relative group">
                        <div class="absolute -inset-3 bg-teal-400 rounded-2xl opacity-30 group-hover:opacity-50 transition duration-500 blur-xl"></div>
                        <img src="https://imgur.com/4ajwHhS.jpg" 
                             alt="Misi Kegiatan Siswa" 
                             class="relative rounded-2xl shadow-xl w-full object-cover h-72 md:h-96 transform transition duration-500 group-hover:scale-[1.02]">
                    </div>
                </div>
                <!-- Teks -->
                <div class="w-full md:w-1/2">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="bg-teal-100 text-teal-600 p-3 rounded-full">
                            <i class="fa-solid fa-hand-holding-heart text-2xl"></i>
                        </span>
                        <h3 class="text-3xl font-bold text-gray-800">Misi Kami</h3>
                    </div>
                    <p class="text-gray-600 leading-relaxed mb-6 text-lg">
                        Mengadakan event kreatif seperti Eco Print, memilah sampah, penanaman tanaman bersama, dan seminar "Go Green" yang seru dan tidak membosankan untuk pelajar SMP.
                    </p>
                    <p class="text-gray-600 leading-relaxed border-l-4 border-teal-500 pl-4 italic bg-gray-50 p-4 rounded-r-lg">
                        "Belajar mencintai bumi bukan hanya teori, tapi aksi nyata yang menyenangkan bersama teman-teman."
                    </p>
                </div>
            </div>

        </div>
    </section>

    <!-- STATISTIK SECTION -->
    <!-- <section class="bg-gradient-to-r from-green-700 to-teal-600 py-16 relative overflow-hidden"> -->
        <!-- Decoration Pattern -->
        <!-- <div class="absolute opacity-10 top-0 left-0 w-full h-full" style="background-image: radial-gradient(#fff 2px, transparent 2px); background-size: 30px 30px;"></div>
        
        <div class="container mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-8 text-center text-white relative z-10">
            <div class="p-4">
                <i class="fa-solid fa-school text-4xl mb-4 text-green-200"></i>
                <h4 class="text-4xl font-extrabold mb-1">15+</h4>
                <p class="text-green-100 font-medium">Sekolah Mitra</p>
            </div>
            <div class="p-4">
                <i class="fa-solid fa-tree text-4xl mb-4 text-green-200"></i>
                <h4 class="text-4xl font-extrabold mb-1">1000+</h4>
                <p class="text-green-100 font-medium">Pohon Ditanam</p>
            </div>
            <div class="p-4">
                <i class="fa-solid fa-users text-4xl mb-4 text-green-200"></i>
                <h4 class="text-4xl font-extrabold mb-1">500+</h4>
                <p class="text-green-100 font-medium">Audiens Event</p>
            </div>
            <div class="p-4">
                <i class="fa-solid fa-calendar-check text-4xl mb-4 text-green-200"></i>
                <h4 class="text-4xl font-extrabold mb-1">20+</h4>
                <p class="text-green-100 font-medium">Event Sukses</p>
            </div>
        </div>
    </section> -->

    <!-- FEEDBACK FORM SECTION -->
    

    <!-- FOOTER -->
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

    <!-- JAVASCRIPT LOGIC -->
    <script>
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        const links = document.querySelectorAll('.mobile-link');
        const navbar = document.getElementById('navbar');

        // Toggle Mobile Menu
        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });

        links.forEach(link => {
            link.addEventListener('click', () => {
                menu.classList.add('hidden');
            });
        });

        // Form Logic
        const form = document.getElementById('feedbackForm');
        const successMsg = document.getElementById('successMessage');

        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const btnSubmit = form.querySelector('button');
            const originalContent = btnSubmit.innerHTML;
            
            // Loading State
            btnSubmit.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Loading...';
            btnSubmit.classList.add('opacity-75', 'cursor-not-allowed');
            
            setTimeout(() => {
                form.reset();
                form.classList.add('hidden'); // Sembunyikan form
                successMsg.classList.remove('hidden'); // Tampilkan pesan sukses
                
                // Kembalikan tombol (jika form mau ditampilkan lagi nanti secara manual)
                btnSubmit.innerHTML = originalContent;
                btnSubmit.classList.remove('opacity-75', 'cursor-not-allowed');

                // Opsional: Reload form setelah 5 detik agar user lain bisa isi
                setTimeout(() => {
                    successMsg.classList.add('hidden');
                    form.classList.remove('hidden');
                }, 5000);
            }, 1500);
        });

        // Navbar Shadow Effect
        window.addEventListener('scroll', () => {
            if (window.scrollY > 20) {
                navbar.classList.add('shadow-md', 'bg-white/95');
            } else {
                navbar.classList.remove('shadow-md');
            }
        });
    </script>
</body>
</html>