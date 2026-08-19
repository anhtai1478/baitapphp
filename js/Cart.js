function Cart() {
    const response = await fetch('up_down.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            id: id
        })
    });

}

// const response = await fetch('ajax_hanlde_cart.php', { //k có chi het, k có html
//     method: 'POST',
//     headers: {
//       'Content-Type': 'application/json', // Gửi dạng JSON
//     },
//     body: JSON.stringify({ id })
//   });