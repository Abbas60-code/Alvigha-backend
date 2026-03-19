<!DOCTYPE html>
<html>
<head>
    <title>Contact Us</title>
</head>
<body>

@if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif

<form action="{{ url('/contact') }}" method="POST">
    @csrf
    <input type="text" name="name" value="Test">
    <input type="email" name="email" value="t@t.com">
    <input type="text" name="phone" value="123">
    <textarea name="message">Test</textarea>
    <input type="submit" value="Send">  {{-- button ki jagah input use karo --}}
</form>

</body>
</html>