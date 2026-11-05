/**
 * HIKETRIP RENTAL - Interactive Client Logic & Realtime Calculation
 */

document.addEventListener('DOMContentLoaded', function () {
    // Inisialisasi Tooltip Bootstrap jika tersedia
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    // Auto calculate pada form booking
    initBookingCalculator();

    // Validasi form konfirmasi delete / reject
    initActionConfirmations();

    // Toggle alamat pengiriman kurir
    initDeliveryToggle();
});

/**
 * Kalkulator Real-time Booking
 */
function initBookingCalculator() {
    const startDateInput = document.getElementById('tgl_sewa');
    const endDateInput = document.getElementById('tgl_kembali');
    const qtyInput = document.getElementById('jumlah_unit');
    const pricePerDayEl = document.getElementById('harga_per_hari_raw');
    const deliveryMethodSelect = document.getElementById('metode_pengambilan');
    const deliveryAddressWrapper = document.getElementById('delivery_address_wrapper');
    const deliveryAddressInput = document.getElementById('alamat_pengiriman');

    // Elemen Tampilan Hasil
    const durationDisplay = document.getElementById('calc_duration');
    const subtotalDisplay = document.getElementById('calc_subtotal');
    const deliveryFeeDisplay = document.getElementById('calc_delivery_fee');
    const totalDisplay = document.getElementById('calc_total');

    if (!startDateInput || !endDateInput) {
        return;
    }

    // Set tanggal minimum sewa adalah hari ini
    const today = new Date().toISOString().split('T')[0];
    if (startDateInput.getAttribute('min') === null) {
        startDateInput.setAttribute('min', today);
    }

    function calculate() {
        const startDateVal = startDateInput.value;
        const endDateVal = endDateInput.value;
        const qtyVal = parseInt(qtyInput ? qtyInput.value : 1, 10) || 1;
        const pricePerDay = parseFloat(pricePerDayEl ? pricePerDayEl.value : 0) || 0;
        const flatDeliveryFee = parseFloat(document.getElementById('flat_delivery_fee')?.value || 25000);

        if (!startDateVal || !endDateVal) {
            return;
        }

        const start = new Date(startDateVal);
        const end = new Date(endDateVal);

        // Validasi: tanggal kembali harus setelah tanggal mulai
        if (end <= start) {
            if (durationDisplay) durationDisplay.textContent = '0 Hari (Tanggal Tidak Valid)';
            if (totalDisplay) totalDisplay.textContent = 'Rp 0';
            return;
        }

        // Hitung selisih hari
        const diffTime = Math.abs(end - start);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        const totalDays = Math.max(1, diffDays);

        // Biaya subtotal
        const subtotal = totalDays * pricePerDay * qtyVal;

        // Biaya pengiriman
        let deliveryFee = 0;
        if (deliveryMethodSelect && deliveryMethodSelect.value === 'Kirim via Kurir') {
            deliveryFee = flatDeliveryFee;
        }

        const grandTotal = subtotal + deliveryFee;

        // Tampilkan ke UI
        if (durationDisplay) durationDisplay.textContent = totalDays + ' Hari (' + totalDays + ' Malam)';
        if (subtotalDisplay) subtotalDisplay.textContent = formatRupiahJs(subtotal);
        if (deliveryFeeDisplay) deliveryFeeDisplay.textContent = formatRupiahJs(deliveryFee);
        if (totalDisplay) totalDisplay.textContent = formatRupiahJs(grandTotal);
    }

    // Event listener
    startDateInput.addEventListener('change', function () {
        if (startDateInput.value) {
            // Set tanggal kembali minimal adalah 1 hari setelah tanggal sewa
            const nextDay = new Date(startDateInput.value);
            nextDay.setDate(nextDay.getDate() + 1);
            const nextDayStr = nextDay.toISOString().split('T')[0];
            endDateInput.setAttribute('min', nextDayStr);
            if (!endDateInput.value || endDateInput.value <= startDateInput.value) {
                endDateInput.value = nextDayStr;
            }
        }
        calculate();
    });

    endDateInput.addEventListener('change', calculate);
    if (qtyInput) qtyInput.addEventListener('input', calculate);
    if (deliveryMethodSelect) deliveryMethodSelect.addEventListener('change', calculate);

    // Initial calculation on load
    calculate();
}

/**
 * Toggle field alamat pengiriman
 */
function initDeliveryToggle() {
    const deliveryMethodSelect = document.getElementById('metode_pengambilan');
    const deliveryAddressWrapper = document.getElementById('delivery_address_wrapper');
    const deliveryAddressInput = document.getElementById('alamat_pengiriman');

    if (!deliveryMethodSelect || !deliveryAddressWrapper) return;

    function toggleAddress() {
        if (deliveryMethodSelect.value === 'Kirim via Kurir') {
            deliveryAddressWrapper.style.display = 'block';
            if (deliveryAddressInput) deliveryAddressInput.setAttribute('required', 'required');
        } else {
            deliveryAddressWrapper.style.display = 'none';
            if (deliveryAddressInput) deliveryAddressInput.removeAttribute('required');
        }
    }

    deliveryMethodSelect.addEventListener('change', toggleAddress);
    toggleAddress();
}

/**
 * Konfirmasi Aksi Sensitif (Hapus Data, Tolak Booking)
 */
function initActionConfirmations() {
    document.querySelectorAll('.btn-confirm-delete').forEach(function (button) {
        button.addEventListener('click', function (e) {
            const message = this.getAttribute('data-message') || 'Apakah Anda yakin ingin menghapus data ini?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });

    document.querySelectorAll('.btn-confirm-action').forEach(function (button) {
        button.addEventListener('click', function (e) {
            const message = this.getAttribute('data-message') || 'Konfirmasi tindakan ini?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
}

/**
 * Helper format rupiah di JS
 */
function formatRupiahJs(number) {
    return 'Rp ' + Math.round(number).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

