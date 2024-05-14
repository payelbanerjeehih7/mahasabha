<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
<body>
	<div class="container">
		<form  id="loginForm">
			@csrf
			@if(session('message'))
			<div class="alert alert-info">{{session('message')}}</div>
			@endif
			<header class="modal-header alert alert-primary"><h1>Login Page</h1></header>
			<div class="form-group">
				<label>Email</label>
				<input type="text" id="email" name="email" class="form-control">
            <div id="email_error" class="error"></div>
            @error('email')
               <small class="text-danger">{{ $message }}</small>
            @enderror
			</div>
			<div class="form-group">
				<label>Password</label>
				<input type="password" id="password" name="password" class="form-control">
            <div id="password_error" class="error"></div>
            @error('password')
               <small class="text-danger">{{ $message }}</small>
            @enderror
			</div>

			<div class="form-group" id="otpDiv" style="display:none;">
            <label>One Time Password</label>
            <input type="number" name="otp" id="otp" class="form-control">
        </div>
        <div id="sendOtp" class="form-group">
            <input type="submit" name="submit" value="Send OTP" class="btn btn-success btn-lg">
            <input type="reset" name="reset" value="Reset" class="btn btn-danger btn-lg">
        </div>
        <div id="finalSubmit" class="form-group" style="display:none;">
            <input type="submit" name="finalSubmitbutton" id="finalSubmitbutton" value="Submit" class="btn btn-success btn-lg">
            <input type="reset" name="reset" value="Reset" class="btn btn-danger btn-lg">
        </div>
		</form>
	</div>
   <script>
      $(document).ready(function() {
         $('.text-danger').remove();

         $('input[name="email"], input[name="password"]').on('input', function() {
            $(this).siblings('.text-danger').text('').css('color', 'black');
        });

          function showErrorSwal(message) {
              Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: message,
                  customClass: {
                      popup: 'my-swal-popup',
                      icon: 'my-swal-icon',
                      title: 'my-swal-title',
                      content: 'my-swal-content',
                      text: 'my-swal-content',
                      confirmButton: 'my-swal-confirm-button'
                  }
              });
          }
          $("#loginForm").submit(function(e) {
              e.preventDefault();
              let email = $('#email').val();
               let password = $('#password').val();

              // Perform your Ajax request here
              $.ajax({
                  type: "POST",
                  url: "{{ url('/save') }}",
                  data: {
                        "_token": "{{ csrf_token() }}",
                        email:email,
                        password: password,
                  },
                  success: function(response) {
                     if(response.status=='error'){
                        showErrorSwal(response.message);
                           $('#email').prop('enabled', true);
                          $('#password').prop('enabled', true);
                          $('#otpDiv').hide();
                          $('#sendOtp').show();
                          $('#finalSubmit').hide();
                        // console.log(response);

                     }else{
                      
                          $('#email').prop('disabled', true);
                          $('#password').prop('disabled', true);
                          $('#otpDiv').show();
                          $('#sendOtp').hide();
                          $('#finalSubmit').show();
                          
                          otp = response[0]['otp'];
                          // console.log(otp);
                          Swal.fire({
                              icon: 'success',
                              title: 'Your One-time password',
                              text: otp,
                              customClass: {
                                  popup: 'my-swal-popup',
                                  icon: 'my-swal-icon',
                                  title: 'my-swal-title',
                                  content: 'my-swal-content',
                                  text: 'my-swal-content',
                                  confirmButton: 'my-swal-confirm-button'
                              }
                          });
                        }
                      
                  },
                  error: function (error) {
                  // Clear existing error messages
                  $('.text-danger').remove();

                  // Display validation errors below each input field
                  
                  $.each(error.responseJSON.errors, function (field, messages) {
                      var inputField = $('#' + field);
                      inputField.after('<small class="text-danger">' + messages[0] + '</small>');
                  });
              }
              });
          });
          $(document).on('click', '#finalSubmitbutton', function(e) {
          e.preventDefault();
          // Getting values from input fields
          let email = $('#email').val();
          let password = $('#password').val();
          let otp = $('#otp').val();
          $.ajax({
              type: 'POST',
              url: '{{ url('/verifyAndLogin') }}',
              data: {
                  "_token": "{{ csrf_token() }}",
                  email:email,
                  password: password,
                  otp: otp
              },
              success: function(response) {
                  // console.log(response);
                  // console.log(response.user[0].id);
                  // var idNumber = response.user[0].id;
                      // Redirect to the dashboard page with the phone number as a query parameter
                  window.location.href = '{{ url('/index') }}'; //
              },
              error: function(xhr, status, error) {
                      var response = JSON.parse(xhr.responseText);
                      if (xhr.status === 404 && response.status === 'error') {
                          showErrorSwal(response.message);
                      } else {
                          $.each(response.errors, function(field, message) {
                              $('#' + field + '_error').text(message[0]);
                          });
                      }
                  }
          });
          });
      });
  </script>
</body>
</html>