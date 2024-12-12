<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Contact Details</h1>
        <div id="contactDetails" class="mt-4"></div>
        <a href="/" class="btn btn-secondary mt-3">Back to Contacts</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function () {
            const contactId = window.location.pathname.split('/').pop();
            fetchContactDetails(contactId);
        });

        function fetchContactDetails(id) {
            $.get(`http://127.0.0.1:8000/api/contacts/${id}`, function (data) {
                const contact = data;
                $("#contactDetails").html(`
                    <h3>${contact.name}</h3>
                    <p><strong>Contact:</strong> ${contact.contact}</p>
                    <p><strong>Email:</strong> ${contact.email}</p>
                    <button class="btn btn-warning" onclick="window.location.href='/edit-contact/${contact.id}'">Edit</button>
                    <button class="btn btn-danger" onclick="deleteContact(${contact.id})">Delete</button>
                `);
            }).fail(function () {
                alert("Failed to fetch contact details.");
            });
        }

        function deleteContact(id) {
            if (confirm('Are you sure you want to delete this contact?')) {
                $.ajax({
                    url: `http://127.0.0.1:8000/api/contacts/${id}`,
                    type: 'DELETE',
                    headers: {
                        Authorization: `Bearer ${authToken}`
                    },
                    success: function () {
                        alert("Contact deleted successfully.");
                        window.location.href = "/";
                    }
                });
            }
        }
    </script>
</body>
</html>
