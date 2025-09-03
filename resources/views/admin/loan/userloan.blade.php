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
                <div class="row">
                   
                </div>

                <div class="row clearfix">
                    <div class="col-lg-12">
                        <div class="card">

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
            function deleteStaff(where_column, where_id, where_table) {
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