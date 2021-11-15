<!DOCTYPE html>
<html>
<head>
  <title>Please check your login details</title>
</head>

<body style="max-width: 600px">
<div style="padding: 10px;">
  <div style="text-align: center">
    {{--<img src="localhost:8080/img/logo.png" style="width: 300px" />--}}
    <p>Please check this information</p>
  </div>
  <p style="font-size: 24px">Hi </p>
  <p style="font-size: 16px;">
    You have registered UkCourier App with these login credentials.
    Please check this information and use for login.
    <br/>
    mobile: {{$mobile}}
    <br/>
    password: {{$password}}
  </p>
  
  <br/>
  <p style="font-size: 20px;">
    Regards, <br/>
    The UKCourier Team
  </p>
</div>
</body>

</html>
