<script setup>
import { ref, onMounted } from 'vue';
import Todo from '@/components/todos/Todo.vue';
import TodoCreator from '@/components/todos/TodoCreator.vue';
import { fetchTodos, addTodo, updateTodo, fetchTodoById } from '@/services/api.js';

const todos = ref([]);

onMounted(async () => {
    todos.value = await fetchTodos();
});

const handleAddTodo = async (todo) => {
    const newTodo = await addTodo(todo);
    if (!todo.parent_id) {
        todos.value.unshift(newTodo);
    } else {
        const parentTodo = todos.value.find((item, index) => item.id == newTodo.parent_id)

        if (parentTodo) {
            parentTodo.subtasks.unshift(newTodo)
        }
    }
};

/**
 * Side effects:
 * If parent is set to 'complete', all subtask should be completed
 * If subtask is set as in progress, parent should be set as in progress
 * @param {*} data 
 */
const handleUpdateTodo = async (data) => {
    const updatedTodo = await updateTodo(data.id, data.status)
    const isParent = updatedTodo.parent_id == null
    const affectedIid = isParent ? updatedTodo.id : updatedTodo.parent_id
    let affectedIndex = null
    
    todos.value.find((item, index) => {
        if (item.id == affectedIid) {
            affectedIndex = index    
            return true;
        }
    })

    // If parent, fully replace todo in case subtasks are affected
    // If subtask, refetch the parent and replace in case it was affects
    if (isParent && affectedIndex !== null) {
        todos.value[affectedIndex] = updatedTodo
    } else if (!isParent && affectedIid !== null) {
        // refetch only if parent is not in progress and the affected subtask was set to in progress
        if (updatedTodo.status === 'in progress' && todos.value[affectedIndex].status !== 'in progress') {
            const refreshedTodo = await fetchTodoById(affectedIid)
            todos.value[affectedIndex] = refreshedTodo
        } else {
            const affectedSubtask = todos.value[affectedIndex].subtasks.find(item => item.id == updatedTodo.id)
            affectedSubtask.status = updatedTodo.status
        }
    }
};

</script>

<template>
    <div>
        <TodoCreator @todo-created="handleAddTodo" />
        <ul>
            <li v-for="todo in todos" :key="todo.id">
                <Todo :todo="todo" @todo-created="handleAddTodo" @todo-updated="handleUpdateTodo"/>
            </li>
        </ul>
    </div>
</template>
