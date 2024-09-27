@include('email.inc.header')

<div class="content-body" style="font-family: Arial, sans-serif; color: #333;">
    <div style="text-align: center; padding: 20px;">
        <img class="header-logo" src="{{ asset('logo.png') }}" alt="Logo" style="max-width: 150px; margin-bottom: 20px;">
        <h1 style="font-size: 24px; color: #4CAF50;">Email Verification</h1>
    </div>

    <p>Dear {{ $name }},</p>
    <p>Thank you for registering with us. To ensure the security of your account, please use the verification code below:</p>
    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ $token }}" style="background-color: #4CAF50; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-size: 16px;">Verify Email</a>
    </div>

    <p>If you didn't request this, you can safely ignore this email.</p>
</div>

@include('email.inc.footer')
