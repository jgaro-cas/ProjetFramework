<template>
  <app-layout>
    <div>
      <div class="container">
        <div class="columns is-multiline">
          <div v-for="project in this.projectsList" v-bind:key="project.object" class="column is-4">
              <inertia-link :href="'projects/' + project.id" :active=false>
                <div class="card">
                  <div class="card-header has-background-primary-dark">
                    <p class="has-text-white has-text-weight-bold is-size-3 pl-2">{{ project.name }}</p>
                  </div>
                  <div class="card-content">
                    <p class="tag is-primary-dark has-text-weight-bold is-medium mb-2">{{ project.number }}</p>
                    <p><span class="icon has-text-primary-dark"><em class="fas fa-clock"></em></span> {{ project.totalHours}}</p>
                  </div>
                </div>
              </inertia-link>
          </div>
          <div v-if="canEdit" class="column is-4">
            <div class="card">
              <div class="card-header has-background-primary-dark">
                <p class="has-text-white has-text-weight-bold is-size-3 pl-2">New Project</p>
              </div>
              <div class="card-content" style="margin: 0 auto">
                <figure @click="updateShowModal(true)" class="image is-64x64" style="margin: 0 auto">
                  <img src="/Icons/Add.png" alt="edit">
                </figure>
              </div>
            </div>
          </div>
        </div>
      </div>
      <project-modal  :title="'New project'"
                      :name="''"
                      :number="''"
                      :show="this.showModal"
                      @closeRequest="updateShowModal(false)"
                      @newProjValues="addNew($event)">

      </project-modal>
    </div>
    </app-layout>
</template>


<script>
  import JetNavLink from '@/Jetstream/NavLink'
  import ProjectModal from './ProjectModal.vue'
  import AppLayout from '@/Layouts/AppLayout'

  export default {
    props: ['projects',
            'hasAuth',
],

    components:{
      AppLayout,
      JetNavLink,
      ProjectModal
    },

    data() {
      return {
        projectsList : this.projects,
        showModal : false,
        canEdit : this.hasAuth,

      }
    },

    methods: {
      updateShowModal(newValue){
        this.showModal = newValue;
      },

      addNew(data){
        axios.post('/projects', {
        name : data.name, number : data.number
        })
        .then(response => {
              if (response.status === 200) {
                window.location.href = '/projects';
              }
            this.updateShowModal(false);
        })
        .catch(error => {
          this.$toasted.show(error.response.data,{duration:3000, icon: 'fa-exclamation-triangle',type:'error'});
          this.updateShowModal(false);
          });
      },
    }
  }

</script>
