<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6 text-center">Register</h2>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                echo "<pre>";
                print_r($_POST);
                echo "</pre>";
                $logData = print_r($_POST, true);
                file_put_contents('form_data.log', $logData, FILE_APPEND);

                $firstName = $_POST["firstname"];
                $lastName = $_POST["lastname"];
                $email = $_POST["email"];
                $password = $_POST["password"];
                $passwordRepeat = $_POST["re_password"];

                $errors = array();

                if (empty($firstName) or empty($lastName) or empty($email) or empty($password) or empty($passwordRepeat)) {
                    array_push($errors, "All field are required");
                }
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    array_push($errors, "email is not valid");
                }
                if (strlen($password) < 6) {
                    array_push($errors, "Password must be at last 6 characters long");
                }
                if ($password !== $passwordRepeat) {
                    array_push($errors, "password does not match");
                    exit();
                }
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                require_once "database.php";
                $sql = "SELECT * FROM users WHERE email = '$email'";
                $result = mysqli_query($conn, $sql);
                $rowCount = mysqli_num_rows($result);
                if($rowCount>0) {
                    array_push($errors, "Email already exists!");
                }

                if (count($errors) > 0) {
                    foreach ($errors as $error) {
                        echo "<div class='flex flex-col items-start gap-4 w-full'>
                        <div class='flex items-center'>
                        <div class='mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10'>
 
                        </div>
                        <div class='mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left'>
                          <h3 class='text-base font-semibold leading-6 text-gray-900' id='modal-title'>Alert message!</h3>
                          <div class='mt-2'>
                            <p class='text-sm text-red-500'>$error</p>
                          </div>
                        </div>
                        </div>
                        ";
                    }
                } else {
                    $sql = "INSERT INTO users (firstname, lastname, email, password) VALUES (?,?,?,?)";
                    // $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)");
                    $stmt = mysqli_stmt_init($conn);
                    // $stmt->bind_param("ssss", $firstName, $lastName, $email, $hashedPassword);
                    $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
                    if($prepareStmt){
                        mysqli_stmt_bind_param($stmt, "ssss", $firstName, $lastName, $email, $hashedPassword);
                        mysqli_stmt_execute($stmt);
                        echo "<div class='text-green-500 text-center mt-4'>Registration successful!</div>";
                    }else{
                        die("<div class='text-red-500 text-center mt-4'>Error: " . $stmt->error . "</div>");
                    }
                    // if ($stmt->execute()) {
                    //     echo "<div class='text-green-500 text-center mt-4'>Registration successful!</div>";
                    // } else {
                    //     echo "<div class='text-red-500 text-center mt-4'>Error: " . $stmt->error . "</div>";
                    // }
                    $stmt->close();
                    $conn->close();
                }
            }
            ?>
            <form action="registration.php" method="POST">
                <div class="grid grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="firstname" class="block text-gray-700 font-semibold mb-2">First Name</label>
                        <input type="text" id="firstname" name="firstname" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div class="mb-4">
                        <label for="lastname" class="block text-gray-700 font-semibold mb-2">Last Name</label>
                        <input type="text" id="lastname" name="lastname" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
                    <input type="email" id="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-semibold mb-2">Password</label>
                    <input type="password" id="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-6">
                    <label for="repassword" class="block text-gray-700 font-semibold mb-2">Confirm Password</label>
                    <input type="password" id="repassword" name="re_password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="text-center">
                    <button type="submit" value="Register" name="submit" class="w-full bg-blue-500 text-white font-semibold px-4 py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">Submit</button>
                </div>
            </form>
            <p class="text-gray-500 text-center mt-4">Already register? <a href="/login_register/login.php">login</a></p>
        </div>
    </div>
</body>

</html>