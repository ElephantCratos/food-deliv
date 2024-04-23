<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Delivery Catalog</title>
    <style>
       body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

header {
    background-color: #f1c40f;
    padding: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

header h1 {
    margin: 0;
    color: #fff;
}

nav ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
}

nav ul li {
    margin: 0 1rem;
}

nav ul li a {
    color: #fff;
    text-decoration: none;
}

section#menu {
    display: flex;
    flex-wrap: wrap;
    padding: 1rem;
    justify-content: space-between;
}

.item {
    background-color: #fff;
    border: 1px solid #ddd;
    padding: 1rem;
    margin: 1rem 0;
    width: calc(33.33% - 2rem);
    text-align: center;
}

.item img {
    width: 100%;
}

.item h3 {
    margin: 1rem 0;
}

.item p {
    margin: 0;
    color: #777;
}

.item button {
    background-color: #f1c40f;
    border: none;
    color: #fff;
    padding: 0.5rem;
    margin: 1rem 0;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.item button:hover {
    background-color: #e0b90f;
}

section#cart {
    background-color: #f1c40f;
    padding: 1rem;
    color: #fff;
    text-align: center;
}

section#cart ul {
    list-style: none;
    margin: 0;
    padding: 0;
}

section#cart li {
    margin: 1rem 0;
}

section#cart button {
    background-color: #fff;
    border: none;
    color: #f1c40f;
    padding: 0.5rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

section#cart button:hover {
    background-color: #e0b90f;
}

footer {
    background-color: #f1c40f;
    padding: 1rem;
    text-align: center;
}

footer p {
    margin: 0;
    color: #fff;
}
    </style>
</head>
<body>
    <header>
        <h1>Welcome to Our Food Delivery Catalog</h1>
        <nav>
            <ul>
                <li><a href="{{route('profile.edit')}}">Profile</a></li>
                <li><a href="#">Menu</a></li>
                <li><a href="#">Order Now</a></li>
                <li><a href="#">Contact Us</a></li>
            </ul>
        </nav>
    </header>
    
    <section id="menu">
        
        <div class="item">
        <img src="food1.jpg" alt="Food Item 1">
        <h3>Food Item 1</h3>
        <p>Description of Food Item 1</p>
        <p>Price: $10.99</p>
        <label for="toppings">Choose Toppings:</label>
    <div id="toppings">
        <label><input type="checkbox" name="topping" value="cheese"> Cheese</label>
        <label><input type="checkbox" name="topping" value="pepperoni"> Pepperoni</label>
        <label><input type="checkbox" name="topping" value="mushrooms"> Mushrooms</label>
        <label><input type="checkbox" name="topping" value="olives"> Olives</label>
    </div>
    <button>Add to Cart</button>
</div>
        
    </section>

   
    
    <section id="cart">
        <h2>Your Cart</h2>
        <ul>
        </ul>
        <p>Total: $0.00</p>
        <button>Checkout</button>
    </section>
    
    <footer>
        <p>&copy; 2024 Food Delivery Catalog. All rights reserved.</p>
    </footer>
</body>
</html>
