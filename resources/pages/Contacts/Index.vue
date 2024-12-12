<template>
    <div>
        <h1>Contacts</h1>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="contact in contacts" :key="contact.id">
                    <td>{{ contact.name }}</td>
                    <td>{{ contact.contact }}</td>
                    <td>{{ contact.email }}</td>
                    <td>
                        <a :href="`/contacts/${contact.id}`">View</a>
                        <a :href="`/contacts/${contact.id}/edit`">Edit</a>
                        <button @click="deleteContact(contact.id)">
                            Delete
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import { router } from "@inertiajs/vue3";

export default {
    props: {
        contacts: Array,
    },
    methods: {
        deleteContact(id) {
            if (confirm("Are you sure you want to delete this contact?")) {
                router.delete(`/contacts/${id}`);
            }
        },
    },
};
</script>
