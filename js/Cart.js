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

    console.log(data);
    // if (data.success) {
    //     location.reload();
    // }

    if (data.success) {

        
        const price = parseInt(tr.dataset.price);

        
        const oldTotal = price * oldQty;

        
        const newTotal = price * qty;

      
        const changeTotal = newTotal - oldTotal;

        const subTotal = document.querySelector('#cart_sub_total');

        const total = document.querySelector('#cart_total');

        let currentTotal = parseInt(
            subTotal.textContent.replace(/\D/g, '')
        );

        currentTotal += changeTotal;

        subTotal.textContent =
            currentTotal.toLocaleString('vi-VN') + ' đ';

        total.textContent =
            currentTotal.toLocaleString('vi-VN') + ' đ';
    }
}

async function deleteCart(id, tr) {

    const response = await fetch('delete.php', {

        method: 'POST',

        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            id: id
        })

    });

    const data = await response.json();

    console.log(data);

    if (data.success) {
        tr.remove();

    } else {
        alert(data.message)
    }

}