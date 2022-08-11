<template>
    <div id="app">
        <form
            action="/save"
            method="post"
        >
            <input type="hidden" name="_token" :value="csrf_token">
            <Input v-model="title" name="title" id="title" placeholder="edit me"/>
            <file-pond
                name="new_file"
                ref="pond"

                v-bind:allow-multiple="false"
                accepted-file-types="image/jpeg, image/png"
                v-model="myFiles"
                v-bind:credits="null"
                v-bind:server="{
                    process: {
                        url: './upload',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN':  csrf_token
                        },
                    }
                }"

            />

            <Button>Send</Button>
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
import Button from "@/Components/Button.vue";


export default {

    name: "UploadFileForm",
    data: function () {
        return {
            // title: '',
            myFiles: FilePond.getFiles()[0].id,
        }
    },
    // methods: {
    //     onSubmit: function (e) {
    //
    //     }
    // },
    components: {
        Button,
        Input,
        FilePond,
    },
    props: {
        csrf_token: String
    }
};
</script>


