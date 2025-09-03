<x-app-layout :title="$title">
@push('css')
<style>
    .card-header {
        padding: 1rem 1.35rem;
        margin-bottom: 0;
        background-color: rgba(33, 40, 50, 0.03) !important;
        border-bottom: 1px solid rgba(33, 40, 50, 0.125) !important;
    }

    .file-upload {
        display: none;
    }

    .circle {
        border-radius: 100% !important;
        overflow: hidden;
        width: 128px;
        height: 128px;
        border: 2px solid rgba(255, 255, 255, 0.2);
        position: absolute;
        top: 72px;
    }

    .img-account-profile {
        width: 120px;
        height: 150px;

    }

    .profile-img-wrap.edit-img {
        border-radius: 50%;
        margin: 0 auto 30px;
        position: relative;
    }

    .profile-view .profile-img-wrap {
        height: 130px;
        width: 130px;
        position: absolute;
    }

    .profile-img-wrap {
        height: 120px;
        position: absolute;
        width: 120px;
        background: #ffffff;
        overflow: hidden;
    }

    .profile-img-wrap .fileupload {
        background: rgba(33, 33, 33, 0.5) !important;
        border: none !important;
        padding: 3px 10px !important;
        border-radius: 0 !important;
        position: absolute !important;
        right: 0 !important;
        bottom: 0 !important;
        left: 0 !important;
    }

    .fileupload .upload {
        cursor: pointer;
        filter: alpha(opacity=0) !important;
        font-size: 20px;
        opacity: 0;
        margin: 0;
        padding: 5px;
        position: absolute;
        top: -3px;
        right: -3px;
    }

    .fileupload.btn:hover {
        color: white !important;
        font-weight: 600;
    }
</style>
<div id="main-content" class="profilepage_2 blog-page">
    <div class="page-content">
    <div class="container-fluid">
 <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Profile</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Profile</a></li>
                                    <li class="breadcrumb-item active">Profile</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
       

        <div class="row g-3">
                 
            <div class="col-lg-12 col-md-12">
                <div class="card mb-3">
                                                <div class="row">
                                <form id="updateProfileForm" method="POST" action="{{ route('admin.updateProfileImg') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="profile-img-wrap edit-img">

                                <a href="{{ !empty(auth()->user()->icon) ? asset('storage/' . auth()->user()->icon) : asset('assets/images/user.jpg') }}" target="_blank">
                                    <img class="img-account-profile rounded-circle mb-2" src="{{ !empty(auth()->user()->icon) ? asset('storage/' . auth()->user()->icon) : asset('assets/images/user.jpg') }}" alt="">
                                </a>

                                <div class="fileupload btn">
                                    <span class="btn-text text-white"><i class="fa fa-camera"></i></span>
                                    <input id="imageInput" class="upload" type="file" name="icon">
                                </div>
                            </div>
                        </form>
                            </div>
              
                    <div class="card-body">
                        <form id="profileForm" action="{{ route('admin.updateProfile') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('patch')

                            <div class="row">
                                <div class="col-sm-12 main-width-box w-100" id="l-section-list">
                                    <input type="hidden" name="id" value="{{ auth()->user()->id }}" />
                                    <div class="row mb-3">
                                        <div class="col-sm-6">
                                            <label>Name</label>
                                            <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}">
                                            @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6">
                                            <label>Email</label>
                                            <input type="text" class="form-control" name="email" value="{{ old('email', auth()->user()->email) }}">
                                            @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>Address</label>
                                            <textarea class="form-control" name="address">{{ old('address', auth()->user()->address) }}</textarea>
                                            @error('address')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6">
                                            <label>Mobile</label>
                                            <input type="number" name="mobile" class="form-control" value="{{ old('mobile', auth()->user()->mobile) }}">
                                            @error('mobile')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row m-2">
                                <div class="col-md-12">
                                    <button class="mt-3 btn btn-primary p-2 form-btn" id="formBtn">SAVE <i class="fa fa-spin fa-spinner" id="formSpin" style="display:none;"></i></button>
                                    <button class="mt-3 btn btn-danger p-2 form-btn" id="cancelBtn"><a class="text-white" href="">Cancel</a></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

        </div>
 </div>

    </div>
</div>
@push('scripts')
<script>
    $(document).ready(function() {
        $('#imageInput').change(function() {
            // alert(1);
            $('#updateProfileForm').submit();
        });
    });

    $('input[name="mobile"]').on('input', function() {
        $(this).val($(this).val().replace(/\D/g, '')); 
        if ($(this).val().length > 10) {
            $(this).val($(this).val().substr(0, 10)); 
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
            mobile: {
                required: true,
            },
            address: {
                required: true,
            },


        }
    });
</script>
<script>
    function removeReadonly() {
        $('input').removeAttr('readonly');
        $('textarea').removeAttr('readonly');
        $('.pro-edit').addClass('d-none');
        $('.submit-btn').removeClass('d-none');


    }
</script>

<script>
    $(document).ready(function() {
        
        $('#profileForm').on('submit', function(e) {
            var formAction = $(this);
            var btnAction = $('#formBtn');
            var spinAction = $('#formSpin');
            e.preventDefault();
            var data = new FormData(this);

            $.ajax({
                type: 'POST',
                url: $(formAction).attr('action'),
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $(btnAction).attr("disabled", true);
                    $(spinAction).show();
                },
                success: function(response) {
                    
                    var response = JSON.parse(response);
                    $(btnAction).removeAttr("disabled");
                    $(spinAction).hide();
                    if (response[0].res == 'success') {
                      
                        toastr.success(response[0].msg, '', {
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
                        if (response[0].redirect) {
                            window.setTimeout(function() {
                                window.location.href = response[0].redirect;
                            }, 3000);
                        }
                    } else if (response[0].res == 'error') {
                        
                     
                        toastr.error(response[0].msg, '', {
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
                    }
                },
                error: function(xhr) {
                 
                    $(btnAction).removeAttr("disabled");
                    $(spinAction).hide();
                    var errorMessage = "Failed to update profile";
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        $('.text-danger').remove();
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            $('[name="' + key + '"]').after('<span class="text-danger">' + value[0] + '</span>');
                        });
                    } else {
                        
                        toastr.error(errorMessage, '', {
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
                    }
                }
            });

        });
    });
</script>
@endpush
</x-app-layout>
