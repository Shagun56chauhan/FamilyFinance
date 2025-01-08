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
    <h1 class="heading" style="color:  #2A9D8F;">Edit Record</h1>



    <form action="<?php echo base_url('viewrecord/update') ?>" method="post" class="forms">



    
        <div class="forms2">
        <?php if (!empty($record)): ?>
            <?php foreach ($record as $record): ?>
            <div class="mb-3">
            <label for="type" class="form-label">Type of Expense</label>
                        <input list="expenseTypes" id="type" name="type" class="form-control"
                            value="<?php echo $record['type']; ?>" placeholder="Select or type expense type" required>
                            <input type="hidden" name="id" value="<?= $record['id']; ?>">
                <datalist id="vehicleTypes">
                  
                        <option value="<?php echo $record['type']; ?>">
                        
                </datalist>

            </div>

            <!-- Vehicle Reading Input -->
            <div class="mb-3">
                <label for="reading" class="form-label">Enter Reading (km)</label>
                <input type="number" id="reading" name="reading" class="form-control"
                value="<?php echo $record['reading']; ?>"
                    placeholder="Enter reading in kilometers" required>
            </div>

            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" id="date" name="date" class="form-control" 
                value="<?php echo $record['created_at']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">Remarks</label>
                <textarea id="notes" name="notes" class="bordered-textarea" rows="3"
                 value="<?php echo $record['remarks']; ?>"
                placeholder="Optional"></textarea>
            </div>

            <div class="d-flex justify-content-end">
                <button type="reset" class="btn btn-secondary me-2"
                    style="background-color:  #2A9D8F; color: white; border: none; padding: 10px 10px; cursor: pointer; font-size: 14px;">Cancel</button>
                <button type="submit" class="btn btn-primary"
                    style="background-color:  #2A9D8F; color: white; border: none; padding: 10px 10px; cursor: pointer; font-size: 14px;">Add
                    Record</button>
            </div>

            <?php endforeach; ?>
            <?php else: ?>
                <span>ERROR</span>
            <?php endif; ?>
    </form>

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