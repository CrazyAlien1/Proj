<template>
    <div>

        <div>

            <div v-for="img in images">
                <div class="warning">
                    <img v-bind:src="'./storage/images/'+ img.path">
                    <small>{{img.path}}</small>
                </div>
            </div>

            <p>Select image to upload</p>
            <input type="file"  name="file" id="file">
        </div>

    </div>
</template>

<script>
    export default {

        data: function() {
            return {
                name: "upload-image",
                images: undefined,
            }
        },
        methods: {
            uploadImage(img){
                //send Laravel
            }
        },
        mounted() {

            axios.get('/images')
            .then((data) => {
                this.images = data.data;
                console.log(this.images);
            })
            .catch( (error) =>{
                console.log("[ERROR loading images]: ", error);
            });

        }
    }
</script>

<style scoped>

</style>