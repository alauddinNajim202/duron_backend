@extends('backend.app', ['title' => 'Update Role'])

@section('content')

<!--app-content open-->
<div class="app-content main-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <div class="page-header">
                <div>
                    <h1 class="page-title">Role</h1>
                </div>
                <div class="ms-auto pageheader-btn">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Access</li>
                        <li class="breadcrumb-item">Role</li>
                        <li class="breadcrumb-item">Update</li>
                    </ol>
                </div>
            </div>

            <div class="row" id="user-profile">
                <div class="col-lg-12">

                    <div class="tab-content">
                        <div class="tab-pane active show" id="editProfile">
                            <div class="card">
                                <div class="card-body border-0">
                                    <form class="form form-horizontal" action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Role Name</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $role->name }}">
                                            @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="guard_name" class="form-label">Guard Name</label>
                                            <select class="form-control @error('guard_name') is-invalid @enderror" id="guard_name" name="guard_name">
                                                <option value="web" {{ $role->guard_name == 'web' ? 'selected' : '' }}>Web</option>
                                                <option value="api" {{ $role->guard_name == 'api' ? 'selected' : '' }}>API</option>
                                            </select>
                                            @error('guard_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                                <div>
                                                    <h5 class="mb-0 fw-bold text-dark">
                                                        <i class="fa-solid fa-shield-halved me-1 text-primary"></i> Module Permissions
                                                        <span class="badge bg-primary rounded-pill ms-2">{{ $totalPermissions ?? 0 }}</span>
                                                    </h5>
                                                    <small class="text-muted">Select specific permissions or entire module groups below</small>
                                                </div>
                                                <div>
                                                    <button type="button" class="btn btn-sm btn-outline-primary me-1" id="selectAllPermissions">
                                                        <i class="fa-solid fa-check-double me-1"></i> Select All
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAllPermissions">
                                                        <i class="fa-solid fa-xmark me-1"></i> Deselect All
                                                    </button>
                                                </div>
                                            </div>

                                            @foreach ($permissionGroups as $groupName => $groupData)
                                                <div class="card mb-3 border shadow-none group-card">
                                                    <div class="card-header py-2 px-3 bg-light d-flex justify-content-between align-items-center">
                                                        <span class="fw-semibold text-dark">
                                                            <i class="fa-solid {{ $groupData['icon'] }} me-2 text-secondary"></i>
                                                            {{ $groupName }}
                                                            <span class="badge bg-secondary-subtle text-secondary rounded-pill ms-1 font-monospace" style="font-size: 0.75rem;">
                                                                {{ count($groupData['permissions']) }}
                                                            </span>
                                                        </span>
                                                        <div class="form-check m-0">
                                                            <input class="form-check-input group-select-all" type="checkbox" id="group-{{ Str::slug($groupName) }}">
                                                            <label class="form-check-label text-muted small fw-normal user-select-none" for="group-{{ Str::slug($groupName) }}" style="cursor: pointer;">
                                                                Select Group
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="card-body p-3">
                                                        <div class="row g-2">
                                                            @foreach ($groupData['permissions'] as $permission)
                                                                <div class="col-md-3 col-sm-6">
                                                                    <div class="form-check py-1">
                                                                        <input class="form-check-input permission-checkbox" 
                                                                               type="checkbox" 
                                                                               name="permissions[]" 
                                                                               value="{{ $permission->name }}" 
                                                                               id="perm-{{ $permission->id }}" 
                                                                               @if($role->hasPermissionTo($permission->name, $permission->guard_name)) checked @endif>
                                                                        <label class="form-check-label text-capitalize small" for="perm-{{ $permission->id }}" style="cursor: pointer;">
                                                                            {{ $permission->name }}
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <button type="submit" class="submit btn btn-primary mt-2">Update</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- CONTAINER CLOSED -->
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectAllBtn = document.getElementById('selectAllPermissions');
        const deselectAllBtn = document.getElementById('deselectAllPermissions');
        const allCheckboxes = document.querySelectorAll('.permission-checkbox');
        const groupCheckboxes = document.querySelectorAll('.group-select-all');

        if (selectAllBtn) {
            selectAllBtn.addEventListener('click', function () {
                allCheckboxes.forEach(cb => cb.checked = true);
                groupCheckboxes.forEach(cb => {
                    cb.checked = true;
                    cb.indeterminate = false;
                });
            });
        }

        if (deselectAllBtn) {
            deselectAllBtn.addEventListener('click', function () {
                allCheckboxes.forEach(cb => cb.checked = false);
                groupCheckboxes.forEach(cb => {
                    cb.checked = false;
                    cb.indeterminate = false;
                });
            });
        }

        document.querySelectorAll('.group-card').forEach(card => {
            const groupToggle = card.querySelector('.group-select-all');
            const cardCheckboxes = card.querySelectorAll('.permission-checkbox');

            if (groupToggle) {
                groupToggle.addEventListener('change', function () {
                    cardCheckboxes.forEach(cb => cb.checked = groupToggle.checked);
                });

                cardCheckboxes.forEach(cb => {
                    cb.addEventListener('change', function () {
                        const allChecked = Array.from(cardCheckboxes).every(c => c.checked);
                        const someChecked = Array.from(cardCheckboxes).some(c => c.checked);
                        groupToggle.checked = allChecked;
                        groupToggle.indeterminate = someChecked && !allChecked;
                    });
                });

                // Set initial state based on checked permissions
                const allChecked = Array.from(cardCheckboxes).length > 0 && Array.from(cardCheckboxes).every(c => c.checked);
                const someChecked = Array.from(cardCheckboxes).some(c => c.checked);
                groupToggle.checked = allChecked;
                groupToggle.indeterminate = someChecked && !allChecked;
            }
        });
    });
</script>
@endpush