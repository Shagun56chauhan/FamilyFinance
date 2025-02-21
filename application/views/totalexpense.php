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
        
      <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>


    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>

    <?php $this->view('includes/header'); ?>






    <div class="container">


        <!-- Top Section with 2 columns -->

        <div class="top-section">
            <!-- Pie Chart -->
            <div class="chart" id="pie-chart2">
                <canvas id="newPieChart"></canvas>
                <script>
                    // Prepare labels (expense types) and data (created_at dates) for the chart
                    let newexpenseAmountsPie = <?php echo json_encode(array_column($pietypes, 'amount')); ?>;
                    let newexpenseTypes = <?php echo json_encode(array_column($pietypes, 'type')); ?>;

                    // Prepare data for the pie chart
                    let newpieChartData = {
                        labels: newexpenseTypes,
                        datasets: [{
                            label: 'Expenses by Types for Current Month',
                            data: newexpenseAmountsPie, // Individual amounts for each date
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)',
                                'rgba(255, 99, 132, 0.3)',
                                'rgba(54, 162, 235, 0.3)',
                                'rgba(255, 206, 86, 0.3)',
                                'rgba(75, 192, 192, 0.3)',
                                'rgba(153, 102, 255, 0.3)',
                                'rgba(255, 159, 64, 0.3)',
                                'rgba(100, 149, 237, 0.2)',  // New color 1
                                'rgba(255, 105, 180, 0.2)',  // New color 2
                                'rgba(34, 193, 195, 0.2)',   // New color 3
                                'rgba(253, 187, 45, 0.2)',   // New color 4
                                'rgba(243, 156, 18, 0.2)',   // New color 5
                                'rgba(46, 204, 113, 0.2)',   // New color 6
                                'rgba(39, 174, 96, 0.2)',    // New color 7
                                'rgba(142, 68, 173, 0.2)',   // New color 8
                                'rgba(52, 152, 219, 0.2)'    // New color 9
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)',
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)',
                                'rgba(100, 149, 237, 1)',  // New color 1 border
                                'rgba(255, 105, 180, 1)',  // New color 2 border
                                'rgba(34, 193, 195, 1)',   // New color 3 border
                                'rgba(253, 187, 45, 1)',   // New color 4 border
                                'rgba(243, 156, 18, 1)',   // New color 5 border
                                'rgba(46, 204, 113, 1)',   // New color 6 border
                                'rgba(39, 174, 96, 1)',    // New color 7 border
                                'rgba(142, 68, 173, 1)',   // New color 8 border
                                'rgba(52, 152, 219, 1)'    // New color 9 border
                            ],
                            borderWidth: 1
                        }]
                    };

                    // Create the pie chart
                    const newpieCtx = document.getElementById('newPieChart').getContext('2d');
                    const newpieChart = new Chart(newpieCtx, {
                        type: 'pie', // Pie chart type
                        data: newpieChartData,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false, // Allow manual size control

                            plugins: {
                                title: {
                                    display: true,  // Enable title
                                    text: 'Total Expense For Month',  // Custom text at the top
                                    font: {
                                        size: 13 // Adjust font size
                                    },
                                    padding: {
                                        top: 10, // Space from top
                                        bottom: 20 // Space before chart
                                    }
                                },
                                legend: {
                                    position: 'top',
                                    labels: {
                                        font: {
                                            size: 14
                                        }
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function (tooltipItem) {
                                            // Display the amount along with the label
                                            return tooltipItem.label + ': ' + tooltipItem.raw;
                                        }
                                    }
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
                    // Prepare labels (expense types) and data (created_at dates) for the chart
                    let expenseAmountsPie = [];
                    let expenseTypes = [];


                    // Extract expense types and dates from the PHP data passed to JavaScript
                    <?php foreach ($types as $expense): ?>
                        expenseAmountsPie.push("<?php echo $expense['amount']; ?>");
                        expenseTypes.push("<?php echo $expense['type']; ?>");
                    <?php endforeach; ?>



                    // Prepare data for the chart
                    let pieChartData = {
                        labels: expenseTypes, // Dates on the X-axis
                        datasets: [{
                            label: 'Expenses by Types',
                            data: expenseAmountsPie, // Amount as the data points
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)',
                                'rgba(255, 99, 132, 0.3)',
                                'rgba(54, 162, 235, 0.3)',
                                'rgba(255, 206, 86, 0.3)',
                                'rgba(75, 192, 192, 0.3)',
                                'rgba(153, 102, 255, 0.3)',
                                'rgba(255, 159, 64, 0.3)',
                                'rgba(100, 149, 237, 0.2)',  // New color 1
                                'rgba(255, 105, 180, 0.2)',  // New color 2
                                'rgba(34, 193, 195, 0.2)',   // New color 3
                                'rgba(253, 187, 45, 0.2)',   // New color 4
                                'rgba(243, 156, 18, 0.2)',   // New color 5
                                'rgba(46, 204, 113, 0.2)',   // New color 6
                                'rgba(39, 174, 96, 0.2)',    // New color 7
                                'rgba(142, 68, 173, 0.2)',   // New color 8
                                'rgba(52, 152, 219, 0.2)'    // New color 9
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)',
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)',
                                'rgba(100, 149, 237, 1)',  // New color 1 border
                                'rgba(255, 105, 180, 1)',  // New color 2 border
                                'rgba(34, 193, 195, 1)',   // New color 3 border
                                'rgba(253, 187, 45, 1)',   // New color 4 border
                                'rgba(243, 156, 18, 1)',   // New color 5 border
                                'rgba(46, 204, 113, 1)',   // New color 6 border
                                'rgba(39, 174, 96, 1)',    // New color 7 border
                                'rgba(142, 68, 173, 1)',   // New color 8 border
                                'rgba(52, 152, 219, 1)'    // New color 9 border
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
                            maintainAspectRatio: false, // Allow manual size control
                            plugins: {
                                title: {
                                    display: true,  // Enable title
                                    text: 'Total Expense',  // Custom text at the top
                                    font: {
                                        size: 13 // Adjust font size
                                    },
                                    padding: {
                                        top: 10, // Space from top
                                        bottom: 20 // Space before chart
                                    }
                                },
                                legend: {
                                    display: true // Show legend for pie chart
                                }
                            }
                        }

                    });
                </script>
            </div>
        </div>

        <!-- Top Section with 2 columns -->


        <!-- pie chart -->



        <!-- Middle Section with 1 column -->

        <div class="top-section">
            <div class="chart" id="line-chart">
                <canvas id="monthlyLineChart"></canvas>
            </div>
            <div class="chart" id="line-chart">
                <canvas id="yearlyLineChart"></canvas>
            </div>

            <script>
                const monthlyLabels = <?php echo json_encode($monthly_expenses['labels']); ?>; // 01, 02, 03...
                const monthlyAmounts = <?php echo json_encode($monthly_expenses['amounts']); ?>;
                const yearlyLabels = <?php echo json_encode($yearly_expenses['labels']); ?>; // Jan, Feb, Mar...
                const yearlyAmounts = <?php echo json_encode($yearly_expenses['amounts']); ?>;

                // Monthly Line Chart
                const monthlyCtx = document.getElementById('monthlyLineChart').getContext('2d');
                new Chart(monthlyCtx, {
                    type: 'line',
                    data: {
                        labels: monthlyLabels,
                        datasets: [{
                            label: 'Monthly Expenditure',
                            data: monthlyAmounts,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false, // ✅ Prevent shrinking on small screens
                        scales: {
                            x: { title: { display: true, text: 'Day (01, 02, 03...)' } },
                            y: { title: { display: true, text: 'Amount' } }
                        },
                        responsive: true
                    }
                });

                // Yearly Line Chart
                const yearlyCtx = document.getElementById('yearlyLineChart').getContext('2d');
                new Chart(yearlyCtx, {
                    type: 'line',
                    data: {
                        labels: yearlyLabels,
                        datasets: [{
                            label: 'Yearly Expenditure',
                            data: yearlyAmounts,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false, // ✅ Prevent shrinking on small screens
                        scales: {
                            x: { title: { display: true, text: 'Month (Jan, Feb, Mar...)' } },
                            y: { title: { display: true, text: 'Amount' } }
                        },
                        responsive: true
                    }
                });
            </script>

        </div>

        <!-- pie chart -->
        <div class="chart" id="bar-chart">

            <canvas id="myChart"></canvas>

            <script>
                // Extract data for the chart from the PHP variables
                const weeklyLabels = <?php echo json_encode($weekly_expenses['labels']); ?>;
                const weeklyAmounts = <?php echo json_encode($weekly_expenses['amounts']); ?>;



                // Prepare data for the chart
                const chartData = {
                    labels: weeklyLabels, // 7-day periods on the X-axis
                    datasets: [{
                        label: 'Weekly Expenses',
                        data: weeklyAmounts, // Sum of amounts per 7-day period
                        backgroundColor: 'rgba(75, 192, 192, 0.2)', // Bar color
                        borderColor: 'rgba(75, 192, 192, 1)', // Border color
                        borderWidth: 1
                    }]
                };

                const ctx = document.getElementById('myChart').getContext('2d');
                const expenseChart = new Chart(ctx, {
                    type: 'bar', // Bar chart type
                    data: chartData,
                    options: {
                        responsive: true,    
                       maintainAspectRatio: false, // ✅ Prevent shrinking on small screens
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
                                    text: 'Amount'
                                }
                            }
                        },
                       
                        plugins: {
                            legend: {
                                display: false // Hide legend if not needed
                            }
                        }
                    }
                });
            </script>

        </div>

    </div>




    <!-- line chart -->



    <!-- table for pie chart -->




    <!-- table for pie chart -->
    <!-- Table to display expense types and amounts -->











    <!-- Bottom Section with 2 columns -->

    <div class="bottom-section">
        <div class="table" id="total-expense-table">

            <section class="shopping-cart">
                <h1 class="heading" style="color:  #2A9D8F;">Total Expense Table</h1>


                <div class="user">
                    <table class="styled-table">
                        <thead>
                            <tr>
                                <th>Expense Type</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $totalAmount = 0; // Initialize total amount
                            foreach ($table_types as $expense):
                                $totalAmount += $expense['amount']; // Accumulate the total
                                ?>

                                <tr>
                                    <td><?php echo htmlspecialchars($expense['type']); ?></td>
                                    <td><?php echo number_format($expense['amount'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th style="font-size:20px; margin: 20px auto; ">TOTAL</th>
                                <th style="font-size:20px; margin: 20px auto; ">
                                    <?php echo number_format($totalAmount, 2); ?>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>


            </section>
        </div>





        <!-- monthly table -->


        <div class="table" id="monthly-expense-table">
            <section class="shopping-cart">
                <h1 class="heading" style="color: #2A9D8F; text-align: center;">Monthly Expense Table</h1>

                <!-- Year and Month Selection Dropdown in the Same Row -->
                <form id="filterForm" class="form-inline"
                    style="display: flex; justify-content: center; align-items: center; gap: 20px; margin-bottom: 20px;">
                    <div class="dropdown-container">
                        <label for="year" class="dropdown-label">Select Year</label>
                        <select name="year" id="year" class="dropdown1">
                            <option value="">-- Select a Year --</option>
                            <?php
                            for ($year = date('Y'); $year >= date('Y') - 5; $year--) {
                                $selected = ($year == $selected_year) ? 'selected' : '';
                                echo "<option value='$year' $selected>$year</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="dropdown-container">
                        <label for="month" class="dropdown-label">Select Month</label>
                        <select name="month" id="month" class="dropdown1">
                            <option value="">-- Select a Month --</option>
                            <?php
                            foreach ($months as $key => $name) {
                                $selected = ($key == $selected_month) ? 'selected' : '';
                                echo "<option value='$key' $selected>$name</option>";
                            }
                            ?>
                        </select>
                    </div>
                </form>

                <!-- Show selected year and month -->
                <?php if ($selected_year && $selected_month): ?>
                    <p style="font-size:18px; text-align: center;">
                        <strong>Selected Period: </strong>
                        <?php echo $months[$selected_month] . " " . $selected_year; ?>
                    </p>
                <?php endif; ?>

                <!-- Show table only if year and month are selected -->
                <div id="expenseTableContainer">
                    <?php if ($selected_year && $selected_month && !empty($month_types)): ?>
                        <div class="user">
                            <table class="styled-table">
                                <thead>
                                    <tr>
                                        <th>Expense Type</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $totalAmount = 0;
                                    foreach ($month_types as $expense):
                                        $totalAmount += $expense['amount'];
                                        ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($expense['type']); ?></td>
                                            <td><?php echo number_format($expense['amount'], 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th style="font-size:20px;">TOTAL</th>
                                        <th style="font-size:20px;">
                                            <?php echo number_format($totalAmount, 2); ?>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    <?php elseif ($selected_year && $selected_month): ?>
                        <p style="text-align: center;">No expenses found for the selected period.</p>
                    <?php endif; ?>
                </div>







            </section>
        </div>

    </div>

    </div>

    <!-- m0nthly -->



    <script>
        $(document).ready(function () {
            $('#year, #month').change(function () {
                var year = $('#year').val();
                var month = $('#month').val();
                if (year && month) {
                    $.ajax({
                        url: '<?php echo base_url("TotalExpense/getMonthlyExpenses"); ?>',
                        type: 'GET',
                        data: { year: year, month: month },
                        success: function (response) {
                            $('#expenseTableContainer').html(response);
                        }
                    });
                }
            });
        });
    </script>

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