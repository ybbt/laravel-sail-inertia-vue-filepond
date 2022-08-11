<template>
    <div id="app">
        <form
            @submit.prevent="form.post('/save')"
        >
            <input type="hidden" name="_token" :value="csrf_token">
            <Input v-model="form.title" name="title" id="title" placeholder="edit me"/>
            <file-pond
                name="new_file"
                ref="pond"
                v-model="form.new_file"

                v-bind:allow-multiple="false"
                accepted-file-types="image/jpeg, image/png"

                v-bind:credits="null"
                v-on:init="handleFilePondInit"
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

// import { reactive } from 'vue'
// import { Inertia } from '@inertiajs/inertia'

import {useForm} from '@inertiajs/inertia-vue3'

export default {
    setup () {
        // const form = reactive({
        //     title: null,
        //     new_file: null,
        // })
        //
        // function onSubmit() {
        //     Inertia.post('/save', form)
        // }
        //
        // return { form, submit }
        const form = useForm({
            title: null,
            new_file: null,
        })

        return { form }
    },
    name: "UploadFileForm",
    data: function () {
        return {
            // title: '',
            // myFiles: [],
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


