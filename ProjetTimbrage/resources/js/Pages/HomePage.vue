<template>
    <app-layout>
        <div class="container mt-5">
            <div class="columns is-2 ">
                <div class="column is-half is-justify-content-space-around is-align-items-center">
                    <div class="box">
                        <div class="columns is-centered">
                            <div class="column is-half">
                                <figure @click="clockIn" class="image is-64x64" style="margin:auto" title="Pointer">
                                    <div class="iconHomePage">
                                        <img class="block" src="/Icons/ClockIn.png" alt="Clock In">
                                    </div>
                                </figure>
                            </div>
                            <div class="column is-half">
                                <a href="/clockings">
                                    <figure class="image is-64x64" style="margin:auto" title="Pointages">
                                        <div class="iconHomePage">
                                            <img class="block" src="/Icons/Clockings.png" alt="Clockings" >
                                        </div>
                                    </figure>
                                </a>
                            </div>
                        </div>
                        <div class="columns is-justify-content-space-around is-align-items-center">
                            <div class="column is-half">
                                <a href="/projects">
                                    <figure class="image is-64x64" style="margin:auto" title="Projets">
                                        <div class="iconHomePage">
                                            <img src="/Icons/Projects.png" alt="Projects">
                                        </div>
                                    </figure>
                                </a>
                            </div>
                            <div class="column is-half">
                                <a href="/assignments">
                                    <figure class="image is-64x64" style="margin:auto">
                                        <div class="iconHomePage">
                                            <img src="/Icons/Assignments.png" alt="Assignments" title="Assignations">
                                        </div>
                                    </figure>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column is-half">
                    <div class="box">
                    <current-clockings :date="this.getCurrentDate()" :key="updateCurrentClockings"></current-clockings>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import CurrentClockings from '@/Components/CurrentClockings'

    export default {
        components: {
            AppLayout,
            CurrentClockings
        },

        data() {
            return {
            updateCurrentClockings : false
        }
    },

        methods : {
            clockIn(){
                axios.post('/clockings', {
                timeStamp : this.getCurrentTimeStamp()
                })
                .then(response => {
                    if (response.status === 200) {
                        this.updateCurrentClockings ^= true; // toggle
                    } 
                })
                .catch(error => {
                this.$toasted.show(error.response.data,{duration:3000, icon: 'fa-exclamation-triangle',type:'error'});
                });
            },
            getCurrentTimeStamp() {
                let date = new Date
                let hours = date.getHours() < 10 ? '0' + date.getHours() : date.getHours()
                let minutes = date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes()
                let seconds = date.getSeconds() < 10 ? '0' + date.getSeconds() : date.getSeconds()
                return this.getCurrentDate() + ' ' + hours + ':' + minutes + ':' + seconds
            },
            getCurrentDate() {
                let date = new Date
                let day = date.getDate() < 10 ? '0' + date.getDate() : date.getDate()
                let month = (date.getMonth() + 1) < 10 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1 // Jan -> 0
                let year = date.getFullYear() < 10 ? '0' + date.getFullYear() : date.getFullYear()
                return year + '-' + month + '-' + day
            }
        }


    }
</script>
