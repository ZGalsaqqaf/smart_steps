<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Smart Steps</title>
    <!-- Bootstrap Offline -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            background-color: #f9f9f9;
            font-family: 'Segoe UI', Tahoma, sans-serif;
        }

        /* عنصر وهمي يغطي الشاشة ويضع الظل */
        body::before {
            content: "";
            position: fixed;
            /* ثابت على الشاشة */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            /* لا يتداخل مع التفاعل */
            box-shadow: inset 0 -15px 25px rgba(13, 202, 240, 0.4),
                inset -15px 0 25px rgba(13, 202, 240, 0.4),
                inset 15px 0 25px rgba(13, 202, 240, 0.4);
            z-index: 9999;
            /* فوق كل شيء */
        }

        nav {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-info shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold text-white" href="{{ url('/') }}">🏫 Smart Steps</a>
            <div>
                <a class="btn btn-light" href="{{ url('/') }}">🏠 Home</a>
                {{-- <a class="btn btn-light" href="{{ route('grades.index') }}">📚 Grades</a> --}}
                {{-- <a class="btn btn-light" href="{{ route('students.index') }}">👩‍🎓 Students</a> --}}
                {{-- <a class="btn btn-light" href="{{ route('questions.index') }}">❓ Questions</a> --}}
                {{-- <a class="btn btn-light" href="{{ url('/leaderboard') }}">🏆 Leaderboard</a> --}}
                <!-- زر الدخول للـ Admin Dashboard -->
                <a class="btn btn-dark mx-4" href="{{ url('/dashboard') }}" title="Admin Dashboard">
                    ⚙️
                </a>

            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="container">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="text-center mt-4 mb-2 text-muted">
        <small>© 2025 Smart Steps</small>
    </footer>

    <!-- Bootstrap Offline -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <!-- jQuery Offline -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>

    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
</body>

</html>
