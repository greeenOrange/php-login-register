<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
<div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6 text-center">login</h2>
            <?php
            session_start();
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $email = $_POST["email"];
                $password = $_POST["password"];

                $errors = array();
                
                require_once "database.php";
                $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();
                
                if ($user) {
                    if (password_verify($password, $user["password"])) {
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['email'] = $user['email'];
                        header("Location: welcome.php");
                        exit();
                    } else {
                        echo "<div class='text-red-500'>Password not correct</div>";
                    }                    
                } else {
                    echo "<div class='text-red-500'>Email not correct</div>";
                }
                $stmt->close();
                $conn->close();
            }
            ?>
            <form action="login.php" method="POST">
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
                    <input type="email" id="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-semibold mb-2">Password</label>
                    <input type="password" id="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="text-center">
                    <button type="submit" value="Register" name="submit" class="w-full bg-blue-500 text-white font-semibold px-4 py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">Submit</button>
                </div>
            </form>
            <p class="text-gray-500 text-center mt-4">New user? <a href="/login_register/registration.php">Register</a></p>
        </div>
    </div>
</body>

</html>