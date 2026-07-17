@extends('layout.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/createuser.css') }}">
@endpush

@section('konten')
    {{-- LOADING OVERLAY --}}
    <div id="loadingOverlay">
        <div class="spinner-box">
            <div class="spinner"></div>
            <div class="loading-text">Menyimpan data...</div>
        </div>
    </div>
    @if (session('success'))
        <div id="successAlert">
            {{ session('success') }}
        </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-12 col-lg-7">

            {{-- HEADER --}}
            <div class="page-header">
                <div>
                    <div class="page-header-title">
                        <i class="bi bi-person-plus me-2"></i>Tambah User
                    </div>
                    <div class="page-header-sub">Buat akun pengguna baru dan atur hak aksesnya</div>
                </div>
                <a href="/User" class="btn-back">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>

            {{-- FORM CARD --}}
            <div class="form-card">

                <div class="form-card-header">
                    <div class="form-card-header-icon">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div>
                        <div class="form-card-header-title">Informasi Akun</div>
                        <div class="form-card-header-sub">Isi data di bawah untuk membuat akun baru</div>
                    </div>
                </div>

                <form action="{{ url('/User/store') }}" method="POST">
                    @csrf
                    <div class="form-body">

                        {{-- NAMA --}}
                        <div class="mb-3">
                            <label class="field-label">Nama lengkap</label>
                            <div class="field-input-wrap">
                                <i class="bi bi-person field-input-icon"></i>
                                <input type="text" name="name"
                                    class="field-input has-icon {{ $errors->has('name') ? 'is-error' : '' }}"
                                    placeholder="Contoh: Syahira Marani" value="{{ old('name') }}" required>
                            </div>
                            @error('name')
                                <div class="field-error"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- EMAIL --}}
                        <div class="mb-3">
                            <label class="field-label">Email</label>
                            <div class="field-input-wrap">
                                <i class="bi bi-envelope field-input-icon"></i>
                                <input type="email" name="email"
                                    class="field-input has-icon {{ $errors->has('email') ? 'is-error' : '' }}"
                                    placeholder="Contoh: syahira@email.com" value="{{ old('email') }}" required>
                            </div>
                            @error('email')
                                <div class="field-error"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- PASSWORD --}}
                        <div class="mb-3">
                            <label class="field-label">Password</label>
                            <div class="field-input-wrap">
                                <i class="bi bi-lock field-input-icon"></i>
                                <input type="password" name="password" id="passwordInput"
                                    class="field-input has-icon {{ $errors->has('password') ? 'is-error' : '' }}"
                                    placeholder="Minimal 8 karakter" required>
                                <button type="button" class="toggle-pass" onclick="togglePassword()">
                                    <i class="bi bi-eye" id="toggleIcon"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="field-error"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="divider">

                        {{-- ROLE --}}
                        <div class="mb-3">
                            <div class="section-title">Role</div>
                            <div class="role-grid">

                                <input type="radio" name="role" id="role_admin" value="admin" class="role-option"
                                    {{ old('role', 'admin') == 'admin' ? 'checked' : '' }}>
                                <label for="role_admin" class="role-label">
                                    <div class="role-icon"><i class="bi bi-shield-check"></i></div>
                                    Admin
                                </label>

                                <input type="radio" name="role" id="role_p4m" value="p4m" class="role-option"
                                    {{ old('role') == 'p4m' ? 'checked' : '' }}>
                                <label for="role_p4m" class="role-label">
                                    <div class="role-icon"><i class="bi bi-briefcase"></i></div>
                                    P4M
                                </label>

                                <input type="radio" name="role" id="role_kajur" value="kajur" class="role-option"
                                    {{ old('role') == 'kajur' ? 'checked' : '' }}>
                                <label for="role_kajur" class="role-label">
                                    <div class="role-icon"><i class="bi bi-building-fill"></i></div>
                                    Kajur
                                </label>

                                <input type="radio" name="role" id="role_kaprodi" value="kaprodi" class="role-option"
                                    {{ old('role') == 'kaprodi' ? 'checked' : '' }}>
                                <label for="role_kaprodi" class="role-label">
                                    <div class="role-icon"><i class="bi bi-diagram-3"></i></div>
                                    Kaprodi
                                </label>

                            </div>
                        </div>

                        {{-- STATUS --}}
                        <div class="mb-1">
                            <div class="section-title">Status akun</div>
                            <div class="status-grid">

                                <input type="radio" name="status" id="status_active" value="active"
                                    class="status-option" {{ old('status', 'active') == 'active' ? 'checked' : '' }}>
                                <label for="status_active" class="status-label">
                                    <i class="bi bi-circle-fill" style="font-size:7px;"></i> Active
                                </label>

                                <input type="radio" name="status" id="status_verify" value="verify"
                                    class="status-option" {{ old('status') == 'verify' ? 'checked' : '' }}>
                                <label for="status_verify" class="status-label">
                                    <i class="bi bi-clock"></i> Verify
                                </label>

                                <input type="radio" name="status" id="status_banned" value="banned"
                                    class="status-option" {{ old('status') == 'banned' ? 'checked' : '' }}>
                                <label for="status_banned" class="status-label">
                                    <i class="bi bi-slash-circle"></i> Banned
                                </label>

                            </div>
                        </div>

                    </div>

                    {{-- FOOTER --}}
                    <div class="form-footer">
                        <a href="/User" class="btn-outline">
                            <i class="bi bi-x-lg"></i> Batal
                        </a>
                        <button type="submit" class="btn-purple">
                            <i class="bi bi-check-lg"></i> Simpan User
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const icon = document.getElementById('toggleIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }
        const form = document.querySelector("form[action*='User/store']");
        const overlay = document.getElementById("loadingOverlay");

        if (form) {
            form.addEventListener("submit", function() {
                overlay.style.display = "flex";
            });
        }
        setTimeout(() => {
            const alert = document.getElementById('successAlert');
            if (alert) {
                alert.style.display = 'none';
            }
        }, 3000);
    </script>
@endsection
