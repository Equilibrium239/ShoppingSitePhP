 
<style>
    
body {
    font-family: 'Inter', sans-serif;
    margin: 0;
    color: #333;
}

/
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 5%;
    background: #fff;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05); 
}


.nav-link {
    text-decoration: none;
    color: #444;
    font-weight: 500;
    transition: color 0.3s;
}

.nav-link:hover {
    color: #007bff;
}


.cart-wrapper {
    text-decoration: none;
    color: #333;
    position: relative;
    font-size: 1.2rem;
    display: inline-flex;
    align-items: center;
}

.cart-counter {
    position: absolute;
    top: -10px;
    right: -10px;
    background: #ff4757;
    color: white;
    font-size: 0.7rem;
    padding: 2px 6px;
    border-radius: 50%;
    font-weight: bold;
    line-height: 1;
}


.Btn {
    background-color: #222;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    transition: 0.3s ease;
}

.Btn:hover {
    background-color: #007bff;
    transform: translateY(-2px);
}


.nav-menu {
    list-style: none;
    display: flex;
    align-items: center;
    gap: 20px;
    margin: 0;
    padding: 0;
}
</style>
 <header class="main-header">
        <nav class="navbar">
            <ul class="nav-menu">
                <li><a href="/index.php" class="nav-link">Hem</a></li>
                <li>
                    <a href="Cart.php" class="cart-wrapper">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span class="cart counter">0</span>
                    </a>
                </li>
                <li>
                    <a href="Medlemskap.php">
                        <button class="Btn">Medlem</button>
                    </a>
                </li>
            </ul>
        </nav>
    </header>