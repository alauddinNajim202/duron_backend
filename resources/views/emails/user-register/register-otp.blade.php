@extends('emails.layout.master_layout')

@section('header-title', 'Email Verification')
@section('header-subtitle', 'Account Registration')

@section('styles')
<style type="text/css">
    /* ── Override: Premium Header ── */
    .header {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%) !important;
        padding: 36px 48px !important;
        border-bottom: none !important;
    }

    .header-title {
        color: #ffffff !important;
        font-size: 20px !important;
        font-weight: 700 !important;
        letter-spacing: 0.3px;
    }

    .header-subtitle {
        color: #94a3b8 !important;
        font-size: 12px !important;
        text-transform: uppercase !important;
        letter-spacing: 1.5px !important;
        margin-top: 6px !important;
    }

    /* ── Premium OTP Block ── */
    .otp-wrapper {
        background: linear-gradient(145deg, #f8faff 0%, #eef2ff 100%);
        border: 1px solid #c7d2fe;
        border-radius: 12px;
        padding: 40px 32px;
        text-align: center;
        margin: 32px 0;
        box-shadow: 0 4px 24px rgba(15, 23, 42, 0.07);
        position: relative;
        overflow: hidden;
    }

    .otp-wrapper::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #3b82f6, #6366f1, #8b5cf6);
    }

    .otp-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: #6366f1;
        margin-bottom: 16px;
    }

    .otp-code {
        font-size: 44px;
        font-weight: 800;
        letter-spacing: 14px;
        color: #0f172a;
        font-family: 'Courier New', Courier, monospace;
        line-height: 1;
        padding: 0 8px;
    }

    .otp-validity {
        margin-top: 16px;
        font-size: 12px;
        color: #64748b;
        font-weight: 500;
    }

    .otp-validity strong {
        color: #4f46e5;
    }

    /* ── Section Title ── */
    .section-title {
        font-size: 22px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 8px;
        letter-spacing: -0.3px;
    }

    .section-divider {
        width: 40px;
        height: 3px;
        background: linear-gradient(90deg, #3b82f6, #6366f1);
        border-radius: 2px;
        margin-bottom: 24px;
    }

    /* ── Greeting ── */
    .greeting {
        font-size: 15px;
        color: #334155;
        margin-bottom: 12px;
        font-weight: 500;
    }

    .body-text {
        font-size: 14px;
        color: #475569;
        line-height: 1.75;
        margin-bottom: 0;
    }

    /* ── Security Notice ── */
    .security-box {
        background-color: #fafafa;
        border: 1px solid #e2e8f0;
        border-left: 4px solid #3b82f6;
        border-radius: 0 8px 8px 0;
        padding: 20px 24px;
        margin: 28px 0;
    }

    .security-box-title {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: #3b82f6;
        margin-bottom: 14px;
    }

    .security-list {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .security-list li {
        font-size: 13px;
        color: #475569;
        padding: 6px 0 6px 20px;
        position: relative;
        line-height: 1.55;
        border-bottom: 1px solid #f1f5f9;
    }

    .security-list li:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .security-list li::before {
        content: "";
        position: absolute;
        left: 0;
        top: 13px;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background-color: #3b82f6;
    }

    /* ── Signature ── */
    .signature {
        margin-top: 36px;
        padding-top: 24px;
        border-top: 1px solid #f1f5f9;
    }

    .signature p {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 4px;
    }

    .signature .sig-name {
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
    }

    /* ── Footer Override ── */
    .footer {
        background-color: #f8fafc !important;
        border-top: 1px solid #e2e8f0 !important;
        padding: 28px 48px !important;
    }

    .footer-tagline {
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 14px !important;
    }

    @media only screen and (max-width: 600px) {
        .header {
            padding: 28px 24px !important;
        }
        .otp-code {
            font-size: 34px !important;
            letter-spacing: 8px !important;
        }
        .otp-wrapper {
            padding: 28px 16px !important;
        }
        .footer {
            padding: 24px !important;
        }
    }
</style>
@endsection

@section('content')
    <div class="section-title">Verification Code</div>
    <div class="section-divider"></div>

    <p class="greeting">Hello {{ $user->profile->first_name ?? 'User' }},</p>

    <p class="body-text">
        Thank you for initiating your registration with <strong>{{ config('app.name') }}</strong>.
        To complete the verification process, please use the one-time password below.
    </p>

    <div class="otp-wrapper">
        <div class="otp-label">One-Time Password</div>
        <div class="otp-code">{{ $otp }}</div>
        <div class="otp-validity">Valid for <strong>60 minutes</strong> from the time of issue</div>
    </div>

    <div class="security-box">
        <div class="security-box-title">Security Notice</div>
        <ul class="security-list">
            <li>Never share this code with anyone, including {{ config('app.name') }} support staff.</li>
            <li>This code expires in 60 minutes and can only be used once.</li>
            <li>If you did not request this, please disregard this message — your account remains secure.</li>
        </ul>
    </div>

    <div class="signature">
        <p>Warm regards,</p>
        <span class="sig-name">The {{ config('app.name') }} Team</span>
    </div>
@endsection

@section('footer-tagline', config('app.name'))
@section('footer-address', 'Security &amp; Compliance Department')
