<!DOCTYPE html>
<html>
<head>
    <title>Vaš nalog je kreiran</title>
</head>
<body>
    <h1>Pozdrav {{ $username }}</h1>
    <p>Vaš nalog je uspešno napravljen.</p>
    <p>Email: {{ $email }}</p>
    <p>Lozinka: {{ $password }}</p>
    <p>Možete se prijaviti na <a href="{{ route('admin.login') }}">ovoj stranici</a>.</p>
</body>
</html>