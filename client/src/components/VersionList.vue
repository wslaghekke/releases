<template>
  <div>
    <version-create-form></version-create-form>
    <table class="aui versions-table">
      <thead>
      <tr>
        <th class="versions-table__handle"></th>
        <th class="versions-table__name">Version</th>
        <th class="versions-table__status">Status</th>
        <!--<th class="versions-table__progress">Progress</th>-->
        <th class="versions-table__date">Start date</th>
        <th class="versions-table__date">Release date</th>
        <th class="versions-table__description">Description</th>
        <th class="dynamic-table__actions">Actions</th>
      </tr>
      </thead>
      <tbody id="releases-table-tbody" class="items">
      <tr v-for="version in versions" :key="version.id" v-bind:data-version-id="version.id">
        <td class="versions-table__handle">
          <span class="aui-icon aui-icon-small aui-iconfont-appswitcher">Reorder versions</span>
        </td>
        <td><a target="_blank" v-bind:href="getIssueUrl(version.id)">{{ version.name }}</a></td>
        <td>
          <aui-lozenge v-if="version.archived" subtle>Archived</aui-lozenge>
          <aui-lozenge v-else-if="version.released" subtle type="success">Released</aui-lozenge>
          <aui-lozenge v-else subtle type="current">Unreleased</aui-lozenge>
        </td>
        <!--<td>TODO: Figure out if its possible to get issue progress data</td>-->
        <td>{{ version.startDate }}</td>
        <td>{{ version.releaseDate }}</td>
        <td>{{ version.description }}</td>
        <td class="action-buttons">
          <div class="aui-buttons">
            <a class="aui-button aui-button" v-on:click="editVersion(version)" title="Edit">
              <span class="aui-icon aui-icon-small aui-iconfont-edit">Edit</span>
            </a>
            <a class="aui-button aui-button" v-on:click="duplicateAndEditVersion(version)" title="Duplicate and Edit">
              <span class="aui-icon aui-icon-small aui-iconfont-copy-clipboard"></span>
            </a>
            <a class="aui-button aui-button" v-on:click="releaseVersion(version)" title="Release">
              <span class="aui-icon aui-icon-small aui-iconfont-approve">Release</span>
            </a>
            <a class="aui-button aui-button" v-on:click="deleteVersion(version)" title="Delete">
              <span class="aui-icon aui-icon-small aui-iconfont-delete">Delete</span>
            </a>
          </div>
        </td>
      </tr>
      </tbody>
    </table>
    <version-delete-dialog ref="deleteDialog"></version-delete-dialog>
    <version-edit-dialog ref="editDialog"></version-edit-dialog>
    <version-release-dialog ref="releaseDialog"></version-release-dialog>
  </div>
</template>

<script>
  import $ from 'jquery';
  import Sortable from 'sortablejs';
  import VersionCreateForm from './VersionCreateForm';
  import VersionDeleteDialog from './dialog/VersionDeleteDialog';
  import VersionEditDialog from './dialog/VersionEditDialog';
  import VersionReleaseDialog from './dialog/VersionReleaseDialog';

  let versionsSortable;

  export default {
    name: 'versionList',
    components: {
      VersionCreateForm,
      VersionDeleteDialog,
      VersionEditDialog,
      VersionReleaseDialog,
    },
    methods: {
      deleteVersion(version) {
        this.$refs.deleteDialog.deleteVersion(version);
      },
      editVersion(version) {
        this.$refs.editDialog.editVersion(version);
      },
      duplicateAndEditVersion(version) {
        this.$refs.editDialog.duplicateAndEditVersion(version);
      },
      releaseVersion(version) {
        this.$refs.releaseDialog.releaseVersion(version);
      },
      getIssueUrl(versionId) {
        return `${window.baseUrl}/browse/${this.selectedProject.key}/fixforversion/${versionId}`;
      },
    },
    computed: {
      versions() {
        return this.$store.state.allVersions.slice().reverse();
      },
      selectedProject() {
        return this.$store.state.selectedProject;
      },
    },
    mounted() {
      const el = document.getElementById('releases-table-tbody');
      if (typeof versionsSortable === 'undefined' && typeof this.$store !== 'undefined' && this.$store !== null) {
        const that = this;

        versionsSortable = new Sortable(el, {
          handle: '.versions-table__handle',
          delay: 0,
          scroll: true,
          onUpdate(event) {
            const currentId = $(event.item).data('version-id');
            const nextId = $(event.item).nextAll('tr[data-version-id]').first().data('version-id');

            that.$store.dispatch('moveVersion', { currentId, nextId }).catch((error) => {
              console.log(
                'Error moving version.',
                `<p>${error.response.status} ${error.response.statusText}</p>`,
              );
            });
          },
        });
      }
    },
  };
</script>

<style lang="scss">
  .versions-table tr:first-child > th {
    border-top: none !important;
  }

  @media(max-width: 610px) {
    .versions-table {
      th:nth-child(2), td:nth-child(2),
      th:nth-child(5), td:nth-child(5) {
        display: none;
      }
    }
  }

  .action-buttons {
    min-width: 120px;
  }
  @media(max-width: 360px) {
    .action-buttons {
      min-width: 80px;
      .aui-button:nth-child(2) {
        display: none;
      }
    }
  }
</style>
