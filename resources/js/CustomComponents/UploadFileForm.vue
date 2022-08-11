<template>
    <div id="app">
        <form
            @submit.prevent="onSubmit"
        >
            <input type="hidden" name="_token" :value="csrf_token">
            <Input v-model="form.title" name="title" id="title" placeholder="edit me"/>
            <file-pond
                name="new_file"
                ref="pond"

                v-bind:allow-multiple="false"
                accepted-file-types="image/jpeg, image/png"
                v-model="form.new_file"
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

// import { reactive } from 'vue'
// import { Inertia } from '@inertiajs/inertia'

export default {
    data() {
        return {
            form: {
                title: null,
                new_file: null,
            },
        }
    },
    methods: {
        onSubmit() {
            this.$inertia.post('/save', this.form)
        },
    },
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


