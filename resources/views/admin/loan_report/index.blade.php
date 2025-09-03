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
                                <li class="breadcrumb-item active">Loan Report</li>
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
                            <div class="row mb-3">
                                <div class="col-sm-12 col-lg-3 mb-2">
                                    
                                                    <select class="form-control select1" name="user_id" id="user_id">
                                                        <option value="">-- Select User --</option>
                                                        @foreach($users as $user)
                                                        <option value="{{ $user->user_id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->first_name }} {{ $user->last_name }}</option>
                                                        @endforeach
                                                    </select>
     
                                </div>
                                <div class="col-sm-12 col-lg-3 mb-2">
                                    
                                    <div class="form-group">
                                        <!-- <label class="form-label">From</label> -->
                                        <input type="text" id="from_date" class="form-control" placeholder="From - dd/mm/yyyy" data-provide="datepicker" data-date-autoclose="true" data-date-format="dd/mm/yyyy">
                                    </div>
                                </div>
                                <div class="col-sm-12 col-lg-3 mb-2">
                                    <div class="form-group">
                                        <!-- <label class="form-label">To</label> -->
                                        <input type="text" id="to_date" class="form-control" placeholder="To - dd/mm/yyyy" data-provide="datepicker" data-date-autoclose="true" data-date-format="dd/mm/yyyy">
                                    </div>
                                </div>
                                <div class=" col-sm-12 col-lg-3 mb-2">
                                    <button id="filter" class="btn btn-md btn-success">Search</button>
                                    <button class="btn btn-md btn-danger"><a class="text-white" onclick="window.location.reload();">Reset</a></button>
                                </div>




                            </div>
                            <!--<h4 class="card-title">Loan Report</h4>-->

                            <div class="table-responsive">
                                <table id="loansTable" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>LoanID</th>
                                            <th>User</th>
                                            <th>Title</th>
                                            <th>Loan Duration</th>
                                            <th>Capital Interest</th>
                                            <th>Amount</th>
                                            <th>Total WIth Interest</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                      
                                        </tr>
                                    </thead>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- end row -->

        </div> <!-- container-fluid -->
    </div>



</x-app-layout>


<!-- Required datatable js -->
<script src="{{asset('assets/libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<!-- Buttons examples -->
<script src="{{asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/libs/jszip/jszip.min.js')}}"></script>
<script src="{{asset('assets/libs/pdfmake/build/pdfmake.min.js')}}"></script>
<script src="{{asset('assets/libs/pdfmake/build/vfs_fonts.js')}}"></script>
<script src="{{asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('assets/libs/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js')}}"></script>

<script src="{{asset('assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js')}}"></script>
<script src="{{asset('assets/libs/datatables.net-select/js/dataTables.select.min.js')}}"></script>

<!-- Responsive examples -->
<script src="{{asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')}}"></script>

<!-- Datatable init js -->
<script src="{{asset('assets/js/pages/datatables.init.js')}}"></script>
<script>
                                    $(document).ready(function() {
                                        var table = $('#loansTable').DataTable({
                                            processing: true,
                                            serverSide: true,
                                            ajax: {
                                                url: '{{ route("admin.loan.report.data") }}',
                                                data: function(d) {
                                                    d.user_id = $('#user_id').val();
                                                    d.from = $('#from_date').val();
                                                    d.to = $('#to_date').val();
                                                }
                                            },
                                            dom: 'Bfrtip',
                                            buttons: ['copy', 'csv', 'pdf', 'print'],
                                            columns: [{
                                                    data: 'id'
                                                },
                                                {
                                                    data: 'loan_id'
                                                },
                                                 {
                                                    data: 'user_name'
                                                },
                                                {
                                                    data: 'title'
                                                },
                                                {
                                                    data: 'loan_period'
                                                },
                                                {
                                                    data: 'capital_interest'
                                                },
                                                {
                                                    data: 'amount'
                                                },
                                                {
                                                    data: 'total_with_interest'
                                                },
                                                {
                                                    data: 'status'
                                                },
                                                {
                                                    data: 'date'
                                                }
                                            ]
                                        });

                                        $('#filter').on('click', function() {
                                            table.draw(); // Redraw with new filter
                                        });

                                        $.fn.dataTable.ext.errMode = 'throw'; // Optional: log DataTables errors
                                    });
                                </script>