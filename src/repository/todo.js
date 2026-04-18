import api from "../services/api.js";

const TodoRepository = {
    getAll: async () => api("/todo"),
    create: async (values) => api('/todo', { method: 'POST', body: JSON.stringify(values) }),
    update: async (id, values) => api(`/todo/${id}`, { method: "PUT", body: JSON.stringify(values) }),
    remove: async (id) => api(`/todo/${id}`, { method: 'DELETE' }),
}

export default TodoRepository;