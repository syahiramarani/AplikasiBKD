@extends('layout.master')


@push('styles')
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
@endpush

@section('konten')
    <div class="row">
        <div class="col-12">

            {{-- NOTIFIKASI --}}
            @if (session('success'))
                <div class="alert-sukses">
                    <i class="bi bi-check-circle-fill"></i>
                    {{ session('success') }}
                </div>
            @endif

            {{-- HEADER --}}
            <div class="user-header">
                <div>
                    <div class="user-header-title">
                        <i class="bi bi-people me-2"></i>Manajemen User
                    </div>
                    <div class="user-header-sub">Kelola akun dan hak akses pengguna sistem</div>
                </div>
                <a href="/User/create" class="btn-add-user">
                    <i class="bi bi-plus-lg"></i> Tambah User
                </a>
            </div>

            {{-- STAT CARDS --}}
            <div class="stat-grid">
                <div class="stat-card">
                    <div class="stat-icon purple"><i class="bi bi-people"></i></div>
                    <div>
                        <div class="stat-val">{{ $users->count() }}</div>
                        <div class="stat-lbl">Total user</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon teal"><i class="bi bi-person-check"></i></div>
                    <div>
                        <div class="stat-val">{{ $users->where('status', 'active')->count() }}</div>
                        <div class="stat-lbl">Aktif</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon amber"><i class="bi bi-person-exclamation"></i></div>
                    <div>
                        <div class="stat-val">{{ $users->where('status', 'verify')->count() }}</div>
                        <div class="stat-lbl">Menunggu verifikasi</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon coral"><i class="bi bi-person-slash"></i></div>
                    <div>
                        <div class="stat-val">{{ $users->where('status', 'banned')->count() }}</div>
                        <div class="stat-lbl">Dibanned</div>
                    </div>
                </div>
            </div>

            {{-- MAIN CARD --}}
            <div class="main-card">
                <div class="user-table-wrap">
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th style="width:44px;">No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th style="width:145px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $avatarColors = [
                                    ['bg' => '#EEEDFE', 'color' => '#3C3489'],
                                    ['bg' => '#E1F5EE', 'color' => '#085041'],
                                    ['bg' => '#FAEEDA', 'color' => '#633806'],
                                    ['bg' => '#FAECE7', 'color' => '#712B13'],
                                ];
                            @endphp

                            @foreach ($users as $user)
                                @php
                                    $words = explode(' ', $user->name);
                                    $inisial = strtoupper(
                                        substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''),
                                    );
                                    $c = $avatarColors[$loop->index % 4];
                                @endphp
                                <tr>
                                    <td style="color:#9ca3af;font-size:12px;">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="avatar-cell">
                                            <div class="user-avatar"
                                                style="background:{{ $c['bg'] }};color:{{ $c['color'] }};">
                                                {{ $inisial }}
                                            </div>
                                            {{ $user->name }}
                                        </div>
                                    </td>
                                    <td class="cell-email">{{ $user->email }}</td>
                                    <td>
                                        @php
                                            $roleClass = match ($user->role) {
                                                'admin' => 'badge-role-admin',
                                                'p4m' => 'badge-role-p4m',
                                                'kajur' => 'badge-role-kajur',
                                                'kaprodi' => 'badge-role-kaprodi',
                                                default => 'badge-role-other',
                                            };
                                        @endphp
                                        <span class="badge {{ $roleClass }}">{{ ucfirst($user->role) }}</span>
                                    </td>
                                    <td>
                                        @if ($user->status == 'active')
                                            <span class="badge badge-active">
                                                <i class="bi bi-circle-fill"
                                                    style="font-size:7px;vertical-align:middle;margin-right:3px;"></i>
                                                Active
                                            </span>
                                        @elseif ($user->status == 'verify')
                                            <span class="badge badge-verify">
                                                <i class="bi bi-clock"
                                                    style="font-size:9px;vertical-align:middle;margin-right:3px;"></i>
                                                Verify
                                            </span>
                                        @else
                                            <span class="badge badge-banned">
                                                <i class="bi bi-slash-circle"
                                                    style="font-size:9px;vertical-align:middle;margin-right:3px;"></i>
                                                Banned
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="display:flex;gap:5px;">
                                            <button class="btn-view"
                                                onclick="viewUser('{{ $user->name }}','{{ $user->email }}','{{ $user->role }}','{{ $user->status }}')">
                                                <i class="bi bi-eye"></i> View
                                            </button>
                                            <button class="btn-edit"
                                                onclick="editUser({{ $user->id }},'{{ $user->name }}','{{ $user->email }}','{{ $user->role }}','{{ $user->status }}')">
                                                <i class="bi bi-pencil"></i> Edit
                                            </button>
                                            <a href="#" class="btn-hapus"
                                                onclick="openDeleteModal('{{ $user->id }}')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    {{-- MODAL VIEW --}}
    <div class="modal fade" id="viewModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content modal-content-custom">
                <div class="modal-header modal-header-purple">
                    <h5 class="modal-title" style="color:#EEEDFE;font-size:16px;font-weight:500;">
                        <i class="bi bi-person-circle me-2"></i>Detail User
                    </h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding:1.25rem;">
                    {{-- Avatar besar --}}
                    <div
                        style="display:flex;align-items:center;gap:12px;margin-bottom:1.25rem;padding-bottom:1rem;border-bottom:0.5px solid #f3f4f6;">
                        <div id="view_avatar"
                            style="width:46px;height:46px;border-radius:50%;background:#EEEDFE;color:#3C3489;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:500;flex-shrink:0;">
                        </div>
                        <div>
                            <div id="view_name" style="font-size:15px;font-weight:500;color:#111827;"></div>
                            <div id="view_email" style="font-size:12px;color:#6b7280;margin-top:2px;"></div>
                        </div>
                    </div>
                    <div class="detail-row">
                        <span class="detail-key">Role</span>
                        <span class="detail-val" id="view_role_badge"></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-key">Status</span>
                        <span class="detail-val" id="view_status_badge"></span>
                    </div>
                </div>
                <div class="modal-footer-custom">
                    <button class="btn-outline" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT --}}
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content modal-content-custom">
                <div class="modal-header modal-header-purple">
                    <h5 class="modal-title" style="color:#EEEDFE;font-size:16px;font-weight:500;">
                        <i class="bi bi-pencil-square me-2"></i>Edit User
                    </h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="formEdit">
                    @csrf
                    <div class="modal-body" style="padding:1.25rem;">
                        <div class="mb-3">
                            <label class="modal-label">Nama</label>
                            <input type="text" name="name" id="edit_name" class="modal-input">
                        </div>
                        <div class="mb-3">
                            <label class="modal-label">Email</label>
                            <input type="email" name="email" id="edit_email" class="modal-input">
                        </div>
                        <div class="mb-3">
                            <label class="modal-label">Role</label>
                            <select name="role" id="edit_role" class="modal-input" style="height:36px;">
                                <option value="admin">Admin</option>
                                <option value="p4m">P4M</option>
                                <option value="kajur">Kajur</option>
                                <option value="kaprodi">Kaprodi</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="modal-label">Status</label>
                            <select name="status" id="edit_status" class="modal-input" style="height:36px;">
                                <option value="active">Active</option>
                                <option value="verify">Verify</option>
                                <option value="banned">Banned</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer-custom">
                        <button type="button" class="btn-outline" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn-purple">Simpan perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="deleteOverlay" class="delete-overlay">
        <div class="delete-box">
            <h5>Yakin ingin menghapus user ini?</h5>
            <p>Data akan dihapus permanen</p>

            <div class="delete-action">
                <a id="btnConfirmDelete" class="btn btn-danger">Hapus</a>
                <button onclick="closeDeleteModal()" class="btn btn-secondary">Batal</button>
            </div>
        </div>
    </div>
    <div id="deleteOverlay" class="delete-overlay">
        <div class="delete-box">
            <h5>Yakin ingin menghapus user ini?</h5>
            <p>Data akan dihapus permanen</p>

            <div class="delete-action">
                <a id="btnConfirmDelete" class="btn btn-danger">Hapus</a>
                <button onclick="closeDeleteModal()" class="btn btn-secondary">Batal</button>
            </div>
        </div>
    </div>

    <script>
        function viewUser(name, email, role, status) {

            const words = name.trim().split(' ');
            const inisial = (words[0][0] + (words[1] ? words[1][0] : '')).toUpperCase();

            document.getElementById('view_avatar').innerText = inisial;
            document.getElementById('view_name').innerText = name;
            document.getElementById('view_email').innerText = email;

            const roleColors = {
                admin: {
                    bg: '#EEEDFE',
                    color: '#3C3489'
                },
                p4m: {
                    bg: '#FAEEDA',
                    color: '#633806'
                },
                kajur: {
                    bg: '#E1F5EE',
                    color: '#085041'
                },
                kaprodi: {
                    bg: '#FAECE7',
                    color: '#712B13'
                }
            };

            const rc = roleColors[role] || {
                bg: '#f3f4f6',
                color: '#6b7280'
            };

            document.getElementById('view_role_badge').innerHTML =
                `<span style="display:inline-block;padding:3px 9px;border-radius:100px;font-size:11px;font-weight:500;background:${rc.bg};color:${rc.color};">
                ${role.charAt(0).toUpperCase() + role.slice(1)}
            </span>`;

            const statusMap = {
                active: {
                    bg: '#E1F5EE',
                    color: '#085041',
                    icon: '●',
                    label: 'Active'
                },
                verify: {
                    bg: '#FAEEDA',
                    color: '#854F0B',
                    icon: '⏱',
                    label: 'Verify'
                },
                banned: {
                    bg: '#FAECE7',
                    color: '#712B13',
                    icon: '⊘',
                    label: 'Banned'
                }
            };

            const sc = statusMap[status] || {
                bg: '#f3f4f6',
                color: '#6b7280',
                icon: '',
                label: status
            };

            document.getElementById('view_status_badge').innerHTML =
                `<span style="display:inline-block;padding:3px 9px;border-radius:100px;font-size:11px;font-weight:500;background:${sc.bg};color:${sc.color};">
                ${sc.icon} ${sc.label}
            </span>`;

            new bootstrap.Modal(document.getElementById('viewModal')).show();
        }

        function editUser(id, name, email, role, status) {

            document.getElementById('edit_name').value = name;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_role').value = role;
            document.getElementById('edit_status').value = status;

            document.getElementById('formEdit').action = '/User/update/' + id;

            new bootstrap.Modal(document.getElementById('editModal')).show();
        }

        function openDeleteModal(id) {
            document.getElementById('deleteOverlay').style.display = 'flex';
            document.getElementById('btnConfirmDelete').href = '/user/delete/' + id;
        }

        function closeDeleteModal() {
            document.getElementById('deleteOverlay').style.display = 'none';
        }

        /* =========================
           LOADING OVERLAY FIX
        ========================= */
        const formEdit = document.getElementById('formEdit');

        if (formEdit) {
            formEdit.addEventListener('submit', function() {

                const loading = document.getElementById('loadingOverlay');
                if (loading) {
                    loading.style.display = 'flex';
                }
            });
        }

        /* =========================
           SUCCESS POPUP FIX
        ========================= */
        document.addEventListener("DOMContentLoaded", function() {

            @if (session('success'))

                setTimeout(() => {

                    const loading = document.getElementById('loadingOverlay');
                    const popup = document.getElementById('successPopup');
                    const text = document.getElementById('successText');

                    // sembunyikan loading kalau masih ada
                    if (loading) {
                        loading.style.display = 'none';
                    }

                    // tampilkan popup sukses
                    if (popup && text) {
                        text.innerText = "{{ session('success') }}";
                        popup.style.display = 'flex';

                        setTimeout(() => {
                            popup.style.display = 'none';
                        }, 2000);
                    }

                }, 200);
            @endif
        });
    </script>
@endsection
