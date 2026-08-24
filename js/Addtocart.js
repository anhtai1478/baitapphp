function Addtocart(id) {
    const formData = new FormData();
    formData.append("id", id);

    fetch("add_to_cart.php", {
        method: "POST",
        body: formData
    })

        .then(response => response.json())
        .then(data => {
            console.log("giỏ hàng hiện tại", data);
            document.querySelector("#cart_count").textContent = data.cart_count;
        })
}