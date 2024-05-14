<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
<body>
    <div class="container">
        <h2>Change Password</h2>
        <form id="changePasswordForm">
            @csrf
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="password" id="password" class="form-control">
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
            </div>
            <div class="form-group">
                <input type="submit" value="Change Password" class="btn btn-primary">
            </div>
        </form>
    </div>
    <script>
    $(document).ready(function () {
        $('#changePasswordForm').submit(function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                type: 'POST',
                url: '{{ url("/change-password") }}',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.status == 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '{{ url("/login") }}';
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                        });
                    }
                },
                error: function (error) {
                  // Clear existing error messages
                  $('.text-danger').remove();
                  $.each(error.responseJSON.errors, function (field, messages) {
                      var inputField = $('#' + field);
                      inputField.after('<small class="text-danger">' + messages[0] + '</small>');
                  });
               }
            });
        });
    });
    </script>
</body>
</html>
