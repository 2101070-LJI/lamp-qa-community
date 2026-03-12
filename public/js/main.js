document.addEventListener('DOMContentLoaded', function () {
    // CSRF token auto-inject (optional enhancement)
    // Vote buttons (will be expanded in Phase 3)
    document.querySelectorAll('.vote-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const form = btn.closest('form');
            if (form) form.submit();
        });
    });
});
