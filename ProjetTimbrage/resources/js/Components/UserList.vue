<template>
    <div class="panel">
        <p class="panel-heading">
            <span>{{title}}</span> <span v-if="editable" @click="addMember()" class="icon has-text-black ml-2"><em class="fas fa-plus-circle"></em></span>
        </p>
        <div class="panel-block">
        <p class="control has-icons-left">
            <input class="input" type="text" v-model="searchQ" placeholder="Search">
            <span class="icon is-left">
            <i class="fas fa-search" aria-hidden="true"></i>
            </span>
        </p>
        </div>
        <div v-if="content.length > 0" style="  max-height: 12em; overflow: auto;">
            <div v-for="member in getFilteredMembers" v-bind:key="member.id" class="panel-block is-align-items-center">
                <div class="media mb-0" @click="selectMember(member.id)">
                    <figure class="media-left">
                        <p class="image is-48x48">
                        <img class="is-rounded" :src="member.profile_photo_url" alt="Profile photo member">
                        </p>
                    </figure>
                </div>
                <div class="media-content" @click="selectMember(member)">
                    <div class="content">
                        <p>
                        <strong> {{ member.firstName }} {{member.lastName}}</strong>
                        <br>
                        <a href="#"> {{ member.email }} </a>
                        </p>
                    </div>
                </div>
                <div v-if="editable" class="media-right" @click="deleteMember(member)">
                    <span class="icon has-text-danger-dark">
                        <em class="fas fa-trash-alt"></em>
                    </span>
                </div>
            </div>
        </div>
        <div v-if="content.length == 0" class="notification is-info is-light">
            <p>Il n'y a aucun employé à afficher</p>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            title:  {
                type : String,
                default : 'Title',
            },

            content: {
                type : Array,
            },

            editable: {
                type : Boolean,
                default : false
            }
        },

        data(){
            return {
                searchQ :''
            }
        },

        methods: {
            deleteMember(id){
                this.$emit('member-deleted', id);
            },

            selectMember(id){
                this.$emit('member-selected', id);
            },

            addMember(){
                this.$emit('member-add');
            }
        },

        computed: {
            getFilteredMembers(){
                return this.content.filter(member => {
                    return  member.firstName.toLowerCase().includes(this.searchQ.toLowerCase()) ||
                            member.lastName.toLowerCase().includes(this.searchQ.toLowerCase()) ||
                            member.email.toLowerCase().includes(this.searchQ.toLowerCase());
               });
            }
        }
    }
</script>

<style>

</style>
