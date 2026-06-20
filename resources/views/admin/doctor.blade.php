@extends('layouts.admin')

@section('title', 'Employees')
@section('page-title', 'Employees')

@section('content')
    <div class="page-header">
        <div class="page-title-group"><h4>Employees</h4></div>
        <div class="header-actions">
            <a href="{{ route('admin.doctor.export') }}{{ request('search') ? '?search='.urlencode(request('search')) : '' }}" class="btn-theme-teal">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </div>
    </div>

    <div class="filter-bar">
        <div class="search-wrap">
            <i class="fas fa-search search-icon"></i>
            <input type="text" id="liveSearch" value="{{ request('search') }}" class="filter-input"
                   placeholder="Search by name or Employee Code..." autocomplete="off">
            <span class="search-spinner" id="searchSpinner"></span>
        </div>
    </div>

    <div class="glass-card desktop-view">
        <div class="table-wrap">
            <table class="doc-table">
                <thead>
                <tr>
                    <th>SR NO.</th>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Employee Code</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($doctors as $index => $doctor)
                    <tr>
                        <td class="serial-cell">{{ $doctors->firstItem() + $index }}</td>
                        <td>
                            @if($doctor->photo_url)
                                <img src="{{ $doctor->photo_url }}" class="photo-thumb"
                                     onclick='openPhotoModal(@json($doctor->photo_url), @json($doctor->doctor_name), @json($doctor->employee_code))'
                                     alt="{{ $doctor->doctor_name }}">
                            @else
                                <span class="text-muted-sm">—</span>
                            @endif
                        </td>
                        <td><span class="doc-name-text">{{ $doctor->doctor_name }}</span></td>
                        <td><span class="badge-mono emp">{{ $doctor->employee_code }}</span></td>
                        <td>
                            <div class="action-btns">
                                @if($doctor->banner_url)
                                    <a class="act-btn banner-btn" href="{{ route('download.banner', $doctor->id) }}" title="Download Card">
                                        <i class="fas fa-download"></i>
                                    </a>
                                @endif
                                <form action="{{ route('admin.doctor.destroy', $doctor->id) }}" method="POST" class="delete-form">
                                    @csrf
                                    <button type="button" class="act-btn del btn-delete" data-name="{{ $doctor->doctor_name }}" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5"><div class="empty-state"><h5>No records found</h5></div></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @includeWhen($doctors->hasPages(), 'admin.partials.employee-pagination', ['doctors' => $doctors])
    </div>

    <div class="mobile-view">
        @forelse($doctors as $index => $doctor)
            <div class="m-card">
                <div class="m-card-header">
                    @if($doctor->photo_url)
                        <img src="{{ $doctor->photo_url }}" class="m-card-photo"
                             onclick='openPhotoModal(@json($doctor->photo_url), @json($doctor->doctor_name), @json($doctor->employee_code))'
                             alt="{{ $doctor->doctor_name }}">
                    @else
                        <div class="m-card-av c1">{{ strtoupper(substr($doctor->doctor_name, 0, 1)) }}</div>
                    @endif
                    <div class="m-card-title">
                        <div class="m-card-name">{{ $doctor->doctor_name }}</div>
                        <div class="m-card-sub">Employee Code: {{ $doctor->employee_code }}</div>
                    </div>
                </div>
                <div class="m-card-footer">
                    @if($doctor->banner_url)
                        <a class="m-media-btn m-btn-banner" href="{{ route('download.banner', $doctor->id) }}">
                            <i class="fas fa-download"></i> Download Card
                        </a>
                    @endif
                    <form action="{{ route('admin.doctor.destroy', $doctor->id) }}" method="POST" class="delete-form">
                        @csrf
                        <button type="button" class="btn-del-mobile btn-delete" data-name="{{ $doctor->doctor_name }}">
                            <i class="fas fa-trash-alt"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="glass-card"><div class="empty-state"><h5>No records found</h5></div></div>
        @endforelse
    </div>

    <div class="media-modal-overlay" id="mediaModal" onclick="if(event.target === this) closePhotoModal()">
        <div class="media-modal-box">
            <button class="media-modal-close" onclick="closePhotoModal()"><i class="fas fa-times"></i></button>
            <div class="media-modal-tabs"><span class="media-tab-badge active-banner">Photo</span></div>
            <div id="mediaContent"></div>
            <div class="media-modal-name" id="mediaName"></div>
            <div class="media-modal-empid" id="mediaEmpId"></div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function openPhotoModal(photoUrl, name, employeeCode) {
        document.getElementById('mediaContent').innerHTML = `<img src="${photoUrl}" alt="">`;
        document.getElementById('mediaName').textContent = name || '';
        document.getElementById('mediaEmpId').textContent = 'Employee Code: ' + (employeeCode || '');
        document.getElementById('mediaModal').classList.add('open');
    }

    function closePhotoModal() {
        document.getElementById('mediaModal').classList.remove('open');
    }

    document.addEventListener('keydown', event => {
        if (event.key === 'Escape') closePhotoModal();
    });

    document.addEventListener('click', function (event) {
        const button = event.target.closest('.btn-delete');
        if (!button) return;
        const form = button.closest('.delete-form');
        Swal.fire({
            title: 'Delete record?',
            text: `Delete ${button.dataset.name || 'this record'}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Delete',
            confirmButtonColor: '#e74a3b'
        }).then(result => { if (result.isConfirmed) form.submit(); });
    });

    (function () {
        const input = document.getElementById('liveSearch');
        const spinner = document.getElementById('searchSpinner');
        let timer;
        input.addEventListener('keyup', function () {
            clearTimeout(timer);
            spinner.style.display = 'block';
            const query = this.value.trim();
            timer = setTimeout(() => {
                const base = @json(route('admin.doctor.index'));
                window.location.href = query ? base + '?search=' + encodeURIComponent(query) : base;
            }, 400);
        });
    })();
</script>
@endpush
