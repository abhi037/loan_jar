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
    </style>
    @endpush
    <div id="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Edit User</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Edit User</a></li>
                                    <li class="breadcrumb-item active">Edit User</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
         
            <div class="row clearfix">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="wizard_with_validation" class="form-validation" action="{{ route('admin.users.update', $editstaff->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <h3>Personal Information</h3>
                                <fieldset>
                                    <div class="row g-2">
                                        <div class="mb-3 col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <label class="form-label">First Name <small class="text-danger">*</small></label>
                                                <input type="text" name="first_name" placeholder="First Name *" class="form-control" value="{{ !empty($editstaff->first_name) ? $editstaff->first_name : old('first_name') }}" required>
                                                <input type="hidden" name="id"  value="{{$editstaff->id}}" >
                                                @error('first_name')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="mb-3 col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <label class="form-label">Last Name <small class="text-danger">*</small></label>
                                                <input type="text" name="last_name" placeholder="Last Name *" class="form-control" value="{{ !empty($editstaff->last_name) ? $editstaff->last_name : old('last_name') }}">
                                                @error('last_name')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="mb-3 col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <label class="form-label">Email ID <small class="text-danger">*</small></label>
                                                <input type="email" name="email" placeholder="Email ID  *" class="form-control" value="{{ !empty($editstaff->email) ? $editstaff->email : '' }}" required>
                                                @error('email')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
  
                                        <div class="mb-3 col-sm-12 col-lg-4">
                                        @php
                                            $password = '';
                                            try {
                                                $decrypted = Crypt::decryptString($user->decrypt_password);
                                                // Check if it's serialized
                                                if (preg_match('/^[aOs]:\d+:/', $decrypted)) {
                                                    $password = unserialize($decrypted);
                                                } else {
                                                    $password = $decrypted;
                                                }
                                            } catch (Exception $e) {
                                                $password = '';
                                            }
                                        @endphp
                                                                   <div class="form-group position-relative">
                                            <label class="form-label">Password <small class="text-danger">*</small></label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" placeholder="Password *" 
                                                       name="password" id="password" value="{{ $password }}" required>
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
                                                    pattern="\d{10}" class="form-control" value="{{ !empty($editstaff->mobile) ? $editstaff->mobile : old('mobile') }}">
                                                @error('mobile')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="mb-3 col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <label class="form-label">Alternate Number <small class="text-danger">*</small></label>
                                                <input type="number" name="alternate_mobile" placeholder="Alternate Number *" class="form-control" value="{{ !empty($editstaff->alternate_mobile) ? $editstaff->alternate_mobile : old('alternate_mobile') }}">
                                                @error('alternate_mobile')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="mb-3 col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <label class="form-label">Gender <small class="text-danger">*</small></label>
                                                <select class="form-select select1" aria-label="Default select example" name="gender" required>
                                                    <option value="" disabled {{ old('gender', $editstaff->gender ?? '') == '' ? 'selected' : '' }}>Choose Gender</option>
                                                    <option value="m" {{ old('gender', $editstaff->gender ?? '') == 'm' ? 'selected' : '' }}>Male</option>
                                                    <option value="f" {{ old('gender', $editstaff->gender ?? '') == 'f' ? 'selected' : '' }}>Female</option>
                                                    <option value="o" {{ old('gender', $editstaff->gender ?? '') == 'o' ? 'selected' : '' }}>Other</option>
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
                                                    <option value="" disabled {{ old('marital_status', $editstaff->marital_status ?? '') == '' ? 'selected' : '' }}>Marital Status</option>
                                                    <option value="married" {{ old('marital_status', $editstaff->marital_status ?? '') == 'married' ? 'selected' : '' }}>Married</option>
                                                    <option value="unmarried" {{ old('marital_status', $editstaff->marital_status ?? '') == 'unmarried' ? 'selected' : '' }}>Unmarried</option>
                                                </select>
                                                @error('marital_status')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="mb-3 col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <label class="form-label">User Category <small class="text-danger">*</small></label>
                                                <select class="form-select select1" aria-label="Default select example" id="employee_category" name="employee_category" onchange="getCompanyDoc()" required>
                                                    <option value="" disabled {{ old('employee_category	', $editstaff->employee_category	 ?? '') == '' ? 'selected' : '' }}>User Category</option>
                                                    <option value="employee" {{ old('employee_category	', $editstaff->employee_category	 ?? '') == 'employee' ? 'selected' : '' }}>Employee</option>
                                                    <option value="student" {{ old('employee_category	', $editstaff->employee_category	 ?? '') == 'student' ? 'selected' : '' }}>Student</option>
                                                </select>
                                                @error('marital_status')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="mb-3 col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <label class="form-label">Aadhar Number <small class="text-danger">*</small></label>
                                                <input type="text" name="aadharnumber" placeholder="Aadhar Number *" class="form-control" value="{{ !empty($editstaff->aadhar_number) ? $editstaff->aadhar_number : old('aadharnumber') }}" required>
                                                @error('aadharnumber')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="mb-3 col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <label class="form-label">Pan Number <small class="text-danger">*</small></label>
                                                <input type="text" name="pannumber" placeholder="Pan Number *" class="form-control" value="{{ !empty($editstaff->pan_number) ? $editstaff->pan_number : old('pannumber') }}" required>
                                                @error('pannumber')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="mb-3 col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <label class="form-label">Date of Birth <small class="text-danger">*</small></label>
                                                <input type="text" data-provide="datepicker" data-date-autoclose="true" class="form-control" name="dob" placeholder="dd/mm/yyyy" data-date-format="dd/mm/yyyy" value="{{ !empty($editstaff->dob) ? $editstaff->dob : old('dob') }}" required>
                                                @error('dob')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                      
                                    </div>
                                </fieldset>
       
                                        <div class="mb-3 col-sm-12 col-lg-3">
                                            <div class="form-group">
                                          
                                                <label class="form-label">Role <small class="text-danger">*</small></label>
                                                    <select class="form-select select1" name="role_id" onchange="toggleDiv(this.value)">
                                                        <option value="">Choose Role</option>
                                                        @foreach($roles as $role)
                                                            <option value="{{ $role->id }}" 
                                                                @if(!empty($editstaff) && $editstaff->role_id == $role->id) selected @endif>
                                                                {{ $role->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                            </div>
                                        </div>

                             
                                    <div id="BankDetails" style="display: none;">
                                <h3>Bank Information</h3>
                                <fieldset>
                                    <div class="row g-2">
                                        <!-- <h6 class="card-title">Bank Information</h6> -->
                                        <div class="mb-3  col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <label class="form-label">Bank Name <small class="text-danger">*</small></label>
                                                <input type="text" class="form-control" placeholder="Bank Name  *" name="bankname" value="{{ !empty($editstaff->bankname) ? $editstaff->bankname : old('bankname') }}">
                                                @error('bankname') 
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="mb-3  col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <label class="form-label">Account Number <small class="text-danger">*</small></label>
                                                <input type="text" class="form-control" placeholder="Account Number*" name="accountnumber" value="{{ !empty($editstaff->accountnumber) ? $editstaff->accountnumber : old('accountnumber') }}">
                                                @error('accountnumber')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="mb-3  col-sm-12 col-lg-4">
                                            <div class="form-group">
                                                <label class="form-label">IFSC code <small class="text-danger">*</small></label>
                                                <input type="text" class="form-control" placeholder="IFSC code*" name="ifsccode" value="{{ !empty($editstaff->ifsccode) ? $editstaff->ifsccode : old('ifsccode') }}">
                                                @error('ifsccode')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="mb-3  col-sm-12 col-lg-12">
                                            <div class="form-group">
                                                <label class="form-label">Bank Address <small class="text-danger">*</small></label>
                                                <textarea name="bankaddress" placeholder=" Bank Address *" class="form-control no-resize" rows="5"> {{ !empty($editstaff->bankaddress) ? $editstaff->bankaddress : old('bankaddress') }}</textarea>
                                                @error('bankaddress')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- UPI Section --}}
                                            <div class="col-12 mt-4">
                                                <div >
                                                    <h6 class="mb-3 text-muted">OR enter UPI ID if bank details are not available</h6>
                                                    <div class="row">
                                                        <div class="mb-3 col-sm-12 col-lg-4">
                                                            <div class="form-group">
                                                                <label class="form-label">UPI ID</label>
                                                                <input type="text" class="form-control" placeholder="UPI ID" name="upi_id" value="{{ !empty($editstaff->upi_id) ? $editstaff->upi_id : old('upi_id') }}">
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
                                                <button class="mt-3 btn btn-primary form-btn"  type="submit">UPDATE </button>
                                                <a class="text-white mt-3 btn btn-danger form-btn" href="{{ route('admin.users.index')}}">Cancel</a>
                                            </div>
                                        </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script>
    function toggleDiv(roleId) {
        if (parseInt(roleId) === 3) {
            $("#BankDetails").slideDown();
        } else {
            $("#BankDetails").slideUp();
        }
    }

    $(document).ready(function () {
        $('.select1').select2();

        // On page load (e.g., for edit or validation failure)
        const selectedRole = $('select[name="role_id"]').val();
        toggleDiv(selectedRole);
    });
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