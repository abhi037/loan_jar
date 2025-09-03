<x-app-layout :title="'Change Password'">
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    b {
        font-weight: 500 !important;
    }

    .card-header {
        padding: 1rem 1.35rem;
        margin-bottom: 0;
        background-color: rgba(33, 40, 50, 0.03) !important;
        border-bottom: 1px solid rgba(33, 40, 50, 0.125) !important;
    }
</style>
@endpush
<div id="main-content" class="profilepage_2 blog-page">
    <div class="container-fluid">
    <div class="page-content">
        <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Change Password </h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Change Password  </a></li>
                                    <li class="breadcrumb-item active">Change Password  </li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>

        <div class="row g-3">
            <div class="col-md-12">
                <div class="card">
               
                    <div class="card-body">

                        <form action="{{route('admin.changePassworddata')}}" method="post">
                            @csrf
                            @method('PUT')

                            <div class="row">

                                <div class="col-sm-12">
                                    <label>Current Password *</label>
                                    <input class="form-control" type="password" name="current-password" value="{{old('current-password')}}">
                                   @error('current-password')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-sm-12">
                                    <label>New Password *</label>
                                    <input class="form-control" type="password" name="new-password" value="{{old('new-password')}}">
                                    @error('new-password')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-sm-12">
                                    <label>Confirm Password *</label>
                                    <input class="form-control" type="password" name="password_confirmation" value="{{old('password_confirmation')}}">
                                    @error('password_confirmation')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                            <div class="row my-2">
                                <div class="col-md-12">
                                    <button class="mt-3 btn btn-primary  p-2 form-btn" id="videoBtn">SAVE <i
                                            class="fa fa-spin fa-spinner" id="videoSpin"
                                            style="display:none;"></i></button>
                                    <button class="mt-3 btn btn-danger  p-2 form-btn" id=""><a class="text-white"
                                            href="">Cancel</a></button>
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
</div>
@push('scripts')
<script src="{{asset('assets/js/pages/profile.js')}}"></script>
@endpush
</x-app-layout>