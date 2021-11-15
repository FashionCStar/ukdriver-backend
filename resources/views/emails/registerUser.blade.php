<!DOCTYPE html>
<html>
<head>
  <title>Please check new user</title>
</head>

<body style="max-width: 600px">
<div style="padding: 10px;">
  <div style="text-align: center">
    {{--<img src="localhost:8080/img/logo.png" style="width: 300px" />--}}
    <p>Please check this new user</p>
  </div>
  <p style="font-size: 24px">Hi Lukas</p>
  <p style="font-size: 16px;">
    I want to register UkCourier App with the email address ({{$email}}).
    Please check user details
    
    <br/>
    email: {{$email}}
    <br/>
    mobile: {{$mobile}}
    <br/>
    password: {{$password}}
  </p>
  
  <br/>
  <p style="font-size: 20px;">
    Regards <br/>
  </p>
</div>
</body>

</html>
