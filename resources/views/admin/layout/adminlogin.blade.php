<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>LoanJar - Login</title>
    <meta content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Lucid HR & Project Admin Dashboard Template with Bootstrap 5x">
    <meta name="author" content="WrapTheme, design by: ThemeMakker.com">

    <link rel="icon" href="{{asset('assets/images/favicon.ico')}}" type="image/x-icon">

    <!-- MAIN CSS -->
    <link rel="stylesheet" href="{{asset('assets/css/main.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/toastr.min.css')}}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesdesign" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">



    <!-- jquery.vectormap css -->
    <link href="{{ asset('assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet" type="text/css" />

    <!-- DataTables -->
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="{{ asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />


</head>

<body class="auth-body-bg">
    <div>
        <div class="container-fluid p-0">
            <div class="row g-0">
                <div class="col-lg-4">
                    <div class="authentication-page-content p-4 d-flex align-items-center min-vh-100">
                        <div class="w-100">
                            <div class="row justify-content-center">
                                <div class="col-lg-9">
                                    <div>
                                        <div class="text-center">
                                            <div>
                                                <a href="#" class="authentication-logo">
                                                    <img src="assets/images/loanjar_dark.svg" alt="" height="50" class="auth-logo logo-dark mt-3 mx-auto">
                                                    <img src="assets/images/loanjar_dark.svg" alt="" height="50" class="auth-logo logo-light mt-3 mx-auto">
                                                </a>
                                            </div>

                                            <h4 class="font-size-18 mt-4">Welcome Back !</h4>
                                            <p class="text-muted">Sign in to continue to LoanJar.</p>
                                        </div>

                                        <div class="p-2 mt-5">
                                            <form action="{{ route('admin.logindata') }}" method="POST" enctype="multipart/form-data">
                                                @csrf

                                                <div class="mb-3 auth-form-group-custom mb-4">
                                                    <i class="ri-user-2-line auti-custom-input-icon" style="color:#84dc04"></i>
                                                    <label for="username" class="fw-semibold">Username</label>
                                                    <input type="email" class="form-control" id="username" name="email" placeholder="Enter username" value="{{old('email')}}">
                                                    <span class="text-danger">
                                                        @error('email')
                                                        {{$message}}
                                                        @enderror
                                                    </span>
                                                </div>
                                         <div class="mb-3 auth-form-group-custom mb-4 position-relative">
                                                <i class="ri-lock-2-line auti-custom-input-icon" style="color:#84dc04"></i>
                                                <label for="userpassword">Password</label>
                                                
                                                <input type="password" class="form-control pe-5" id="userpassword" name="password" placeholder="Enter password">
                                                
                                                <!-- Eye icon absolutely positioned -->
                                                <span id="togglePassword" style="position: absolute; top: 50%; right: 15px; transform: translateY(-50%); cursor: pointer;">
                                                    <i class="ri-eye-off-line" id="eyeIcon"></i>
                                                </span>
                                            
                                                <span class="text-danger">
                                                    @error('password')
                                                    {{$message}}
                                                    @enderror
                                                </span>
                                            </div>

                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="customControlInline">
                                                    <label class="form-check-label" for="customControlInline">Remember me</label>
                                                </div>

                                                <div class="mt-4 text-center">
                                                    <button class="btn w-md waves-effect waves-light" type="submit" style="background:#84dc04">Log In</button>
                                                </div>

                                                <div class="mt-4 text-center">
                                                    <a href="{{route('admin.forget.password.get')}}" class="text-muted"><i class="mdi mdi-lock me-1"></i> Forgot your password?</a>
                                                </div>
                                            </form>
                                        </div>

                                        <div class="mt-5 text-center">
                                            <p>Don't have an account ? <a href="https://wa.me/918299155755?text=Register" class="fw-medium text-primary" target="_blank">
                                                    Register
                                                </a>

                                            <p>© <script>
                                                    document.write(new Date().getFullYear())
                                                </script> LoanJar. Crafted <i class="mdi mdi-heart text-danger"></i> by <a href="https://www.chetanatech.in/" target="_blank">Chetana Tech</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="authentication-bg">
                        <div class="bg-overlay"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- JAVASCRIPT -->
    <script src="{{asset('assets/libs/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/libs/metismenu/metisMenu.min.js')}}"></script>
    <script src="{{asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
    <script src="{{asset('assets/libs/node-waves/waves.min.js')}}"></script>

    <script src="{{asset('assets/js/app.js')}}"></script>
    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function (e) {
            const passwordField = document.getElementById('userpassword');
            const eyeIcon = document.getElementById('eyeIcon');
            
            // Toggle the type of the password field
            if (passwordField.type === "password") {
                passwordField.type = "text";
                eyeIcon.classList.remove("ri-eye-off-line");
                eyeIcon.classList.add("ri-eye-line");
            } else {
                passwordField.type = "password";
                eyeIcon.classList.remove("ri-eye-line");
                eyeIcon.classList.add("ri-eye-off-line");
            }
        });
    </script>
</body>
<script src="{{asset('assets/bundles/mainscripts.bundle.js')}}"></script>
<script src="{{asset('assets/bundles/libscripts.bundle.js')}}"></script>
<script src="{{asset('assets/bundles/toastr.bundle.js')}}"></script>
<script src="{{asset('assets/js/pages/index.js')}}"></script>
<!--- Validation CDN --->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.20.0/jquery.validate.min.js" integrity="sha512-WMEKGZ7L5LWgaPeJtw9MBM4i5w5OSBlSjTjCtSnvFJGSVD26gE5+Td12qN5pvWXhuWaWcVwF++F7aqu9cvqP0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $('input').keypress(function(e) {
        if (this.value.length === 0 && e.which === 32) e.preventDefault();
    });
    $('textarea').keypress(function(e) {
        if (this.value.length === 0 && e.which === 32) e.preventDefault();
    });
    $('input[name="mobile"]').on('input', function() {
        $(this).val($(this).val().replace(/\D/g, '')); // Remove non-digits
        if ($(this).val().length > 10) {
            $(this).val($(this).val().substr(0, 10)); // Limit to 10 digits
        }
    });
    $('form').validate({

        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 5,
                password: true

            },

        },
        errorElement: 'span',
        errorPlacement: function(error, element) {

            error.addClass('text-danger');
            error.insertAfter(element);

        },
        highlight: function(element) {
            $(element).addClass('is-invalid mb-1');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid mb-1');
        }
    });
</script>

<!-- toastr init -->
<script>
    @if(Session::has('message'))
    var messageType = '{{ Session::get("status") }}';
    var message = '{{ Session::get("message") }}';

    toastr[messageType](message, '', {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": 300,
        "hideDuration": 1000,
        "timeOut": 5000,
        "extendedTimeOut": 1000,
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    });
    @endif


    @if(Session::has('success'))
    toastr.success('{{ Session::get("success") }}', '', {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": 300,
        "hideDuration": 1000,
        "timeOut": 5000,
        "extendedTimeOut": 1000,
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    });
    @endif


    @if(Session::has('error'))
    toastr.error('{{ Session::get("error") }}', '', {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": 300,
        "hideDuration": 1000,
        "timeOut": 5000,
        "extendedTimeOut": 1000,
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    });
    @endif
</script>

</html>