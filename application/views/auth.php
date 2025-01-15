<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login Page</title>

      <!-- font awesome cdn link -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- custom css file link -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">
</head>
<body>



<?php if ($this->session->flashdata('message')): ?>
        <div class="message">
            <span><?php echo $this->session->flashdata('message'); ?></span>
            <i class="fas fa-times" onclick="this.parentElement.style.display = 'none';"></i>
        </div>
    <?php endif; ?>


<form action="<?php echo base_url('Auth/login')?>" method="post" class="forms" onsubmit="return validateForm()">
    <div class="forms2">
    <h2>LOGIN</h2>
    <?php if (isset($_GET['error'])) { ?>
       <p class="error"><?php echo $_GET['error']; ?></p>
    <?php } ?>
    <label>User Name</label>
    <input type="text" name="name" placeholder="User Name" required><br>

    <label>Password</label>
    <input type="password" name="password" placeholder="Password" id="password" required><br>

    <button type="submit"id="sub_btn" name="login">Login</button>
    <a href="<?php echo base_url('Signup')?>"class="ca">Create an account</a>
    </div>
</form>



<script>
    function validateForm() {
        const password = document.getElementById('password').value;
        if (password.length < 6) {
            alert('Password must be at least 6 characters long.');
            return false;
        }
        return true;
    }
</script>






   
    <!-- custom js file link -->
    <script src="<?php echo base_url('assets/js/script.js');?>"></script>
    
</body>
</html>