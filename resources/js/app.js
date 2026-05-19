import './bootstrap';

import $ from 'jquery';
import DataTable from 'datatables.net-dt';
import Alpine from 'alpinejs';
import toastr from 'toastr';
import Swal from 'sweetalert2';

window.$ = window.jQuery = $;
window.DataTable = DataTable;
window.Alpine = Alpine;

Alpine.start();

const root = document.documentElement;
const storageKey = 'money-tracker-theme';

const applyTheme = (theme) => {
    root.setAttribute('data-theme', theme);
    const icons = document.querySelectorAll('[data-theme-icon]');

    icons.forEach((icon) => {
        icon.className = theme === 'dark' ? 'fa-solid fa-moon' : 'fa-solid fa-sun';
    });
};

toastr.options = {
    closeButton: true,
    progressBar: false,
    newestOnTop: true,
    positionClass: 'toast-top-right',
    preventDuplicates: true,
    timeOut: 3500,
    extendedTimeOut: 1000,
};

const applySwalButtonStyle = (button, variant) => {
    if (!button) {
        return;
    }

    button.classList.add('brutal-swal-btn');

    if (variant === 'confirm') {
        button.classList.add('brutal-swal-confirm');
        button.style.background = 'var(--accent)';
        button.style.color = '#000';
    } else {
        button.classList.add('brutal-swal-cancel');
        button.style.background = 'var(--surface)';
        button.style.color = 'var(--ink)';
    }

    button.style.display = 'inline-flex';
    button.style.alignItems = 'center';
    button.style.justifyContent = 'center';
    button.style.minWidth = '9rem';
    button.style.margin = '0';
    button.style.padding = '0.75rem 1rem';
    button.style.border = '4px solid var(--ink)';
    button.style.borderRadius = '0';
    button.style.boxShadow = '4px 4px 0 0 var(--ink)';
    button.style.fontSize = '0.875rem';
    button.style.fontWeight = '900';
    button.style.textTransform = 'uppercase';
    button.style.letterSpacing = '0.14em';
    button.style.lineHeight = '1.1';
    button.style.cursor = 'pointer';
    button.style.appearance = 'none';
};

const formatRupiahInput = (value) => {
    const digits = String(value || '').replace(/[^\d]/g, '');

    if (!digits) {
        return '';
    }

    return new Intl.NumberFormat('id-ID').format(Number(digits));
};

const formatRupiah = (value) => {
    return `Rp ${new Intl.NumberFormat('id-ID').format(Number(value || 0))}`;
};

const savedTheme = localStorage.getItem(storageKey);
const preferredDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
const initialTheme = savedTheme || (preferredDark ? 'dark' : 'light');

applyTheme(initialTheme);

document.addEventListener('click', (event) => {
    const trigger = event.target.closest('[data-theme-toggle]');

    if (!trigger) {
        return;
    }

    const nextTheme = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
    localStorage.setItem(storageKey, nextTheme);
    applyTheme(nextTheme);
});

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-toast-message]').forEach((toast) => {
        const type = toast.dataset.toastType || 'info';
        const message = toast.dataset.toastText;

        if (message && typeof toastr[type] === 'function') {
            toastr[type](message);
        }
    });

    document.querySelectorAll('[data-datatable]').forEach((table) => {
        if (DataTable.isDataTable(table)) {
            return;
        }

        const disableSorting = table.dataset.disableSorting === 'true';

        new DataTable(table, {
            autoWidth: false,
            order: [],
            ordering: !disableSorting,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']],
            columnDefs: [
                {
                    targets: -1,
                    orderable: false,
                    searchable: false,
                },
            ],
            language: {
                emptyTable: 'Belum ada data nih',
                zeroRecords: 'Data tidak ditemukan',
                search: 'Cari:',
                lengthMenu: 'Show _MENU_',
                info: '_START_-_END_ / _TOTAL_',
                infoEmpty: '0 data',
                paginate: {
                    previous: 'Prev',
                    next: 'Next',
                },
            },
        });
    });

    document.querySelectorAll('[data-money-format]').forEach((input) => {
        input.value = formatRupiahInput(input.value);

        input.addEventListener('input', () => {
            input.value = formatRupiahInput(input.value);
        });
    });

    document.querySelectorAll('[data-gold-price-widget]').forEach((widget) => {
        const url = widget.dataset.url;
        const valueElement = widget.querySelector('[data-gold-price-value]');
        const sourceElement = widget.querySelector('[data-gold-price-source]');

        if (!url || !valueElement) {
            return;
        }

        $.ajax({
            url,
            method: 'GET',
            dataType: 'json',
        })
            .done((response) => {
                if (!response || typeof response.price === 'undefined') {
                    valueElement.textContent = 'Harga tidak tersedia';

                    return;
                }

                valueElement.textContent = `${formatRupiah(response.price)} / ${response.unit || '0.01 gram'}`;

                if (sourceElement && response.source) {
                    sourceElement.textContent = `Sumber: ${response.source}`;
                }
            })
            .fail(() => {
                valueElement.textContent = 'Harga tidak tersedia';
            });
    });
});

document.addEventListener('submit', (event) => {
    const form = event.target;

    if (!(form instanceof HTMLFormElement) || !form.hasAttribute('data-confirm-delete')) {
        return;
    }

    if (form.dataset.confirmed === 'true') {
        return;
    }

    event.preventDefault();

    Swal.fire({
        title: form.dataset.confirmTitle || 'Hapus data?',
        text: form.dataset.confirmText || 'Data yang dihapus tidak bisa dikembalikan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal',
        customClass: {
            popup: 'brutal-swal',
            confirmButton: 'brutal-swal-btn brutal-swal-confirm',
            cancelButton: 'brutal-swal-btn brutal-swal-cancel',
        },
        buttonsStyling: false,
        didOpen: () => {
            applySwalButtonStyle(Swal.getConfirmButton(), 'confirm');
            applySwalButtonStyle(Swal.getCancelButton(), 'cancel');
        },
    }).then((result) => {
        if (!result.isConfirmed) {
            return;
        }

        form.dataset.confirmed = 'true';
        form.requestSubmit();
    });
});
