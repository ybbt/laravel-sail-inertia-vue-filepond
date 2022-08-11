<template>
    <UploadFileForm v-bind:csrf_token="csrf_token"/>
    <h1 class="text-red-900 text-3xl">List of Files</h1>
    <div id="files-list">
        <FilesListItem v-for="file in files" :key="file.id" v-bind:title="file.title" v-bind:filename="file.filename"></FilesListItem>
    </div>
</template>

<script>
import UploadFileForm from "@/CustomComponents/UploadFileForm.vue";
import FilesListItem from "@/CustomComponents/FilesListItem.vue";
export default {
    name: "FilesList",
    components: {
        UploadFileForm,
        FilesListItem,
    },
    props: {
        files: Array,
        csrf_token: String,
    },
}
</script>

