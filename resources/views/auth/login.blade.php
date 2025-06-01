@extends('client.client')


@section('title', 'Trang đăng nhập')
@section('description', '')
@section('content')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;800&display=swap');

        *,
        *::after,
        *::before {
            box-sizing: border-box;
        }

        * {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        a {
            text-decoration: none;
            color: white;
        }

        a::after {
            content: "";
        }

        a:hover::after {
            display: block;
            border: 1px solid white;
            animation: under-line .5s ease-in-out 1;
        }

        @keyframes under-line {
            0% {
                width: 0%;
            }

            100% {
                width: 100%;
            }
        }

        section {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url(https://img.freepik.com/premium-photo/educational-triangles-geometric-design-maroon_1242493-1960.jpg?ga=GA1.1.396411406.1742834388&semt=ais_hybrid&w=740) center;
            background-size: cover;
            color: #fff;
        }

        .login-container {
            height: 450px;
            width: 400px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            backdrop-filter: blur(5px);
            border: 1px white solid;
            border-radius: 20px;
        }

        h2 {
            font-size: 1.75em;
            margin: 0 0 20px;
            filter: drop-shadow(0 6px 2px black);
        }

        .input-box {
            position: relative;
            border-bottom: 2px solid white;
            margin: 30px 0;
            width: 310px;
        }

        .input-box label {
            position: absolute;
            top: 50%;
            left: 5px;
            transform: translateY(-50%);
            pointer-events: none;
            font-size: 1em;
            transition: .5s;
        }

        .input-box input:valid~label,
        .input-box input:focus~label {
            top: -5px;
        }

        .input-box input {
            background: transparent;
            border: none;
            outline: none;
            color: white;
            width: 100%;
            height: 50px;
            font-size: 1em;
            padding: 0 40px 0 5px;
        }

        .input-box .icon {
            position: absolute;
            right: 8px;
            font-size: 1.4em;
            line-height: 45px;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            width: 310px;
            margin: 0 0 20px;
        }

        button {
            height: 40px;
            width: 310px;
            border-radius: 20px;
            margin: 0 0 10px;
            outline: none;
            border: none;
            background: white;
            color: black;
            transition: .3s;
        }

        button:hover {
            background-color: rgba(255, 255, 255, .8);
        }

        .create-account {
            margin: 12px 0 0 0;
        }

        @media screen and (max-width: 360px) {
            .login-container {
                width: 100%;
                height: 100vh;
                border: none;
                border-radius: none;
            }
        }
    </style>

    <section>
        <div class="login-container">
            <h2>Đăng nhập</h2>
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <form action="{{ route('loginAuth') }}" method="POST">
                @csrf
                <div class="input-box">
                    <span class="icon">
                        <i class="fa-solid fa-envelope"></i>
                    </span>

                    <input required type="text" id="email" name="email">
                    <label for="email">Nhập email của bạn</label>
                    @error('email')
                        <span class="error" style="color: red; font-size: 0.9em;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-box">
                    <span class="icon">
                        <i class="fa-solid fa-lock"></i>
                    </span>

                    <input required type="password" id="contraseña" name="password">
                    <label for="contraseña">Nhập mật khẩu của bạn</label>
                    @error('password')
                        <span class="error" style="color: red; font-size: 0.9em;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="remember-forgot">
                    <label>
                        <input type="checkbox"> Ghi nhớ tôi
                    </label>

                    <a href="{{ route('password.request') }}">Quên mật khẩu</a>
                </div>

                <button type="submit">Đăng nhập</button>
            </form>

            <div class="create-account">
                <a href="{{ route('auth.register') }}">Tạo tài khoản</a>
            </div>
        </div>
    </section>


@endsection
