<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="text-center bg-white p-4 rounded-lg shadow-lg max-w-lg mx-auto">
        <h1 class="display-1 text-danger mb-3">404</h1>
        <p class="h2 text-dark mb-3">Student Not Found</p>
        <p class="text-muted mb-4">We couldn't find the student you were looking for. Try searching again.</p>
        <a href="{{ route('students.create') }}" class="btn btn-info btn-sm rounded-pill px-3 py-1 d-inline-flex align-items-center">Back to Student List</a>

    </div>
</div>

<!-- Include Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">

<!-- Optional custom styling -->
<style>
    .bg-white {
        background-color: #ffffff;
    }
    .shadow-lg {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }
    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004085;
    }
    .text-danger {
        color: #dc3545;
    }
    .text-dark {
        color: #343a40;
    }
    .text-muted {
        color: #6c757d;
    }
</style>