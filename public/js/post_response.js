document.addEventListener('DOMContentLoaded', () => {

    const form = document.getElementById('tutorsForm');
    const saveBtn = document.getElementById('saveChangesBtn');
    const sortSelect = document.getElementById('sortSelect');
    const container = document.getElementById('profilesContainer');

    // Safety check
    if (!form || !saveBtn || !container) return;

    let changesMade = false;

    const setChangesMade = () => {
        changesMade = true;
        saveBtn.disabled = false;
    };

    // ✅ EVENT DELEGATION: toggle shortlist
    form.addEventListener('click', (e) => {
        const btn = e.target.closest('.btn-shortlist');
        if (!btn) return;

        const card = btn.closest('.tutor-card');
        if (!card) return;

        // Toggle the card class
        const isShortlisted = card.classList.toggle('shortlisted');

        // Update button UI
        if (isShortlisted) {
            btn.classList.add('remove');
            btn.innerHTML = '<i class="bi bi-check-circle"></i> Remove';
        } else {
            btn.classList.remove('remove');
            btn.innerHTML = '<i class="bi bi-check-circle"></i> Shortlist';
        }

        // Update hidden input value
        const input = card.querySelector('.shortlist-input');
        if (input) input.value = isShortlisted ? 1 : 0;

        setChangesMade();
    });

    // ✅ Sorting
    if (sortSelect) {
        sortSelect.addEventListener('change', () => {
            const cards = Array.from(container.querySelectorAll('.tutor-card'));
            let sorted = [...cards];

            switch (sortSelect.value) {
                case 'rating_desc':
                    sorted.sort((a, b) => parseFloat(b.dataset.rating) - parseFloat(a.dataset.rating));
                    break;
                case 'rating_asc':
                    sorted.sort((a, b) => parseFloat(a.dataset.rating) - parseFloat(b.dataset.rating));
                    break;
                case 'salary_desc':
                    sorted.sort((a, b) => parseFloat(b.dataset.salary) - parseFloat(a.dataset.salary));
                    break;
                case 'salary_asc':
                    sorted.sort((a, b) => parseFloat(a.dataset.salary) - parseFloat(b.dataset.salary));
                    break;
            }

            sorted.forEach(card => container.appendChild(card));
        });
    }
    // Warn if there are unsaved changes
    window.addEventListener('beforeunload', (e) => {
        if (!saveBtn.disabled) {
            e.preventDefault();
            e.returnValue = ''; // required for most browsers
        }
    });
    // ✅ Filter: Show shortlisted only
    window.showShortlisted = () => {
        container.querySelectorAll('.tutor-card').forEach(card => {
            card.style.display = card.classList.contains('shortlisted') ? '' : 'none';
        });
    };

    // ✅ Reset filters
    window.resetFilters = () => {
        container.querySelectorAll('.tutor-card').forEach(card => card.style.display = '');
        if (sortSelect) sortSelect.value = '';
    };

});