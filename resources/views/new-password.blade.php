<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forget Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
<body>
    <div class="container">
    <form  id="loginForm" method="POST" action="{{route('reset.password.post')}}">
        @csrf
        <input type="text" name="token" id="token" value="{{$token}}" hidden>
        {{-- <input type="text" name="token" id="token" value="{{$email}}"> --}}
        @if(session('message'))
        <div class="alert alert-info">{{session('message')}}</div>
        @endif
        <header class="modal-header alert alert-primary"><h1>Forget Password Page</h1></header>
        <div class="form-group">
            <label>Email</label>
            <input type="text" id="email" name="email" class="form-control" value="{{$email}}" readonly>
        <div id="email_error" class="error"></div>
        @error('email')
           <small class="text-danger">{{ $message }}</small>
        @enderror
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="text" id="password" name="password" class="form-control">
        <div id="password_error" class="error"></div>
        @error('password')
           <small class="text-danger">{{ $message }}</small>
        @enderror
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="text" id="password_confirmation" name="password_confirmation" class="form-control">
        <div id="password_confirmation_error" class="error"></div>
        @error('password_confirmation')
           <small class="text-danger">{{ $message }}</small>
        @enderror
        </div>
        <div class="form-group">
            <input type="submit" name="submit" value="Submit" class="btn btn-success btn-lg">
            <input type="reset" name="reset" value="Reset" class="btn btn-danger btn-lg">
        </div>
    </form>
</div>
</body>
</html>