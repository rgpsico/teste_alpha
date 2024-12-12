<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Management</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Contact Management</h1>
        <button class="btn btn-primary my-3" onclick="checkAuth(() => $('#contactModal').modal('show'))">Add Contact</button>
        <button class="btn btn-success my-3" onclick="$('#registerModal').modal('show')">Register</button>
        <button class="btn btn-danger my-3" onclick="logout()">Logout</button>

        <!-- Contacts Table -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="contactTableBody">
                <!-- Rows will be added dynamically -->
            </tbody>
        </table>

        <!-- Modal for Adding/Editing Contact -->
        <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="contactModalLabel">Add Contact</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="contactForm">
                            <input type="hidden" id="contactId">
                            <div class="mb-3">
                                <label for="contactName" class="form-label">Name</label>
                                <input type="text" class="form-control" id="contactName" required>
                            </div>
                            <div class="mb-3">
                                <label for="contactPhone" class="form-label">Contact</label>
                                <input type="text" class="form-control" id="contactPhone" required>
                            </div>
                            <div class="mb-3">
                                <label for="contactEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="contactEmail" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="saveContact">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for Register -->
        <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="registerModalLabel">Register</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="registerForm">
                            <div class="mb-3">
                                <label for="registerName" class="form-label">Name</label>
                                <input type="text" class="form-control" id="registerName" required>
                            </div>
                            <div class="mb-3">
                                <label for="registerEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="registerEmail" required>
                            </div>
                            <div class="mb-3">
                                <label for="registerPassword" class="form-label">Password</label>
                                <input type="password" class="form-control" id="registerPassword" required>
                            </div>
                            <div class="mb-3">
                                <label for="registerPasswordConfirmation" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="registerPasswordConfirmation" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="registerButton">Register</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for Login -->
        <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="loginModalLabel">Login</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="loginForm">
                            <div class="mb-3">
                                <label for="loginEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="loginEmail" required>
                            </div>
                            <div class="mb-3">
                                <label for="loginPassword" class="form-label">Password</label>
                                <input type="password" class="form-control" id="loginPassword" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="loginButton">Login</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let contacts = [];
        let authToken = null; // Token de autenticação

        function fetchContacts() {
            $.get("http://127.0.0.1:8000/api/contacts", function (data) {
                contacts = data;
                renderContacts();
            });
        }

        function renderContacts() {
            const tbody = $("#contactTableBody");
            tbody.empty();
            contacts.forEach(contact => {
                tbody.append(`
                    <tr>
                        <td>${contact.name}</td>
                        <td>${contact.contact}</td>
                        <td>${contact.email}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" onclick="checkAuth(() => editContact(${contact.id}))"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger" onclick="checkAuth(() => deleteContact(${contact.id}))"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                `);
            });
        }

        function resetForm() {
            $("#contactId").val('');
            $("#contactName").val('');
            $("#contactPhone").val('');
            $("#contactEmail").val('');
            $("#contactModalLabel").text('Add Contact');
        }

        function editContact(id) {
            if (localStorage.getItem('isAuthenticated') === 'true') {
                const contact = contacts.find(c => c.id === id);
                if (contact) {
                    $("#contactId").val(contact.id);
                    $("#contactName").val(contact.name);
                    $("#contactPhone").val(contact.contact);
                    $("#contactEmail").val(contact.email);
                    $("#contactModalLabel").text('Edit Contact');
                    $("#contactModal").modal('show');
                }
            } else {
                alert("You must be logged in to edit contacts.");
            }
        }

        function deleteContact(id) {
            if (localStorage.getItem('isAuthenticated') === 'true') {
                if (confirm('Are you sure you want to delete this contact?')) {
                    $.ajax({
                        url: `http://127.0.0.1:8000/api/contacts/${id}`,
                        type: 'DELETE',
                        headers: {
                            Authorization: `Bearer ${authToken}`
                        },
                        success: function () {
                            fetchContacts();
                        }
                    });
                }
            } else {
                alert("You must be logged in to delete contacts.");
            }
        }

        function checkAuth(callback) {
            const isAuthenticated = localStorage.getItem('isAuthenticated') === 'true';
            if (!isAuthenticated) {
                $("#loginModal").modal('show');
                $("#loginButton").off('click').on('click', function () {
                    const email = $("#loginEmail").val();
                    const password = $("#loginPassword").val();

                    $.ajax({
                        url: "http://127.0.0.1:8000/api/login",
                        type: "POST",
                        contentType: "application/json",
                        Accept: "application/json",
                        data: JSON.stringify({ email, password }),
                        success: function (response) {
                            authToken = response.token;
                            localStorage.setItem('isAuthenticated', 'true'); // Armazena a autenticação
                            $("#loginModal").modal('hide');
                            callback();
                        },
                        error: function () {
                            alert("Login failed. Please check your credentials.");
                        }
                    });
                });
            } else {
                callback();
            }
        }

        function logout() {
            authToken = null;
            localStorage.removeItem('isAuthenticated');
            alert("You have been logged out.");
        }

        $(document).ready(function () {
            fetchContacts();

            $("#saveContact").click(function () {
                const id = $("#contactId").val();
                const name = $("#contactName").val();
                const phone = $("#contactPhone").val();
                const email = $("#contactEmail").val();

                const contactData = {
                    name: name,
                    contact: phone,
                    email: email
                };

                const request = {
                    url: id ? `http://127.0.0.1:8000/api/contacts/${id}` : "http://127.0.0.1:8000/api/contacts",
                    type: id ? "PUT" : "POST",
                    contentType: "application/json",
                    data: JSON.stringify(contactData),
                    headers: {
                        Authorization: `Bearer ${authToken}`
                    },
                    success: function () {
                        fetchContacts();
                        $("#contactModal").modal('hide');
                        resetForm();
                    },
                    error: function (xhr) {
                        alert("Error saving contact.");
                    }
                };

                $.ajax(request);
            });

            $("#registerButton").click(function () {
                const name = $("#registerName").val();
                const email = $("#registerEmail").val();
                const password = $("#registerPassword").val();
                const passwordConfirmation = $("#registerPasswordConfirmation").val();

                const registerData = {
                    name: name,
                    email: email,
                    password: password,
                    password_confirmation: passwordConfirmation
                };

                $.ajax({
                    url: "http://127.0.0.1:8000/api/register",
                    type: "POST",
                    contentType: "application/json",
                    Accept: "application/json",
                    data: JSON.stringify(registerData),
                    success: function (response) {
                        authToken = response.token;
                        localStorage.setItem('isAuthenticated', 'true'); // Armazena a autenticação
                        $("#registerModal").modal('hide');
                        alert("Registration successful and logged in.");
                    },
                    error: function () {
                        alert("Registration failed. Please check your input.");
                    }
                });
            });
        });
    </script>
</body>
</html>
