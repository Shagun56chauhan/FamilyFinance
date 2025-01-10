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


    <div class="container">


        <!-- Top Section with 2 columns -->

        <div class="top-section">
            <div class="chart" id="bar-chart">

                <canvas id="myChart"></canvas>

                <script>
                    // Extract data for the chart from the PHP variables
                    const weeklyLabels = <?php echo json_encode($weekly_records['labels']); ?>;
                    const weeklyDistance = <?php echo json_encode($weekly_records['distance']); ?>;


                    // Prepare data for the chart
                    const chartData = {
                        labels: weeklyLabels, // 7-day periods on the X-axis
                        datasets: [{
                            label: 'Weekly Distance Traveled',
                            data: weeklyDistance, // Sum of amounts per 7-day period
                            backgroundColor: 'rgba(75, 192, 192, 0.2)', // Bar color
                            borderColor: 'rgba(75, 192, 192, 1)', // Border color
                            borderWidth: 1
                        }]
                    };

                    const ctx = document.getElementById('myChart').getContext('2d');
                    const recordChart = new Chart(ctx, {
                        type: 'bar', // Bar chart type
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
                                    display: false // Hide legend if not needed
                                }
                            }
                        }
                    });
                </script>

            </div>






            <!-- Pie Chart -->
            <div class="chart" id="pie-chart">

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
            </div>
        </div>


        <!-- pie chart -->



        <!-- Middle Section with 1 column -->
        <div class="top-section">
            <div class="chart" id="bar-chart">

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
            </div>

            <!-- Pie Chart -->
            <div class="chart" id="pie-chart">

                <!-- pie chart -->
                <canvas id="currentMonthPieChart"></canvas>
                <script>
                    // Use the current month's data passed from the controller
                    let currentMonthTypes = <?php echo json_encode($current_month_types); ?>;
                    let currentMonthDistances = <?php echo json_encode($current_month_distances); ?>;

                    // Prepare data for the chart
                    let currentMonthPieChartData = {
                        labels: currentMonthTypes, // Vehicle types as labels
                        datasets: [{
                            label: 'Total Distance by Vehicle Type (Current Month)',
                            data: currentMonthDistances, // Distances as data points
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

                    // Create the pie chart
                    let currentMonthPieCtx = document.getElementById('currentMonthPieChart').getContext('2d');
                    let currentMonthPieChart = new Chart(currentMonthPieCtx, {
                        type: 'pie', // Pie chart type
                        data: currentMonthPieChartData,
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


            </div>
        </div>








        <!-- line chart -->


        <!-- Table to display vehicle type and total distance -->

        <!-- Bottom Section with 2 columns -->

        <div class="bottom-section">
            <div class="table" id="total-expense-table">

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
                                    <th>Total Distance</th>
                                    <th>
                                        <?php echo number_format($grandTotalDistance, 2); ?> km
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>


                </section>
            </div>




            <!--  monthly table  -->


            <div class="table" id="monthly-expense-table">
                <section class="shopping-cart">
                    <h1 class="heading" style="color: #2A9D8F;">Monthly Record Type</h1>

                    <!-- Month Selection Dropdown -->
                    <form method="get" action="" class="mb-4">
                        <select name="month" id="month" onchange="this.form.submit()">
                            <option value="">-- Select a Month --</option>
                            <?php
                            // Loop to generate the month options
                            foreach ($months as $month_num => $month_name) {
                                // Check if the current option is selected
                                $selected = ($month_num == $selected_month) ? 'selected' : '';
                                echo "<option value='" . date('Y') . '-' . str_pad($month_num, 2, '0', STR_PAD_LEFT) . "' $selected>$month_name</option>";
                            }
                            ?>
                        </select>
                    </form>

                    <!-- Show selected month name -->
                    <?php if ($selected_month): ?>
                        <p style="font-size:18px;">
                            <strong>Selected Month: </strong>
                            <?php
                            // Ensure selected_month is correctly formatted
                            $month_name = $months[(int) $selected_month];
                            echo $month_name . ' ' . date('Y');
                            ?>
                        </p>
                    <?php endif; ?>

                    <!-- Show table only if a month is selected -->
                    <?php if ($selected_month && !empty($month_types)): ?>
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
                                    foreach ($month_types as $record):
                                        $grandTotalDistance += $record['total_distance']; // Accumulate the total distance
                                        ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($record['type']); ?></td>
                                            <td><?php echo number_format($record['total_distance'], 2); ?> km</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Total Distance</th>
                                        <th>
                                            <?php echo number_format($grandTotalDistance, 2); ?> km
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    <?php elseif ($selected_month): ?>
                        <p>No record found for the selected month.</p>
                    <?php endif; ?>
                </section>
            </div>
        </div>
    </div>






    <!-- monthly table -->




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