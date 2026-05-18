<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ADMIN LOGIN | THE VOID</title>
<link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&display=swap" rel="stylesheet">

<style>
:root {
    --blood: #8b0000;
    --cult-red: #ff1a1a;
    --success-green: #00ff55;
    --oil: #050505;
    --metal: #1a1a1a;
    --text: #8a8a8a;
}

/* ================= BODY + BLOOD + GLITCH ================= */
body {
    margin: 0;
    font-family: 'Share Tech Mono', monospace;
    background: radial-gradient(circle at 20% 30%, rgba(120,0,0,.1), transparent 40%), 
                url("https://www.transparenttextures.com/patterns/asfalt-dark.png"),
                #050505;
    color: var(--text);
    overflow-x: hidden;
    cursor: crosshair !important;
    position: relative;
}

/* Кровь на весь экран */
body::before {
    content: "";
    position: fixed;
    inset: 0;
    background: url('https://www.transparenttextures.com/patterns/blood-splatter.png');
    opacity: 0.25; /* сделал сильнее, чтобы видно было */
    pointer-events: none;
    z-index: 9999;
}

/* Глитч всей страницы, уменьшена скорость и интенсивность */
@keyframes globalGlitch {
    0% { transform: none; filter: brightness(1); }
    20% { transform: translate(-1px,1px) skew(0.5deg); filter: hue-rotate(5deg); }
    40% { transform: translate(1px,-1px) skew(-0.5deg); filter: invert(0.02); }
    60% { transform: translate(-0.5px,0.5px); filter: brightness(1.1); }
    80% { transform: translate(0.5px,-0.5px); filter: contrast(1.1); }
    100% { transform: none; filter: none; }
}
.glitch { animation: globalGlitch 1.5s infinite; }

/* ================= TERMINAL ================= */
.boot-terminal {
    max-width: 700px;
    margin: 40px auto 20px;
    color: var(--cult-red);
    font-size: 0.8rem;
    letter-spacing: 2px;
    line-height: 1.6;
    opacity: 0;
    animation: fadeIn 0.8s forwards;
}

.boot-terminal p { margin: 4px 0; opacity: 0; animation: bootLine 0.5s forwards; }
.boot-terminal p:nth-child(1){ animation-delay: 0.2s; }
.boot-terminal p:nth-child(2){ animation-delay: 0.7s; }
.boot-terminal p:nth-child(3){ animation-delay: 1.2s; }
.boot-terminal p:nth-child(4){ animation-delay: 1.7s; }

.cursor { animation: blink 1s infinite; display:inline-block; }
@keyframes blink { 50% { opacity: 0; } }
@keyframes bootLine { to { opacity: 1; } }
@keyframes fadeIn { to { opacity: 1; } }

/* ================= FORM ================= */
.admin-container {
    max-width: 400px;
    margin: 20px auto;
    padding: 30px;
    background: rgba(15,15,15,.95);
    border: 2px solid var(--metal);
    box-shadow: 0 0 60px rgba(255,0,0,0.2);
    position: relative;
    overflow: hidden;
    opacity: 0;
    animation: formFadeIn 1s ease-out forwards;
}

@keyframes formFadeIn { to { opacity: 1; } }

/* Сканирующая линия по форме */
.admin-container::after {
    content: "";
    position: absolute;
    left: 0;
    top: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(to bottom, transparent, rgba(255,0,0,0.15), transparent);
    animation: scanLine 5s linear infinite; /* чуть медленнее */
    pointer-events: none;
}

@keyframes scanLine {
    0% { top: -100%; }
    100% { top: 100%; }
}

h2.cult-main {
    text-align: center;
    color: var(--cult-red);
    letter-spacing: 4px;
    text-shadow: 0 0 10px #ff0000;
    margin-bottom: 10px;
}

.blood-line {
    height: 4px;
    width: 100%;
    background: linear-gradient(90deg, transparent, #ff0000, transparent);
    box-shadow: 0 0 10px #ff0000;
    margin-bottom: 25px;
}

.form-group { margin-bottom: 20px; }
.form-label { color: #8a8a8a; font-size: 0.75rem; letter-spacing: 2px; display: block; margin-bottom: 6px; }

.cult-input {
    width: 100%;
    padding: 12px;
    background: #000;
    border: 1px solid #300;
    color: var(--cult-red);
    outline: none;
    font-family: 'Share Tech Mono';
    transition: 0.3s;
}

.cult-input:focus { border-color: #ff0000; box-shadow: 0 0 15px rgba(255,0,0,0.5); }

.submitBtn {
    width: 100%;
    padding: 12px;
    background: linear-gradient(180deg, #300, #100);
    border: 1px solid #600;
    color: #aaa;
    text-transform: uppercase;
    letter-spacing: 2px;
    cursor: crosshair;
}

.submitBtn:hover { background: #a00000; color: #fff; box-shadow: 0 0 20px #ff0000; }

a { display: block; text-align: center; margin-top: 10px; font-size: 0.7rem; color: #444; text-decoration: none; }
a:hover { color: #ff1a1a; }

.status-message {
    text-align: center;
    margin-top: 15px;
    font-size: 0.85rem;
    letter-spacing: 2px;
    min-height: 1.2em;
}

.status-hash { color: var(--cult-red); }
.status-success { color: var(--success-green); }
.status-error { color: var(--blood); }
</style>
</head>
<body class="glitch">

<div class="boot-terminal">
    <p>> VOID SYSTEM ONLINE</p>
    <p>> ARCHITECT ID REQUIRED</p>
    <p>> AUTHENTICATION SEQUENCE START</p>
    <p>> ENTER CREDENTIALS BELOW</p>
    <span class="cursor">█</span>
</div>

<div class="admin-container">
    <h2 class="cult-main">ARCHITECT LOGIN</h2>
    <div class="blood-line"></div>

    <form id="loginForm" action="login" method="POST">
        <div class="form-group">
            <label class="form-label">IDENTIFIER (EMAIL)</label>
            <input type="text" name="email" class="cult-input" required autofocus>
        </div>

        <div class="form-group">
            <label class="form-label">ACCESS KEY</label>
            <input type="password" name="password" class="cult-input" required>
        </div>

        <button type="submit" name="btnLogin" class="submitBtn">AUTHENTICATE</button>
        <div class="status-message" id="statusMessage"></div>

        <a href="../">[ ESCAPE TO MAIN SITE ]</a>
    </form>
</div>

<script>
const status = document.getElementById('statusMessage');
const errorText = <?php echo json_encode($_SESSION['errorString'] ?? ''); ?>;
if (errorText) {
    status.className = 'status-message status-error';
    status.textContent = errorText;
}
</script>

</body>
</html>
