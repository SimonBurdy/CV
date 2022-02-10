<template>
    <div>

        <div class="row" v-if="comments">
            <div class="col-md-12">

                <h3>Commentaires ({{ comments.length }})</h3>


                <ckeditor :editor="editor" v-model="editorData" :config="editorConfig"></ckeditor>


                <div class="text-right" style="padding-top: 10px;">
                    <button type="button" @click="addComment" :disabled="!addable" class="btn btn-primary">Commenter
                    </button>
                </div>

                <div class="page-header">
                </div>
                <div class="comments-list">
                    <div :key="'comment'+comment.id" v-for="(comment,index) in comments" class="row">
                        <div class="col-auto">
                            <img class="rounded" :src="comment.user.gravatar+'?s=40'">
                        </div>
                        <div class="col-8 text-left">
                            <div class="py-3">
                                <strong>{{ comment.user.name }}</strong>
                            </div>


                            <div v-if="editedComment.id == comment.id">
                                <ckeditor :editor="editor" v-model="editedComment.body"
                                          :config="editorConfig"></ckeditor>
                                <div class="text-right" style="padding-top: 10px;">

                                    <button type="button" @click="updateComment(index)" :disabled="loading"
                                            class="btn btn-sm btn-primary">Modifier
                                    </button>
                                </div>
                            </div>

                            <div v-else v-html="comment.body"></div>

                            <p>
                                <small>
                                    <button v-if="comment.is_updatable" type="button"
                                            @click.prevent="editedComment = comment"
                                            class="btn btn-link btn-sm">Modifier
                                    </button>
                                    <button v-if="comment.is_updatable" type="button"
                                            @click.prevent="delComment(index)"
                                            class="btn btn-link btn-sm">Supprimer
                                    </button>
                                </small>
                            </p>
                        </div>
                        <div class="col-auto ml-auto text-right">
                            <small>{{ comment.created_at | dateParse('YYYY-MM-DD HH:mm:ss') | dateFormat('DD/MM/YYYY')
                                }}</small>
                        </div>
                        <!--                        <div class="ml-auto">-->
                        <!--                            <small>{{ comment.created_at  }}</small>-->
                        <!--                        </div>-->
                        <!--                        <a href="#">-->
                        <!--                            <img :src="comment.user.gravatar+'?s=40'">-->
                        <!--                        </a>-->

                    </div>
                </div>


            </div>
        </div>


        <div v-else>
            <i class="la la-spinner"></i> Chargement
        </div>
    </div>

</template>

<style>
    .user_name {
        font-size: 14px;
        font-weight: bold;
    }

    .comments-list .row {
        border-bottom: 1px dotted #ccc;
    }

    .atwho-li {
        font-weight: bold;
    }

    .atwho-li img {
        margin-right: 5px;
    }



    .tag {
        font-weight: bold;
    }
</style>


<script>
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

    export default {
        name: 'Comments',
        data() {
            return {
                loading: false,
                // tributeOptions:{
                //     // column to search against in the object (accepts function or string)


                editor: ClassicEditor,
                editorData: '',
                editorConfig: {
                    toolbar: ['bold', 'italic', '|', 'link'],
                },
                comments: '',
                team: [],
                editedComment: {}

            }
        },
        components: {},
        computed: {
            addable() {
                return !this.loading && this.editorData
            }
        },
        watch: {
            comments(newVal, oldVal) {
                if (oldVal) {
                    this.$emit('updated', {
                        comments: newVal
                    })
                }
            }
        },
        props: {
            modelClass: {
                required: true
            },
            modelId: {
                required: true
            },
            originalComments: {
                required: false
            }
        },
        methods: {
            getComments() {

                // sinon on load
                this.loading = true
                this.$http.get('comments', {
                    params: {
                        modelClass: this.modelClass,
                        modelId: this.modelId
                    }
                }).then((response) => {
                    this.comments = response
                })
                    .catch((err) => {
                        console.error('get comments', err)
                    })
                    .finally(() => {
                        this.loading = false
                    })
            },
            addComment() {
                this.loading = true
                this.$http.post('comments',
                    {
                        modelClass: this.modelClass,
                        modelId: this.modelId,
                        body: this.editorData
                    }
                ).then((response) => {
                    this.editorData = ''
                    this.comments.unshift(response)
                }).catch((error) => {
                    console.error('post comments', error)
                }).finally(() => {
                    this.loading = false
                })
            },
            updateComment(index) {
                this.loading = true
                this.$http.put('comments/' + this.editedComment.id, {
                    body: this.editedComment.body
                }).then((response) => {
                    this.editedComment = {}
                    this.$set(this.comments, index, response)
                }).catch((error) => {
                    console.error('put comments', error)
                }).finally(() => {
                    this.loading = false
                })
            },
            delComment(index) {
                if (confirm('Supprimer ce commentaire ? Ceci est irréversible')) {
                    this.loading = true
                    this.$http.delete('comments/' + this.comments[index].id)
                        .then((response) => {
                            this.comments.splice(index, 1)
                        })
                        .catch((error) => {
                            console.error('del comments', error)
                        })
                        .finally(() => {
                            this.loading = false
                        })
                }
            },

            // getTeam() {
            //     this.loading = true
            //     this.$http.get('team')
            //         .then((response) => {
            //             this.loading = false
            //             this.team = response
            //         })
            //         .catch((error) => {
            //             this.loading = false
            //             console.error('get team', error)
            //         })
            // },


        },
        mounted() {
            this.getComments()
            // this.getTeam()
        }
    }
</script>
