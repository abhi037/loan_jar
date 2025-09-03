<x-app-layout :title="$title">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Loan Report</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Loan Report</a></li>
                                <li class="breadcrumb-item active">Loan Report </li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="card-title">Loan Report</h4>
                            <div class="row m-3">
                                <div class="col-sm-12 col-lg-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-label">From</label>
                                        <input type="text" id="from_date" class="form-control" placeholder="dd/mm/yyyy" data-provide="datepicker" data-date-autoclose="true" data-date-format="dd/mm/yyyy">
                                    </div>
                                </div>
                                <div class="col-sm-12 col-lg-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-label">To</label>
                                        <input type="text" id="to_date" class="form-control" placeholder="dd/mm/yyyy" data-provide="datepicker" data-date-autoclose="true" data-date-format="dd/mm/yyyy">
                                    </div>
                                </div>
                                <div class="col-sm-12 col-lg-3  mb-2">
                                    <button id="filter" class="btn btn-md btn-success">Search</button>
                                </div>




                            </div>

                            <div class="table-responsive">
                                <table id="loansTable" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>User</th>
                                            <th>Document</th>
                                            <th>Created At</th>
                                        </tr>
                                    </thead>
                                </table>

                                @push('scripts')
                                <script>
                                    $(function() {
                                        $('#loansTable').DataTable({
                                            processing: true,
                                            serverSide: true,
                                            ajax: '{{ route("loan.report.data.install") }}', // Adjust to your route name
                                            dom: 'Bfrtip',
                                            order: [
                                                [1, 'asc']
                                            ], // Adjust as needed
                                            buttons: [
                                                'copy',
                                                'csv',
                                                'pdf',
                                                'print'
                                            ],
                                            columns: [{
                                                    data: 'id'
                                                },
                                                {
                                                    data: 'title'
                                                },
                                                {
                                                    data: 'email'
                                                },
                                                {
                                                    data: 'phone'
                                                },
                                                {
                                                    data: 'amount'
                                                },
                                                {
                                                    data: 'status'
                                                },
                                                {
                                                    data: 'user_name'
                                                },
                                                {
                                                    data: 'document',
                                                    orderable: false,
                                                    searchable: false
                                                },
                                                {
                                                    data: 'created_at'
                                                }
                                            ]
                                        });
                                    });
                                </script>
                                @endpush



                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- end row -->

        </div> <!-- container-fluid -->
    </div>


</x-app-layout>