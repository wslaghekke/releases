<template>
  <div id="app">
    <div id="aui-message-bar"></div>
    <div class="aui-page-panel">
      <div class="aui-page-panel-inner">
        <section class="aui-page-panel-content contains-header">
          <header class="aui-page-header aui-page-header-fixed">
            <div class="aui-page-header-inner">
              <h1>Releases</h1>
            </div>
          </header>
          <div class="field-group">
            <label>
              Select project:&nbsp;
              <aui-select2-single placeholder="Select project" :value="selected ? selected.key : undefined"
                                  @input="updateSelectedProject">
                <aui-select2-option v-for="project in projects" :key="project.key" :value="project.key">{{ project.name
                  }}
                </aui-select2-option>
              </aui-select2-single>
            </label>
            <label>Recent projects:</label>
            <button v-for="project in recentProjects" class="aui-button aui-button-link"
                    v-on:click="updateSelectedProject(project.key)">{{ project.name }}
            </button>
          </div>
          <span v-if="selectedProjectChangePending" class="aui-icon aui-icon-wait">Loading...</span>
          <br>
          <version-list></version-list>
        </section>
      </div>
    </div>
  </div>
</template>

<script>
  /* global AJS */
  import { mapGetters } from 'vuex';
  import VersionList from './VersionList';

  export default {
    name: 'app',
    components: {
      VersionList,
    },
    data() {
      return {
        selectedProjectChangePending: false,
      };
    },
    computed: mapGetters({
      projects: 'allProjects',
      selected: 'selectedProject',
      recentProjects: 'recentProjects',
    }),
    methods: {
      updateSelectedProject(projectKey) {
        this.selectedProjectChangePending = true;
        this.$store.dispatch('setSelectedProject', projectKey).then(() => {
          this.selectedProjectChangePending = false;
        }, () => {
          this.selectedProjectChangePending = false;
        });
      },
    },
    created() {
      this.$store.dispatch('getAllProjects').catch((error) => {
        AJS.messages.error({
          title: 'Error loading projects.',
          body: `<p>${error.response.status} ${error.response.statusText}</p>`,
        });
      });
    },
  };
</script>

<style>
</style>
