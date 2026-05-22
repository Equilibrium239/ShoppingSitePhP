<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Processing Checkout</title>

<style>
    body {
        margin: 0;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background: #f4f4f4;
        font-family: Arial, sans-serif;
    }

    .container {
        text-align: center;
    }

 
    .loader {
        width: 100px;
        height: 100px;
        border: 10px solid #ddd;
        border-top: 10px solid #28a745;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto;
    }

    @keyframes spin {
        100% {
            transform: rotate(360deg);
        }
    }

    .checkmark {
        display: none;
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: #28a745;
        position: relative;
        margin: 0 auto;
        animation: pop 0.4s ease;
    }

    .checkmark::after {
        content: '';
        position: absolute;
        left: 28px;
        top: 18px;
        width: 25px;
        height: 50px;
        border: solid white;
        border-width: 0 8px 8px 0;
        transform: rotate(45deg);
    }

    @keyframes pop {
        0% {
            transform: scale(0);
        }

        100% {
            transform: scale(1);
        }
    }

    h2 {
        margin-top: 20px;
        color: #333;
    }
</style>
</head>

<body>

<div class="container">

    <div class="loader" id="loader"></div>

    <div class="checkmark" id="checkmark"></div>

    <h2 id="text">Processing your order...</h2>

</div>

<script>
    setTimeout(() => {

        document.getElementById("loader").style.display = "none";

        document.getElementById("checkmark").style.display = "block";

        document.getElementById("text").innerText = "Order Complete!";

    }, 5000);
</script>

</body>
</html>