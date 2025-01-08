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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.css"
        integrity="sha512-/zs32ZEJh+/EO2N1b0PEdoA10JkdC3zJ8L5FTiQu82LR9S/rOQNfQN7U59U9BC12swNeRAz3HSzIL2vpp4fv3w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"
        integrity="sha512-d9xgZrVZpmmQlfonhQUvTR7lMPtO7NkZMkA0ABN3PHCbKA5nqylQ/yWlFAyY6hYgdF1Qh6nYiuADWwKB4C2WSw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</head>

<body>

    <?php $this->view('includes/header'); ?>







    <div class="order_main">

        <section class="shopping-cart">
            <h1 class="heading" style="color:  #2A9D8F;">Total Vehicle Record</h1>
            <?php if (isset($message)): ?>
                <p><?= $message; ?></p>
            <?php endif; ?>
            <div class="he-form">


                <!-- Form to Show Total Distance for Selected Vehicle -->
                <form action="<?php echo base_url('statisticrecord/show_vehicle_distance') ?>" method="get">
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
                        <?php if (isset($total_distance)): ?>
                            <div>
                                <h2>Total Distance Traveled by Selected Vehicle Type: <?php echo $total_distance; ?> km</h2>
                            </div>
                        <?php endif; ?>
                    </div>
                </form>


            </div>
            <div class="order_main">

                <section class="shopping-cart">
                    <h1 class="heading" style="color:  #2A9D8F;">view order</h1>


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
                                    <?php foreach ($records as $record): ?>
                                        <tr>
                                            <td><?= $record['user_id']; ?></td>
                                            <td><?= $record['type']; ?></td>
                                            <td><?= $record['reading']; ?></td>
                                            <td><?= $record['created_at']; ?></td>
                                            <td><?= $record['remarks']; ?></td>
                                            <td> <a href="<?php echo base_url('viewrecord/edit/') . $record['id']; ?>"
                                                    class="btn btn-info btns">Edit</a>
                                                &nbsp;
                                                <a href="<?php echo base_url('viewrecord/delete/') . $record['id']; ?>"
                                                    class="btn btn-danger btns"
                                                    onclick="return confirm('Are you sure you want to delete this?')">
                                                    Delete
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="14">No record found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </section>
    </div>




   
    <canvas id="myChart"></canvas>

    <script>
        // Extract data for the chart from the PHP variables
        const weeklyLabels = <?php echo json_encode($weekly_records['labels']); ?>;
        const weeklyDistance = <?php echo json_encode($weekly_records['distance']); ?>;


        console.log("Labels: ", weeklyLabels);
        console.log("Distances: ", weeklyDistance);
        // Prepare the data for the chart
        const chartData = {
            labels: weeklyLabels,  // 7-day periods on the X-axis
            datasets: []           // Array to hold datasets for each vehicle
        };

        // Vehicle types (since you only have two vehicles)
        const vehicleTypes = Object.keys(weeklyDistance);

        // Iterate over each vehicle type and prepare the dataset for the chart
        vehicleTypes.forEach(vehicleType => {
            const distances = weeklyLabels.map(label => {
                // If the vehicle has a reading for the date, use it; otherwise, assign 0
                return weeklyDistance[vehicleType][label] || 0;
            });

            chartData.datasets.push({
                label: vehicleType,  // Vehicle type name as the label
                data: distances,     // Distance values for each date (0 if no record)
                backgroundColor: 'rgba(75, 192, 192, 0.2)', // Bar color
                borderColor: 'rgba(75, 192, 192, 1)',       // Border color
                borderWidth: 1
            });
        });

        // Create the chart
        const ctx = document.getElementById('myChart').getContext('2d');
        const recordChart = new Chart(ctx, {
            type: 'bar',  // Bar chart type
            data: chartData,
            options: {
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Date Range'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Distance Traveled (km)'
                        }
                    }
                },
                responsive: true,
                plugins: {
                    legend: {
                        display: true  // Show legend for vehicle types
                    }
                }
            }
        });

    </script>






    <!-- pie chart -->
    <canvas id="pieChart"></canvas>
    <script>
        // Use the new data in your JavaScript code for the pie chart
        let recordTypes = <?php echo json_encode($types); ?>;
        let recordDistances = <?php echo json_encode($distances); ?>;


        // Prepare data for the chart
        let pieChartData = {
            labels: recordTypes, // Dates on the X-axis
            datasets: [{
                label: 'Total Distance by Vehicle Type',
                data: recordDistances, // Amount as the data points
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        };

        // Create the chart
        let pieCtx = document.getElementById('pieChart').getContext('2d');
        let pieChart = new Chart(pieCtx, {
            type: 'pie', // Pie chart type
            data: pieChartData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true // Show legend for pie chart
                    }
                }
            }
        });
    </script>


    <!-- pie chart -->



    <!-- line chart -->


    <!-- Line chart for monthly data -->
    <canvas id="lineChart"></canvas>

    <script>
        // Extract data for the chart from the PHP variables
        const monthlyLabels = <?php echo json_encode($monthly_records['labels']); ?>;
        const monthlyDistance = <?php echo json_encode($monthly_records['distance']); ?>;
        const currentMonth = "<?php echo $currentMonth; ?>";
        // Prepare data for the line chart
        const lineChartData = {
            labels: monthlyLabels.map(date => date.split('-').slice(2).join('/')),  // Show "01", "02", etc.
            datasets: [{
                label: `Distance Traveled in ${currentMonth}`,
                data: monthlyDistance, // Total distance per month
                backgroundColor: 'rgba(75, 192, 192, 0.2)', // Area fill color
                borderColor: 'rgba(75, 192, 192, 1)', // Line color
                borderWidth: 2,
                fill: true, // Fill under the line
                tension: 0.4 // Smooth the line curve
            }]
        };

        const lineCtx = document.getElementById('lineChart').getContext('2d');
        const lineChart = new Chart(lineCtx, {
            type: 'line', // Line chart type
            data: lineChartData,
            options: {
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Date'
                        },
                        ticks: {
                            autoSkip: false, // Do not skip dates to ensure continuity across months
                            maxTicksLimit: 30, // Show up to 30 dates per month (adjust as needed)
                            callback: function (value, index, values) {
                                // Display only the first day of each month for clarity
                                const date = new Date(value);
                                return date.getDate() === 1 ? value : '';
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Amount'
                        }
                    }
                },
                responsive: true,
                plugins: {
                    legend: {
                        display: true // Show legend for line chart
                    }
                }
            }
        });
    </script>




    <!-- line chart -->




    <!-- table for pie chart -->
    <!-- Table to display expense types and amounts -->

    <div class="order_main">

        <section class="shopping-cart">
            <h1 class="heading" style="color:  #2A9D8F;">Record Type</h1>


            <div class="user">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Vehicle Type</th>
                            <th>Total Distance (km)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $grandTotalDistance = 0; // Initialize total distance
                        foreach ($types as $index => $type):
                            $distance = $distances[$index]; // Get corresponding distance for each type
                            $grandTotalDistance += $distance; // Accumulate the total distance
                            ?>

                            <tr>
                                <td><?php echo htmlspecialchars($type); ?></td>
                                <td><?php echo number_format($distance, 2); ?> km</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th style="font-size:20px; margin: 20px auto; ">Total Distance</th>
                            <th style="font-size:20px; margin: 20px auto; ">
                                <?php echo number_format($grandTotalDistance, 2); ?> km</th>
                        </tr>
                    </tfoot>
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

</body>

</html>