<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Form</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        /* Hide the up/down arrows */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
  
        /* Hide the side scrollbar */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
</head>
<body>
	<div class="container">
    <div id="success-message" class="alert alert-success" style="display: none;"></div>
		<form id="myForm"  enctype="multipart/form-data">
			@csrf
			<header class="modal-header alert alert-primary"><h1>Registration</h1></header>
			<div class="form-group">
				<label>Name</label>
				<input type="text" name="name" id="name" class="form-control">
                <small id="name_error" class="error"></small>
			</div>
			<div class="form-group">
				<label>Email</label>
				<input type="text" name="email" id="email" class="form-control">
                <small id="email_error" class="error"></small>
			</div>
            <div class="form-group">
				<label>Password</label>
				<input type="password" name="password" id="password" class="form-control">
                <small id="password_error" class="error"></small>
			</div>
			<div class="form-group">
				<label>Phone</label>
				<input type="number" name="phone" id="phone" class="form-control">
                <small id="phone_error" class="error"></small>
			</div>
			
            <div class="form-group">
				<label>Gender</label>
				<label class="btn btn-secondary">
				<input type="radio" name="gender" id="gender_male" autocomplete="off" value="Male">Male
				</label>
				<label class="btn btn-secondary">
				<input type="radio" name="gender" id="gender_female" autocomplete="off" value="Female">Female
				</label>
				<label class="btn btn-secondary">
				<input type="radio" name="gender" id="gender" autocomplete="off" value="Others">Others
				</label>
                <div><small id="gender_error" class="error"></small></div>
                
			</div>
            <div class="form-group">
                <label>Date of Birth</label>
                <input type="date" name="dob" id="dob" class="form-control">
                <small id="dob_error" class="error"></small>
            </div>
			<div class="form-group">
				<label>Address</label>
				<input type="text" name="address" id="address" class="form-control">
                <small id="address_error" class="error"></small>
			</div>
			<div class="form-group">
				<label>City</label>
				<input type="text" name="city" id="city" class="form-control">
                <small id="city_error" class="error"></small>
			</div>
			<div class="form-group">
				<label>State</label>
				<input type="text" name="state" id="state" class="form-control">
                <small id="state_error" class="error"></small>
			</div>

            <div class="form-group">
                <label>Date of Marriage</label>
                <input type="date" name="dom" id="dom" class="form-control">
            </div>
            <div class="form-group">
				<label>Profile Picture</label>
				<input type="file" name="file" id="file" class="form-control">
			</div>
			
			<div class="form-group">
				<input type="submit" name="submit" id="submit" value="Submit" class="btn btn-success btn-lg" >
				<input type="reset" name="reset" value="Reset" class="btn btn-danger btn-lg">
			</div>
		</form>
	</div>
    <script>    
    $(document).ready(function () {

        $('input[name="name"], input[name="password"],input[name="phone"],input[name="dob"],input[name="gender"],input[name="address"],input[name="city"],input[name="state"]').on('input', function() {
            $(this).siblings('.error').text('').css('color', 'black');
        });
        
        $('input[name="gender"]').on('change', function() {
            $('#gender_error').text('').css('color', 'black');
        });

        $('#email').on('input', function() {
            var email = $(this).val();
            var emailRegex = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$/;
            if (email === '') {
                $('#email_error').text('Email field is required').css('color', 'red');
            } else if (!emailRegex.test(email)) {
                $('#email_error').text('Invalid email format').css('color', 'red');
            } else {
                $('#email_error').text('');
            }
        });

        
        $('#phone').on('input', function() {
            var phone = $(this).val();
            var phoneRegex = /^\d{10}$/;
            if (phone === '') {
                $('#phone_error').text('Phone field is required').css('color', 'red');
            } else if (!phoneRegex.test(phone)) {
                $('#phone_error').text('Phone number must be 10 digits').css('color', 'red');
            } else {
                $('#phone_error').text('');
            }
        });

        //stop after 10th digit
        $('#phone').on('keydown', function(event) {
            if (this.value.length >= 10 && event.key !== 'Backspace' && event.key !== 'Delete') {
                event.preventDefault();
            }
        });

        $('#myForm').submit(function (e) {
            e.preventDefault();
            let isValid = true;

            $('.text-danger').text('');

            // Validate each field
            if ($('#name').val() === '') {
                    $('#name_error').text('Name field is required').css('color', 'red');
                    isValid = false;
                }
            if ($('#email').val() === '') {
                $('#email_error').text('Email field is required').css('color', 'red');
                isValid = false;
            }
            if ($('#password').val() === '') {
                $('#password_error').text('Password field is required').css('color', 'red');
                isValid = false;
            }
            if ($('#phone').val() === '') {
                $('#phone_error').text('Phone field is required').css('color', 'red');
                isValid = false;
            }
            if (!$('input[name="gender"]:checked').val()) {
                $('#gender_error').text('Gender field is required').css('color', 'red');
                isValid = false;
            }
            if ($('#dob').val() === '') {
                $('#dob_error').text('Date of Birth field is required').css('color', 'red');
                isValid = false;
            }
            if ($('#address').val() === '') {
                $('#address_error').text('Address field is required').css('color', 'red');
                isValid = false;
            }
            if ($('#city').val() === '') {
                $('#city_error').text('City field is required').css('color', 'red');
                isValid = false;
            }
            if ($('#state').val() === '') {
                $('#state_error').text('State field is required').css('color', 'red');
                isValid = false;
            }
            if (isValid) {

            let name = $('#name').val();
            let email = $('#email').val();
            let password = $('#password').val();
            let phone = $('#phone').val();
            let dob = $('#dob').val();
            let gender = $('input[name="gender"]:checked').val();
            let address = $('#address').val();
            let city = $('#city').val();
            let state = $('#state').val();
            let dom=$('#dom').val();
            let fileInput = $('#file')[0].files[0];

            // Create a new FormData object
            let formData = new FormData();
            formData.append('_token', "{{ csrf_token() }}");
            formData.append('name', name);
            formData.append('email', email);
            formData.append('password', password);
            formData.append('phone', phone);
            formData.append('dob', dob);
            formData.append('gender', gender);
            formData.append('address', address);
            formData.append('city', city);
            formData.append('state', state);
            formData.append('dom', dom);
            formData.append('file', fileInput);
            

            $.ajax({
                type: 'POST',
                url: '{{url('/submit')}}',
                data: formData,
                processData: false, // Important for FormData
                contentType: false, // Important for FormData
                success: function (response) {
                    if(response.status == 'success' && response.message == 'Inserted data successfully'){
                        // Hide all error messages within the form
                        Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                            }).then((result) => {
                            if (result.isConfirmed) {
                                // Redirect to dashboard
                                window.location.href = '{{ url("/signup") }}';
                            }
                        });
                    }else{
                        Swal.fire({
                                icon: 'error',
                                title: 'error',
                                text: response.message,
                                
                            }).then((result) => {
                            if (result.isConfirmed) {
                                // Redirect to dashboard
                                window.location.href = '{{ url("/login") }}';
                            }
                        });
                    }
                    
                },
                error: function (error) {
                    // Clear existing error messages
                    $('.text-danger').remove();

                    // Display validation errors below each input field
                    $.each(error.responseJSON.errors, function (field, messages) {
                        if (field === 'gender') {
                          // Display validation error message below the payout option field
                          $('#gender-error').remove(); // Remove previous error message
                          $('input[name="gender"]').after('<small class="text-danger">' + messages[0] + '</small>');
                      } else {
                        var inputField = $('#' + field);
                        inputField.after('<small class="text-danger">' + messages[0] + '</small>');
                      }
                    });
                    
                }
            });
            }
        });
    });
</script>

</body>
</html>
