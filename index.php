<!DOCTYPE html>
<html>
<head>
    <title>Home - E-Voting System</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        /* Candidate Cards Styles */
        .candidates {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 50px;
        }

        .candidate-card {
            width: 200px;
            padding: 20px;
            margin: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .candidate-card img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 20px;
        }

        .candidate-card h3 {
            margin: 0;
            color: #333333;
        }
    </style>
</head>
<body>
    <div class="index-header">
        <h1>Welcome to E-Voting</h1>
        <a href="login.html" class="login-button">Login</a>
    </div>
    <div class="index-container">
        <h2>Secure and Reliable E-Voting System</h2>
        <p>Welcome to our secure e-voting platform. Here you can vote safely and conveniently from anywhere. Click the login button to get started.</p>
    </div>
    
    <!-- Candidates Section -->
    <div class="candidates">
        <div class="candidate-card">
            <img src="res/candidate-1.jpg" alt="Candidate 1 Photo">
            <h3>Candidate 1</h3>
        </div>
        <div class="candidate-card">
            <img src="res/candidate-2.jpg" alt="Candidate 2 Photo">
            <h3>Candidate 2</h3>
        </div>
        <div class="candidate-card">
            <img src="res/candidate-3.jpg" alt="Candidate 3 Photo">
            <h3>Candidate 3</h3>
        </div>
    </div>
</body>
</html>
