<template>
    <div class="border border-gray-500 m-1 p-1">
        <h1 class="text-emerald-600">{{ title }}</h1>
        <div>{{ filename }}</div>
    </div>
</template>

<script>
import Button from "@/Components/Button.vue";
import UploadFileForm from "@/CustomComponents/UploadFileForm.vue";

export default {
    name: "FilesListItem",
    components: {Button, UploadFileForm},
    props: {
        title: String,
        filename: String,
    }
}
</script>


