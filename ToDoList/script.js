// Color reminder effect
document.addEventListener('DOMContentLoaded', function () {
    const colors = ['#DB2B39', '#399E5A', '#F40076', '#20A4F3', '#E36414', '#0F4C5C'];
    let currentColorIndex = 0;
    const reminderElement = document.getElementById('reminder');

    setInterval(() => {
        reminderElement.style.color = colors[currentColorIndex];
        currentColorIndex = (currentColorIndex + 1) % colors.length;
    }, 2000);

    initializeApp();
});

// Initialize App
function initializeApp() {
    document.getElementById('add-task-btn').addEventListener('click', addTask);
    renderTasks();
}

// Add Task
function addTask() {
    const taskInput = document.getElementById('task');
    const dueDateInput = document.getElementById('dueDate');
    const priorityInput = document.getElementById('priority');

    const taskText = taskInput.value.trim();
    const dueDate = dueDateInput.value;
    const priority = priorityInput.value;

    if (!validateTask(taskText, dueDate)) return;

    const task = {
        id: Date.now(),
        text: taskText,
        dueDate: dueDate,
        priority: priority,
        completed: false
    };

    saveTask(task);
    clearInputFields();
    renderTasks();
}

// Validate Task Inputs
function validateTask(taskText, dueDate) {
    if (taskText === '') {
        alert('Task description cannot be empty!');
        return false;
    }
    if (dueDate === '') {
        alert('Please select a due date!');
        return false;
    }
    return true;
}

// Save Task to Local Storage
function saveTask(task) {
    const tasks = getTasks();
    tasks.push(task);
    localStorage.setItem('tasks', JSON.stringify(tasks));
}

// Get Tasks from Local Storage
function getTasks() {
    return JSON.parse(localStorage.getItem('tasks')) || [];
}

// Render Tasks
function renderTasks() {
    const taskList = document.getElementById('tasks');
    taskList.innerHTML = '';

    const tasks = getTasks();
    tasks.sort((a, b) => priorityValue(b.priority) - priorityValue(a.priority));

    tasks.forEach(task => {
        taskList.appendChild(createTaskElement(task));
    });
}

// Create Task HTML Element
function createTaskElement(task) {
    const li = document.createElement('li');
    li.classList.add('task-item');
    li.innerHTML = `
        <div>
            <span class="task-text ${task.priority} ${task.completed ? 'completed' : ''}">${task.text}</span>
            <span>Due: ${task.dueDate} | Priority: <strong>${capitalize(task.priority)}</strong></span>
        </div>
        <div>
            <button onclick="toggleComplete(${task.id})">Complete</button>
            <button onclick="removeTask(${task.id})">Remove</button>
        </div>
    `;
    return li;
}

// Toggle Task Completion
function toggleComplete(id) {
    let tasks = getTasks();
    tasks = tasks.map(task => {
        if (task.id === id) task.completed = !task.completed;
        return task;
    });
    localStorage.setItem('tasks', JSON.stringify(tasks));
    renderTasks();
}

// Remove Task
function removeTask(id) {
    let tasks = getTasks();
    tasks = tasks.filter(task => task.id !== id);
    localStorage.setItem('tasks', JSON.stringify(tasks));
    renderTasks();
}

// Clear Input Fields
function clearInputFields() {
    document.getElementById('task').value = '';
    document.getElementById('dueDate').value = '';
    document.getElementById('priority').value = 'low';
}

// Utility: Priority Sorting
function priorityValue(priority) {
    return { 'low': 1, 'medium': 2, 'high': 3 }[priority];
}

// Utility: Capitalize String
function capitalize(text) {
    return text.charAt(0).toUpperCase() + text.slice(1);
}
