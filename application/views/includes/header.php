<header class="header">
    <div class="flex">
        <a href="#" class="logo">FamilyFinance</a>
        <nav class="navbar">
            <div class="hover dropdown">
                <a href="#" class="dropdown-toggle">Expense</a>
                <div class="dropdown-content">
                    <a href="<?php echo base_url('Expense'); ?>" id="shagu">Add Expense</a>
                    <a href="<?php echo base_url('ViewExpense'); ?>" id="shagu">View Expense</a>
                    <a href="<?php echo base_url('TotalExpense'); ?>" id="shagu">Statistics</a>
                </div>
            </div>

            <div class="hover dropdown">
            <a href="#" id="login_btn" style="display: none;">LOG IN</a>
                <div class="dropdown-content">
                    <a href="<?php echo base_url('Auth/logout'); ?>" id="log_out">LOG OUT</a>
                </div>
            </div>

            <div class="hover dropdown">
                <a href="#" class="dropdown-toggle">Vehicle Log</a>
                <div class="dropdown-content">
                    <a href="<?php echo base_url('AddRecord'); ?>" id="shagu">Add Record</a>
                    <a href="<?php echo base_url('ViewRecord'); ?>" id="shagu">View Records</a>
                    <a href="<?php echo base_url('StatisticRecord'); ?>" id="shagu">Statistics</a>
                </div>
            </div>
        </nav>

        <div id="menu-btn" class="fas fa-bars"></div>
    </div>
</header>

<script>
   document.addEventListener("DOMContentLoaded", () => {
    const menuBtn = document.getElementById("menu-btn");
    const navbar = document.querySelector(".navbar");
    const dropdownToggles = document.querySelectorAll(".dropdown-toggle");

    // Toggle navbar in mobile view
    menuBtn.addEventListener("click", () => {
        menuBtn.classList.toggle('fa-times');
        navbar.classList.toggle("active");
    });

    window.onscroll = ()=>{
    menuBtn.classList.remove('fa-times');
    navbar.classList.remove('active');
};

    // Dropdown toggle behavior with auto-close for other dropdowns
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener("click", (e) => {
            e.preventDefault(); // Prevent default navigation

            // Close other open dropdowns
            document.querySelectorAll(".dropdown.open").forEach(openDropdown => {
                if (openDropdown !== toggle.parentElement) {
                    openDropdown.classList.remove("open");
                }
            });

            // Toggle the current dropdown
            const parent = toggle.parentElement;
            parent.classList.toggle("open");
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener("click", (e) => {
        if (!e.target.closest(".navbar")) {
            document.querySelectorAll(".dropdown.open").forEach(openDropdown => {
                openDropdown.classList.remove("open");
            });
        }
    });
});

</script>


