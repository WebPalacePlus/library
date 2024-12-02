@extends('layouts.master')

@section('head')
    <link rel="stylesheet" href="/css/form.css" />
    <style>
        header {
            display: none;
        }

        body {
            width: 100%;
            min-height: 100vh;
            background: linear-gradient(0deg, var(--dark-red) 0%, var(--jigari) 100%);
        }

        .login-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 350px;
            border: 2px solid var(--dark-red);
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: var(--dark-red);
        }

        .login-btn {
            width: 100%;
            padding: 10px;
            background-color: var(--dark-red);
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .login-btn:hover {
            background-color: var(--hover-dark-red);
        }

        @media (max-width: 768px) {
            .login-container {
                width: 80%;
            }
        }
    </style>
@endsection

@section('content')
    <section class="login-container">
        <form method="post" action="auth">
            <h1 style="width:max-content;margin:1em auto;">ورود به کتابخانه</h1>
            @csrf
            @if ($errors->any())
                <div class="error-group">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="form-group">
                <label for="username">نام کاربری</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">رمز عبور</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group remember-me">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">مرا به خاطر بسپار</label>
            </div>
            <button type="submit" class="login-btn">ورود</button>
        </form>
    </section>
@endsection
