<template>
    <div>
        <li class="list-group-item" :class="className"><slot></slot></li>
        <small class="badge float-right" :class="badgeClass"> <span>{{ user }}</span> <small>{{ time }}</small> </small>
    </div>
</template>

<script>
    export default {
        props: [
            'color',
            'user',
            'time',
            'current'
        ],
        computed: {
            className() {
                if(this.current==this.user){ 
                    return 'list-group-item-success' 
                } 
                else{ 
                    return 'list-group-item-warning' 
                }
            },
            badgeClass() {
                return 'badge-'+this.color
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
