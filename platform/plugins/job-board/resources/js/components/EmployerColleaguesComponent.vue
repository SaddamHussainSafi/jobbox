<script>
import { defineComponent } from 'vue'

export default defineComponent({
    props: {
        data: {
            type: Array,
            default: () => null,
        },
    },

    data() {
        return {
            items: [{ value: '' }],
        }
    },

    mounted() {
        let data = this.data
        if (data.length) {
            this.items = []
            for (const item of data) {
                this.items.push({ value: item })
            }
        }
    },

    methods: {
        addRow: function () {
            this.items.push({ value: '' })
        },
        deleteRow: function (index) {
            this.items.splice(index, 1)
        },
    },
})
</script>

<template>
    <slot v-bind="{ items, addRow, deleteRow }" />
</template>
