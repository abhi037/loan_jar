<x-app-layout :title="$title">
    @push('css')
    <style>
        .dropify-wrapper {
            height: 120px !important;
        }

        .profile .dropify-wrapper {
            height: 200px !important;
        }
    </style>

    @endpush

    <div id="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Loan List</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Loan List</a></li>
                                    <li class="breadcrumb-item active">Loan List</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row clearfix">
                    <div class="col-lg-12">
                        <div class="card">

                            <div class="row m-3">
                                <div class="col-sm-12 col-lg-3 mb-2">
                                    
                                                    <select class="form-control select1" name="user_id" id="user_id">
                                                        <option value="">-- Select User --</option>
                                                        @foreach($users as $user)
                                                        <option value="{{ $user->user_id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->first_name }} {{ $user->last_name }}</option>
                                                        @endforeach
                                                    </select>
     
                                </div>
                                <div class="col-sm-12 col-lg-3  mb-2">
                                    <div class="form-group">
                                        <!-- <label class="form-label">From</label> -->
                                        <input type="text" id="from_date" class="form-control" placeholder="From - dd/mm/yyyy" data-provide="datepicker" data-date-autoclose="true" data-date-format="dd/mm/yyyy">
                                    </div>
                                </div>
                                <div class="col-sm-12 col-lg-3   mb-2">
                                    <div class="form-group">
                                        <!-- <label class="form-label">To</label> -->
                                        <input type="text" id="to_date" class="form-control" placeholder="To - dd/mm/yyyy" data-provide="datepicker" data-date-autoclose="true" data-date-format="dd/mm/yyyy">
                                    </div>
                                </div>
                                <div class="col-sm-12 col-lg-2  mb-2">
                                <!-- <label class="form-label"></label> -->

                                    <button id="filter" class="btn btn-md btn-success">Search</button>
                                    <button class="btn btn-md btn-danger"><a class="text-white" onclick="window.location.reload();">Reset</a></button>

                                </div>



                                <div class="col-sm-12 col-lg-1  mb-2 d-flex justify-content-end">
                                    @can('add_loans')
                                    <ul class="metismenu list-unstyled">
                                        <li>
                                            <a href="{{ route('admin.loans.create') }}" class="btn btn-sm btn-outline-primary">Add New</a>
                                        </li>
                                    </ul>
                                    @endcan
                                </div>
                            </div>


                            <div class="card-body">
                                <div class="table-responsive">
                                    {{ $dataTable->table(['class' => ' table-hover table table-bordered data-table dt-responsive nowrap']) }}

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->
            </div> <!-- container-fluid -->
        </div>

        <!---Delet Model--->
        <div class="modal fade" id="delete_modal" tabindex="-1" aria-labelledby="delete_modal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center pt-4">
                        <i class="fa fa-warning fa-4x text-warning"></i>
                        <h3>Delete Data</h3>
                        <p>Are you sure want to delete?</p>
                        <div class="mb-3">
                            <form id="deleteForm" action="" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="Id" id="delId" />
                                <input type="hidden" name="column" id="delColumn" />
                                <input type="hidden" name="table" id="delTable" />
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger">Yes, delete it!</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      
        @push('scripts')
        {{ $dataTable->scripts() }}
<script>
$(document).ready(function () {
    let table = $('.data-table').DataTable();

    $('#filter').on('click', function () {
        table.ajax.reload();
    });

    $.fn.dataTable.ext.errMode = 'throw';

    // Modify ajax.data to include filters
    $('.data-table').on('preXhr.dt', function (e, settings, data) {
        data.user_id = $('#user_id').val();
        data.from_date = $('#from_date').val();
        data.to_date = $('#to_date').val();
    });
});

</script>

        <script>
        

                                    
            function deleteLoan(where_column, where_id, where_table) {
                $('#delete_modal').modal('show');

                // Set hidden input values
                $('#delColumn').val(where_column);
                $('#delId').val(where_id);
                $('#delTable').val(where_table);

                // Dynamically set the form action with the correct ID
                let deleteUrl = "{{ route('admin.loans.destroy',':id') }}".replace(':id', where_id);
                $('#deleteForm').attr('action', deleteUrl);
            }
        </script>
        @endpush
</x-app-layout>