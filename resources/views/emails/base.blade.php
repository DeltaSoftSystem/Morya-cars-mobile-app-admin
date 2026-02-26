<!DOCTYPE html>
<html>
<body style="font-family: Arial;">
    <h3>Hello {{ $data['name'] ?? 'User' }},</h3>

    <p>{{ $data['message'] }}</p>

    @isset($data['extra'])
        <p>{{ $data['extra'] }}</p>
    @endisset

    <br>
    <p>Regards,<br><strong>Morya Cars Team</strong></p>
</body>
</html>
