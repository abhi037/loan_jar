@canany(['view_user_dashboard'])
<div class="dropdown d-inline-block user-dropdown">
    <button type="button" class="btn text-dark waves-effect" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="d-xl-inline-block ms-1">
            <div class="badge bg-info-subtle text-info font-size-12"><i class="fa fa-cog"></i></div>
        </span>
        <i class="fa fa-angle-down"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-end p-2">
       
        @can('view_user_dashboard')
        <a class="dropdown-item" href="{{ route('admin.loan.history', ['loan' => $query->id, 'user' => $query->user_id]) }}">
            <i class="fa fa-search"></i> History
        </a>

        @endcan
       
    </div>
</div>
@endcanany
 