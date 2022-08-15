<template>
    <div id="app" class="min-w-[30rem] border border-gray-600 m-2">
        <p v-if="errors.length">
            <b class="text-red-900">Errors:</b>
            <ul>
                <InputError v-for="error in errors" :message="error"/>
            </ul>
        </p>
        <form
            @submit.prevent="onSubmit"
        >
            <input type="hidden" name="_token" :value="csrf_token">
            <div class="border border-gray-400 p-2 m-2">
                <Label>Title</label>
                <Input v-model="form.title" name="title" id="title" placeholder="edit me" class="w-full"/>
            </div>
            <InputError v-if="createErrors?.title" :message="createErrors.title"/>

            <div class="border border-gray-400 p-2 m-2">
                <Label>File</label>
                <file-pond
                    class="w-full"
                    name="new_file"
                    ref="pond"
                    v-bind:allow-multiple="false"
                    accepted-file-types="image/jpeg, image/png"
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
                        }
                    }"

                />
            </div>
            <InputError v-if="createErrors?.new_file" :message="createErrors.new_file"/>

            <div class="p-2"><Button>Send</Button></div>
        </form>
    </div>

</template>

<script>

import vueFilePond from "vue-filepond";
import "filepond/dist/filepond.min.css";
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
// import FilePondPluginImagePreview from "filepond-plugin-image-preview";
// import FilePondPluginMediaPreview from "filepond-plugin-media-preview";


const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
    FilePondPluginImagePreview,
    // FilePondPluginMediaPreview,
    // FilePondPluginImagePreview,
);

import Input from "@/Components/Input.vue";
import InputError from "@/Components/InputError.vue";
import Button from "@/Components/Button.vue";
import Label from "@/Components/Label.vue";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";

export default {
    data() {
        return {
            form: {
                title: this.file?.title,
                new_file: null,
            },
            errors: [],
        }
    },
    methods: {

        onSubmit(event) {

            console.log(this.$refs.pond.FileStatus);

            let serverId;
            if (this.$refs.pond.getFile()?.serverId){
                serverId = this.$refs.pond.getFile().serverId;
            }

            this.form.new_file = serverId;

            this.errors = [];
            if(!this.form.title) {
                this.errors.push('The title field is required.');
            }
            if(!this.form.new_file){
                this.errors.push('The file field is required.');
            }

            if(this.errors.length > 0) return;

            if (!this.isEdit) {
                this.$inertia.post('/save', this.form);
            } else {
                this.$inertia.post(`/edit/${this.file?.id}`, this.form);
            }
            this.$refs.pond.removeFile();
            event.target.reset();
            this.$emit('isSendEditData');
        },
    },
    components: {
        Button,
        Label,
        Input,
        InputError,
        FilePond,
    },
    props: {
        createErrors: Object,
        csrf_token: String,
        file: Object,
        isEdit: Boolean
    },
    watch: {
        file: function () {
            this.form.title = this.file?.title;
        }
    },
};
</script>


