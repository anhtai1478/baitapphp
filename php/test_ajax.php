!-- 
- click mua product mà k reload trang => dùng js(xu ly tren man hinh)

- qua trang cart.php ,lấy thông tin trong SS ra va hien thi ra table


click mua hang bằng js(Fe) <=  ajax(js, trao đổi data giữa frontend va backend)  => luu thông SS php(be) 

(ajax: chay ngầm)


thứ tự làm:
- click mua hàng thi lây ID product ra (dung js)
- co ID dùng ajax gui ID qua php (chay ngầm)
- qua php goi ID ra, viet sql lấy thông tin của product này theo ID (tra ve 1 mang)=> SS



$mangcon['qty'] = 1;

$_ss['CART'][] = $mangcon


[
	0:[
		price:..
		img:...
		title:...
		qty:2
		id:1
	],
	1:[
		price:..
		img:...
		title:...
		qty:2,
		id:1
	]
]

 -->
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>
	<!-- <form id="userForm">
	  <input type="text" id="name" placeholder="Nhập tên" required>
	  <input type="email" id="email" placeholder="Nhập email" required> -->
	  <button type="submit" id="test">click</button>
	<!-- </form> -->

	<!-- <div id="result"></div> -->

	<!-- <a id="test">add to cart</a> -->

<script>
	
document.querySelector('#test').addEventListener('click', async function(e) {
  e.preventDefault(); // Ngăn trang reload nếu có

  // Lấy dữ liệu từ form
  // const name = document.querySelector('#name').value;


  let id = 123 //vdu lay dc id

  // Gửi AJAX (fetch) (giong form)
  const response = await fetch('ajax_hanlde_cart.php', { //k có chi het, k có html
    method: 'POST',
    headers: {
      'Content-Type': 'application/json', // Gửi dạng JSON
    },
    body: JSON.stringify({ id })
  });

  // Nhận phản hồi JSON từ PHP
  const data = await response.json(); 
 
  console.log(data)

  // hiên thị thông báo lên thẻ html
  // document.querySelector('#result').textContent = data.message;
});
</script>

</body>
</html>
