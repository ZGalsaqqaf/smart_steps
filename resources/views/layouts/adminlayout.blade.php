<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
</head>

<body>
    <!-- Navbar خاص بالـ Admin -->
    <nav class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url('/dashboard') }}">⚙️ Admin Panel</a>
            <div>
                <!-- home page -->
                <a class="btn btn-outline-light" href="{{ url('/') }}">🏠 Home</a>

                <a class="btn btn-outline-light" href="{{ route('grades.index') }}">Grades</a>
                <a class="btn btn-outline-light" href="{{ route('categories.index') }}">Categories</a>
                <a class="btn btn-outline-light" href="{{ route('students.index') }}">Students</a>
                <a class="btn btn-outline-light" href="{{ route('questions.index') }}">Questions</a>
            </div>
        </div>
    </nav>

    <!-- المحتوى -->
    <div class="container mt-4">
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>

    <!-- Toast Notifications للنجاح والخطأ -->
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end', // يظهر في الطرف العلوي الأيمن
            showConfirmButton: false,
            timer: 3000, // يختفي بعد 3 ثواني
            timerProgressBar: true
        });

        @if (session('success'))
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif

        @if (session('error'))
            Toast.fire({
                icon: 'error',
                title: '{{ session('error') }}'
            });
        @endif
    </script>

    <!-- سكربت الحذف الموحد -->
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This item will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
</body>

</html>
