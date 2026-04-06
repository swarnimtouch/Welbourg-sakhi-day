@extends('layouts.admin')

@section('title', 'Doctors')
@section('page-title', 'Doctors')

@push('styles')

@endpush

@section('content')

    <div class="page-header">
        <div class="page-title-group">
            <h4>Doctors</h4>
        </div>
        <a href="{{ route('admin.doctor.export') }}{{ request('search') ? '?search='.request('search') : '' }}"
           class="btn-add btn-export">
            <i class="fas fa-file-excel"></i> Export Excel
        </a>
        <button id="downloadAllBtn" class="btn btn-primary">
            Download All Banners
        </button>

    </div>

    @if(session('success'))
        <div class="alert-success-bar">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="filter-bar">
        <div class="search-wrap">
            <i class="fas fa-search search-icon"></i>
            <input type="text"
                   id="liveSearch"
                   value="{{ request('search') }}"
                   class="filter-input"
                   placeholder="Type to search by name or Employee ID..."
                   autocomplete="off">
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
                    <th class="th-doctor">Doctor Name</th>
                    <th class="th-doctor">Qualification</th>
                    <th class="th-doctor">Mobile Number</th>
                    <th class="th-employee">Employee Name</th>
                    <th class="th-employee">Employee Code</th>
                    <th class="th-employee">Employee hq</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($doctors as $index => $doctor)
                    @php
                        $colors    = ['c1','c2','c3','c4','c5'];
                        $c         = $colors[$index % 5];
                        $photoUrl  = $doctor->photo_url;
                        $bannerUrl = $doctor->banner_url;
                        $videoUrl  = $doctor->video_url ?? null;
                    @endphp
                    <tr>
                        <td class="serial-cell">{{ $doctors->firstItem() + $index }}</td>

                        <td>
                            @if($photoUrl)
                                <img src="{{ $photoUrl }}"
                                     class="photo-thumb"
                                     onclick="openMediaModal('photo', '{{ $photoUrl }}', null, '{{ addslashes($doctor->doctor_name) }}', '{{ $doctor->employee_code }}')"
                                     alt="{{ $doctor->doctor_name }}"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';">
                                <span class="text-muted-sm" style="display:none;">—</span>
                            @else
                                <span class="text-muted-sm">—</span>
                            @endif
                        </td>


                        <td>
                            <div class="doc-name-cell">
                                <span class="doc-name-text">{{$doctor->doctor_prefix.''. $doctor->doctor_name }}</span>
                            </div>
                        </td>
                        <td><span class="badge-mono">{{ $doctor->doctor_qualification ?? '—' }}</span></td>
                        <td class="text-muted-sm">{{ $doctor->doctor_phone ?? '—' }}</td>
                        <td style="font-weight:500;">{{ $doctor->employee_name ?? '—' }}</td>
                        <td><span class="badge-mono emp">{{ $doctor->employee_code ?? '—' }}</span></td>
                        <td><span class="badge-mono emp">{{ $doctor->employee_hq ?? '—' }}</span></td>

                        <td class="text-muted-sm" style="font-size:0.75rem; white-space:nowrap;">
                            {{ $doctor->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}
                        </td>

                        <td>
                            <div class="action-btns">

                                @if($bannerUrl)
                                    <button type="button"
                                            class="act-btn banner-btn"
                                            onclick="openMediaModal(
                                                    'banner',
                                                    null,
                                                    '{{ $bannerUrl }}',
                                                    '{{ addslashes($doctor->doctor_name) }}',
                                                    '{{ $doctor->employee_code ?? '' }}',
                                                    null,
                                                    '{{ $doctor->id }}',
                                                    null
                                                )"
                                            title="View Banner">
                                        <i class="fas fa-image"></i>
                                    </button>
                                @else
                                    <button type="button" class="act-btn media-disabled" title="No Banner" disabled>
                                        <i class="fas fa-image"></i>
                                    </button>
                                @endif

                                <form action="{{ route('admin.doctor.destroy', $doctor->id) }}"
                                      method="POST"
                                      class="delete-form">
                                    @csrf
                                    <button type="button"
                                            class="act-btn del btn-delete"
                                            data-name="{{ $doctor->doctor_name }}"
                                            title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="fas fa-user-md"></i>
                                <h5>No records found</h5>
                                <p>No doctors have been added yet.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($doctors->hasPages())
            <div class="pagination-wrap">
                <div class="page-info">
                    Showing {{ $doctors->firstItem() }}–{{ $doctors->lastItem() }} of {{ $doctors->total() }}
                </div>
                <div class="custom-pagination">
                    @if($doctors->onFirstPage())
                        <span class="page-btn" style="opacity:0.4;cursor:not-allowed;"><i class="fas fa-chevron-left"></i></span>
                    @else
                        <a href="{{ $doctors->previousPageUrl() }}" class="page-btn"><i class="fas fa-chevron-left"></i></a>
                    @endif
                    @foreach($doctors->getUrlRange(1, $doctors->lastPage()) as $page => $url)
                        <a href="{{ $url }}" class="page-btn {{ $page == $doctors->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                    @endforeach
                    @if($doctors->hasMorePages())
                        <a href="{{ $doctors->nextPageUrl() }}" class="page-btn"><i class="fas fa-chevron-right"></i></a>
                    @else
                        <span class="page-btn" style="opacity:0.4;cursor:not-allowed;"><i class="fas fa-chevron-right"></i></span>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- MOBILE VIEW --}}
    <div class="mobile-view">

        @forelse($doctors as $index => $doctor)
            @php
                $colors    = ['c1','c2','c3','c4','c5'];
                $c         = $colors[$index % 5];
                $photoUrl  = $doctor->photo_url;
                $bannerUrl = $doctor->banner_url;
                $videoUrl  = $doctor->video_url ?? null;
            @endphp

            <div class="m-card" style="animation-delay:{{ $index * 0.04 }}s;">

                <div class="m-card-header">
                    @if($photoUrl)
                        <img src="{{ $photoUrl }}"
                             class="m-card-photo"
                             onclick="openMediaModal('photo', '{{ $photoUrl }}', null, '{{ addslashes($doctor->doctor_name) }}', '{{ $doctor->employee_code }}')"
                             alt="{{ $doctor->doctor_name }}"
                             onerror="this.outerHTML='<div class=\'m-card-av {{ $c }}\'>{{ strtoupper(substr($doctor->doctor_name,0,1)) }}</div>'">
                    @else
                        <div class="m-card-av {{ $c }}">{{ strtoupper(substr($doctor->doctor_name, 0, 1)) }}</div>
                    @endif
                    <div class="m-card-title">
                        <div class="m-card-name">{{ $doctor->doctor_prefix.''.$doctor->doctor_name }}</div>
                        <div class="m-card-sub">{{ $doctor->doctor_hospital ?? '' }}</div>
                    </div>
                    <span class="m-card-serial-badge">#{{ $doctors->firstItem() + $index }}</span>
                </div>

                <div class="m-media-btns">
                    @if($bannerUrl)
                        <button type="button"
                                class="m-media-btn m-btn-banner"
                                onclick="openMediaModal(
                                    'banner',
                                    null,
                                    '{{ $bannerUrl }}',
                                    '{{ addslashes($doctor->doctor_name) }}',
                                    '{{ $doctor->employee_code ?? '' }}',
                                    null,
                                    '{{ $doctor->id }}',
                                    null
                                )">
                            <i class="fas fa-image"></i> View Banner
                        </button>
                    @else
                        <button type="button" class="m-media-btn m-btn-disabled" disabled>
                            <i class="fas fa-image"></i> No Banner
                        </button>
                    @endif

                </div>

                <div class="m-section-label banner">
                    <i class="fas fa-user-md"></i> Doctor Details
                </div>
                <div class="m-card-body">
                    <div class="m-fields-grid">
                        <div class="m-field">
                            <div class="m-field-label"><i class="fas fa-graduation-cap"></i> Degree</div>
                            <div class="m-field-value {{ $doctor->doctor_qualification ? '' : 'muted' }}">{{ $doctor->doctor_qualification ?? 'Not set' }}</div>
                        </div>
                        <div class="m-field">
                            <div class="m-field-label"><i class="fas fa-phone"></i> Mobile Number</div>
                            <div class="m-field-value {{ $doctor->doctor_phone ? '' : 'muted' }}">{{ $doctor->doctor_phone ?? 'Not set' }}</div>
                        </div>
                    </div>
                </div>

                <div class="m-section-label employee">
                    <i class="fas fa-id-card"></i> Employee Details
                </div>
                <div class="m-card-body">
                    <div class="m-fields-grid">
                        <div class="m-field">
                            <div class="m-field-label"><i class="fas fa-user"></i> Employee Name</div>
                            <div class="m-field-value">{{ $doctor->employee_name ?? '—' }}</div>
                        </div>
                        <div class="m-field">
                            <div class="m-field-label"><i class="fas fa-id-badge"></i> Employee Code</div>
                            <div class="m-field-value mono-emp">{{ $doctor->employee_code ?? '—' }}</div>
                        </div>
                        <div class="m-field">
                            <div class="m-field-label"><i class="fas fa-id-badge"></i> Employee Hq</div>
                            <div class="m-field-value mono-emp">{{ $doctor->employee_hq ?? '—' }}</div>
                        </div>
                    </div>
                </div>

                <div class="m-card-footer">
                    <div class="m-card-date">
                        <i class="fas fa-calendar me-1"></i>
                        {{ $doctor->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}
                    </div>
                    <form action="{{ route('admin.doctor.destroy', $doctor->id) }}"
                          method="POST"
                          class="delete-form">
                        @csrf
                        <button type="button"
                                class="btn-del-mobile btn-delete"
                                data-name="{{ $doctor->doctor_name }}">
                            <i class="fas fa-trash-alt"></i> Delete
                        </button>
                    </form>
                </div>

            </div>

        @empty
            <div class="glass-card">
                <div class="empty-state">
                    <i class="fas fa-user-md"></i>
                    <h5>No records found</h5>
                    <p>No Banners have been added yet.</p>
                </div>
            </div>
        @endforelse

        @if($doctors->hasPages())
            <div class="pagination-wrap" style="border:none; padding:4px 0 16px;">
                <div class="page-info">{{ $doctors->firstItem() }}–{{ $doctors->lastItem() }} of {{ $doctors->total() }}</div>
                <div class="custom-pagination">
                    @if($doctors->onFirstPage())
                        <span class="page-btn" style="opacity:0.4;cursor:not-allowed;"><i class="fas fa-chevron-left"></i></span>
                    @else
                        <a href="{{ $doctors->previousPageUrl() }}" class="page-btn"><i class="fas fa-chevron-left"></i></a>
                    @endif
                    @foreach($doctors->getUrlRange(1, $doctors->lastPage()) as $page => $url)
                        <a href="{{ $url }}" class="page-btn {{ $page == $doctors->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                    @endforeach
                    @if($doctors->hasMorePages())
                        <a href="{{ $doctors->nextPageUrl() }}" class="page-btn"><i class="fas fa-chevron-right"></i></a>
                    @else
                        <span class="page-btn" style="opacity:0.4;cursor:not-allowed;"><i class="fas fa-chevron-right"></i></span>
                    @endif
                </div>
            </div>
        @endif

    </div>


    {{-- MEDIA MODAL --}}
    <div class="media-modal-overlay" id="mediaModal" onclick="closeMediaModal(event)">
        <div class="media-modal-box">

            <button class="media-modal-close" onclick="closeMediaModalDirect()">
                <i class="fas fa-times"></i>
            </button>

            <div class="media-modal-tabs" id="mediaTabs"></div>
            <div id="mediaContent"></div>
            <div class="media-modal-name"  id="mediaName"></div>
            <div class="media-modal-empid" id="mediaEmpId"></div>
            <div class="media-dl-row" id="mediaDlRow"></div>

        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const downloadBannerRoute = "{{ route('download.banner', ':id') }}";
    </script>
    <script>

        function openMediaModal(type, photoUrl, bannerUrl, name, empCode, videoUrl, bannerId, videoId) {
            const modal   = document.getElementById('mediaModal');
            const content = document.getElementById('mediaContent');
            const tabs    = document.getElementById('mediaTabs');
            const dlRow   = document.getElementById('mediaDlRow');

            document.getElementById('mediaName').textContent  = name     || '';
            document.getElementById('mediaEmpId').textContent = empCode ? 'Employee Code: ' + empCode : '';

            content.innerHTML = '';
            tabs.innerHTML    = '';
            dlRow.innerHTML   = '';

            if (type === 'photo') {
                tabs.innerHTML = `<span class="media-tab-badge active-banner"><i class="fas fa-user"></i> Profile Photo</span>`;
                if (photoUrl && photoUrl !== 'null') {
                    content.innerHTML = `<img src="${photoUrl}" alt="${name}" onerror="this.replaceWith(noMediaPlaceholder('image'))">`;
                } else {
                    content.appendChild(noMediaPlaceholder('image'));
                }

            } else if (type === 'banner') {
                tabs.innerHTML = `<span class="media-tab-badge active-banner"><i class="fas fa-image"></i> Banner Image</span>`;
                if (bannerUrl && bannerUrl !== 'null') {
                    content.innerHTML = `<img src="${bannerUrl}" alt="${name}" onerror="this.replaceWith(noMediaPlaceholder('image'))">`;
                    const downloadUrl = downloadBannerRoute.replace(':id', bannerId);
                    dlRow.innerHTML = `
                        <a href="${downloadUrl}" class="media-dl-btn btn-dl-banner">
                            <i class="fas fa-download"></i> Download Banner
                        </a>`;
                } else {
                    content.appendChild(noMediaPlaceholder('image'));
                }

            } else if (type === 'video') {
                tabs.innerHTML = `<span class="media-tab-badge active-video"><i class="fas fa-video"></i> Video Banner</span>`;
                if (videoUrl && videoUrl !== 'null') {
                    const vid = document.createElement('video');
                    vid.controls   = true;
                    vid.preload    = 'metadata';
                    vid.style.cssText = 'width:100%;max-height:320px;border-radius:12px;background:#000;';

                    const src  = document.createElement('source');
                    src.src    = videoUrl;
                    const ext  = videoUrl.split('.').pop().toLowerCase();
                    src.type   = ext === 'webm' ? 'video/webm' : (ext === 'ogg' ? 'video/ogg' : 'video/mp4');
                    vid.appendChild(src);
                    vid.onerror = () => { content.innerHTML = ''; content.appendChild(noMediaPlaceholder('video')); };
                    content.appendChild(vid);

                    const downloadUrl = downloadVideoRoute.replace(':id', videoId);
                    dlRow.innerHTML = `
                        <a href="${downloadUrl}" class="media-dl-btn btn-dl-video">
                            <i class="fas fa-download"></i> Download Video
                        </a>`;
                } else {
                    content.appendChild(noMediaPlaceholder('video'));
                }
            }

            modal.classList.add('open');
        }

        function noMediaPlaceholder(mediaType) {
            const div = document.createElement('div');
            div.style.cssText = `width:100%;height:160px;border-radius:12px;background:rgba(255,255,255,0.03);
                border:2px dashed rgba(255,255,255,0.08);display:flex;flex-direction:column;
                align-items:center;justify-content:center;color:rgba(255,255,255,0.3);gap:10px;`;
            const icon = mediaType === 'video' ? 'fa-video' : 'fa-image';
            const text = mediaType === 'video' ? 'No video available' : 'No image available';
            div.innerHTML = `<i class="fas ${icon}" style="font-size:2.2rem;opacity:0.22;"></i>
                             <span style="font-size:0.82rem;">${text}</span>`;
            return div;
        }

        function closeMediaModal(e) {
            if (e.target.id === 'mediaModal') closeMediaModalDirect();
        }

        function closeMediaModalDirect() {
            const modal = document.getElementById('mediaModal');
            modal.classList.remove('open');
            const vid = modal.querySelector('video');
            if (vid) { vid.pause(); vid.currentTime = 0; }
        }

        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeMediaModalDirect(); });

        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-delete');
            if (!btn) return;
            e.preventDefault();

            const form    = btn.closest('.delete-form');
            const docName = btn.getAttribute('data-name') || 'this Banner';

            Swal.fire({
                title: 'Delete Banner?',
                html: `Are you sure you want to delete <strong>${docName}</strong>?<br><small style="color:#aaa;">This action cannot be undone.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-trash-alt"></i> Yes, Delete',
                cancelButtonText:  '<i class="fas fa-times"></i> Cancel',
                confirmButtonColor: '#e74a3b',
                cancelButtonColor:  '#4e73df',
                background: '#1a2035',
                color: '#e8eaf6',
                iconColor: '#f6c23e',
                customClass: {
                    popup:         'swal-custom-popup',
                    title:         'swal-custom-title',
                    confirmButton: 'swal-confirm-btn',
                    cancelButton:  'swal-cancel-btn',
                },
                reverseButtons: true,
                focusCancel: true,
            }).then(result => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Deleting...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        background: '#1a2035',
                        color: '#e8eaf6',
                        didOpen: () => Swal.showLoading()
                    });
                    form.submit();
                }
            });
        });

        @if(session('success'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true,
            background: '#1a2035',
            color: '#1cc88a',
            iconColor: '#1cc88a',
        });
        @endif

        (function () {
            const input   = document.getElementById('liveSearch');
            const spinner = document.getElementById('searchSpinner');
            if (!input) return;
            let timer = null;
            input.addEventListener('keyup', function () {
                clearTimeout(timer);
                const query = this.value.trim();
                spinner.style.display = 'block';
                timer = setTimeout(function () {
                    const baseUrl = '{{ route('admin.doctor.index') }}';
                    const url = query.length > 0
                        ? baseUrl + '?search=' + encodeURIComponent(query)
                        : baseUrl;
                    window.location.href = url;
                }, 400);
            });
        })();
    </script>
    <script>
        document.getElementById('downloadAllBtn').addEventListener('click', async function () {
            this.disabled = true;
            this.innerText = 'Fetching...';

            const res  = await fetch('{{ route("admin.banners.downloadAll") }}');
            const files = await res.json();

            let i = 0;
            const interval = setInterval(() => {
                if (i >= files.length) {
                    clearInterval(interval);
                    this.disabled = false;
                    this.innerText = 'Download All Banners';
                    return;
                }

                const a = document.createElement('a');
                a.href     = files[i].url;
                a.download = files[i].filename;
                a.target   = '_blank';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);

                i++;
            }, 800); // ⬅️ 800ms gap — browser block na kare isliye
        });
    </script>

@endpush
