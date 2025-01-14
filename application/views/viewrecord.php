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
            <h1 class="heading" style="color:  #2A9D8F;">Total Vehicle Record</h1>
            <?php if (isset($message)): ?>
                <p><?= $message; ?></p>
            <?php endif; ?>
            <div class="he-form">


                <!-- Form to Show Total Distance for Selected Vehicle -->
                <form action="<?php echo base_url('ViewRecord') ?>" method="get">
                    <div class="forms6">
                        <div class="mb-6">
                            <select id="type" name="type" class="form-control" required onchange="toggleGoButton()">
                                <option value="" id="vlu">--Select Reading For Vehicle--</option>
                                <?php foreach ($record_types as $record): ?>
                                    <option value="<?= $record['type']; ?>" <?= isset($_GET['type']) && $_GET['type'] == $record['type'] ? 'selected' : '' ?>>
                                        <?= $record['type']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-info btns" style="display: none;" id="btn22"
                            style="background-color:  #2A9D8F; color: white; border: none; padding: 10px 10px; cursor: pointer; font-size: 14px; border-radius:5px;">Show
                            Reading</button>

                        <!-- Display Total Distance for Selected Vehicle Type -->
                        <?php if (isset($total_distance) && ($selected_type)): ?>
                            <div>
                                <h2>Total Distance Traveled by Selected Vehicle Type: <?php echo $total_distance; ?> km</h2>
                            </div>
                        <?php endif; ?>
                    </div>
                </form>


            </div>


            <div class="user">
                <table class="table">

                    <thead>
                        <th>id</th>
                        <th>Vehicle Type</th>
                        <th>Reading</th>
                        <th>Date</th>
                        <th>Remarks</th>
                        <th>Action</th>



                    </thead>

                    <tbody>
                        <?php if (!empty($records)): ?>
                            <?php foreach ($records as $records): ?>
                                <tr>
                                    <td><?= $records['user_id']; ?></td>
                                    <td><?= $records['type']; ?></td>
                                    <td><?= $records['reading']; ?></td>
                                    <td><?= $records['created_at']; ?></td>
                                    <td><?= $records['remarks']; ?></td>
                                    <td> <a href="<?php echo base_url('ViewRecord/edit/') . $records['id']; ?>"
                                            class="btn btn-info btns">Edit</a>
                                        &nbsp;
                                        <a href="<?php echo base_url('ViewRecord/delete/') . $records['id']; ?>"
                                            class="btn btn-danger btns"
                                            onclick="return confirm('Are you sure you want to delete this?')">
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="14">No views found.</td>
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