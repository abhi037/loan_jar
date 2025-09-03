<x-app-layout :title="$title">
    @push('css')
    <style>
        .dropify-wrapper {
            height: 150px !important;
        }

        .profile .dropify-wrapper {
            height: 200px !important;
        }

        .error {
            color: #fc5a69;
        }

        /* .upi-box {
            border: 1px dashed #aaa;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            margin-top: 10px;
        } */
    </style>
    @endpush
    <div id="main-content">


    <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Add User</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Add User</a></li>
                                    <li class="breadcrumb-item active">Add User</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="row">
                <div class="col-12">
                    <div class="card ">
                        <div class="card-body">
                            <form id="wizard_with_validation" class="form-validation" action="{{ route('admin.users.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <!-- <h3>Personal Information</h3> -->
                                <fieldset>
                                    <div class="row g-2">
                                        <div class="mb-3 col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <label class="form-label">First Name <small class="text-danger">*</small></label>
                                                <input type="text" name="first_name" placeholder="First Name *" class="form-control" value="{{ !empty($editemployee->first_name) ? $editemployee->first_name : old('first_name') }}" required>
                                                @error('first_name')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="mb-3 col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <label class="form-label">Last Name <small class="text-danger">*</small></label>
                                                <input type="text" name="last_name" placeholder="Last Name *" class="form-control" value="{{ !empty($editemployee->last_name) ? $editemployee->last_name : old('last_name') }}">
                                                @error('last_name')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="mb-3 col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <label class="form-label">Email ID <small class="text-danger">*</small></label>
                                                <input type="email" name="email" placeholder="Email ID  *" class="form-control" value="{{ !empty($editemployee->email) ? $editemployee->email : '' }}" required>
                                                @error('email')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                              <div class="mb-3 col-sm-12 col-lg-4">
                                        <div class="form-group position-relative">
                                            <label class="form-label">Password <small class="text-danger">*</small></label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" placeholder="Password *" name="password" id="password" required>
                                                <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                                    <i class="fa fa-eye-slash" id="eyeIcon"></i>
                                                </span>
                                            </div>
                                            @error('password')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                        <div class="mb-3 col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <label class="form-label">Mobile Number <small class="text-danger">*</small></label>
                                                <input type="number" name="mobile" placeholder="Mobile Number *" maxlength="10"
                                                    pattern="\d{10}" class="form-control" value="{{ !empty($editemployee->mobile) ? $editemployee->mobile : old('mobile') }}">
                                                @error('mobile')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="mb-3 col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <label class="form-label">Alternate Number <small class="text-danger">*</small></label>
                                                <input type="number" name="alternate_mobile" placeholder="Alternate Number *" class="form-control" value="{{ !empty($editemployee->alternate_mobile) ? $editemployee->alternate_mobile : old('alternate_mobile') }}">
                                                @error('alternate_mobile')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="mb-3 col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <label class="form-label">Gender <small class="text-danger">*</small></label>
                                                <select class="form-select select1" aria-label="Default select example" name="gender" required>
                                                    <option value="" disabled {{ old('gender', $editemployee->gender ?? '') == '' ? 'selected' : '' }}>Choose Gender</option>
                                                    <option value="m" {{ old('gender', $editemployee->gender ?? '') == 'm' ? 'selected' : '' }}>Male</option>
                                                    <option value="f" {{ old('gender', $editemployee->gender ?? '') == 'f' ? 'selected' : '' }}>Female</option>
                                                    <option value="o" {{ old('gender', $editemployee->gender ?? '') == 'o' ? 'selected' : '' }}>Other</option>
                                                </select>
                                                @error('gender')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="mb-3 col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <label class="form-label">Marital Status <small class="text-danger">*</small></label>
                                                <select class="form-select select1" aria-label="Default select example" name="marital_status" required>
                                                    <option value="" disabled {{ old('marital_status', $editemployee->marital_status ?? '') == '' ? 'selected' : '' }}>Marital Status</option>
                                                    <option value="married" {{ old('marital_status', $editemployee->marital_status ?? '') == 'married' ? 'selected' : '' }}>Married</option>
                                                    <option value="unmarried" {{ old('marital_status', $editemployee->marital_status ?? '') == 'unmarried' ? 'selected' : '' }}>Unmarried</option>
                                                </select>
                                                @error('marital_status')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="mb-3 col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <label class="form-label">User Category <small class="text-danger">*</small></label>
                                                <select class="form-select select1" aria-label="Default select example" id="employee_category" name="employee_category" required>
                                                    <option value="" disabled {{ old('employee_category	', $editemployee->employee_category	 ?? '') == '' ? 'selected' : '' }}>User Category</option>
                                                    <option value="employee" {{ old('employee_category	', $editemployee->employee_category	 ?? '') == 'married' ? 'selected' : '' }}>Employee</option>
                                                    <option value="studdent" {{ old('employee_category	', $editemployee->employee_category	 ?? '') == 'unmarried' ? 'selected' : '' }}>Student</option>
                                                </select>
                                                @error('marital_status')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="mb-3 col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <label class="form-label">Aadhar Number <small class="text-danger">*</small></label>
                                                <input type="text" name="aadharnumber" placeholder="Aadhar Number *" class="form-control" value="{{ !empty($editemployee->aadharno) ? $editemployee->aadharno : old('aadharnumber') }}" required>
                                                @error('aadharnumber')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="mb-3 col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <label class="form-label">Pan Number <small class="text-danger">*</small></label>
                                                <input type="text" name="pannumber" placeholder="Pan Number *" class="form-control" value="{{ !empty($editemployee->pancardno) ? $editemployee->pancardno : old('pannumber') }}" required>
                                                @error('pannumber')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="mb-3 col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <label class="form-label">Date of Birth <small class="text-danger">*</small></label>
                                                <input type="text" data-provide="datepicker" data-date-autoclose="true" class="form-control" name="dob" placeholder="dd/mm/yyyy" data-date-format="dd/mm/yyyy" value="{{ !empty($editemployee->dob) ? $editemployee->dob : old('dob') }}" required>
                                                @error('dob')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                       
                                        <div class="mb-3 col-sm-12 col-lg-12">
                                            <div class="form-group">
                                                <label class="form-label">Role <small class="text-danger">*</small></label>
                                                <select class="form-select select1" aria-label="Default select example" onchange="toggleDiv(this.value)" name="role_id" required>
                                                    <option value="">Choose Role</option>
                                                    @foreach($roles as $role)
                                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                
                                <div class="mb-3" style="display:none;" id="BankDetails">
                                    <h3>Bank Information</h3>
                                    <fieldset>
                                        <div class="row g-2">
                                            <!-- <h6 class="card-title">Bank Information</h6> -->

                                            {{-- Bank Name --}}
                                            <div class="mb-3 col-sm-12 col-lg-4">
                                                <div class="form-group">
                                                    <label class="form-label">Bank Name <small class="text-danger">*</small></label>
                                                    <input type="text" class="form-control" placeholder="Bank Name  *" name="bankname" value="{{ !empty($editemployee->bankname) ? $editemployee->bankname : old('bankname') }}">
                                                    @error('bankname')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{-- Account Number --}}
                                            <div class="mb-3 col-sm-12 col-lg-4">
                                                <div class="form-group">
                                                    <label class="form-label">Account Number <small class="text-danger">*</small></label>
                                                    <input type="text" class="form-control" placeholder="Account Number*" name="accountnumber" value="{{ !empty($editemployee->accountnumber) ? $editemployee->accountnumber : old('accountnumber') }}">
                                                    @error('accountnumber')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{-- IFSC Code --}}
                                            <div class="mb-3 col-sm-12 col-lg-4">
                                                <div class="form-group">
                                                    <label class="form-label">IFSC code <small class="text-danger">*</small></label>
                                                    <input type="text" class="form-control" placeholder="IFSC code*" name="ifsccode" value="{{ old('ifsccode') }}">
                                                    @error('ifsccode')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{-- Bank Address --}}
                                            <div class="mb-3 col-sm-12 col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-label">Bank Address <small class="text-danger">*</small></label>
                                                    <textarea name="bankaddress" placeholder=" Bank Address *" class="form-control no-resize" rows="5">{{ old('bankaddress') }}</textarea>
                                                    @error('bankaddress')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{-- UPI Section --}}
                                            <div class="col-12 mt-4">
                                                <div>
                                                    <h6 class="mb-3 text-muted">OR enter UPI ID if bank details are not available</h6>
                                                    <div class="row">
                                                        <div class="mb-3 col-sm-12 col-lg-4">
                                                            <div class="form-group">
                                                                <label class="form-label">UPI ID</label>
                                                                <input type="text" class="form-control" placeholder="UPI ID" name="upi_id" value="{{ !empty($editemployee->upi_id) ? $editemployee->upi_id : old('upi_id') }}">
                                                                @error('upi_id')
                                                                <span class="text-danger">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </fieldset>
                                </div>
                                <div class="row my-2">
                                    <div class="col-md-12">
                                        <button class="mt-3 btn btn-primary form-btn" id="videoBtn" type="submit">SAVE <i class="fa fa-spin fa-spinner" id="videoSpin" style="display:none;"></i></button>
                                        <a class="text-white mt-3 btn btn-danger form-btn" href="{{ route('admin.users.index')}}">Cancel</a>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
                <!-- end row -->
            </div> <!-- container-fluid -->
        </div>


    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('.select1').select2();
            $('.employeedoc').addClass('d-none');


        });
                function toggleDiv(value){
            if(value == 3){
                $("#BankDetails").show();
            }else{
                 $("#BankDetails").hide();
            }
        }
    </script>
 
    <script>
        $(document).on('input', 'input[name="mobile"]', function() {
            this.value = this.value.replace(/\D/g, '').slice(0, 10);
        });

        $(document).on('keypress', 'input, textarea', function(e) {
            if (this.value.length === 0 && e.which === 32) {
                e.preventDefault();
            }
        });
    </script>
    <script>
        $(document).on('input', 'input[name="alternate_mobile"]', function() {
            this.value = this.value.replace(/\D/g, '').slice(0, 10);
        });

        $(document).on('keypress', 'input, textarea', function(e) {
            if (this.value.length === 0 && e.which === 32) {
                e.preventDefault();
            }
        });
    </script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const passwordInput = document.getElementById("password");
        const togglePassword = document.getElementById("togglePassword");
        const eyeIcon = document.getElementById("eyeIcon");

        togglePassword.addEventListener("click", function () {
            const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
            passwordInput.setAttribute("type", type);
            eyeIcon.classList.toggle("fa-eye");
            eyeIcon.classList.toggle("fa-eye-slash");
        });
    });
</script>


    @endpush
</x-app-layout>






  