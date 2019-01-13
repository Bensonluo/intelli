<template>
    <div>
    <h3>Members List</h3>

        <div class="navbar navbar-light bg-light">
            <form class="form-inline">
                <input class="form-control mr-sm-2" v-model="inputfirstname" placeholder="firstname" aria-label="Search">
                <input class="form-control mr-sm-2" v-model="inputsurname" placeholder="surname" aria-label="Search">
                <input class="form-control mr-sm-2" v-model="inputemail" placeholder="email" aria-label="Search">
                <a class="btn btn-outline-success my-2 my-sm-0"
                        @click="searchMembers(inputfirstname, inputsurname, inputemail)">Search</a>
            </form>
        </div>

        <table class="table">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Firstname</th>
                <th scope="col">Surname</th>
                <th scope="col">Email</th>
                <th scope="col">Gender</th>
                <th scope="col">Joined date</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="member in members" v-bind:key="member.id">
                <th scope="row">{{member.id}}</th>
                <td>{{member.firstname}}</td>
                <td>{{member.surname}}</td>
                <td>{{member.email}}</td>
                <td>{{member.gender}}</td>
                <td>{{member.joined_date}}</td>
            </tr>
            </tbody>
        </table>

        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item"><a class="page-link" href="#"
                       @click="fetchMembers(pagination.first_page_url)">First</a></li>
                <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item">
                    <a class="page-link" href="#"
                       @click="fetchMembers(pagination.prev_page_url)">Previous</a></li>

                <li class="page-item disabled"><a class="page-link text-dark" href="#">
                    Page {{pagination.current_page}} of {{pagination.last_page}}</a></li>

                <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item">
                    <a class="page-link" href="#"
                       @click="fetchMembers(pagination.next_page_url)">Next</a></li>
                <li class="page-item"><a class="page-link" href="#"
                        @click="fetchMembers(pagination.last_page_url)">Last</a></li>
            </ul>
        </nav>

    </div>
</template>

<script>
    export default {
        data() {
            return {
                members: [],
                member:{
                    id:'',
                    firstname:'',
                    surname:'',
                    email:'',
                    gender:'',
                    joined_date:''
                },
                member_id:'',
                pagination: {},
                edit:false,
                inputfirstname:'',
                inputsurname:'',
                inputemail:''
            }
        },

        created() {
            this.fetchMembers();
        },

        methods: {
            fetchMembers(page_url) {
                let vm = this;
                page_url = page_url || '/api/members';
                fetch(page_url)
                    .then(res => res.json())
                    .then(res => {
                        console.log(res.data);
                        this.members = res.data;
                        vm.makePagination(res);
                    })
                    .catch(err => console.log(err));
            },

            makePagination(data) {
                let pagination = {
                    current_page: data.current_page,
                    last_page: data.last_page,
                    first_page_url: data.first_page_url,
                    last_page_url: data.last_page_url,
                    next_page_url: data.next_page_url,
                    prev_page_url: data.prev_page_url
                };
                console.log(pagination);
                this.pagination = pagination;
            },

            searchMembers(inputfirstname, inputsurname, inputemail) {
                let args = {
                    "firstname": inputfirstname,
                    "surname": inputsurname,
                    "email": inputemail
                };
                let search_url = '/api/searchMembers';
                fetch(search_url, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(args),
                    })
                    .then(response => response.json())
                    .then(response => {
                        console.log(response);
                        this.members = response;
                    })
                    .catch(err => console.log(err));
            }
        }
    }
</script>

<style scoped>

</style>
