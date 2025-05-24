<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f8ff;
            flex-direction: column;
        }

        .container {
            text-align: center;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 1200px;
            padding: 40px;
        }

        h1 {
            color: #333;
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .left {
            flex: 1;
            padding: 20px;
        }

        .left h2 {
            color: #4caf50;
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .right {
            flex: 1;
            padding: 20px;
            text-align: center;
        }

        .right img {
            width: 200px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .button-container {
            margin-top: 20px;
        }

        button {
            background-color: #4caf50;
            color: white;
            font-size: 1.1rem;
            padding: 15px 30px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        @media (max-width: 768px) {
            .content {
                flex-direction: column;
                align-items: center;
            }

            .left, .right {
                flex: none;
                width: 100%;
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Welcome to the Application</h1>

        <div class="content">
            <div class="left">
                <h2>WELCOME WEBSITE PKL DANESWARA</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean vel turpis eget eros volutpat varius.</p>
            </div>

            <div class="right">
                <img src="https://via.placeholder.com/200" alt="Profile Image">
                <div class="button-container">
                    <a href="{{ route('login') }}">
                        <button>Login</button>
                    </a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
