<template>
    <Head><title>ToDo List</title></Head>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">My ToDo List</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                    <div class="p-6 text-gray-900">
                        <div class="todoGrid">
                            <div>Is Done</div>
                            <div>ToDo Item</div>
                            <div></div>
                        </div>
                        <div v-for="todo in todos.data" class="todoGrid">
                            <div>
                                <input type="checkbox" :checked="todo.is_done" @click="changeDoneTodo(todo.id, $event)" />
                            </div>
                            <div :class="{ 'line-through' : todo.is_done}">{{ todo.title }}</div>
                            <div>
                                <button class="btn w-full rounded bg-red-50 text-center" @click="dellTodo(todo.id)">Delete</button>
                            </div>
                        </div>

                        <h5 class="mt-8">Add new ToDo Item</h5>
                        <div class="mt-1 todoGrid todoGrid--add">
                            <div>
                                <input class="w-full rounded" type="text" v-model="newTodoTitle">
                            </div>
                            <div>
                                <button class="btn w-full rounded bg-indigo-50 text-center" @click="addTodo">Add</button>
                            </div>
                        </div>
                        <div v-if="errorText" class="text-red-600">{{ errorText }}</div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>


<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/inertia-vue3';

export default {
    components: {
        Head,
        AuthenticatedLayout,
    },

    data: function () {
        return {
            newTodoTitle: '',
            errorText: '',
        }
    },

    props: [
        'todos'
    ],

    methods: {
        addTodo() {
            this.$inertia.post(route('dashboard.store'), {
                    title: this.newTodoTitle
                },
                {
                    onSuccess: (result) => {
                        this.errorText = ''
                        this.newTodoTitle = ''
                    },
                    onError: (result) => {
                        this.errorText = result.title
                        setTimeout(() => this.errorText = '', 5000)
                    }
                }
            )
        },

        dellTodo(id) {
            if (confirm('Are you sure ?')) {
                this.$inertia.delete(route('dashboard.destroy', {id}))
            }
        },

        changeDoneTodo(id, event) {
            if (confirm('Are you sure ?')) {
                this.$inertia.patch(route('dashboard.update', {id}), {is_done: event.target.checked})
            }
        }
    }
}
</script>


<style lang="scss">
.todoGrid {
    display: grid;
    grid-template-columns: 100px 1fr 200px;
    column-gap: 20px;

    font-size: 18px;
    padding: 6px 0;

    &--add {
        grid-template-columns: 1fr 200px;
    }

    .btn {
        height: 2.5rem;
    }
}

.todoGrid + .todoGrid {
    border-top: 1px solid lightblue;
}

</style>
