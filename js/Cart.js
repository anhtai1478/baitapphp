document.addEventListener('DOMContentLoaded', function () {

    // 1. Nút tăng (+) -> gọi increase.php
    document.querySelectorAll('.cart_quantity_up').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const id = this.closest('tr').dataset.id;
            sendCartAction('increase.php', id);
        });
    });

    // 2. Nút giảm (-) -> gọi decrease.php
    document.querySelectorAll('.cart_quantity_down').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const id = this.closest('tr').dataset.id;
            sendCartAction('decrease.php', id);
        });
    });

    // 3. Nút xóa (X) -> gọi delete.php
    document.querySelectorAll('.cart_quantity_delete').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const id = this.closest('tr').dataset.id;
            if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) {
                sendCartAction('delete.php', id);
            }
        });
    });

});

// Hàm dùng chung để gửi API
async function sendCartAction(url, id) {
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: parseInt(id) })
        });

        const data = await response.json();

        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Có lỗi xảy ra!');
        }
    } catch (error) {
        console.error('Lỗi kết nối:', error);
    }
}