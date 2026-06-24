const projectsListContainer = document.querySelector('[data-projects-list]');

if (projectsListContainer) {
    const pageSize = Number(projectsListContainer.dataset.pageSize) || 5;
    const items = Array.from(projectsListContainer.querySelectorAll('[data-project-item]'));
    const prevButton = projectsListContainer.querySelector('[data-pagination-prev]');
    const nextButton = projectsListContainer.querySelector('[data-pagination-next]');
    const status = projectsListContainer.querySelector('[data-pagination-status]');

    let currentPage = 1;
    const totalPages = Math.max(Math.ceil(items.length / pageSize), 1);

    function renderPage(page) {
        currentPage = Math.min(Math.max(page, 1), totalPages);
        const start = (currentPage - 1) * pageSize;
        const end = start + pageSize;

        items.forEach((item, index) => {
            item.hidden = index < start || index >= end;
        });

        if (status) {
            status.textContent = `Page ${currentPage} / ${totalPages}`;
        }

        if (prevButton) {
            prevButton.disabled = currentPage === 1;
        }

        if (nextButton) {
            nextButton.disabled = currentPage === totalPages;
        }
    }

    if (prevButton) {
        prevButton.addEventListener('click', () => {
            renderPage(currentPage - 1);
        });
    }

    if (nextButton) {
        nextButton.addEventListener('click', () => {
            renderPage(currentPage + 1);
        });
    }

    renderPage(1);
}