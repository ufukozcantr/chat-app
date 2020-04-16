<template>
    <div class="card card-default chat-box">
        <div class="card-header">
            <b :class="{'text-danger':user.session.block}">
                {{user.name}} <small v-if="isTyping">is typing . . .</small>
                <span v-if="user.session.block">(Blocked)</span>
            </b>
            <a href="" @click.prevent="close">
                <i class="fa fa-times float-right"></i>
            </a>

            <div class="dropdown float-right mr-4">
                <a href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-ellipsis-v text-right"></i>
                </a>

                <div class="dropdown-menu" aria-labelledby="dropDownMenuButton">
                    <a class="dropdown-item" href="#" @click.prevent="unblock" v-if="user.session.block && can">UnBlock</a>
                    <a class="dropdown-item" href="#" @click.prevent="block" v-if="!user.session.block">Block</a>

                    <a class="dropdown-item" href="#" @click.prevent="clear">Clear Chat</a>
                </div>
            </div>
        </div>
        <div class="card-body" v-chat-scroll>
            <p class="card-text" :class="{'text-right': message.type == 0, 'text-success': message.read_at != null}" v-for="message in messages">
                {{message.message}}
                <br>
                <small>
                    <i class="fa fa-check" :class="{'text-right': message.type == 0, 'text-success': message.read_at != null}"></i>
                    <i class="fa fa-check" :class="{'text-right': message.type == 0, 'text-success': message.read_at != null}"></i>
                </small>
            </p>
        </div>
        <form class="card-footer" @submit.prevent="send">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Write message" :disabled="user.session.block"  v-model="message"/>
                <div class="input-group-append">
                    <button class="btn btn-success" type="submit">Send</button>
                </div>
            </div>
<!--            <div class="form-group">-->
<!--                <input type="text" class="form-control" placeholder="Write message" :disabled="user.session.block" v-model="message">-->
<!--            </div>-->
        </form>
    </div>
</template>

<script>
    export default {
        name: "MessageComponent",
        data() {
            return {
                messages: [],
                message: null,
                isTyping: false
            }
        },
        props: ['user', 'authUser'],
        computed: {
            can() {
                return this.user.session.blocked_by == this.authUser.id
            }
        },
        watch: {
            message(value) {
                if(value){
                    Echo.private(`chat.${this.user.session.id}`).whisper('typing', {
                        name: this.authUser.name
                    })
                }
            }
        },
        methods: {
            send(){
                if(this.message){
                    this.pushToChat(this.message)
                    axios.post(`send/${this.user.session.id}`, {
                        content: this.message,
                        toUser: this.user.id
                    }).then(res => {
                        this.messages[this.messages.length - 1].id = res.data
                    })
                    this.message = null
                }
            },
            pushToChat(){
                this.messages.push({message: this.message, read_at: null, type: 0, send_at: 'Now'})
            },
            close(){
                this.$emit('close')
            },
            clear(){
                this.messages = []
                axios.post(`session/${this.user.session.id}/clear`).then(res => {})
            },
            block(){
                this.user.session.block = true
                axios.post(`session/${this.user.session.id}/block`).then(res => {
                    this.user.session.blocked_by = this.authUser.id
                })
            },
            unblock(){
                this.user.session.block = false
                axios.post(`session/${this.user.session.id}/unblock`).then(res => {
                    this.user.session.blocked_by = null
                })
            },
            getAllMessages(){
                axios.get(`session/${this.user.session.id}/chats`).then(res => {
                    this.messages = res.data.data
                })
            },
            read(){
                axios.post(`session/${this.user.session.id}/read`)
            }
        },
        created() {
            this.read()
            this.getAllMessages()

            Echo.private(`chat.${this.user.session.id}`).listen('PrivateChatEvent', e => {
                this.user.session.open ? this.read() : ""
                this.messages.push({message: e.content, type: 1, send_at: 'Now'})
            })

            Echo.private(`chat.${this.user.session.id}`).listen('MessageReadEvent', e => {
                this.messages.forEach(message => {
                    if(message.id == e.chat.id)
                        message.read_at = e.chat.read_at
                })
            })

            Echo.private(`chat.${this.user.session.id}`).listen('BlockEvent', e => {
                this.user.session.block = e.blocked
            })

            Echo.private(`chat.${this.user.session.id}`).listenForWhisper('typing', (e) => {
                this.isTyping = true
                setTimeout(() => {
                    this.isTyping = false
                }, 2000)
            });
        }
    }
</script>

<style scoped>
    .chat-box{
        height: 600px;
    }

    .card-body{
        overflow-y: scroll
    }
</style>
