<!-- auth/login.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 col-xl-5">
            <div class="card card-custom animate-on-scroll" style="border-top: 4px solid #4e73df;">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <img src="https://img.icons8.com/color/96/000000/dumpling.png" alt="Dumpling" width="80">
                        <h2 class="mt-3 fw-bold text-primary">Dumpling Kasir</h2>
                        <p class="text-muted">Silakan masuk untuk melanjutkan</p>
                    </div>

                    <form action="{{ url('/login') }}" method="POST">
                        @csrf

                        @if($errors->has('login'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ $errors->first('login') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        <div class="mb-4">
                            <label for="username" class="form-label fw-semibold">Username</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-person-fill text-muted"></i>
                                </span>
                                <input type="text" name="username" id="username" class="form-control border-start-0" required autofocus>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-lock-fill text-muted"></i>
                                </span>
                                <input type="password" name="password" id="password" class="form-control border-start-0" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="bi bi-eye-fill"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                                <i class="bi bi-box-arrow-in-right me-2"></i> Masuk
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="text-center mt-4 text-muted">
                <small>© {{ date('Y') }} Dumpling Kasir. All rights reserved.</small>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const passwordInput = this.previousElementSibling;
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            this.querySelector('i').classList.toggle('bi-eye-fill');
            this.querySelector('i').classList.toggle('bi-eye-slash-fill');
        });
    });
</script>
@endpush
@endsection