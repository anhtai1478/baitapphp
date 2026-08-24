    document.addEventListener('DOMContentLoaded', function () {

        // (+)
        document.querySelectorAll('.cart_quantity_up')
            .forEach(button => {

                button.addEventListener('click', function (e) {

                    e.preventDefault();

                    const tr = this.closest('tr');

                    const id = tr.dataset.id;

                    const qtyInput = tr.querySelector('.cart_quantity_input');

                    const qty = parseInt(qtyInput.value);

                    const newQty = qty + 1;

                    qtyInput.value = newQty;

                    Cart(id, newQty);

                });

            });

    });

    // (-) 
    document.querySelectorAll('.cart_quantity_down')
        .forEach(button => {

            button.addEventListener('click', function (e) {

                e.preventDefault();

                const tr = this.closest('tr');

                const id = tr.dataset.id;

                const qtyInput = tr.querySelector('.cart_quantity_input');

                const qty = parseInt(qtyInput.value);

                if (qty <= 1) {
                    alert('Số lượng sản phẩm không thể nhỏ hơn 1');
                    return;
                }

                const newQty = qty - 1;

                qtyInput.value = newQty;

                Cart(id, newQty);

            });

        })


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

    async function Cart(id, qty) {

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
        if (data.success) {
            location.reload();
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