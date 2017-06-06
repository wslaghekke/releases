<template>
  <section role="dialog" id="version-release-dialog" class="aui-layer aui-dialog2 aui-dialog2-medium" aria-hidden="true"
           data-aui-focus-selector="#release-version-name">
    <form class="aui top-label version-release-form" v-on:submit.prevent="onSubmit">
      <header class="aui-dialog2-header">
        <h2 class="aui-dialog2-header-main">Release version: {{ version ? version.name : '' }}</h2>
        <a class="aui-dialog2-header-close">
          <span class="aui-icon aui-icon-small aui-iconfont-close-dialog">Close</span>
        </a>
      </header>
      <div class="aui-dialog2-content" style="min-height: 80px">
        <div class="field-group">
          <label for="release-release-date">Release date</label>
          <div class="input-with-button">
            <input id="release-release-date" class="text" type="text" v-model="version.releaseDate">
            <a class="aui-button aui-button-primary" v-on:click="setReleaseDateToday">Today</a>
          </div>
        </div>
      </div>
      <footer class="aui-dialog2-footer">
        <div class="aui-dialog2-footer-actions">
          <button class="aui-button aui-button-primary" type="submit">Save</button>
          <button class="aui-button aui-button-link" v-on:click="cancel">Cancel</button>
        </div>
      </footer>
    </form>
  </section>
</template>

<script>
  /* global AJS */
  const getFormattedDate = (date) => {
    let month = date.getMonth();
    if (month < 10) {
      month = `0${month}`;
    }
    let day = date.getDate();
    if (day < 10) {
      day = `0${day}`;
    }
    return `${date.getFullYear()}-${month}-${day}`;
  };

  export default {
    name: 'VersionReleaseDialog',
    data() {
      return {
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
      releaseVersion(version) {
        this.resetData();
        this.version = Object.assign(this.version, version);
        if (version.released) {
          // Submit without prompt if undoing release
          this.version.released = false;
          this.onSubmit();
        } else {
          this.version.released = true;
          AJS.dialog2('#version-release-dialog').show();
        }
      },
      setReleaseDateToday() {
        this.version.releaseDate = getFormattedDate(new Date());
      },
      onSubmit() {
        AJS.dialog2('#version-release-dialog').hide();
        this.$store.dispatch('editVersion', Object.assign({}, this.version)).catch((error) => {
          AJS.messages.error({
            title: 'Error releasing version.',
            body: `<p>${error.response.status} ${error.response.statusText}</p>`,
          });
        });
      },
      cancel() {
        AJS.dialog2('#version-release-dialog').hide();
      },
    },
  };
</script>

<style lang="scss">
  .version-release-form {
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
