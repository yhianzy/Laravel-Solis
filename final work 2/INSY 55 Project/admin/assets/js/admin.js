// Dashboard view tabs and revenue filter buttons
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.dash-tab');
    const views = document.querySelectorAll('.dashboard-view');
    const revenueButtons = document.querySelectorAll('.rev-filter-btn');

    // Switch between dashboard views
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const target = tab.getAttribute('data-view');

            // Toggle active tab styling
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            // Show the matching view
            views.forEach(view => {
                if (view.id === `view-${target}`) {
                    view.classList.add('active');
                } else {
                    view.classList.remove('active');
                }
            });
        });
    });

    // Revenue filter buttons (General view)
    revenueButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            revenueButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            // Later you can hook this to update revenue numbers based on period
        });
    });
});
// import DataTable from 'datatables.net-dt';
// import 'datatables.net-responsive-dt';
 
let table = new DataTable('#myTable', {
    responsive: true
});
