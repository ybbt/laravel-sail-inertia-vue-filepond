<template>
    <div class="flex justify-center">
        <div class="max-w-[60rem] bg-gray-50">
            <div>
                <h1 class="text-red-900 text-3xl">Upload Form</h1>
                <UploadFileForm :csrf_token="csrf_token" :createErrors="errors"/>
            </div>
            <div>
                <h1 class="text-red-900 text-3xl">List of Files</h1>
                <div id="files-list">
                    <FilesListItem
                        v-for="file in files"
                        :key="file.id"
                        :file="file"
                        :csrf_token="csrf_token"
                        :user_id="auth.user.id"
                        @isOpenModal="onOpenModal"
                    />
                </div>
            </div>
        </div>
    </div>
    <EditModal :isOpenProp="isOpen" :file="file" @isCloseModal="onOpenModal" :csrf_token="csrf_token"/>
<!--    <div>{{isOpen}}</div>-->
</template>

<script>
import UploadFileForm from "@/CustomComponents/UploadFileForm.vue";
import FilesListItem from "@/CustomComponents/FilesListItem.vue";
import EditModal from "@/CustomComponents/EditModal.vue";
export default {
    name: "FilesList",
    data() {
        return {
            isOpen: false,
            file: Object,
        }
    },
    components: {
        EditModal,
        UploadFileForm,
        FilesListItem,
    },
    props: {
        auth: Object,
        errors: Object,
        files: Array,
        csrf_token: String,
    },
    methods: {
        onOpenModal: function (data){
           this.isOpen = data.isOpenModal;
           this.file = data.file;
        },
    },
}
</script>

