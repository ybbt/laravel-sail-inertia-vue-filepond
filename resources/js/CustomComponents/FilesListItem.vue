<template>
    <div class="border border-gray-500 m-1 p-1">
        <h1 class="text-emerald-600">{{ file?.title }}</h1>
        <a :href="file?.url">{{ file?.filename }}</a>
        <div v-if="user_id === file.user_id" class="flex justify-end w-full">
            <div class="p-1">
                <Button @click="onHandlerDelete">Delete</Button>
            </div>

            <div class="p-1">
                <EditModal :file="file" :csrf_token="csrf_token"/>
            </div>
        </div>
    </div>

</template>

<script>
import Button from "@/Components/Button.vue";
import NavLink from "@/Components/NavLink.vue";
import UploadFileForm from "@/CustomComponents/UploadFileForm.vue";
import EditModal from "@/CustomComponents/EditModal.vue";

export default {
    name: "FilesListItem",
    components: {Button, NavLink, UploadFileForm, EditModal},
    props: {
        file: Object,
        csrf_token: String,
        user_id: Number,
    },
    methods: {
        onHandlerDelete: function () {
            this.$inertia.delete(`/delete/${this.file?.id}`);
        }
    },
}
</script>


