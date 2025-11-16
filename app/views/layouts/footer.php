</div> <!-- End admin-layout -->
    
    <!-- JavaScript -->
    <script src="/block-sports-center/public/assets/js/main.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var adminLayout = document.querySelector('.admin-layout');
        var toggleBtn   = document.querySelector('.sidebar-toggle');

        if (adminLayout && toggleBtn) {
            toggleBtn.addEventListener('click', function () {
                adminLayout.classList.toggle('sidebar-collapsed');
            });
        }
    });
    </script>
</body>
</html>