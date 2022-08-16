<template>
    <div class="flex justify-center">
        <div class="max-w-[60rem] bg-gray-50">
            <div v-if="auth.user?.id">
                <h1 class="text-green-900 text-3xl">Upload Form</h1>
                <UploadFileForm :csrf_token="csrf_token" :serverErrors="errors.saveFile"/>
            </div>
            <div class="mt-10 min-w-[30rem]">
                <h1 class="text-green-900 text-3xl">List of Files</h1>
                <p v-if="errors.editFile">
                    <b class="text-red-900">Errors:</b>
                    <InputError v-if="errors.editFile?.title" :message="errors.editFile.title"/>
                    <InputError v-if="errors.editFile?.new_file" :message="errors.editFile.new_file"/>
                </p>
                <div id="files-list">
                    <FilesListItem
                        v-for="file in files.data"
                        :key="file.id"
                        :file="file"
                        :csrf_token="csrf_token"
                        :user_id="auth.user?.id"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import UploadFileForm from "@/CustomComponents/UploadFileForm.vue";
import FilesListItem from "@/CustomComponents/FilesListItem.vue";
import EditModal from "@/CustomComponents/EditModal.vue";
import InputError from "@/Components/InputError.vue";

export default {
    name: "FilesList",
    data() {
        return {
            file: Object,
        }
    },
    components: {
        EditModal,
        UploadFileForm,
        FilesListItem,
        InputError,
    },
    props: {
        auth: Object,
        errors: Object,
        files: Array,
        csrf_token: String,
    },
}
</script>

