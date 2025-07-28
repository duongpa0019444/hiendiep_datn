@extends('client.client')

@section('title', 'Trang đăng ký')
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

        .register-container {
            height: 600px;
            /* Tăng chiều cao để chứa thêm trường */
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
            margin: 20px 0;
            /* Giảm margin để vừa với chiều cao container */
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

        .terms {
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

        .login-link {
            margin: 12px 0 0 0;
        }

        @media screen and (max-width: 360px) {
            .register-container {
                width: 100%;
                height: 100vh;
                border: none;
                border-radius: none;
            }
        }
    </style>

    <section>
        <div class="register-container">
            <h2>Đăng ký</h2>

            <form action="{{ route('registerAuth') }}" method="POST">
                @csrf
                <div class="input-box">
                    <span class="icon">
                        <i class="fa-solid fa-user"></i>
                    </span>
                    <input required type="text" id="name" name="name">
                    <label for="name">Nhập tên của bạn</label>
                    @error('name')
                        <span class="error" style="color: red; font-size: 0.9em;">{{ $message }}</span>
                    @enderror

                </div>
                <div class="input-box">
                    <span class="icon">
                        <i class="fa-solid fa-phone"></i>
                    </span>
                    <input required type="tel" id="phone" name="phone" inputmode="numeric" pattern="[0-9]{10,15}"
                        value="{{ old('phone') }}"
                        onkeypress="return (event.charCode !=8 && event.charCode == 0) ? true : (event.charCode >= 48 && event.charCode <= 57)">
                    <label for="phone">Nhập số điện thoại của bạn</label>
                    @error('phone')
                        <span class="error" style="color: red; font-size: 0.9em;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-box">
                    <span class="icon">
                        <i class="fa-solid fa-envelope"></i>
                    </span>
                    <input required type="email" id="email" name="email">
                    <label for="email">Nhập email của bạn</label>
                    @error('email')
                        <span class="error" style="color: red; font-size: 0.9em;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-box">
                    <span class="icon">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input required type="password" id="password" name="password">
                    <label for="password">Nhập mật khẩu của bạn</label>
                    @error('password')
                        <span class="error" style="color: red; font-size: 0.9em;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-box">
                    <span class="icon">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input required type="password" id="password_confirmation" name="password_confirmation">
                    <label for="password_confirmation">Xác nhận mật khẩu</label>
                    @error('password_confirmation')
                        <span class="error" style="color: red; font-size: 0.9em;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="terms">
                    <label>
                        <input type="checkbox" required> Tôi đồng ý với điều khoản
                    </label>
                    {{-- <a href="#">Xem điều khoản</a> --}}
                </div>

                <button type="submit">Đăng ký</button>
            </form>

            <div class="login-link">
                <a href="{{ route('auth.login') }}">Đã có tài khoản? Đăng nhập</a>
            </div>
        </div>
    </section>

@endsection
