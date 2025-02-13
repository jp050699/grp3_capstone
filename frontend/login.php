<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css" type="text/css">
</head>
<body class="bg-dark d-flex align-items-center justify-content-center vh-100">
    <div class="container">
        <div class="card card-register mx-auto mt-5 col-6">
            <div class="card-header text-center"><h1>Login</h1></div>
            <div class="card-body">

                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    session_start();
                    $apiUrl = "http://localhost/capstone/api/login.php"; // Update this to your API URL

                    // Prepare POST data
                    $postData = http_build_query([
                        'email' => $_POST['email'],
                        'password' => $_POST['password']
                    ]);

                    // Create a stream context
                    $options = [
                        'http' => [
                            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                            'method'  => 'POST',
                            'content' => $postData,
                        ],
                    ];
                    $context = stream_context_create($options);

                    // Send the request and handle the response
                    $response = @file_get_contents($apiUrl, false, $context);

                    if ($response === FALSE) {
                        
                        echo '<div class="alert alert-danger">Error: Unable to connect to the API.</div>';
                    } else {
                        $responseData = json_decode($response, true);
                        if ($responseData['success']) {
                            echo '<div class="alert alert-success">' . htmlspecialchars($responseData['message']) . '</div>';
                            $_SESSION['username'] = $responseData['user']['username']; // Store username in session
                            header("Location: home.php"); // Redirect to home.php
                            exit();
                        } else {
                            echo '<div class="alert alert-danger">' . htmlspecialchars($responseData['message']) . '</div>';
                        }
                    }
                }
                ?>

                <form method="POST" action="">
                <div class="form-group mb-3">
                    <label>Email</label>
                    <input class="form-control" type="text" name="email" placeholder="Enter Email" required>
                </div>
                <div class="form-group mb-3">
                    <label>Password</label>
                    <input class="form-control"  type="password" name="password" placeholder="Enter Password" required>
                </div>
                <div class="align-items-center mb-3"><button type="submit" class="btn btn-success btn-block  w-100" >Login</button></div>
                <p class="text-center">Don't have an account? <a href="signup.php">Register Now</a></p>
                </form>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-zO8E1wPmxfK3+dQovp5fgMDd05aGrR4OpPTAn1EVrF3s9d8c2uLkOwdF+hB6Uksy" crossorigin="anonymous"></script>

</body>
</html>
