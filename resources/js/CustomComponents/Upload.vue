<!--<script setup>-->
<!--let token = document.head.querySelector('meta[name="csrf-token"]');-->
<!--</script>-->

<!--<template>-->
<!--    <div>Hello World</div>-->
<!--</template>-->

<template>
    <div id="app">

            <file-pond
                name="new_file"
                ref="pond"

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
            <div>{{myFiles}}</div>

    </div>
</template>

<script>
// Import Vue FilePond
import vueFilePond from "vue-filepond";

// Import FilePond styles
import "filepond/dist/filepond.min.css";

// Import FilePond plugins
// Please note that you need to install these plugins separately

// Import image preview plugin styles
// import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";

// Import image preview and file type validation plugins
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";

// Create component
const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
    // FilePondPluginImagePreview

);



export default {
    name: "Upload",
    // data: function () {
    //     return {};
    // },
    methods: {
        handleFilePondInit: function () {
            console.log("FilePond has initialized");

            // FilePond instance methods are available on `this.$refs.pond`
        },

    },
    components: {
        FilePond,
    },
    props: {
        csrf_token: String
    }
};
</script>


