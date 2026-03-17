// post_response.js

let originalOrder = [];

document.addEventListener("DOMContentLoaded", () => {
    const container = document.getElementById("profilesContainer");
    if (!container) return;

    originalOrder = Array.from(container.querySelectorAll(".tutor-card"));

    const sortSelect = document.getElementById("sortSelect");
    if (sortSelect) {
        sortSelect.addEventListener("change", applySorting);
    }
});


// Shortlist toggle
function toggleShortlist(index) {
    const container = document.getElementById("profilesContainer");
    if (!container) return;

    const cards = Array.from(container.querySelectorAll(".tutor-card"));
    const card = cards[index];
    if (!card) return;

    card.classList.toggle("shortlisted");

    applySorting(); // reapply sorting automatically
}


// 🔽 MAIN SORT FUNCTION
function applySorting() {
    const container = document.getElementById("profilesContainer");
    const sortSelect = document.getElementById("sortSelect");

    if (!container || !sortSelect) return;

    const value = sortSelect.value;
    const cards = Array.from(container.querySelectorAll(".tutor-card"));

    cards.sort((a, b) => {
        const aRating = parseFloat(a.dataset.rating) || 0;
        const bRating = parseFloat(b.dataset.rating) || 0;

        const aSalary = parseFloat(a.dataset.salary) || 0;
        const bSalary = parseFloat(b.dataset.salary) || 0;

        const aShort = a.classList.contains("shortlisted") ? 1 : 0;
        const bShort = b.classList.contains("shortlisted") ? 1 : 0;

        // ✅ APPLY SORT
        if (value === "rating_desc") return bRating - aRating;
        if (value === "rating_asc") return aRating - bRating;
        if (value === "salary_desc") return bSalary - aSalary;
        if (value === "salary_asc") return aSalary - bSalary;

        // ✅ DEFAULT (no sort)
        if (aShort !== bShort) return bShort - aShort;

        return originalOrder.indexOf(a) - originalOrder.indexOf(b);
    });

    cards.forEach(card => container.appendChild(card));
}


// ⭐ Show shortlisted only
function showShortlisted() {
    document.querySelectorAll(".tutor-card").forEach(card => {
        card.style.display = card.classList.contains("shortlisted") ? "" : "none";
    });
}


// 🔄 Reset everything
function resetFilters() {
    const sortSelect = document.getElementById("sortSelect");

    document.querySelectorAll(".tutor-card").forEach(card => {
        card.style.display = "";
    });

    if (sortSelect) {
        sortSelect.value = "";
    }

    applySorting();
}