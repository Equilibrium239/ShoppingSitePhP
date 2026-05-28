async function addToCart(productId) { 
    let resp = await fetch(`/javascriptAddToCart?id=${productId}`);
    let data = await resp.json();
    document.getElementById('cartItemCount').innerText = data.cartItemCount;

    // fetch(`/javascriptAddToCart?id=${productId}`)
    // .then(response => response.json())
    // .then(data => {
    //         document.getElementById('cartItemCount').innerText = data.cartItemCount;
    // });
}