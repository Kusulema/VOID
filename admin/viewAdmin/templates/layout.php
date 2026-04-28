<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>THE VOID | ADMIN</title>
<link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&display=swap" rel="stylesheet">

<style>
:root {
    --blood: #8b0000;
    --cult-red: #ff1a1a;
    --success: #00ff55;
    --metal: #1a1a1a;
    --oil: #050505;
    --text: #8a8a8a;
}

/* ================= BODY + BLOOD + GLITCH ================= */
body {
    margin:0;
    font-family:'Share Tech Mono', monospace;
    background:
        radial-gradient(circle at 20% 30%, rgba(120,0,0,.1), transparent 40%),
        url("https://www.transparenttextures.com/patterns/asfalt-dark.png"),
        var(--oil);
    color:var(--text);
    overflow-x:hidden;
    cursor:crosshair !important;
    position:relative;
}
body::before{
    content:"";
    position:fixed;
    inset:0;
    background:url('https://www.transparenttextures.com/patterns/blood-splatter.png');
    opacity:0.25;
    pointer-events:none;
    z-index:9999;
}

/* Глитч */
.glitch { animation: globalGlitch 1.5s infinite; }
@keyframes globalGlitch {
    0%,100% { transform:none; filter:brightness(1); }
    20% { transform:translate(-1px,1px) skew(0.5deg); filter:hue-rotate(5deg);}
    40% { transform:translate(1px,-1px) skew(-0.5deg); filter:invert(0.02);}
    60% { transform:translate(-0.5px,0.5px); filter:brightness(1.1);}
    80% { transform:translate(0.5px,-0.5px); filter:contrast(1.1);}
}

/* ================= TERMINAL BOOT ================= */
.boot-terminal{
    max-width:700px;
    margin:40px auto 20px;
    color:var(--cult-red);
    font-size:0.8rem;
    letter-spacing:2px;
    line-height:1.6;
    opacity:0;
    animation: fadeIn 0.8s forwards;
}
.boot-terminal p{
    margin:4px 0;
    opacity:0;
    animation: bootLine 0.5s forwards;
}
.boot-terminal p:nth-child(1){animation-delay:0.2s;}
.boot-terminal p:nth-child(2){animation-delay:0.7s;}
.boot-terminal p:nth-child(3){animation-delay:1.2s;}
.boot-terminal p:nth-child(4){animation-delay:1.7s;}
.cursor{animation:blink 1s infinite; display:inline-block;}
@keyframes blink{50%{opacity:0;}}
@keyframes bootLine{to{opacity:1;}}
@keyframes fadeIn{to{opacity:1;}}

/* ================= ADMIN CONTAINER ================= */
.admin-container{
    max-width:800px;
    margin:30px auto;
    padding:30px;
    background:rgba(15,15,15,.95);
    border:2px solid var(--metal);
    box-shadow:0 0 80px rgba(255,0,0,0.25);
    position:relative;
    overflow:hidden;
    opacity:0;
    animation: formFadeIn 1s forwards;
    border-radius:4px;
}
@keyframes formFadeIn{to{opacity:1;}}

/* Сканирующая линия */
.admin-container::after{
    content:"";
    position:absolute;
    left:0; top:-100%;
    width:100%; height:100%;
    background: linear-gradient(to bottom, transparent, rgba(255,0,0,0.15), transparent);
    animation: scanLine 5s linear infinite;
    pointer-events:none;
}
@keyframes scanLine{0%{top:-100%;}100%{top:100%;}}

/* ================= HEADINGS ================= */
h2,h3{ color:var(--cult-red); text-shadow:0 0 10px #ff0000; letter-spacing:3px; text-align:center; }

/* ================= FORMS ================= */
input[type="text"], input[type="number"], input[type="file"], textarea, select{
    width:100%;
    padding:12px;
    margin-bottom:15px;
    background:#050505;
    border:1px solid #300;
    color:var(--cult-red);
    font-family:'Share Tech Mono';
    outline:none;
}
input:focus, textarea:focus, select:focus{border-color:#ff0000; box-shadow:0 0 15px rgba(255,0,0,0.5);}

/* ================= BUTTONS ================= */
button, .btn{
    padding:10px 20px;
    background:linear-gradient(180deg,#300,#100);
    border:1px solid #600;
    color:#aaa;
    text-transform:uppercase;
    letter-spacing:2px;
    cursor:crosshair;
    text-decoration:none;
}
button:hover, .btn:hover{ background:#a00000; color:#fff; box-shadow:0 0 20px #ff0000; }

/* ================= TABLES ================= */
table{width:100%; border-collapse:collapse; margin-bottom:15px; color:var(--text);}
table th, table td{padding:10px; border:1px solid #222;}
table th{background:#111;}
table tr:hover{background:#200;}

/* ================= STATUS MESSAGE ================= */
.status-message{text-align:center; margin-top:15px; font-size:0.85rem; letter-spacing:2px; min-height:1.2em;}
.status-hash{color:var(--cult-red);}
.status-success{color:var(--success);}
.status-error{color:var(--blood);}

</style>
</head>
<body class="glitch">

<!-- TERMINAL BOOT -->
<div class="boot-terminal">
    <p>> VOID SYSTEM ONLINE</p>
    <p>> ADMIN AUTH REQUIRED</p>
    <p>> INITIATING TERMINAL SEQUENCE</p>
    <p>> ENTER CREDENTIALS / SELECT FORM</p>
    <span class="cursor">█</span>
</div>

<!-- ADMIN CONTENT -->
<div class="admin-container">
    <?php echo $content; ?>
</div>

<script>
// Пример: HASHING PASSWORD и ACCESS GRANTED/ERROR
document.querySelectorAll('form').forEach(form=>{
    const status=document.createElement('div');
    status.className='status-message';
    form.appendChild(status);

    form.addEventListener('submit',function(e){
        e.preventDefault();
        status.className='status-message status-hash';
        status.textContent='HASHING DATA...';
        setTimeout(()=>{
            const success=Math.random()>0.3;
            if(success){
                status.className='status-message status-success';
                status.textContent='ACCESS GRANTED';
                form.submit();
            } else {
                status.className='status-message status-error glitch';
                status.textContent='ACCESS DENIED';
            }
        },1200);
    });
});
</script>

</body>
</html>
<?php $finalLayout=ob_get_clean(); echo $finalLayout; ?>
