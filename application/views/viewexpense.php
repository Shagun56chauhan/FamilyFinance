<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>view orders</title>

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- custom css file link -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">
    <link href="//cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css" rel="stylesheet">
</head>

<body>

    <?php if ($this->session->flashdata('message')): ?>
        <div class="message">
            <span><?php echo $this->session->flashdata('message'); ?></span>
            <i class="fas fa-times" onclick="this.parentElement.style.display = 'none';"></i>
        </div>
    <?php endif; ?>

    <?php $this->view('includes/header'); ?>



    <div class="order_main">

<section class="shopping-cart">
    <h1 class="heading" style="color:  #2A9D8F;">Total Expense</h1>
    <?php if (isset($message)): ?>
        <p><?= $message; ?></p>
    <?php endif; ?>
    <div class="he-form">


        <div class="forms3">
            <form method="post" action="<?php echo base_url('ViewExpense'); ?>" class="mb-4">

                <select id="type" name="type" class="form-control" required onchange="toggleGoButton()">
                    <option value="" id="vlu">--Select Expense Type--</option>
                    <?php foreach ($expense_types as $expense): ?>
                        <option value="<?= $expense['type']; ?>">
                            <?= $expense['type']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button name="go" class="btn btn-info btns" style="display: none;" id="btn22">GO</button>
            </form>

        </div>
        <h1 class="hie">
            Total Expenses: <?= isset($total_expense) ? '₹' . number_format($total_expense, 2) : '₹0.00'; ?>
        </h1>

        <!-- Display total expense for the selected type if available -->
        <?php if (isset($selected_type) && isset($selected_type_expense)): ?>
            
            <h1 class="heading" style="color: #E76F51; font-size:15px; margin-left:50px;">
                Total Expense for <?= htmlspecialchars($selected_type); ?>:
                ₹<?= number_format($selected_type_expense, 2); ?>
            
            </h1>
        <?php endif; ?>

    </div>
    <div class="user">
        <table>

            <thead>
                <th>id</th>
                <th>Expense Type</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Remarks</th>
                <th>Action</th>



            </thead>

            <tbody>
                <?php if (!empty($view)): ?>
                    <?php foreach ($view as $view): ?>
                        <tr>
                            <td><?= $view['user_id']; ?></td>
                            <input type="hidden" name="type" value="<?= $view['type']; ?>">
                            <td><?= $view['type']; ?></td>
                            <td style="color:  red;">₹<?= $view['amount']; ?></td>
                            <td><?= $view['created_at']; ?></td>
                            <td><?= $view['remarks']; ?></td>
                            <td> <a href="<?php echo base_url('expense/edit/') . $view['id']; ?>"
                                    class="btn btn-info btns">Edit</a>
                                &nbsp;
                                <a href="<?php echo base_url('expense/delete/') . $view['id']; ?>"
                                    class="btn btn-danger btns"
                                    onclick="return confirm('Are you sure you want to delete this?')">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No records found please selected expense type.</td>
                    </tr>
                <?php endif; ?>


            </tbody>
        </table>
    </div>


</section>
</div>














   



   

    <script>

        // Toggle the display of the GO button based on dropdown selection
        function toggleGoButton() {
            const selectElement = document.getElementById('type');
            const goButton = document.getElementById('btn22');
            goButton.style.display = selectElement.value ? 'inline' : 'none';
            
        }





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
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="//cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            $('.table').DataTable();
        });
    </script>

</body>

</html>