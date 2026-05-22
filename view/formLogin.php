<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN | VOID</title>

    <link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

    <style>
        body {
            background: #050505;
            font-family: 'Share Tech Mono', monospace;
        }

        .boot-terminal {
            max-width: 700px;
            margin: 40px auto 20px;
            color: #ff1a1a;
            font-size: 0.8rem;
            letter-spacing: 2px;
            line-height: 1.6;
        }

        .boot-terminal p {
            margin: 4px 0;
            opacity: 0;
            animation: bootLine .5s forwards;
        }

        .boot-terminal p:nth-child(1) { animation-delay: .2s }
        .boot-terminal p:nth-child(2) { animation-delay: .7s }
        .boot-terminal p:nth-child(3) { animation-delay: 1.2s }

        @keyframes bootLine {
            to { opacity: 1; }
        }

        .cursor {
            animation: blink 1s infinite;
            margin-top: 4px;
        }

        @keyframes blink {
            50% { opacity: 0; }
        }

        .cult-form-container {
            max-width: 500px;
            margin: 50px auto 0;
            padding-top: 10px;
            animation: fadeIn .6s ease-out forwards;
            opacity: 0;
            animation-delay: 1.8s;
        }

        @keyframes fadeIn {
            to { opacity: 1; }
        }

        html, body {
            min-height: 100%;
            height: 100%;
            overflow-y: auto !important;
            overflow-x: hidden !important;
        }

        body {
            background: #050505;
            font-family: 'Share Tech Mono', monospace;
            overflow: auto !important;
        }

        section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 12px;
        }

        .divBox {
            width: min(100%, 760px);
        }

        .auth-switch {
            display: flex;
            justify-content: center;
            gap: 14px;
            margin-bottom: 22px;
        }

        .auth-switch__item {
            color: #aaa;
            text-decoration: none;
            font-size: 0.82rem;
            letter-spacing: 1.5px;
            padding: 10px 18px;
            border: 1px solid transparent;
            border-radius: 999px;
            transition: .25s;
        }

        .auth-switch__item:hover,
        .auth-switch__item.active {
            color: #fff;
            border-color: rgba(255,255,255,.18);
            background: rgba(255,255,255,.06);
        }

        @media (max-width: 768px) {
            section {
                align-items: flex-start;
                padding-top: 18px;
            }
        }

        .cult-input {
            background: #1f1f1f;
            border: 1px solid #333;
            color: #fff;
            padding: 15px;
            margin-bottom: 20px;
            width: 100%;
            outline: none;
            font-size: 1rem;
            transition: .3s;
        }

        .cult-input:focus {
            border-color: #ff0000;
            box-shadow: 0 0 15px rgba(255,0,0,.4);
        }

        .form-label {
            color: #8a8a8a;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 0.75rem;
            margin-bottom: 6px;
            display: block;
        }

        .hint {
            text-align: center;
            color: #555;
            font-size: 0.7rem;
            margin-top: 20px;
            letter-spacing: 2px;
        }

        .terminal-link {
            display: inline-block;
            color: #ff8a8a;
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.14);
            padding: 8px 14px;
            border-radius: 999px;
            text-decoration: none;
            font-size: .8rem;
            letter-spacing: 1.5px;
            transition: .25s;
        }

        .terminal-link:hover {
            color: #fff;
            background: rgba(255,255,255,.14);
            border-color: rgba(255,255,255,.25);
        }

        .error-box {
            margin-top: 18px;
            padding: 14px 16px;
            border: 1px solid rgba(255, 26, 26, 0.35);
            color: #ff7a7a;
            background: rgba(40, 0, 0, 0.4);
        }
    </style>
</head>

<body>

<section>
    <div class="divBox">

        <h1 class="cult-main" style="text-align:center;">
            VOID ACCESS TERMINAL
        </h1>

        <div class="blood-line"></div>

        <div class="boot-terminal">
            <p>> VOID SYSTEM ONLINE</p>
            <p>> USER AUTHENTICATION REQUIRED</p>
            <p>> ENTER CREDENTIALS BELOW</p>
            <div class="cursor">█</div>
        </div>

        <div class="cult-form-container">
            <div class="auth-switch">
                <a class="auth-switch__item active" href="login">[ SIGN IN ]</a>
                <a class="auth-switch__item" href="registerForm">[ SIGN UP ]</a>
            </div>
            <form method="POST" action="login">
                <div class="form-group">
                    <label class="form-label">EMAIL</label>
                    <input type="email" class="cult-input" name="email" required autofocus>
                </div>

                <div class="form-group">
                    <label class="form-label">PASSWORD</label>
                    <input type="password" class="cult-input" name="password" required>
                </div>

                <button type="submit" class="submitBtn" name="btnLogin" style="width:100%;">
                    ENTER THE VOID
                </button>

                <?php if (!empty($errorString)): ?>
                    <div class="error-box"><?php echo htmlspecialchars($errorString); ?></div>
                <?php endif; ?>

                <div class="hint">
                    [ AUTHENTICATION UNLOCKS THE CABINET ]
                </div>


                <p style="text-align:center;">
                    <a class="terminal-link" href="./">
                        [ RETURN TO MAIN TERMINAL ]
                    </a>
                </p>
            </form>
        </div>

    </div>
</section>

<script src="void-effects.js"></script>

</body>
</html>