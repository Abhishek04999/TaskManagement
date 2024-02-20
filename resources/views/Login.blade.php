<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup and Login Form</title>
    <style>
        /* Form container styles */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            background: #f7f7f7;
        }

        /* Form styles */
        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
            animation: fadeIn 0.5s ease;
            display: none;
        }

        /* Input field styles */
        input {
            margin-bottom: 15px;
            padding: 10px;
            width: calc(100% - 20px);
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        /* Button styles */
        button {
            background-color: #2383eb;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            width: calc(100% - 20px);
            box-sizing: border-box;
            transition: background-color 0.3s;
            font-size: 18px;
        }

        .btn{
            background-color: rgb(214, 124, 102);
            font-size: 18px;
        }
        .btn:hover{
            background-color: rgb(181, 43, 8)
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Animation keyframes */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div id="signupForm" class="form-container" style="display: block;">
            <h2>Sign Up</h2>
            <form method="post" action="{{url('/')}}">
                @csrf
                <input type="text" name="name" placeholder="Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button>Sign Up</button><hr>

            </form>
            <a href="{{ route('google.login') }}"><button class="btn" >Continue with google</button></a>
            <div style="margin-top: 10px;">
                <span>or<span>
            </div>
            <button id="loginBtn">Login</button>
        </div>


        <div id="loginForm" class="form-container">
            <h2>Login</h2>
            <form method="post" action="{{url('/login')}}">
                @csrf
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button>Login</button><hr>
            </form>
            <a href="{{ route('google.login') }}"><button class="btn" >Continue with google</button></a>

            <div style="margin-top: 10px;">
                <span>or<span>
            </div>
            <button id="signupBtn">Sign Up</button>
        </div>
    </div>

    <script>
        document.getElementById('loginBtn').addEventListener('click', function() {
            document.getElementById('signupForm').style.display = 'none';
            document.getElementById('loginForm').style.display = 'block';
        });

        document.getElementById('signupBtn').addEventListener('click', function() {
            document.getElementById('loginForm').style.display = 'none';
            document.getElementById('signupForm').style.display = 'block';
        });
    </script>
</body>

</html>
