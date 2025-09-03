@canany(['edit_loans', 'delete_loans', 'view_loans'])
<div class="dropdown d-inline-block user-dropdown">
    <button type="button" class="btn text-dark waves-effect" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="d-xl-inline-block ms-1">
            <div class="badge bg-info-subtle text-info font-size-12"><i class="fa fa-cog"></i></div>
        </span>
        <i class="fa fa-angle-down"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-end p-2">
        @can('edit_loans')
        <a class="dropdown-item" href="{{ route('admin.loans.edit', $query->id) }}">
            <i class="fa fa-edit"></i> Edit
        </a>
        @endcan

        @can('delete_loans')
        <a class="dropdown-item" href="javascript:void(0)" onclick="deleteLoan('id', '{{ $query->id }}', '{{ $tableName }}')">
            <i class="fa fa-trash"></i> Delete
        </a> 
        @endcan 
        @can('view_loans')
        <a class="dropdown-item" href="{{ route('admin.loans.history', ['loan' => $query->id, 'user' => $query->user_id]) }}">
            <i class="fa fa-search"></i> History
        </a>

        @endcan
       
    </div>
</div>
@endcanany
 