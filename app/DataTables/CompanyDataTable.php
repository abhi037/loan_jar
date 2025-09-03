<?php

namespace App\DataTables;

use App\Models\Company;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;

use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Services\DataTable;

class CompanyDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $tableName = (new Company)->getTable();
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('logo', function ($query) {
                return '<img src="' . asset('storage/' . $query->logo) . '" alt="' . $query->company_name . '" height="50" width="100">';
            })
            ->addColumn('status', function ($query) use ($tableName) {
                $status = $query->status == 1 ? 'Active' : 'Inactive';
                $badgeClass = $query->status == 1 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger';
                $newStatus = $query->status == 1 ? 0 : 1;
                $iconId = $query->status == 1 ? "PendingSpin{$query->id}" : "publicationSpin{$query->id}";
                $statusText = $query->status == 1 ? 'Inactive' : 'Active';

                return <<<HTML
                <div class='dropdown d-inline-block user-dropdown'>
                    <button type='button' class='btn text-dark waves-effect' id='page-header-user-dropdown' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                        <div class='badge $badgeClass font-size-12'>
                            <i class='fa fa-spin fa-spinner' style='display:none' id='$iconId'></i> $status
                        </div>
                        <i class='fa fa-angle-down'></i>
                    </button>
                    <div class='dropdown-menu dropdown-menu-end p-2'>
                        <a class='dropdown-item' style='cursor:pointer;' onclick="changeStatus('id', '{$query->id}', 'status', '{$newStatus}', '{$tableName}')">
                            $statusText
                        </a>
                    </div>
                </div>
                HTML;
            })


            ->addColumn('action', function ($query) use ($tableName) {
                $edit_url = route('admin.company.edit', $query->id);
                $user = Auth::user();

                // Check permissions
                $canEdit = $user->can('edit_company');
                $canDelete = $user->can('delete_company');

                // If user has no permission, return nothing
                if (!$canEdit && !$canDelete) {
                    return '';
                }

                // Build dropdown only if at least one permission is true
                $html = '<div class="dropdown d-inline-block user-dropdown">
                <button type="button" class="btn text-dark waves-effect" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="d-xl-inline-block ms-1">
                        <div class="badge bg-info-subtle text-info font-size-12"><i class="fa fa-cog"></i></div>
                    </span>
                    <i class="fa fa-angle-down"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end p-2">';

                if ($canEdit) {
                    $html .= "<a class='dropdown-item' href='$edit_url'><i class='fa fa-edit'></i> Edit</a>";
                }

                if ($canDelete) {
                    $html .= "<a class='dropdown-item' href='javascript:void(0)' onclick=\"deleteCompany('id', '$query->id', '$tableName')\"><i class='fa fa-trash'></i> Delete</a>";
                }

                $html .= '</div></div>';

                return $html;
            })

            ->setRowId('id')
            ->rawColumns(['logo', 'status', 'action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Company $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('position_by', 'desc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('company-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                // Button::make('excel'),
                // Button::make('csv'),
                // Button::make('pdf'),
                // Button::make('print'),
                // Button::make('reset'),
                // Button::make('reload')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->data('DT_RowIndex')->searchable(false)->orderable(false),
            Column::make('company_name')->title('Company Name'),
            Column::make('logo')->title('Logo'),
            Column::make('address'),
            Column::make('status'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-start'),

        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Company_' . date('YmdHis');
    }
}
