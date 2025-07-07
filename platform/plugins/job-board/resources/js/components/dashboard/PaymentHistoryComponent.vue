<template>
    <slot v-bind="{ isLoading, isLoadingMore, data, getData }"></slot>
</template>

<script>
import { HalfCircleSpinner } from 'epic-spinners'
import axios from 'axios'

export default {
    components: {
        HalfCircleSpinner,
    },

    data() {
        return {
            isLoading: true,
            isLoadingMore: false,
            data: [],
        }
    },
    props: {
        url: {
            type: String,
            default: () => null,
            required: true,
        },
    },
    mounted() {
        this.getData()
    },

    methods: {
        getData(url = null) {
            if (url) {
                this.isLoadingMore = true
            } else {
                this.isLoading = true
            }
            axios.get(url || this.url).then((res) => {
                let oldData = []
                if (this.data.data) {
                    oldData = this.data.data
                }
                this.data = res.data
                this.data.data = oldData.concat(this.data.data)
                this.isLoading = false
                this.isLoadingMore = false
            })
        },
    },
}
</script>

