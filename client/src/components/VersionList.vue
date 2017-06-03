<template>
  <div>
    <version-create-form></version-create-form>
    <table class="aui versions-table">
      <thead>
      <tr>
        <!--<th class="versions-table__handle"></th>-->
        <th class="versions-table__name">Version</th>
        <th class="versions-table__status">Status</th>
        <!--<th class="versions-table__progress">Progress</th>-->
        <th class="versions-table__date">Start date</th>
        <th class="versions-table__date">Release date</th>
        <th class="versions-table__description">Description</th>
        <th class="dynamic-table__actions">Actions</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="version in versions" :key="version.id">
        <!--<th>TODO: TABLE_HANDLE</th>-->
        <td>{{ version.name }}</td>
        <td>
          <aui-lozenge subtle :type="version.released ? 'success' : 'current'">
            {{ version.released ? 'Released' : 'Unreleased' }}
          </aui-lozenge>
        </td>
        <!--<td>TODO: Figure out if its possible to get issue progress data</td>-->
        <td>{{ version.startDate }}</td>
        <td>{{ version.releaseDate }}</td>
        <td>{{ version.description }}</td>
        <td>
          <a class="aui-button aui-button-subtle" v-on:click="deleteVersion(version)">
            <span class="aui-icon aui-icon-small aui-iconfont-delete">Delete</span>
          </a>
        </td>
      </tr>
      </tbody>
    </table>
    <version-delete-dialog ref="deleteDialog"></version-delete-dialog>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';
  import VersionCreateForm from './VersionCreateForm';
  import VersionDeleteDialog from './VersionDeleteDialog';

  export default {
    name: 'versionList',
    components: {
      VersionCreateForm,
      VersionDeleteDialog,
    },
    methods: {
      deleteVersion(version) {
        this.$refs.deleteDialog.deleteVersion(version);
      },
    },
    computed: mapGetters({
      versions: 'allVersions',
    }),
  };
</script>

<style lang="scss">
  .versions-table tr:first-child > th {
    border-top: none !important;
  }
  @media(max-width: 810px) {
    .versions-table {
      th:nth-child(2), td:nth-child(2),
      th:nth-child(5), td:nth-child(5) {
        display: none;
      }
    }
  }
</style>
