<template>
    <div id="app" class="min-w-[30rem] border border-gray-600 m-2">
        <p v-if="pageErrors.length">
            <b class="text-red-900">Errors:</b>
            <InputError v-for="error in pageErrors" :message="error"/>
        </p>
        <form
            @submit.prevent="onSubmit"
        >
            <input type="hidden" name="_token" :value="csrf_token">

            <div class="border border-gray-400 p-2 m-2">
                <Label>Title</label>
                <Input v-model="form.title" name="title" id="title" placeholder="edit me" class="w-full"/>
            </div>
            <InputError v-if="serverErrors?.title" :message="serverErrors.title"/>
            <div class="border border-gray-400 p-2 m-2">
                <Label>File</label><!--                    class="w-full"-->
                <file-pond

                    name="new_file"
                    ref="pond"
                    v-bind:allow-multiple="false"
                    accepted-file-types="image/jpeg, image/png, video/*"
                    chunkUploads="false"

                    v-bind:credits="null"
                    v-bind:server="{
                        process: {
                            url: './upload',
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN':  csrf_token
                            },
                        },
                        revert: {
                            headers: {
                                'X-CSRF-TOKEN':  csrf_token
                            },
                        },
                        load: {
                            url:'./load/',
                            method: 'GET',
                            headers: {
                                'X-CSRF-TOKEN':  csrf_token
                            },
                        },
                    }"
                    v-bind:files="myFiles"
                    v-on:init="initHandler"
                />
            </div>
            <InputError v-if="serverErrors?.new_file" :message="serverErrors.new_file"/>

            <div class="p-2">
                <Button>Send</Button>
            </div>
        </form>
    </div>

</template>

<script>

import vueFilePond from "vue-filepond";
import "filepond/dist/filepond.min.css";
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";


const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
);

import Input from "@/Components/Input.vue";
import InputError from "@/Components/InputError.vue";
import Button from "@/Components/Button.vue";
import Label from "@/Components/Label.vue";
import {useForm} from "@inertiajs/inertia-vue3";


export default {
    methods: {
        initHandler() {
            if (this.isEdit) {
                console.log(this.file, "URL");
                this.myFiles = [
                    {
                        source: this.file?.folder,

                        options: {
                            type: 'local',
                        },
                    },
                ]
            }
        },
        onSubmit(event) {

            console.log(this.file?.folder);

            let serverId;
            if (this.$refs.pond.getFile()?.serverId) {
                serverId = this.$refs.pond.getFile().serverId;
            }

            this.form.new_file = serverId;

            this.pageErrors = [];
            if (!this.form.title) {
                this.pageErrors.push('The title field is required.');
            }
            if (!this.form.new_file) {
                this.pageErrors.push('The file field is required.');
            }

            if (this.pageErrors.length > 0) return;

            if (!this.isEdit) {
                this.$inertia.post('/save', this.form, {
                    errorBag: 'saveFile',
                });
            } else {
                this.$inertia.post(`/edit/${this.file?.id}`, this.form, {
                    errorBag: 'editFile',
                });
            }

            this.$refs.pond.removeFile();
            this.form.reset('title', 'new_file'),
            event.target.reset();
            this.$emit('isSendEditData');
        },
    },

    data() {

        return {

            form: useForm({
                title: this.file?.title,
                new_file: null,
            }),
            myFiles: [],
            pageErrors: [],
        }
    },
    components: {
        Button,
        Label,
        Input,
        InputError,
        FilePond,
    },
    props: {
        serverErrors: Object,
        csrf_token: String,
        file: Object,
        isEdit: Boolean
    },

};
</script>


