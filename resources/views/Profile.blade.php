<!DOCTYPE html>
<html>
<head>
    <title>Crud using Ajax</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <h1>Create User Profile Using Ajax</h1>
    <a class="btn btn-success" href="javascript:void(0)" id="createNewProfile">Create New User</a>
    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Role Name</th>
                <th width="300px">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<div class="modal fade" id="createModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading">Create New User</h4>
            </div>
            <div class="modal-body">
                <form id="createProfileForm" name="createProfileForm" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="" maxlength="50" required="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email" class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-12">
                            <input type="email" class="form-control" required id="email" name="email" placeholder="Enter Email" value="" required="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="phone" class="col-sm-2 control-label">Phone</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="phone" onkeypress="return isNumber(event)" name="phone" placeholder="Enter Phone" value="" required="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="role" class="col-sm-2 control-label">Role</label>
                        <div class="col-sm-12">
                            <select class="form-control" id="role" name="role_id">
                                <!-- Populate the options dynamically from the roles table -->
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->Name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="profile_image" class="col-sm-2 control-label">Profile Image</label>
                        <div class="col-sm-12">
                            <input type="file" class="form-control" id="profile_image" name="profile_image" onchange="previewImage(this)" required="">
                            <img id="preview" src="#" alt="Profile Image" style="display: none; max-width: 200px; max-height: 200px;">
                        </div>
                    </div>

                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary" id="createBtn" value="create">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editModelHeading">Edit User</h4>
            </div>
            <div class="modal-body">
                <form id="editProfileForm" name="editProfileForm" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="profile_id" id="profile_id">
                    <div class="form-group">
                        <label for="edit_name" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="edit_name" name="name" placeholder="Enter Name" value="" maxlength="50" required="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_email" class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-12">
                            <input type="email" class="form-control" id="edit_email" required name="email" placeholder="Enter Email" value="" required="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_phone" class="col-sm-2 control-label">Phone</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="edit_phone" onkeypress="return isNumber(event)" name="phone" placeholder="Enter Phone" value="" required="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_role" class="col-sm-2 control-label">Role</label>
                        <div class="col-sm-12">
                            <select class="form-control" id="edit_role" name="role_id">
                                <!-- Populate the options dynamically from the roles table -->
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->Name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_profile_image" class="col-sm-2 control-label">Profile Image</label>
                        <div class="col-sm-12">
                            <input type="file" class="form-control" id="edit_profile_image" name="profile_image" onchange="previewImage(this)">
                            <img id="edit_preview" src="#" alt="Profile Image" style="display: none; max-width: 200px; max-height: 200px;">
                        </div>
                    </div>

                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary" id="editBtn" value="edit">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 library -->
<script type="text/javascript">
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('profiles.index') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'name', name: 'name' },
                { data: 'phone', name: 'phone' },
                { data: 'email', name: 'email' },
                { data: 'role_name', name: 'role_name' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });

        $('#createNewProfile').click(function () {
            $('#createBtn').show();
            $('#editBtn').hide();
            $('#createProfileForm').trigger("reset");
            $('#preview').attr('src', '#').hide();
            $('#modelHeading').html("Create New User");
            $('#createModal').modal('show');
        });

        $('body').on('click', '.editBook', function () {
    var profile_id = $(this).data('id');
    $.ajax({
        url: "{{URL::to('/fetch')}}",
        type: "GET",
        data: { 'profile_id': profile_id },
        dataType: 'json',
        success: function (data) {
            $('#editBtn').show();
            $('#createBtn').hide();
            $('#editModelHeading').html("Edit User");
            $('#editModal').modal('show');
            $('#profile_id').val(data.id);
            $('#edit_name').val(data.name);
            $('#edit_email').val(data.email);
            $('#edit_phone').val(data.phone);
            $('#edit_role').val(data.role_id);
            $('#edit_preview').attr('src', '{{ asset('images/') }}/' + data.profile_image).show();
        },
        error: function (xhr, status, error) {
            console.log('Error:', xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong: ' + xhr.responseText.error,
            });
        }
    });
});


        $('#createBtn').click(function (e) {
            e.preventDefault();
            // $(this).html('Save');
            if (!$('#createProfileForm').valid()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please fill in all the required fields.',
                });
                return;
            }
            var phoneNumber = $('#phone').val();
            if (!phoneNumber.match(/^\d{10}$/)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Phone number should contain 10 digits.',
                });
                return;
            }
            var formData = new FormData($('#createProfileForm')[0]);

            $.ajax({
                data: formData,
                url: "{{ route('profiles.store') }}",
                type: "POST",
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function (data) {
                    $('#createProfileForm').trigger("reset");
                    $('#createModal').modal('hide');
                    table.draw();

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Profile has been created!',
                    });
                },
                error: function (xhr, status, error) {
                     console.log('Error:', xhr.responseText);
                   Swal.fire({
                    icon: 'error',
                    title: 'Duplicate Value', 
                    text: 'Duplicate Value Not Allowed',
                });
                }
            });
        });

        $('#editBtn').click(function (e) {
            e.preventDefault();
            // $(this).html('Save');
            if (!$('#editProfileForm').valid()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please fill in all the required fields.',
                });
                return;
            }
            var phoneNumber = $('#edit_phone').val();
            if (!phoneNumber.match(/^\d{10}$/)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Phone number should contain 10 digits.',
                });
                return;
            }
            var profile_id = $('#profile_id').val();
            var formData = new FormData($('#editProfileForm')[0]);

            $.ajax({
                url: "{{URL::to('/update')}}/" + profile_id,
                data: formData,
                type: "POST",
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (data) {
                    $('#editProfileForm').trigger("reset");
                    $('#editModal').modal('hide');
                    table.draw();

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Profile has been updated!',
                    });
                },
                error: function (xhr, status, error) {
                    console.log('Error:', xhr.responseText);
                    Swal.fire({
                    icon: 'error',
                    title: 'Duplicate Value', 
                    text: 'Duplicate Value Not Allowed',
                });
                }
            });
        });

        $('body').on('click', '.deleteBook', function () {
            var profile_id = $(this).data("id");
            confirm("Are you sure you want to delete!");

            $.ajax({
                type: "GET",
                url: "{{URL::to('/deletedata')}}",
                data: { 'profile_id': profile_id },
                success: function (data) {
                    table.draw();

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Profile has been deleted!',
                    });
                },
                error: function (xhr, status, error) {
                    console.log('Error:', xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong: ' + xhr.responseText,
                    });
                }
            });
        });
    });

    // Function to preview the chosen image
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $(input).siblings('img').attr('src', e.target.result).show();
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
</script>
</body>
</html>
