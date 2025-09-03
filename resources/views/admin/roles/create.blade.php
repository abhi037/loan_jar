<x-app-layout :title="$title">
@push('css')
<style>
    .dropify-wrapper {
        height: 150px !important;
    }

    .profile .dropify-wrapper {
        height: 200px !important;
    }
</style>
@endpush
<div id="main-content">
       <div class="page-content">
    <div class="container-fluid">
        <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Add Role</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Add Role</a></li>
                                    <li class="breadcrumb-item active">Add Role</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
       
        <div class="row clearfix">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form id="addForm" action="{{ route('admin.role.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-2">

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label">Role Name <small class="text-danger">*</small></label>
                                        <input type="text" class="form-control" placeholder="Role Name" name="name" value="{{ old('name') }}">
                                        @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row my-2">
                                <div class="col-md-12">
                                    <button class="mt-3 btn btn-primary form-btn" id="videoBtn" type="submit">SAVE <i class="fa fa-spin fa-spinner" id="videoSpin" style="display:none;"></i></button>
                                    <a class="text-white mt-3 btn btn-danger form-btn" href="{{ route('admin.role.index')}}">Cancel</a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> </div>
</div>
@push('scripts')
<script>
    $(document).ready(function() {
        $('#addForm').validate({
            ignore: ":hidden:not('.validate-hidden')",
            rules: {
                name: {
                    required: true,
                    remote: {
                        url: "{{ route('admin.checkRoleName') }}",
                        type: "post",
                        data: {
                            name: function() {
                                return $("input[name='name']").val();
                            },
                            id: function() {
                                return $("input[name='id']").val();
                            },
                            _token: "{{ csrf_token() }}"
                        },
                        dataFilter: function(response) {
                            var data = JSON.parse(response);
                            if (data.isDuplicate) {
                                return JSON.stringify("Role name already exists");
                            } else {
                                return true;
                            }
                        }
                    }
                },
            },
            messages: {
                name: {
                    required: "Please enter the role",
                    remote: "Role name already exists"
                },
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('text-danger');
                if (element.attr("name") == "nametype") {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function(element) {
                $(element).addClass('is-invalid mb-1');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid mb-1');
            }
        });

    });
</script>
@endpush
</x-app-layout>