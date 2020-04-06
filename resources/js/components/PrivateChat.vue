<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-3">
                <div class="card card-default">
                    <div class="card-header">
                        Users
                    </div>
                    <ul class="list-group">
                        <li class="list-group-item" v-for="user in users" :key="user.id">
                            <a href="" @click.prevent="openChat(user)">
                                {{user.name}}
                                <span class="text-danger" v-if="user.session && (user.session.unreadCount > 0)">{{ user.session.unreadCount }}</span>
                            </a>
                            <i class="fa fa-circle float-right text-success" v-if="user.online"></i>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-9">
                <span v-for="user in users" :key="user.id" v-if="user.session">
                    <message-component
                        v-if="user.session.open"
                        @close="close(user)"
                        :user="user"
                    ></message-component>
                </span>
            </div>
        </div>
    </div>
</template>

<script>
    import MessageComponent from "./MessageComponent";
    export default {
        name: "PrivateChat",
        components: {MessageComponent},
        data() {
            return {
                users: []
            }
        },
        methods: {
            close(user){
                user.session.open = false
                // user.session.unreadCount = 0
            },
            getUsers(){
                axios.get('/users').then(res => {
                    this.users = res.data.data
                    this.users.forEach(user => {
                        if(user.session) {
                            this.listenForEverySession(user)
                        }
                    })
                })
            },
            openChat(user){
                this.users.forEach(item => {
                    if(item.session)
                        item.session.open = false
                })
                if(user.session){
                    user.session.open = true
                    user.session.unreadCount = 0
                }else{
                    this.createSession(user)
                }
            },
            createSession(user){
                axios.post('session/create', {user_id:user.id}).then(res => {
                    user.session = res.data.data
                    user.session.open = true
                })
            },
            listenForEverySession(user) {
                Echo.private(`chat.${user.session.id}`).listen('PrivateChatEvent', e => {
                    user.session.open ? "" : user.session.unreadCount++
                })
            }
        },
        created(){
            this.getUsers()

            Echo.channel('chat').listen('SessionEvent', e => {
                let user = this.users.find(user => user.id == e.session_by)
                user.session = e.session
                this.listenForEverySession(user)
            })

            Echo.private('chat')
                .listen('ChatEvent', (e) => {
                    console.log(e, "e")
                })
                .listenForWhisper('typing', (e) => {
                    console.log(e, "e2")
                })

            Echo.join('chat')
                .here((e) => {
                    this.users.forEach(user => {
                        e.forEach(u => {
                            if(user.id == u.id){
                                user.online = true
                            }
                        })
                    })
                })
                .joining((e) => {
                    this.users.forEach(u => u.id == e.id ? u.online = true : false)
                })
                .leaving((e) => {
                    this.users.forEach(u => u.id == e.id ? u.online = false : true)
                });
        }
    }
</script>

<style scoped>

</style>
