<template>
    <div class="border border-gray-500 m-1 p-1">
        <h1 class="text-emerald-600">{{ file?.title }}</h1>
        <div>{{ file?.filename }}</div>
        <div v-if="user_id === file.user_id" class="flex justify-end w-full">
            <div class="p-1">
                <Button @click="onHandlerDelete">Delete</Button>
            </div>
            <div class="p-1">
                <Button @click="onHandlerEditModal">Edit</Button>
            </div>
        </div>
    </div>

</template>

<script>
import Button from "@/Components/Button.vue";
import UploadFileForm from "@/CustomComponents/UploadFileForm.vue";

export default {
    name: "FilesListItem",
    components: {Button, UploadFileForm},
    props: {
        file: Object,
        csrf_token: String,
        user_id: Number,
    },
    emits: ['isOpenModal'],
    methods: {
        onHandlerEditModal: function (){
            this.$emit('isOpenModal', {
                isOpenModal: true,
                file: this.file,
            })
        },
        onHandlerDelete: function (){
            this.$inertia.delete(`/delete/${this.file?.id}`);
        }
    }
}
</script>


