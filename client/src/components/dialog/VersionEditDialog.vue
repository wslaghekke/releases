<template>
  <section role="dialog" id="version-edit-dialog" class="aui-layer aui-dialog2 aui-dialog2-medium" aria-hidden="true"
           data-aui-focus-selector="#edit-version-name">
    <form class="aui top-label version-edit-form" v-on:submit.prevent="onSubmit">
      <header class="aui-dialog2-header">
        <h2 class="aui-dialog2-header-main">Edit {{ version ? version.name : '' }}</h2>
        <a class="aui-dialog2-header-close">
          <span class="aui-icon aui-icon-small aui-iconfont-close-dialog">Close</span>
        </a>
      </header>
      <div class="aui-dialog2-content" style="min-height: 80px">
        <div class="field-group">
          <label for="edit-version-name">Version name<span class="aui-icon icon-required"> required</span></label>
          <input id="edit-version-name" class="text" type="text" v-model="version.name" placeholder="Version name"
                 required>
        </div>
        <div class="field-group">
          <label for="edit-start-date">Start date</label>
          <div class="input-with-button">
            <input id="edit-start-date" class="text" type="text" v-model="version.startDate">
            <a class="aui-button aui-button-primary" v-on:click="setStartDateToday">Today</a>
          </div>
        </div>
        <div class="field-group">
          <label for="edit-release-date">Release date</label>
          <div class="input-with-button">
            <input id="edit-release-date" class="text" type="text" v-model="version.releaseDate">
            <a class="aui-button aui-button-primary" v-on:click="setReleaseDateToday">Today</a>
          </div>
        </div>
        <div class="field-group">
          <label for="edit-version-description">Description</label>
          <input id="edit-version-description" class="text" type="text" v-model="version.description">
        </div>
        <div class="field-group">
          <aui-toggle-button v-model="version.released" id="edit-version-released" label="Released"></aui-toggle-button>
          <aui-toggle-button v-model="version.archived" id="edit-version-archived" label="Archived"></aui-toggle-button>
        </div>
      </div>
      <footer class="aui-dialog2-footer">
        <div class="aui-dialog2-footer-actions">
          <button type="submit" class="aui-button aui-button-primary">Save</button>
          <button type="button" class="aui-button aui-button-link" v-on:click="cancel">Cancel</button>
        </div>
      </footer>
    </form>
  </section>
</template>

<script>
  /* global AJS */
  import * as moment from 'moment';

  export default {
    name: 'VersionEditDialog',
    data() {
      return {
        duplicateVersion: null,
        version: {
          name: '',
          startDate: '',
          releaseDate: '',
          description: '',
          released: false,
          archived: false,
        },
      };
    },
    methods: {
      resetData() {
        this.version.name = '';
        this.version.startDate = '';
        this.version.releaseDate = '';
        this.version.description = '';
        this.version.released = false;
        this.version.archived = false;
      },
      editVersion(version) {
        this.resetData();
        this.version = Object.assign(this.version, version);
        AJS.dialog2('#version-edit-dialog').show();
      },
      duplicateAndEditVersion(version) {
        this.duplicateVersion = {
          name: version.name,
          description: version.description,
          startDate: moment().format('YYYY-MM-DD'),
          releaseDate: '',
          released: false,
          archived: false,
        };
        this.editVersion(version);
      },
      setStartDateToday() {
        this.version.startDate = moment().format('YYYY-MM-DD');
      },
      setReleaseDateToday() {
        this.version.releaseDate = moment().format('YYYY-MM-DD');
      },
      onSubmit() {
        AJS.dialog2('#version-edit-dialog').hide();
        this.$store.dispatch('editVersion', Object.assign({}, this.version)).then(() => {
          if (this.duplicateVersion !== null) {
            if (this.version.releaseDate !== '') {
              this.duplicateVersion.startDate = this.version.releaseDate;
            }
            this.$store.dispatch('createVersion', this.duplicateVersion).catch((error) => {
              AJS.messages.error({
                title: 'Error duplicating version.',
                body: `<p>${error.response.status} ${error.response.statusText}</p>`,
              });
            });
          }
        }, (error) => {
          AJS.messages.error({
            title: 'Error editing version.',
            body: `<p>${error.response.status} ${error.response.statusText}</p>`,
          });
        });
      },
      cancel() {
        AJS.dialog2('#version-edit-dialog').hide();
      },
    },
    mounted() {
      const vm = this;
      // eslint-disable-next-line
      AJS.$('#edit-start-date').datePicker({
        overrideBrowserDefault: true,
        onSelect(dateText) {
          vm.version.startDate = dateText;
        },
      });
      // eslint-disable-next-line
      AJS.$('#edit-release-date').datePicker({
        overrideBrowserDefault: true,
        onSelect(dateText) {
          vm.version.releaseDate = dateText;
        },
      });
    },
  };
</script>

<style lang="scss">
  .version-edit-form {
    input.text {
      max-width: none;
    }
    .input-with-button {
      display: flex;
      input {
        margin-right: 5px;
        flex: 1;
      }
      a, button {
        flex: 0;
      }
    }
  }
</style>
