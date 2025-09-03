<?php

namespace App\DataTables;

use App\Models\Loan;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Auth;

class LoanDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $roleId = Auth::user()->role_id;
        $tableName = (new Loan)->getTable();

        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('staff_name', fn($row) => $row->user->name ?? '')
            ->addColumn('loan_period', fn($row) => $row->loan_duration . '-' . strtoupper(substr($row->apply_interest_on, 0, 1)))
            ->addColumn('capital_interest', fn($row) => $row->capital_interest . '%')
            ->editColumn('date', fn($row) => \Carbon\Carbon::parse($row->date)->format('d M Y'))
            ->editColumn('status', function ($query) use ($tableName, $roleId) {
                $statusMap = [
                    0 => ['label' => 'Pending', 'class' => 'bg-primary-subtle text-primary', 'next' => 1],
                    1 => ['label' => 'Approved', 'class' => 'bg-success-subtle text-success', 'next' => 2],
                    2 => ['label' => 'Rejected', 'class' => 'bg-danger-subtle text-danger', 'next' => 3],
                    3 => ['label' => 'Closed', 'class' => 'bg-warning-subtle text-warning', 'next' => 0],
                ];

                $currentStatus = $statusMap[$query->status] ?? $statusMap[0];
                $iconId = "statusSpin{$query->id}";

                if (in_array($roleId, [2, 3])) {
                    return <<<HTML
                        <div class='badge {$currentStatus['class']} font-size-12'>
                            {$currentStatus['label']}
                        </div>
                    HTML;
                }

                $nextStatus = $statusMap[$currentStatus['next']];
                return <<<HTML
                    <div class='dropdown d-inline-block user-dropdown'>
                        <button type='button' class='btn text-dark waves-effect' data-bs-toggle='dropdown'>
                            <div class='badge {$currentStatus['class']} font-size-12'>
                                <i class='fa fa-spin fa-spinner' style='display:none' id='$iconId'></i> {$currentStatus['label']}
                            </div>
                            <i class='fa fa-angle-down'></i>
                        </button>
                        <div class='dropdown-menu dropdown-menu-end p-2'>
                            <a class='dropdown-item' style='cursor:pointer;' onclick="changeStatus('id', '{$query->id}', 'status', '{$currentStatus['next']}', '{$tableName}')">
                                Change to {$nextStatus['label']}
                            </a>
                        </div>
                    </div>
                HTML;
            })
            ->addColumn('action', function ($query) use ($tableName, $roleId) {
                return $roleId == 3
                    ? view('admin.roles.partials.userloanaction', compact('query', 'tableName'))->render()
                    : view('admin.roles.partials.loanaction', compact('query', 'tableName'))->render();
            })
            ->setRowId('id')
            ->rawColumns(['status', 'action']);
    }

    public function query(Loan $model): QueryBuilder
    {
        $query = $model->newQuery()->with('staff');
        $roleId = Auth::user()->role_id;
        $loggedInID = Auth::user()->id;

        if ($roleId == 3) {
            $query->where('user_id', $loggedInID);
        } elseif ($roleId != 1) {
            $query->where('created_by', $loggedInID);
        }

        if (request()->filled('from_date') && request()->filled('to_date')) {
            $from = \Carbon\Carbon::createFromFormat('d/m/Y', request('from_date'))->format('Y-m-d');
            $to = \Carbon\Carbon::createFromFormat('d/m/Y', request('to_date'))->format('Y-m-d');
            $query->whereBetween('date', [$from, $to]);
        }

        if (request()->filled('user_id')) {
            $query->where('user_id', request('user_id'));
        }

        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('loan-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(1)
            ->buttons([
                Button::make('copy')->exportOptions(['columns' => ':not(.not-exported)']),
                Button::make('csv')->exportOptions(['columns' => ':not(.not-exported)']),
                Button::make('pdf')->exportOptions(['columns' => ':not(.not-exported)']),
                Button::make('print')->exportOptions(['columns' => ':not(.not-exported)']),
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->data('id')->searchable(false)->orderable(false),
            Column::make('loan_id')->title('Loan Id'),
            Column::computed('staff_name')->title('User Name'),
            Column::make('title')->title('Title'),
            Column::make('amount')->title('Amount'),
            Column::computed('loan_period')->title('Loan Duration'),
            Column::computed('capital_interest')->title('Capital Interest'),
            Column::make('date')->title('Date'),
            Column::make('status')->title('Status'),
            Column::computed('action')
                ->title('Action')
                ->addClass('text-start not-exported') // Important for excluding from export
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(false),
        ];
    }

    protected function filename(): string
    {
        return 'Loan_' . date('YmdHis');
    }
}
