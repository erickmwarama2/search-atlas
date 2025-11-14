<template>
  <v-card flat>
    <v-card-title class="d-flex justify-space-between align-center">
      <span class="text-h6">Loans</span>
      <v-btn size="small" variant="tonal" :loading="loading" @click="loadLoans">
        Refresh
      </v-btn>
    </v-card-title>

    <v-data-table
      class="elevation-1"
      :headers="headers"
      :items-per-page-options="[25, 50, 100]"
      :items-per-page="25"
      :items="loans"
      :loading="loading"
    >
      <template #item.loaned_at="{item}">
        {{ moment(item.loaned_at).format('MMM Do YYYY \\a\\t h:mm A') }} <br/>
        {{ moment(item.due_at).format('MMM Do YYYY \\a\\t h:mm A') }}
      </template>

      <template #item.returned_at="{item}">
        {{ item.returned_at ? moment(item.returned_at).format('MMM Do YYYY \\a\\t h:mm A') : '-' }}
      </template>

      <template #loading>
        <v-sheet class="pa-4 text-center">Loading loans...</v-sheet>
      </template>
    </v-data-table>

    <v-dialog persistent v-model="dialog.open" max-width="640">
      <v-card>
        <v-card-title class="text-h6">{{ dialog.form.id }}</v-card-title>

        <v-card-text>
          <v-form ref="authorForm" @submit.prevent="submitDialog">
            <v-row dense>
              <v-col cols="12" sm="6">
                <v-select
                  label="Days"
                  :items="[{'1 Day':'1'}, {'3 Days':'3'}, {'7 Days':'7'}]"
                  v-model.number="dialog.form.days"
                  autocomplete="off"
                />
              </v-col>
            </v-row>

            <button type="submit" class="d-none" />
          </v-form>
        </v-card-text>

        <v-card-actions class="justify-end">
          <v-btn variant="text" @click="closeDialog" :disabled="dialog.saving">Cancel</v-btn>
          <v-btn color="primary" :loading="dialog.saving" @click="submitDialog">
            Submit
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-card>
</template>

<script>
import { toast } from 'vue3-toastify';
import axios from 'axios';
import moment from 'moment';

export default {
  name: 'LoansTab',

  data () {
    return {
      moment,

      loading: false,
      loans: [],
      dialog: {
        form: {
          id: 'Extend Loan',
          days: ''
        }
      },
      headers: [
        { title: 'ID', key: 'id' },
        { title: 'User', key: 'user.name' },
        { title: 'Book', key: 'book.title' },
        { title: 'Loan Date', key: 'loaned_at' },
        { title: 'Return Date', key: 'returned_at' },
      ],
    };
  },

  methods: {
    loadLoans () {
      this.loading = true;

      return axios.get('/api/v1/loans')
        .then(r => this.loans = r.data)
        .catch(e => {
          toast(e.response?.data?.message || e.response?.statusText || 'Error', {type: 'error'});
          console.error(e);
        })
        .finally(() => this.loading = false);
    },

    submitDialog () {
      if (this.dialog.saving) return;
      this.dialog.saving = true;

      const payload = {
        additional_days: this.dialog.form.days,
      };

      const url = `/api/v1/loans/extend/${this.dialog.form.id}`;

      axios.put(url, payload)
        .then(() => {
          toast('Loan Extended', { type: 'success' });
          this.loadLoans();
          this.closeDialog();
        })
        .catch(e => {
          toast(e.response?.data?.message || e.response?.statusText || 'Error', { type: 'error' });
          console.error(e);
        })
        .finally(() => this.dialog.saving = false);
    },

    dialogInit () {
      this.dialog.form = {
          id        : '',
          days: '',
        };
    },

    closeDialog () {
      this.dialog.open = false;
      this.dialogInit();
    }
  },

  mounted () {
    this.loadLoans();
  },
};
</script>
