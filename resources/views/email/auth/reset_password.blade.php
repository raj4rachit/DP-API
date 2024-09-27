@include('email.inc.header')

<div class="content-body" style="font-family: Arial, sans-serif; color: #333; padding: 20px;">
    <div style="text-align: center; padding: 20px;">
        <img class="header-logo" src="{{ asset('logo.png') }}" alt="Logo" style="max-width: 150px; margin-bottom: 20px;">
        <h1 style="font-size: 24px; color: #4CAF50;">Reset Password</h1>
    </div>

    <p>Dear {{ $name }},</p>

    <p>We received a request to reset your password. If you made this request, please click the button below to reset your password:</p>

    <div style="text-align: center; margin: 20px 0;">
        <a href="{{ $link }}" style="background-color: #4CAF50; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-size: 16px;">Reset Password</a>
    </div>

    <p>If you didn't request this, you can safely ignore this email.</p>
</div>

@include('email.inc.footer')

