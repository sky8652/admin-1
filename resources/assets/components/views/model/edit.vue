<template>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header clearfix">
                    <h3 class="box-title">{{ $t('sco.box.edit') }} {{ config.title }}</h3>

                    <div class="btn-group btn-group-sm pull-right margin-r-5">
                        <button
                            type="button"
                            class="btn btn-default"
                            @click.prevent="$router.push({ name: 'admin.model.index', params: {model: $route.params.model}})">
                            <i class="fa fa-reply"></i>
                            {{ $t('sco.box.back') }}
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <v-form
                    :elements="info.elements"
                    v-model="info.values"
                    v-loading="formLoading"
                    :errors="errors">
                </v-form>

                <!-- /.box-body -->
                <div class="box-footer">
                    <el-button
                        type="primary"
                        @click="save"
                        :loading="buttonLoading">
                        {{ $t('sco.box.ok') }}
                    </el-button>
                    <el-button
                        class="btn btn-primary"
                        @click.prevent="refresh">
                        {{ $t('sco.box.reset') }}
                    </el-button>
                </div>
                <!-- /.box-footer -->
            </div>
        </div>
    </div>
</template>

<script>
    import vForm from './form.vue';
    import getConfig from '../../../mixins/get-config'

    export default {
        mixins: [
            getConfig
        ],
        components: {
            vForm
        },
        data() {
            return {
                info: {},
                errors: {},
                formLoading: false,
                buttonLoading: false,
            }
        },
        computed: {},
        created() {
            this.getEditInfo();
        },
        methods: {
            save() {
                this.buttonLoading = true;
                this.errors = {};
                this.$http.post(
                    `/${this.getUrlPrefix()}/${this.$route.params.model}/${this.$route.params.id}/edit`,
                    this.info.values
                ).then(response => {
                    this.buttonLoading = false;
                    this.$message.success(this.$t('sco.box.editSuccess'))
                    this.$router.push({
                        name: 'admin.model.index',
                        params: {model: this.$route.params.model}
                    })
                }).catch(error => {
                    this.buttonLoading = false;
                    if (typeof error.response.data.errors == 'object') {
                        this.errors = error.response.data.errors;
                    }
                })
            },
            getEditInfo() {
                this.formLoading = true;
                this.info = {};

                this.$http.get(
                    `/${this.getUrlPrefix()}/${this.$route.params.model}/${this.$route.params.id}/edit/info`
                ).then(response => {
                    this.formLoading = false;
                    this.info = response.data;
                }).catch(error => {
                    this.$message.error(error.response.data.message)
                })
            },
            refresh() {
                this.getEditInfo();
            }
        }
    }
</script>
