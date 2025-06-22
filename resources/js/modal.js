export function showErrorModal(message) {
    let modal = document.getElementById('custom-error-modal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'custom-error-modal';
        modal.style.position = 'fixed';
        modal.style.top = '20px';
        modal.style.left = '50%';
        modal.style.transform = 'translateX(-50%)';
        modal.style.background = '#f87171';
        modal.style.color = '#fff';
        modal.style.padding = '16px 32px';
        modal.style.borderRadius = '8px';
        modal.style.zIndex = '9999';
        modal.style.boxShadow = '0 2px 8px rgba(0,0,0,0.15)';
        document.body.appendChild(modal);
    }
    modal.textContent = message;
    modal.style.display = 'block';

    setTimeout(() => {
        modal.style.display = 'none';
    }, 3000);
}