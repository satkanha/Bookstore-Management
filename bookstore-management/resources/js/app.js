import './bootstrap';
import * as bootstrap from 'bootstrap';
import Chart from 'chart.js/auto';
import Alpine from 'alpinejs';

window.bootstrap = bootstrap;
window.Chart = Chart;
window.Alpine = Alpine;

const currency = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
});

const money = (value) => currency.format(Number.isFinite(value) ? value : 0);

const updateLoadingButton = (button, loadingText) => {
    if (!button || button.dataset.loading === 'true') {
        return;
    }

    button.dataset.loading = 'true';
    button.dataset.originalHtml = button.innerHTML;
    button.disabled = true;
    button.innerHTML = `<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>${loadingText}`;
};

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.bookstore-toast').forEach((toast) => {
        bootstrap.Toast.getOrCreateInstance(toast).show();
    });

    document.querySelectorAll('form[data-loading-form]').forEach((form) => {
        form.addEventListener('submit', () => {
            const button = form.querySelector('[data-loading-button]');
            const loadingText = button?.dataset.loadingText || document.body.dataset.loadingText || 'Loading...';
            updateLoadingButton(button, loadingText);
        });
    });

    const orderModal = document.getElementById('bookOrderModal');
    const orderForm = document.getElementById('bookOrderForm');

    if (orderModal) {
        const quantityInput = orderModal.querySelector('[data-order-quantity]');
        const totalOutput = orderModal.querySelector('[data-order-total]');
        const shippingOutput = orderModal.querySelector('[data-order-shipping]');
        const confirmButton = orderModal.querySelector('[data-loading-button]');
        let selectedPrice = 0;
        let selectedStock = 0;

        const refreshOrderTotal = () => {
            const quantity = Math.max(1, Number.parseInt(quantityInput?.value || '1', 10));
            const subtotal = selectedPrice * quantity;
            const shipping = subtotal >= 50 ? 0 : 4.99;

            if (shippingOutput) {
                shippingOutput.textContent = money(shipping);
            }

            if (totalOutput) {
                totalOutput.textContent = money(subtotal + shipping);
            }
        };

        orderModal.addEventListener('show.bs.modal', (event) => {
            const trigger = event.relatedTarget;

            if (!trigger) {
                return;
            }

            selectedPrice = Number.parseFloat(trigger.dataset.bookPrice || '0');
            selectedStock = Number.parseInt(trigger.dataset.bookStock || '0', 10);

            orderModal.querySelector('[data-order-cover]')?.setAttribute('src', trigger.dataset.bookCover || '');
            orderModal.querySelector('[data-order-cover]')?.setAttribute('alt', trigger.dataset.bookCoverAlt || '');
            orderModal.querySelector('[data-order-title]').textContent = trigger.dataset.bookTitle || '';
            orderModal.querySelector('[data-order-meta]').textContent = [trigger.dataset.bookAuthor, trigger.dataset.bookCategory].filter(Boolean).join(' / ');
            orderModal.querySelector('[data-order-price]').textContent = money(selectedPrice);
            orderModal.querySelector('[data-order-stock]').textContent = trigger.dataset.bookStockLabel || `${selectedStock}`;

            const bookIdInput = orderModal.querySelector('[data-order-book-id]');
            if (bookIdInput) {
                bookIdInput.value = trigger.dataset.bookId || '';
            }

            if (orderForm && trigger.dataset.orderUrl) {
                orderForm.action = trigger.dataset.orderUrl;
            }

            if (quantityInput) {
                quantityInput.max = Math.max(selectedStock, 1).toString();
                quantityInput.value = '1';
                quantityInput.disabled = selectedStock < 1;
            }

            if (confirmButton) {
                confirmButton.disabled = selectedStock < 1;
            }

            refreshOrderTotal();
        });

        quantityInput?.addEventListener('input', () => {
            const quantity = Number.parseInt(quantityInput.value || '1', 10);

            if (quantity > selectedStock) {
                quantityInput.value = selectedStock.toString();
            }

            if (quantity < 1) {
                quantityInput.value = '1';
            }

            refreshOrderTotal();
        });
    }

    const deleteModal = document.getElementById('deleteConfirmModal');
    const deleteForm = document.getElementById('deleteConfirmForm');

    if (deleteModal && deleteForm) {
        deleteModal.addEventListener('show.bs.modal', (event) => {
            const trigger = event.relatedTarget;

            if (!trigger) {
                return;
            }

            deleteForm.action = trigger.dataset.deleteUrl || '';
            deleteModal.querySelector('[data-delete-title]').textContent = trigger.dataset.deleteTitle || '';
            deleteModal.querySelector('[data-delete-message]').textContent = trigger.dataset.deleteMessage || '';
        });
    }
});

Alpine.start();
