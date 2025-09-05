
document.addEventListener('DOMContentLoaded', () => {

    const groups = document.querySelectorAll('.habits-group');
    groups.forEach(group => {
        const items = group.querySelector('.habits-items');
        if (!items || !items.querySelector('li:not(:contains("Нет привычек."))')) {
            group.style.display = 'none';
        }
    });

    document.querySelectorAll('.toggle-header').forEach(header => {
        header.addEventListener('click', () => {
            const targetId = header.getAttribute('data-target');
            const list = document.getElementById(targetId);
            const icon = header.querySelector('.toggle-icon');

            if (list.style.display === 'none') {
                list.style.display = 'block';
                icon.textContent = '▼';
            } else {
                list.style.display = 'none';
                icon.textContent = '▶';
            }
        });
    });
});
