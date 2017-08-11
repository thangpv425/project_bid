<!DOCTYPE html>
<html>
<head>
    <title>Page Title</title>
</head>
<body>
<p>Chào bạn, bạn vừa yêu cầu đăng ký vào hệ thống với thông tin như sau</p>
<ul>
    <li>Nickname: {{$data['nickname']}}</li>
    <li>Email: {{$data['email']}}</li>
    <li>Password: {{$data['password']}}</li>
</ul>

<a href="{{$data['link']}}">Click here</a>

</body>
</html>