<script>
    // Mobile menu toggle
    const menuBtn = document.getElementById("menu-btn");
    const mobileMenu = document.getElementById("mobile-menu");
    if (menuBtn && mobileMenu) {
        menuBtn.addEventListener("click", () => {
            mobileMenu.classList.toggle("hidden");
        });
    }

    // Modal functions
    function openModal(id) {
        document.getElementById(id)?.classList.remove("hidden");
    }
    
    function closeModal(id) {
        document.getElementById(id)?.classList.add("hidden");
    }

    // Dashboard sidebar toggle
    const sidebar = document.getElementById("sidebar");
    const sidebarToggle = document.getElementById("sidebar-toggle");
    const sidebarClose = document.getElementById("sidebar-close");
    const sidebarOverlay = document.getElementById("sidebar-overlay");
    const mainContent = document.getElementById("main-content");

    function openSidebar() {
        sidebar?.classList.remove("-translate-x-full");
        sidebarOverlay?.classList.remove("hidden");
    }
    
    function closeSidebar() {
        sidebar?.classList.add("-translate-x-full");
        sidebarOverlay?.classList.add("hidden");
    }

    sidebarToggle?.addEventListener("click", openSidebar);
    sidebarClose?.addEventListener("click", closeSidebar);
    sidebarOverlay?.addEventListener("click", closeSidebar);

    // Responsive adjustments
    function adjustSidebar() {
        if (window.innerWidth >= 768 && sidebar && mainContent) {
            sidebar.classList.remove("-translate-x-full");
            sidebarOverlay?.classList.add("hidden");
            mainContent.style.marginLeft = "18rem";
        } else if (mainContent) {
            mainContent.style.marginLeft = "0";
        }
    }
    
    window.addEventListener("resize", adjustSidebar);
    adjustSidebar();
</script>