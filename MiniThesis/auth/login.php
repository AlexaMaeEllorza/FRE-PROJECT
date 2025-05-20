    <?php
    session_start();
    require_once '../config/db.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = trim($_POST["username"]);
        $password = $_POST["password"];
        $remember = isset($_POST["remember"]);

        $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                session_regenerate_id(true); // prevent session fixation

                $_SESSION["user_id"] = $user["id"];
                $_SESSION["username"] = $user["username"];
                $_SESSION["role"] = $user["role"];

                if ($remember) {
                    setcookie("username", $username, time() + (86400 * 30), "/");
                } else {
                    setcookie("username", "", time() - 3600, "/");
                }
                if (isset($_SESSION["user_id"])) {
                    if (in_array($_SESSION["role"], ["admin", "super_admin"])) {
                        header("Location: ../admin/dashboard.php"); // correct path from /auth/
                    } elseif ($_SESSION["role"] === "staff") {
                        header("Location: ../user/home.php"); // correct path from /auth/
                    } else {
                        header("Location: logout.php"); // logout is in /auth/, so this is fine
                    }
                } else {
                    header("Location: login.php"); // same folder
                }

                exit();
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "No user found with that username.";
        }
    }
    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Login</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <h2>Login</h2>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="POST">
        <label>Username:</label><br>
        <input type="text" name="username" value="<?php echo isset($_COOKIE['username']) ? htmlspecialchars($_COOKIE['username']) : ''; ?>" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <input type="checkbox" name="remember" <?php if (isset($_COOKIE['username'])) echo 'checked'; ?>> Remember Me<br><br>

        <input type="submit" value="Login">
    </form>
    </body>
    </html>
