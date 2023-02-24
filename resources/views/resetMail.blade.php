<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Mail</title>
</head>
<body>
    <h3>Welcome to Bulletin_board , {{$user['name']}}</h3>
    <h4>Your user name is : {{$user['name']}}</h4>
    <a href="http://127.0.0.1:8000/reset-password/{{$token}}">http://127.0.0.1:8000/api/reset-password/{{$token}}</a>
    <p>Click the link and reset the password</p>
    <p>Thanks for joining hava a great day</p>   
</body>
</html>