<script>
    function openModal(id) {
        document.getElementById(id).classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
        if (!document.querySelector('.sa-modal.active')) {
            document.body.style.overflow = '';
        }
    }

    document.querySelectorAll('.sa-modal').forEach(function (modal) {
        modal.addEventListener('click', function (e) {
            if (e.target === this) closeModal(this.id);
        });
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.sa-modal.active').forEach(function (m) { closeModal(m.id); });
        }
    });

    function setValue(id, value, fallback) {
        document.getElementById(id).value = (value ?? fallback ?? '');
    }
</script>