<?php
session_start();

// Initialize the inventory if it doesn't exist
if (!isset($_SESSION['inventory'])) {
    $_SESSION['inventory'] = [];
}

$inventory = $_SESSION['inventory'];
$search_result = "";

// Handle the addition of new items
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $item_name = trim($_POST['item_name']);
    $quantity = intval($_POST['quantity']);
    
    // Validate item name and quantity
    if ($item_name === '' || $quantity <= 0) {
        $error_message = "Error: Item name cannot be empty and quantity must be positive.";
    } elseif (isset($inventory[$item_name])) {
        $error_message = "Error: Item already exists in the inventory.";
    } else {
        // Add the new item to the inventory
        $_SESSION['inventory'][$item_name] = $quantity;
        $success_message = "Item added successfully!";
    }
}

// Handle the search functionality
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'search') {
    $search_name = trim($_POST['search_name']);
    
    if (isset($inventory[$search_name])) {
        $search_result = "Item Found: " . htmlspecialchars($search_name) . " - Quantity: " . htmlspecialchars($inventory[$search_name]);
    } else {
        $search_result = "Product not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Inventory Dashboard</h1>

    <!-- Display any messages -->
    <?php if (!empty($error_message)): ?>
        <p style="color:red;"><?= $error_message ?></p>
    <?php elseif (!empty($success_message)): ?>
        <p style="color:green;"><?= $success_message ?></p>
    <?php endif; ?>

    <!-- Display Inventory Table -->
    <h2>Current Inventory</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($inventory as $item => $quantity) : ?>
                <tr>
                    <td><?= htmlspecialchars($item) ?></td>
                    <td><?= htmlspecialchars($quantity) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Form to Add New Item -->
    <h2>Add a New Item</h2>
    <form action="index.php" method="POST">
        <input type="hidden" name="action" value="add">
        <label for="item_name">Item Name:</label>
        <input type="text" id="item_name" name="item_name" required>
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" min="1" required>
        <button type="submit">Add Item</button>
    </form>

    <!-- Form to Search for an Item -->
    <h2>Search for an Item</h2>
    <form action="index.php" method="POST">
        <input type="hidden" name="action" value="search">
        <label for="search_name">Item Name:</label>
        <input type="text" id="search_name" name="search_name" required>
        <button type="submit">Search</button>
    </form>

    <!-- Display Search Results -->
    <?php if (!empty($search_result)): ?>
        <h3>Search Result:</h3>
        <p><?= $search_result ?></p>
    <?php endif; ?>
</body>
</html>
