<template>
  <form class="aui top-label releases-add-version" v-on:submit.prevent="onSubmit">
    <div id="add-version-fieldset">
      <div class="field-group">
        <label for="create-version-name">Version name<span class="aui-icon icon-required"> required</span></label>
        <input v-bind:disabled="versionCreatePending" id="create-version-name" class="text" type="text" v-model="versionName"
               placeholder="Version name" required autofocus>
      </div>
      <div class="field-group">
        <label for="create-start-date">Start date</label>
        <div class="input-with-button">
          <input v-bind:disabled="versionCreatePending" id="create-start-date" class="text" type="text"
                 v-model="versionStartDate">
          <a class="aui-button aui-button-primary" v-on:click="setStartDateToday">Today</a>
        </div>
      </div>
      <div class="field-group">
        <label for="create-release-date">Release date</label>
        <div class="input-with-button">
          <input v-bind:disabled="versionCreatePending" id="create-release-date" class="text" type="text"
                 v-model="versionReleaseDate">
          <a class="aui-button aui-button-primary" v-on:click="setReleaseDateToday">Today</a>
        </div>
      </div>
      <div class="field-group field-group-checkbox">
        <label for="create-version-tst">TST</label>
        <aui-toggle-button v-bind:disabled="versionCreatePending" v-model="versionTst" id="create-version-tst">
        </aui-toggle-button>
      </div>
      <div class="field-group field-group-checkbox">
        <label for="create-version-acc">ACC</label>
        <aui-toggle-button v-bind:disabled="versionCreatePending" v-model="versionAcc" id="create-version-acc">
        </aui-toggle-button>
      </div>
      <div class="field-group field-group-checkbox">
        <label for="create-version-prod">PROD</label>
        <aui-toggle-button v-bind:disabled="versionCreatePending" v-model="versionProd" id="create-version-prod">
        </aui-toggle-button>
      </div>
      <div class="field-group">
        <label for="create-version-description">Description</label>
        <input v-bind:disabled="versionCreatePending" id="create-version-description" class="text" type="text"
               v-model="versionDescription">
      </div>
      <div id="button-group" class="field-group">
        <button class="aui-button aui-button-primary">Add</button>
      </div>
    </div>
  </form>
</template>

<script>
  /* global AJS */
  import * as moment from 'moment';
  import { formatErrorResponse } from '../util';


  export default {
    name: 'versionList',
    data() {
      return {
        versionName: '',
        versionStartDate: moment().format('YYYY-MM-DD'),
        versionReleaseDate: '',
        versionTst: false,
        versionAcc: false,
        versionProd: false,
        versionDescription: '',
        versionCreatePending: false,
      };
    },
    methods: {
      onSubmit() {
        this.versionCreatePending = true;
        let descriptionString = '';
        descriptionString += this.versionTst ? 'TST ' : '';
        descriptionString += this.versionAcc ? 'ACC ' : '';
        descriptionString += this.versionProd ? 'PROD ' : '';
        descriptionString += this.versionDescription;
        this.$store.dispatch('createVersion', {
          name: this.versionName,
          description: descriptionString,
          startDate: this.versionStartDate,
          releaseDate: this.versionReleaseDate,
        }).then(() => {
          this.versionCreatePending = false;
          this.versionName = '';
          this.versionStartDate = moment().format('YYYY-MM-DD');
          this.versionReleaseDate = '';
          this.versionTst = false;
          this.versionAcc = false;
          this.versionProd = false;
          this.versionDescription = '';
          setTimeout(() => {
            AJS.$('#create-version-name').focus();
          }, 100);
        }, (error) => {
          this.versionCreatePending = false;
          AJS.messages.error({
            title: 'Error creating version.',
            body: formatErrorResponse(error.response.data),
          });
        });
      },
      setStartDateToday() {
        this.versionStartDate = moment().format('YYYY-MM-DD');
      },
      setReleaseDateToday() {
        this.versionReleaseDate = moment().format('YYYY-MM-DD');
      },
      cancelDelete() {
        AJS.dialog2('#delete-confirm-dialog').hide();
      },
    },
    mounted() {
      const vm = this;
      // eslint-disable-next-line
      AJS.$('#create-start-date').datePicker({
        overrideBrowserDefault: true,
        onSelect(dateText) {
          vm.versionStartDate = dateText;
        },
      });
      // eslint-disable-next-line
      AJS.$('#create-release-date').datePicker({
        overrideBrowserDefault: true,
        onSelect(dateText) {
          vm.versionReleaseDate = dateText;
        },
      });
    },
  };
</script>

<style lang="scss">
  .releases-add-version {
    background-color: whitesmoke;
    border-top: 1px solid #D3D3D3;
    border-bottom: 1px solid #D3D3D3;
    padding: 10px 0;
    margin: 20px 20px 0;
  }

  #add-version-fieldset {
    display: flex;
    flex-direction: row;
    justify-content: flex-end;
    .field-group {
      flex: 1;
      padding: 0 10px;
      label {
        display: block;
      }
      input {
        display: inline-flex;
        max-width: none;
        width: 100%;
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

      &.field-group-checkbox {
        flex: 0;
      }

      &#button-group {
        flex: 0;
        align-self: flex-end;
      }
    }
  }

  @media(max-width: 810px) {
    #add-version-fieldset {
      display: block;
      .field-group {
        width: 100%;
      }
    }
  }
</style>
