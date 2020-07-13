<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gaea</title>
</head>
<body>
<h3 style="border-bottom: 1px dotted">哈喽，{{ $label }}</h3>
<p style="border-bottom: 1px dotted;text-indent: 30px">您刚刚获取并生成了新密码
    <span style="background: black;margin: 0 5px;color: white;font-size: 25px;text-align: center;">
        {{ $password }}
    </span>，如非本人请忽略!
</p>
<span style="font-size: 15px;color: red;display: block;margin-bottom: 30px;">为确保安全，使用邮箱和此密码登陆后请修改密码！</span>

<span>系统邮件，请勿回复</span>
</body>
</html>
