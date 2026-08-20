document.addEventListener('DOMContentLoaded', function () {

    // 1. Nút tăng số lượng (+)
    document.querySelectorAll('.cart_quantity_up').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const tr = this.closest('tr');
            const id = tr.dataset.id;
            const qtyInput = tr.querySelector('.cart_quantity_input');
            const newQty = parseInt(qtyInput.value) + 1;

            qtyInput.value = newQty;
            updateCart(id, newQty);
        });
    });

    // 2. Nút giảm số lượng (-)
    document.querySelectorAll('.cart_quantity_down').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const tr = this.closest('tr');
            const id = tr.dataset.id;
            const qtyInput = tr.querySelector('.cart_quantity_input');
            const currentQty = parseInt(qtyInput.value);

            if (currentQty > 1) {
                const newQty = currentQty - 1;
                qtyInput.value = newQty;
                updateCart(id, newQty);
            } else {
                updateCart(id, 0);
            }
        });
    });

    // 3. Nút xóa sản phẩm (X)
    document.querySelectorAll('.cart_quantity_delete').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const tr = this.closest('tr');
            const id = tr.dataset.id;

            if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) {
                updateCart(id, 0);
            }
        });
    });

});

// Hàm gửi API Fetch (Có từ khóa async chuẩn)
async function updateCart(id, qty) {
    try {
        const response = await fetch('up_down.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: parseInt(id),
                qty: parseInt(qty)
            })
        });

        const data = await response.json();
        console.log('Response từ server:', data);

        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Có lỗi xảy ra');
        }
    } catch (error) {
        console.error('Lỗi kết nối API:', error);
    }
}