<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Registration Successful</title>
</head>
<body>
    <h1>Volunteer Registration Successful</h1>
    <p>Redirecting in <span id="countdown">5</span> seconds...</p>

    <script>
        let countdown = 5;
        const countdownElement = document.getElementById('countdown');

        const timer = setInterval(() => {
            countdown--;
            countdownElement.textContent = countdown;

            if (countdown === 0) {
                clearInterval(timer);
                window.location.href = "../../../index.php";
            }
        }, 1000);
    </script>
</body>
</html>