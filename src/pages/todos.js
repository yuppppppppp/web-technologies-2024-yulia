import Auth from "../services/auth.js";
import location from "../services/location.js";
import loading from "../services/loading.js";
import TodoRepository from "../repository/todo.js";

const init = async () => {
    const { ok: isLogged } = await Auth.me()

    if (!isLogged) {
        return location.login()
    } else {
        loading.stop()
    }

    // create POST /todo { description: string }
    // get get /todo/1 - 1 это id
    // getAll get /todo
    // update put /todo/1 - 1 это id { description: string }
    // delete delete /todo/1 - 1 это id

    const response = await TodoRepository.getAll();

    const todos = response.data;

    const main = document.querySelector("main.main");

    main.innerHTML = `
        <div class="todos-wrapper">
            <h1>Todos</h1>
            <form id="todo-form" class="todo-form">
                <input 
                    type="text" 
                    name="description" 
                    placeholder="Введите задачу"
                    required
                />
                <button type="submit">Добавить</button>
            </form>
            <div id="todo-list" class="todo-list"></div>
        </div>
    `;

    const todoList = document.getElementById("todo-list");

    const createTodoElement = (todo) => {
    const div = document.createElement("div");
    div.dataset.id = todo.id;
    div.innerHTML = `
        <input type="checkbox" ${todo.completed ? "checked" : ""} />
        <span style="${todo.completed ? "text-decoration: line-through" : ""}">${todo.description}</span>
        <button class="todo-delete-btn">Удалить</button>
    `;

    const checkbox = div.querySelector("input[type='checkbox']");
    checkbox.addEventListener("change", async () => {
        checkbox.checked = !checkbox.checked;
        const res = await TodoRepository.update(todo.id, { completed: !todo.completed });
        if (res.ok) {
            todo.completed = !todo.completed;
            checkbox.checked = todo.completed;
            div.querySelector("span").style.textDecoration = todo.completed ? "line-through" : "";
        }
    });

    const deleteBtn = div.querySelector(".todo-delete-btn");
    deleteBtn.addEventListener("click", async () => {
        const res = await TodoRepository.remove(todo.id);
        if (res.ok) div.remove();
    });
    return div;
};

todos.forEach(todo => todoList.appendChild(createTodoElement(todo)));

const form = document.getElementById("todo-form");
form.addEventListener("submit", async (event) => {
    event.preventDefault();
    const formData = new FormData(form);
    const response = await TodoRepository.create({ description: formData.get("description") });
    todoList.appendChild(createTodoElement(response.data));
    form.reset();
});

}
if (document.readyState === 'loading') {
    document.addEventListener("DOMContentLoaded", init)
} 
else {
    init()
}
