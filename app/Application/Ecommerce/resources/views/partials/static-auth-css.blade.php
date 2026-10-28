@php $dirIsRtl = $dirIsRtl ?? in_array(app()->getLocale(), ['ar', 'he', 'fa', 'ur'], true); @endphp
@if(app()->getLocale() === 'ar')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap/css/bootstrap.rtl.min.css') }}" id="bootstrap-css">
@else
    <link rel="stylesheet" href="{{ asset('assets/ecommerce/css/bootstrap.css') }}" id="bootstrap-css">
@endif
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    :root{--ink:#252525;--muted:#666;--line:#f1f3f7;--yellow:#306FAA;--input:#e8f0ff}*{box-sizing:border-box}body{margin:0;color:var(--ink);background:#fff;font:13px 'Cairo',Tahoma,sans-serif}a{text-decoration:none;color:inherit}button,input{font:inherit}button{border:0;cursor:pointer}.container{width:min(1260px,calc(100% - 40px));margin:auto}.topbar{height:74px;border-bottom:1px solid #f0f1f3;display:flex;align-items:center}.topbar-inner{display:flex;align-items:center;justify-content:space-between;gap:25px}.brand{min-width:172px}.brand img{display:block;width:175px;height:76px;object-fit:contain}.search{height:42px;max-width:535px;flex:1;display:flex;direction:ltr;border:1px solid var(--line);border-radius:9px;overflow:hidden}.search input{flex:1;border:0;outline:0;direction:{{ $dirIsRtl ? 'rtl' : 'ltr' }};padding:0 18px}.search button{width:48px;background:var(--yellow);font-size:18px;color:#fff}.top-actions{display:flex;align-items:center;gap:18px;color:#5d6269;direction:ltr}.action{font-size:19px}.service{padding:8px 13px;border-radius:5px;background:var(--yellow);font-size:11px;font-weight:700;color:#fff}.nav{height:51px;border-bottom:1px solid #f3f3f3;box-shadow:0 3px 12px #00000008}.nav ul{height:100%;display:flex;align-items:center;justify-content:center;gap:40px;list-style:none;margin:0;padding:0}.nav a{font-size:12px}.nav a.active,.nav a:hover{color:#EA993E;font-weight:700}.login-shell{min-height:570px;padding:30px 0 70px;background:radial-gradient(circle at 10% 12%,#eef4fa 0 6px,transparent 7px),radial-gradient(circle at 14% 20%,#eef4fa 0 5px,transparent 6px),#fff}.login-layout{display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:start}.login-panel{min-height:440px;background:#fff;padding:0 30px 38px;box-shadow:0 2px 16px rgba(41,55,75,.025)}.panel-heading{height:62px;display:flex;align-items:flex-start;justify-content:center}.panel-heading h2{position:relative;margin:0;padding-top:0;font-size:23px;line-height:1.5}.panel-heading h2:after{content:'';position:absolute;right:50%;bottom:-10px;width:18px;height:5px;border-radius:1px;background:var(--yellow);transform:rotate(-45deg)}.login-card{padding-top:28px}.login-card .subheading{display:none}.login-card form{max-width:590px;margin:auto}.alert{margin:0 0 18px;padding:10px 13px;border-radius:4px;font-size:12px}.alert-success{background:#eaf7ef;color:#237344}.alert-danger{background:#fff0f0;color:#b53a3a}.field{margin-bottom:30px}.field label{display:block;margin-bottom:7px;font-size:13px}.field input{width:100%;height:53px;padding:0 24px;border:0;border-radius:4px;background:var(--input);outline:0;direction:{{ $dirIsRtl ? 'rtl' : 'ltr' }};}.field input:focus{box-shadow:0 0 0 2px #306FAA70}.field input.is-invalid{box-shadow:0 0 0 1px #dc3545}.error{display:block;margin-top:6px;color:#c0392b;font-size:11px}.login-options{display:flex;align-items:center;justify-content:space-between;margin:0 0 8px;color:#555;font-size:12px}.remember{display:flex;align-items:center;gap:7px}.remember input{width:16px;height:16px;accent-color:var(--yellow)}.login-options a{color:#EA993E}.submit{display:block;width:120px;height:41px;margin-right:0;border-radius:0;background:var(--yellow);color:#ffffff;font-weight:600}.submit:hover{background:#245C8E}.register-panel{padding-top:0}.register-copy{margin:0 auto 22px;max-width:530px;color:#666;line-height:2.2;text-align:right}.register-button{display:block;width:110px;height:41px;margin-right:0;background:var(--yellow);text-align:center;line-height:41px;color:#fff}.register-button:hover{background:#245C8E}.register-note{display:none}footer{padding:24px 0 18px;background:#252b31;color:#fff;text-align:center}.footer-logo{width:160px;height:65px;object-fit:contain}.footer-copy{margin:5px 0 0;color:#d2d4d6;font-size:11px}@media(max-width:800px){.container{width:calc(100% - 24px)}.topbar{height:auto;padding:10px 0}.topbar-inner{flex-wrap:wrap;gap:10px}.brand{min-width:130px}.brand img{width:125px;height:58px}.top-actions{margin-right:auto;gap:12px}.service{padding:7px 9px}.search{order:3;flex-basis:100%;max-width:none}.nav{overflow:auto}.nav ul{justify-content:flex-start;gap:27px;width:max-content;padding:0 10px}.login-shell{padding:24px 0 45px}.login-layout{grid-template-columns:1fr;gap:16px}.login-panel{min-height:0;padding:0 18px 30px}.panel-heading{height:58px}.login-card{padding-top:22px}.field{margin-bottom:22px}.register-copy{text-align:right}.submit,.register-button{width:120px}}
    /* Login surface refinements */
    .login-shell {
        background: #f6f7f9;
    }
    .login-panel {
        border: 1px solid #eceef1;
        border-radius: 4px;
        box-shadow: 0 8px 24px rgba(36, 43, 53, 0.08);
        display: flex;
        flex-direction: column;
        justify-content: center;
        text-align: right;
    }
    .login-card {
        background: #fff;
    }
    .field input {
        background: #fafafa !important;
        border: 1px solid #e1e4e8;
        color: #252525;
        transition: border-color .2s ease, box-shadow .2s ease, background-color .2s ease;
    }
    .field input:focus {
        background: #fff !important;
        border-color: #306FAA;
        box-shadow: 0 0 0 3px rgba(48, 111, 170, 0.18);
    }
    .field input.is-invalid {
        border-color: #dc3545;
        box-shadow: none;
    }
    .submit,
    .register-button {
        border-radius: 3px;
        box-shadow: 0 3px 8px rgba(48, 111, 170, 0.18);
        transition: background-color .2s ease, box-shadow .2s ease, transform .2s ease;
    }
    .submit:hover,
    .register-button:hover {
        box-shadow: 0 5px 12px rgba(48, 111, 170, 0.24);
        transform: translateY(-1px);
    }
    .remember input {
        accent-color: #306FAA;
    }
    .login-hero {
        min-height: 138px;
        padding: 0 0 22px;
        color: #fff;
        background: linear-gradient(110deg, #102d49 0%, #173f65 52%, #245c8e 100%);
        display: flex;
        align-items: flex-end;
    }
    .login-hero-inner {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 24px;
    }
    .login-hero-title {
        margin: 0;
        color: #fff;
        font-size: 31px;
        font-weight: 700;
        line-height: 1.2;
    }
    .login-hero-breadcrumb {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
        padding: 0;
        list-style: none;
        font-size: 14px;
        font-weight: 600;
    }
    .login-hero-breadcrumb a:hover {
        color: var(--yellow);
    }
    .login-hero-breadcrumb i {
        font-size: 12px;
    }
    .panel-heading {
        justify-content: flex-start;
    }
    .panel-heading h2 {
        text-align: right;
    }
    .login-card form,
    .register-copy {
        width: 100%;
        margin-left: 0;
        margin-right: 0;
    }
    .register-copy {
        text-align: right;
    }
    .register-button,
    .submit {
        margin-right: 0;
        margin-left: auto;
    }
    .footer-grid{display:flex;justify-content:center}.footer-brand{text-align:center}.footer-brand p{margin:0;font-size:14px;line-height:1.8}.copyright{display:flex;align-items:center;justify-content:space-between;gap:20px;border-top:1px solid #3d4348;color:#afb4b8;font-size:11px;margin-top:15px;padding-top:10px}.footer-links{display:flex;gap:18px}.footer-links a,.socials a{color:#d2d4d6;font-size:10px;text-decoration:none}.socials{display:flex;gap:14px}@media(max-width:800px){.copyright{flex-wrap:wrap;justify-content:center;text-align:center}.footer-links{gap:10px}}
    .auth-layout{grid-template-columns:1fr}
    .auth-layout-narrow{max-width:640px;margin:auto}
    .field-row{display:grid;grid-template-columns:1fr 1fr;gap:18px}
    .submit.block{width:100%;min-width:0}
    .auth-switch{margin-top:22px;text-align:center;color:#667;font-size:12px;line-height:1.8}
    .auth-switch a{color:#EA993E;font-weight:700}
    .auth-email{display:block;text-align:center;color:#EA993E;font-size:14px;margin:2px 0 24px}
    .auth-hint{text-align:center;color:#666;font-size:13px;line-height:1.9}
    .auth-resend{background:none;border:0;color:#EA993E;font-weight:700}
    .auth-resend:disabled{opacity:.6;cursor:not-allowed}
    @media(max-width:800px){.field-row{grid-template-columns:1fr}}
</style>