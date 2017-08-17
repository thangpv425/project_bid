<!DOCTYPE html>
<html>
<head>
    <title>Page Title</title>
</head>
<body>
<p>
    Chào bạn {{$data['nickname']}}, bạn vừa yêu cầu đổi email thành {{$data['new_email']}}, hãy click vào link dưới đây để
    xác nhận.
</p>

<a href="{{$data['link']}}">Click here</a>

</body>
</html>