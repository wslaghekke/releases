<template>
  <section role="dialog" id="delete-confirm-dialog" class="aui-layer aui-dialog2 aui-dialog2-medium" aria-hidden="true">
    <header class="aui-dialog2-header">
      <h2 class="aui-dialog2-header-main">Delete {{ deleteConfirmVersion ? deleteConfirmVersion.name : '' }}</h2>
      <a class="aui-dialog2-header-close">
        <span class="aui-icon aui-icon-small aui-iconfont-close-dialog">Close</span>
      </a>
    </header>
    <div class="aui-dialog2-content" style="min-height: 80px">
      <p>Are you sure you want to delete {{ deleteConfirmVersion ? deleteConfirmVersion.name : '' }}?</p>
    </div>
    <footer class="aui-dialog2-footer">
      <div class="aui-dialog2-footer-actions">
        <button class="aui-button aui-button-primary" v-on:click="confirmDelete">
          Yes
        </button>
        <button class="aui-button aui-button-link" v-on:click="cancelDelete">Cancel</button>
      </div>
    </footer>
  </section>
</template>

<script>
  /* global AJS */
  export default {
    name: 'VersionDeleteDialog',
    data() {
      return {
        deleteConfirmVersion: null,
      };
    },
    methods: {
      deleteVersion(version) {
        this.deleteConfirmVersion = version;
        AJS.dialog2('#delete-confirm-dialog').show();
      },
      confirmDelete() {
        AJS.dialog2('#delete-confirm-dialog').hide();
        this.$store.dispatch('deleteVersion', this.deleteConfirmVersion).catch((error) => {
          AJS.messages.error({
            title: 'Error deleting version.',
            body: `<p>${error.response.status} ${error.response.statusText}</p>`,
          });
        });
      },
      cancelDelete() {
        AJS.dialog2('#delete-confirm-dialog').hide();
      },
    },
  };
</script>
