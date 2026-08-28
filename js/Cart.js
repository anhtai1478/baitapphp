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

    const qtyInput = tr.querySelector('.cart_quantity_input');

    const response = await fetch('up_down.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            id: id,
            qty: qty
        })
    });

    const data = await response.json();

    if (!data.success) {
        qtyInput.value = oldQty;
        alert(data.message);
        return;
    }

    console.log(data);
    // if (data.success) {
    //     location.reload();
    // }

    if (data.success) {


        const price = parseInt(tr.dataset.price);


        const oldTotal = price * oldQty;


        const newTotal = price * qty;

        // cập nhật total của sản phẩm 
        const productTotal = tr.querySelector('.cart_total_price');
        // productTotal.textContent = newTotal.toLocaleString('vi-VN') + ' đ';

        const changeTotal = newTotal - oldTotal;

        const subTotal = document.querySelector('#cart_sub_total');

        const total = document.querySelector('#cart_grand_total');

        let currentTotal = parseInt(subTotal.textContent.replace(/\D/g, ''));

        currentTotal += changeTotal;

        subTotal.textContent = currentTotal.toLocaleString('vi-VN') + ' đ';

        total.textContent = currentTotal.toLocaleString('vi-VN') + ' đ';

        const cartCount = document.querySelector('#cart_count');
        cartCount.textContent = Number(cartCount.textContent) + (qty - oldQty);
    }
}

async function deleteCart(id, tr) {

    const response = await fetch('up_down.php', {

        method: 'POST',

        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            id: id,
            action: 'delete'
        })

    });

    const data = await response.json();

    console.log(data);

    if (data.success) {
        const quantity = Number(
            tr.querySelector('.cart_quantity_input').value
        );

        const price = Number(tr.dataset.price);
        const productTotal = price * quantity;

        const subTotal = document.querySelector('#cart_sub_total');
        const grandTotal = document.querySelector('#cart_grand_total');
        const cartCount = document.querySelector('#cart_count');

         const currentTotal = Number(subTotal.textContent.replace(/\D/g, ''));
        const newTotal = currentTotal - productTotal;

        subTotal.textContent = newTotal.toLocaleString('vi-VN') + ' đ';

        grandTotal.textContent = newTotal.toLocaleString('vi-VN') + ' đ';

        cartCount.textContent = Number(cartCount.textContent) - quantity;

        tr.remove();
    }

}