</div> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Tự động mở menu cha nếu có menu con đang active
        document.addEventListener("DOMContentLoaded", function(){
            var activeSubLink = document.querySelector('.submenu a.sub-active');
            if(activeSubLink){
                var submenuId = activeSubLink.closest('.collapse').id;
                var parentLink = document.querySelector('a[href="#' + submenuId + '"]');
                if(parentLink){
                    parentLink.classList.remove('collapsed');
                    parentLink.setAttribute('aria-expanded', 'true');
                    var collapseElement = document.getElementById(submenuId);
                    var bsCollapse = new bootstrap.Collapse(collapseElement, { toggle: false });
                    bsCollapse.show();
                }
            }
        });
    </script>
</body>
</html>