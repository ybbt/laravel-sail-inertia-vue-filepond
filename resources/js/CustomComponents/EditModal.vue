<template>
    <div class="container mx-auto">
        <div class="flex justify-center">
<!--            <button-->
<!--                @click="this.isOpen = true"-->
<!--                class="px-6 py-2 text-white bg-blue-600 rounded shadow"-->
<!--                type="button"-->
<!--            >-->
<!--                Open Model-->
<!--            </button>-->

            <div
                v-show="isOpen"
                class="
                  absolute
                  inset-0
                  flex
                  items-center
                  justify-center
                  bg-gray-700 bg-opacity-50
                "
            >
                <div class="max-w-2xl p-6 bg-white rounded-md shadow-xl">
                    <div class="flex items-center justify-between">
                        <h3 class="text-2xl">Edit Form</h3>
                        <svg
                            @click="onClickClose"
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-8 h-8 text-red-900 cursor-pointer"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                    <div class="mt-4">
                        <UploadFileForm :isEdit="true" :file="file" :csrf_token="csrf_token" @isSendEditData="onClickClose"/>
<!--                        <button-->
<!--                            @click="onClickClose"-->
<!--                            class="px-6 py-2 text-blue-800 border border-blue-600 rounded"-->
<!--                        >-->
<!--                            Cancel-->
<!--                        </button>-->
<!--                        <button class="px-6 py-2 ml-2 text-blue-100 bg-blue-600 rounded">-->
<!--                            Save-->
<!--                        </button>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import UploadFileForm from "@/CustomComponents/UploadFileForm.vue";
export default {
    data() {
        return {
            isOpen: this.isOpenProp,
        };
    },
    props: {
        isOpenProp:Boolean,
        file: Object,
        csrf_token: String,
    },
    watch: {
        isOpenProp: function () {
            this.isOpen = this.isOpenProp;
        }
    },
    emits: ['isCloseModal'],
    methods: {
        onClickClose: function (){
            this.isOpen = false;
            this.$emit('isCloseModal', {
                isOpenModal: false,
            })
        }
    },
    components: {
        UploadFileForm,
    }
};
</script>
