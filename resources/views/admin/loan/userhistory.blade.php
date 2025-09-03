<x-app-layout :title="$title">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">

        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Loan History</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Loan History</a></li>
                                    <li class="breadcrumb-item active">Loan History</li>
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

                <!--<h4 class="card-title">Loan History</h4>-->

              <table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Date</th>
            <th>Title</th>
            <th>Description</th>
            <th>Amount</th>
            <th>Pay Method</th>
            <th>Screenshot</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($installments as $i => $installment)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ \Carbon\Carbon::parse($installment->date)->format('d/m/Y') }}</td>
            <td>{{ $installment->title }}</td>
            <td>{{ $installment->description }}</td>
            <td>{{ $installment->amount }}</td>
            <td>{{ $installment->payment_method }}</td>
            <td>
                @if($installment->screenshot)
                    <a href="{{ asset('storage/' . $installment->screenshot) }}" target="_blank"><i class="ri-eye-line"></i></a>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>


            </div>
        </div>
    </div>
</div>

                <!-- end row -->

            </div> <!-- container-fluid -->
        </div>

   
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

</x-app-layout>