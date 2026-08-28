document.addEventListener('DOMContentLoaded', function () {

    // (+)
    document.querySelectorAll('.cart_quantity_down, .cart_quantity_up')
        .forEach(button => {

            button.addEventListener('click', function (e) {

                e.preventDefault();

                const tr = this.closest('tr');

                const id = tr.dataset.id;

                const qtyInput = tr.querySelector('.cart_quantity_input');


                const change = this.classList.contains('cart_quantity_down') ? -1 : 1;

                const qty = parseInt(qtyInput.value);
                const oldQty = qty;


                const newQty = qty + change;


                if (newQty < 1) {
                    alert("số lượng sản phẩm k được nhỏ hơn 1")
                    return;
                }
                qtyInput.value = newQty;

                Cart(id, newQty, tr, oldQty);

            });

        });

});


//(x)
document.querySelectorAll('.cart_quantity_delete')
    .forEach(button => {

        button.addEventListener('click', function (e) {

            e.preventDefault();

            const tr = this.closest('tr');

            const id = tr.dataset.id;

            deleteCart(id, tr);

        });

    });

async function Cart(id, qty, tr, oldQty) {
    try {
        const response = await fetch('up_down.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: id, qty: qty })
        });
        const data = await response.json();

        if (!data.success) {
            throw new Error(data.message);
        }

        const price = Number(tr.dataset.price);
        const oldTotal = price * oldQty;
        const newTotal = price * qty;
        const changeTotal = newTotal - oldTotal;

        tr.querySelector('.cart_total_price').textContent =
            newTotal.toLocaleString('vi-VN') + ' đ';
        updateCartSummary(changeTotal, qty - oldQty);
    } catch (error) {
        tr.querySelector('.cart_quantity_input').value = oldQty;
        alert(error.message || 'Không thể cập nhật số lượng');
    }
}

async function deleteCart(id, tr) {
    try {
        const response = await fetch('up_down.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: id, action: 'delete' })
        });
        const data = await response.json();

        if (!data.success) {
            throw new Error(data.message);
        }

        const qty = Number(tr.querySelector('.cart_quantity_input').value);
        const price = Number(tr.dataset.price);

        tr.remove();
        updateCartSummary(-(price * qty), -qty);
    } catch (error) {
        alert(error.message || 'Không thể xóa sản phẩm');
    }
}

function updateCartSummary(changeTotal, changeCount) {
    const subTotal = document.querySelector('#cart_sub_total');
    const grandTotal = document.querySelector('#cart_grand_total');
    const cartCount = document.querySelector('#cart_count');
    const currentTotal = Number(subTotal.textContent.replace(/\D/g, '')) + changeTotal;
    const currentCount = Number(cartCount.textContent) + changeCount;
    const formattedTotal = currentTotal.toLocaleString('vi-VN') + ' đ';

    subTotal.textContent = formattedTotal;
    grandTotal.textContent = formattedTotal;
    cartCount.textContent = Math.max(0, currentCount);
}