window.App = {
    csrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content || '';
    },

    showToast(message, type = 'success') {
        const container = document.getElementById('toastContainer');
        if (!container) return;

        const bg = type === 'success' ? 'text-bg-success' : 'text-bg-danger';
        const el = document.createElement('div');
        el.className = `toast align-items-center ${bg} border-0`;
        el.setAttribute('role', 'alert');
        el.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>`;
        container.appendChild(el);
        const toast = new bootstrap.Toast(el, { delay: 4000 });
        toast.show();
        el.addEventListener('hidden.bs.toast', () => el.remove());
    },

    setLoading(show) {
        let overlay = document.getElementById('globalLoading');
        if (!overlay) {
            overlay = document.createElement('div');
            overlay.id = 'globalLoading';
            overlay.className = 'loading-overlay';
            overlay.innerHTML = '<div class="spinner-border text-primary" role="status"></div>';
            document.body.appendChild(overlay);
        }
        overlay.classList.toggle('show', !!show);
    }
};
