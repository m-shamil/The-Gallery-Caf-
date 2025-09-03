document.addEventListener('DOMContentLoaded', function() {
    loadFoodMenu();
    loadUsers();

    document.getElementById('add-food').addEventListener('click', function() {
        addFood();
    });

    document.getElementById('update-food').addEventListener('click', function() {
        updateFood();
    });

    document.getElementById('delete-food').addEventListener('click', function() {
        deleteFood();
    });

    document.getElementById('add-user').addEventListener('click', function() {
        addUser();
    });

    document.getElementById('update-user').addEventListener('click', function() {
        updateUser();
    });

    document.getElementById('delete-user').addEventListener('click', function() {
        deleteUser();
    });
});

function loadFoodMenu() {
    fetch('get_food_menu.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.querySelector('#food-menu-table tbody');
            tableBody.innerHTML = '';
            data.forEach(item => {
                const row = `<tr>
                    <td>${item.id}</td>
                    <td>${item.name}</td>
                    <td>${item.description}</td>
                    <td>${item.price}</td>
                    <td>${item.category}</td>
                </tr>`;
                tableBody.innerHTML += row;
            });
        });
}

function loadUsers() {
    fetch('get_users.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.querySelector('#user-table tbody');
            tableBody.innerHTML = '';
            data.forEach(user => {
                const row = `<tr>
                    <td>${user.id}</td>
                    <td>${user.username}</td>
                    <td>${user.password}</td>
                    <td>${user.email}</td>
                </tr>`;
                tableBody.innerHTML += row;
            });
        });
}

function addFood() {
    const name = document.getElementById('food-name').value;
    const description = document.getElementById('food-description').value;
    const price = document.getElementById('food-price').value;
    const category = document.getElementById('food-category').value;

    fetch('manage_food_menu.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ action: 'add', name, description, price, category }),
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        loadFoodMenu();
    });
}

function updateFood() {
    const id = prompt('Enter ID of the food item to update:');
    const name = document.getElementById('food-name').value;
    const description = document.getElementById('food-description').value;
    const price = document.getElementById('food-price').value;
    const category = document.getElementById('food-category').value;

    fetch('manage_food_menu.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ action: 'update', id, name, description, price, category }),
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        loadFoodMenu();
    });
}

function deleteFood() {
    const id = prompt('Enter ID of the food item to delete:');
    fetch('manage_food_menu.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ action: 'delete', id }),
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        loadFoodMenu();
    });
}

function addUser() {
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const email = document.getElementById('email').value;

    fetch('manage_users.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ action: 'add', username, password, email }),
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        loadUsers();
    });
}

function updateUser() {
    const id = prompt('Enter ID of the user to update:');
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const email = document.getElementById('email').value;

    fetch('manage_users.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ action: 'update', id, username, password, email }),
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        loadUsers();
    });
}

function deleteUser() {
    const id = prompt('Enter ID of the user to delete:');
    fetch('manage_users.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ action: 'delete', id }),
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        loadUsers();
    });
}
