@include('email.inc.header')

<div class="content-body" style="font-family: Arial, sans-serif; color: #333; padding: 20px;">
    <div style="text-align: center; padding: 20px;">
        <img class="header-logo" src="{{ asset('logo.png') }}" alt="Logo" style="max-width: 150px; margin-bottom: 20px;">
        <h1 style="font-size: 24px; color: #4CAF50;">Welcome</h1>
    </div>

    <p>Dear {{ $name }},</p>
    <p>We welcome you</p>

    <div style="text-align: center; margin: 20px 0;">
        <a href="{{ $dashboardLink }}" style="background-color: #4CAF50; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-size: 16px;">Dashboard</a>
    </div>
</div>

@include('email.inc.footer')
