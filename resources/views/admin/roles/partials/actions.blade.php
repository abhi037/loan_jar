@canany(['edit_roles', 'delete_roles', 'view_role-permission'])
<div class="dropdown d-inline-block user-dropdown">
    <button type="button" class="btn text-dark waves-effect" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="d-xl-inline-block ms-1">
            <div class="badge bg-info-subtle text-info font-size-12"><i class="fa fa-cog"></i></div>
        </span>
        <i class="fa fa-angle-down"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-end p-2">
        @can('edit_roles')
        <a class="dropdown-item" href="{{ route('admin.role.edit', $query->id) }}">
            <i class="fa fa-edit"></i> Edit
        </a>
        @endcan

        @can('delete_roles')
        <a class="dropdown-item" href="javascript:void(0)" onclick="deleteRole('id', '{{ $query->id }}', '{{ $tableName }}')">
            <i class="fa fa-trash"></i> Delete
        </a> 
        @endcan

        @can('view_role-permission')
        <a class="dropdown-item" href="{{ route('admin.rolepermissions.show', $query->id) }}">
            <i class="fa fa-eye"></i> Permissions
        </a>
        @endcan
    </div>
</div>
@endcanany
