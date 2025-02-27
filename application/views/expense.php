<!DOCTYPE html>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense</title>
    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- custom css file link -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">

</head>


<?php if ($this->session->flashdata('message')): ?>
    <div class="message">
        <span><?php echo $this->session->flashdata('message'); ?></span>
        <i class="fas fa-times" onclick="this.parentElement.style.display = 'none';"></i>
    </div>
<?php endif; ?>
<?php $this->view('includes/header'); ?>

<div class="container">
    <h1 class="heading" style="color:  #2A9D8F;">Add Expense</h1>



    <form action="<?php echo base_url('Expense/submit_btn') ?>" method="post" class="forms">

        <div class="forms2">
            <label for="reading" class="form-label">Select Expense</label>
            <div class="mb-7">
                <select id="type" name="type" class="form-control">
                    <option value="" id="vlu">--Select Expense Type--</option>
                    <?php foreach ($expense_types as $expense): ?>
                        <option value="<?= $expense['type']; ?>">
                            <?= $expense['type']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="amount" class="form-label">Amount</label>
                <input type="number" step="0.01" id="amount" name="amount" class="form-control"
                    placeholder="Enter amount" required>
            </div>

            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" id="date" name="date" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">Remarks</label>
                <textarea id="notes" name="notes" class="bordered-textarea" rows="3" placeholder="Optional"></textarea>
            </div>

            <div class="d-flex justify-content-end">
                <button type="reset" class="btn btn-secondary me-2"
                    style="background-color:  #2A9D8F; color: white; border: none; padding: 10px 10px; cursor: pointer; font-size: 14px;">Cancel</button>
                <button type="submit" class="btn btn-primary"
                    style="background-color:  #2A9D8F; color: white; border: none; padding: 10px 10px; cursor: pointer; font-size: 14px;">Add
                    Expense</button>
            </div>
            </div>
    </form>

</div>
</div>








<script>
    // Check if the session has the username and display it
    <?php if ($this->session->userdata('name')): ?>
        const loginBtn = document.getElementById('login_btn');
        log_out = document.getElementById('log_out');
        log_out.style.display = 'block';
        const username = "<?php echo $this->session->userdata('name'); ?>";
        loginBtn.innerHTML = username; // Set the username

        loginBtn.style.display = 'inline'; // Show the button
    <?php else: ?>
        const loginBtn = document.getElementById('login_btn');
        loginBtn.innerHTML = "Login"; // Show default login text
        loginBtn.style.display = 'inline'; // Ensure it's visible
    <?php endif; ?>
</script>

</body>

</html>