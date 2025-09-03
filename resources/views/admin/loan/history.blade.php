<x-app-layout :title="$title">
<!--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->

<!--<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">-->

        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Loan History - {{$loan_bank->first_name}} {{$loan_bank->last_name}} - {{$editloan->loan_id}}</h4>

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
                <div class="container mb-3">
                    <div class="row">
                            <div class="col-lg-4">
                                <div class="card border border-success">
                                    <div class="card-header bg-transparent border-success">
                                        <h5 class="my-0 text-success"><i class="mdi mdi-check-all me-3"></i>Capital Amount</h5>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">{{ number_format($editloan->amount / 1000, 1) }}K</h5>
                                        
                                    </div>
                                </div>
                            </div>
        
                            <div class="col-lg-4">
                                <div class="card border border-success">
                                    <div class="card-header bg-transparent border-success">
                                        <h5 class="my-0 text-success"><i class="mdi mdi-check-all me-3"></i>Total Amount</h5>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">{{ number_format($editloan->total_with_interest / 1000, 1) }}K </h5>
                                        
                                    </div>
                                </div>
                            </div>
        
                            <div class="col-lg-4">
                                <div class="card border border-success">
                                    <div class="card-header bg-transparent border-success">
                                        <h5 class="my-0 text-success"><i class="mdi mdi-check-all me-3"></i>Collected Amount</h5>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">{{ number_format($totalAmount/ 1000, 1) }}K</h5>
                                       
                                    </div>
                                </div>
                            </div>
                        </div>

                <div class="row m-2 {{ ($loan_bank->bankname == '')?'d-none':'' }}">
                    <div class="col-sm-3 border-success border">BANK NAME : {{ $loan_bank->bankname }}</div>
                    
                                        <div class="col-sm-3 border-success border">ACCOUNT NO : {{$loan_bank->accountnumber }}</div>
                                        
                                                            <div class="col-sm-3 border-success border">IFSC CODE : {{$loan_bank->ifsccode }}</div>
                                                            
                                                                                <div class="col-sm-3 border-success border">ADDRESS : {{$loan_bank->bankaddress }}</div>
                </div>
                <div class="row m-2 {{ ($loan_bank->upi_id != '')?'d-block': 'd-none' }}">
                    <div class="col-sm-12 border-success border">IFSC : {{ $loan_bank->upi_id }}</div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

                                <!-- <h4 class="card-title">Loan History</h4> -->

                                <div class="table-responsive">
                                @php
                                  $oldInstallments = $installments ?? [];
                                @endphp

                                <form action="{{ route('admin.loans.saveHistory', $editloan->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $editloan->user_id }}">
                                    <input type="hidden" name="admin_id" value="{{ $admin->id }}">
                                    <table class="table table-editable table-nowrap align-middle table-edits">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Date</th>
                                                <th>Title</th>
                                                <th>Description</th>
                                                <th>Amount</th>
                                                <th>Through Pay</th>
                                                <th>Screenshot (SS)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @for ($i = 1; $i <= $editloan->loan_duration; $i++)
                                            @php
                                                $old = $oldInstallments[$i] ?? null;
                                            @endphp
                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td>
                                                <input type="text" name="installments[{{ $i }}][date]" data-provide="datepicker" data-date-autoclose="true" class="form-control datepicker"
                                             value="{{ $old && $old->date ? \Carbon\Carbon::parse($old->date)->format('d-m-Y') : '' }}"
                                             placeholder="dd-mm-yyyy">
                                        
                                                </td>
                                                <td>
                                                    <input type="text" name="installments[{{ $i }}][title]" class="form-control"
                                                        value="{{ $old->title ?? '' }}">
                                                </td>
                                                <td>
                                                    <textarea name="installments[{{ $i }}][description]" class="form-control" rows="1">{{ $old && $old->description ? $old->description : '' }}</textarea>
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" name="installments[{{ $i }}][amount]" class="form-control"
                                                        value="{{ $old && $old->amount ? $old->amount : '' }}">
                                                </td>
                                                <td>
                                                    <select name="installments[{{ $i }}][payment_method]" class="form-control">
                                                        <option value="UPI" {{ $old && $old->payment_method == 'UPI' ? 'selected' : '' }}>UPI</option>
                                                        <option value="Bank Transfer" {{ $old && $old->payment_method == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    @if ($old && $old->screenshot)
                                                        <a href="{{ asset('storage/' . $old->screenshot) }}" target="_blank">View</a><br>
                                                    @endif
                                                    <input type="file" name="installments[{{ $i }}][screenshot]" class="form-control" accept="image/*">
                                                </td>
                                                <input type="hidden" name="installments[{{ $i }}][id]" value="{{ $old->id ?? '' }}">
                                            </tr>
                                        @endfor

                                </tbody>

                                    </table>
 
                                   <button type="submit" class="btn btn-sm btn-primary"
                                        @if($editloan->status == 3) disabled @endif>Save Installments</button>
                            
                                   <a href="{{ route('admin.loans.closeloan', ['id' => $editloan->id]) }}" 
                                       class="btn btn-sm btn-warning"
                                       @if($editloan->status == 3) disabled style="pointer-events: none;background:#f2d18a;" @endif>
                                       Close Loan
                                    </a>

                            
                                    <a href="{{ route('admin.loans.index') }}" 
                                        class="btn btn-sm btn-danger">
                                        Cancel
                                    </a>
                                </form>

                </div>

            </div>
        </div>
    </div>
</div>

                <!-- end row -->

            </div> <!-- container-fluid -->
        </div>

   
        <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>-->

</x-app-layout>