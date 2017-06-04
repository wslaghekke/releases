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
          <label>
            Select project:
            <aui-select2-single placeholder="Select project" :value="selected ? selected.key : undefined"
                                @input="updateSelectedProject">
              <aui-select2-option v-for="project in projects" :key="project.key" :value="project.key">{{ project.name
                }}
              </aui-select2-option>
            </aui-select2-single>
          </label>
          <br><br><br>
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
    computed: mapGetters({
      projects: 'allProjects',
      selected: 'selectedProject',
    }),
    methods: {
      updateSelectedProject(projectKey) {
        this.$store.dispatch('setSelectedProject', projectKey);
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
