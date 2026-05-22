<?php
ob_start();

if(isset($result)) {
    if($result[0]==true)
        {
        ?>
            <div class="container">
                <div class="alert alert-info">
                    <strong>User created.</strong> You can now sign in to your account. <a href="login">Sign in</a>
                </div>
            </div>
            <?php
        }
    else if($result[0]==false)
    {
        ?>
        <div class="container">
            <div class="alert alert-warning">
                <strong>Error!</strong> <?php echo $result[1];?><a href="registerForm">Registration form</a>
            </div>
        </div>
    <?php
    }
}
?>

<?php 
$content = ob_get_clean();
include "view/layout.php"; 
?>