<button type="button" class="btn btn-warning btn-sm shadow" id="back-to-top-btn">
    <i class="bi bi-arrow-up"></i>
</button>
<script>
    function showBackToTopBtnOnScroll(btn) {
        if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
            btn.style.display = "block";
        } else {
            btn.style.display = "none";
        }
    }

    function backToTop() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }

    let backToTopBtn = document.getElementById("back-to-top-btn");
    backToTopBtn.addEventListener("click", backToTop);

    window.onscroll = function() {
        showBackToTopBtnOnScroll(backToTopBtn);
    };
</script>
