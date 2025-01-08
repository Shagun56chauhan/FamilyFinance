<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGN UP</title>
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

    <form action="<?php echo base_url('signup/submit_btn') ?>" method="post" class="forms">
        <div class="forms2">
            <h2>SIGN UP</h2>
           


                <label>Name</label>
        <input type="text" name="name" placeholder="Enter your name" required><br>

        <label>Username</label>
        <input type="text" name="user_name" placeholder="Enter your username" required><br>

        <label>Password</label>
        <input type="password" name="password" placeholder="Enter your password" required><br>

        <label>Re-Password</label>
        <input type="password" name="re_password" placeholder="Re-enter your password" required><br>

        <button type="submit">Sign Up</button>
                <a href="<?php echo base_url('auth'); ?>" class="ca">Already have an account?</a>
        </div>
    </form>
</body>

</html>