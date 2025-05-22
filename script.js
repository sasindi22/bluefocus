document.addEventListener("DOMContentLoaded", () => {
  const authForm = document.getElementById("authForm");
  const modalTitle = document.getElementById("modalTitle");

  const usernameField = document.getElementById("usernameField");
  const emailField = document.getElementById("emailField");
  const passwordField = document.getElementById("passwordField");
  const forgotPasswordLink = document.getElementById("forgotPasswordLink");

  const codeField = document.getElementById("codeField");
  const newPasswordField = document.getElementById("newPasswordField");
  const confirmNewPasswordField = document.getElementById(
    "confirmNewPasswordField"
  );

  const switchLink = document.getElementById("switchLink");
  const hiddenEmail = document.getElementById("hiddenEmail");

  let isLoggedIn = localStorage.getItem("isLoggedIn") === "true";

  const state = {
    mode: "login",
    stage: 1,
  };

  const show = (el) => el.classList.remove("d-none");
  const hide = (el) => el.classList.add("d-none");

  function updateAuthButtons() {
    const authBtns = document.getElementById("authButtons");
    const logoutBtn = document.getElementById("logoutButton");
    if (isLoggedIn) {
      authBtns.classList.add("d-none");
      logoutBtn.classList.remove("d-none");
    } else {
      authBtns.classList.remove("d-none");
      logoutBtn.classList.add("d-none");
    }
  }

  function setLoginView() {
    state.mode = "login";
    state.stage = 1;
    modalTitle.textContent = "Login";

    hide(usernameField);
    show(emailField);
    show(passwordField);
    hide(codeField);
    hide(newPasswordField);
    hide(confirmNewPasswordField);

    forgotPasswordLink.style.display = "block";
    switchLink.textContent = "Don't have an account? Register";
    switchLink.onclick = setRegisterView;

    authForm.reset();
  }

  function setRegisterView() {
    state.mode = "register";
    state.stage = 1;
    modalTitle.textContent = "Register";

    show(usernameField);
    show(emailField);
    show(passwordField);
    hide(codeField);
    hide(newPasswordField);
    hide(confirmNewPasswordField);

    forgotPasswordLink.style.display = "none";
    switchLink.textContent = "Already have an account? Login";
    switchLink.onclick = setLoginView;

    authForm.reset();
  }

  function setForgotStage1() {
    state.mode = "forgot";
    state.stage = 1;
    modalTitle.textContent = "Reset Password";

    hide(usernameField);
    show(emailField);
    hide(passwordField);
    hide(codeField);
    hide(newPasswordField);
    hide(confirmNewPasswordField);

    forgotPasswordLink.style.display = "none";
    switchLink.textContent = "Back to Login";
    switchLink.onclick = setLoginView;

    authForm.reset();
  }

  function setForgotStage2() {
    state.stage = 2;
    modalTitle.textContent = "Reset Password";

    hide(emailField);
    hide(passwordField);
    hide(usernameField);
    show(codeField);
    show(newPasswordField);
    show(confirmNewPasswordField);

    forgotPasswordLink.style.display = "none";
  }

  window.toggleAuth = () =>
    state.mode === "login" ? setRegisterView() : setLoginView();
  window.toggleForgotPassword = setForgotStage1;
  window.logout = () => {
    fetch("logout.php", { method: "POST" })
      .then((res) => res.json())
      .then((data) => {
        if (data.status === "success") {
          isLoggedIn = false;
          localStorage.removeItem("isLoggedIn");
          updateAuthButtons();
          alert("You have been logged out.");
        } else {
          alert("Logout failed on server.");
        }
      })
      .catch(() => alert("Network error during logout."));
  };

  authForm.addEventListener("submit", (evt) => {
    evt.preventDefault();

    if (state.mode === "forgot") {
      if (state.stage === 1) {
        const email = document.getElementById("email").value.trim();
        if (!email) return alert("Please enter your email.");

        const fd = new FormData();
        fd.append("email", email);

        fetch("send_reset_code.php", { method: "POST", body: fd })
          .then((r) => r.json())
          .then((d) => {
            alert(d.message || "Response received.");
            if (d.status === "success") {
              hiddenEmail.value = email;
              setForgotStage2();
            }
          })
          .catch(() => alert("Network error."));
        return;
      }

      if (state.stage === 2) {
        const code = document.getElementById("code").value.trim();
        const pw1 = document.getElementById("newPassword").value;
        const pw2 = document.getElementById("confirmNewPassword").value;

        if (!code) return alert("Enter the verification code.");
        if (!pw1 || !pw2) return alert("Enter the new password twice.");
        if (pw1 !== pw2) return alert("Passwords do not match.");

        const fd = new FormData();
        fd.append("email", hiddenEmail.value);
        fd.append("verification_code", code);
        fd.append("new_password", pw1);

        fetch("verify_reset_code.php", { method: "POST", body: fd })
          .then((r) => r.json())
          .then((d) => {
            alert(d.message || "Response received.");
            if (d.status === "success") setLoginView();
          })
          .catch(() => alert("Network error."));
        return;
      }
    }

    const url = state.mode === "login" ? "login.php" : "register.php";
    fetch(url, { method: "POST", body: new FormData(authForm) })
      .then((r) => r.json())
      .then((d) => {
        if (d.status !== "success")
          return alert(d.message || "Something went wrong.");

        if (state.mode === "login") {
          isLoggedIn = true;
          localStorage.setItem("isLoggedIn", "true");
          updateAuthButtons();
          alert("Login successful");
          bootstrap.Modal.getInstance(
            document.getElementById("authModal")
          ).hide();
        } else {
          alert("Registration complete — please log in.");
          setLoginView();
        }
      })
      .catch(() => alert("Network error."));
  });

  updateAuthButtons();
  setLoginView();
});

document.addEventListener("DOMContentLoaded", () => {
  const calendarConfig = {
    daysContainer: document.getElementById("calendar-days"),
    currentMonthEl: document.querySelector(".current-month"),
    calendarDay: document.querySelector(".calendar-day"),
    calendarMonth: document.querySelector(".calendar-month"),
    calendarYear: document.querySelector(".calendar-year"),
    prevBtn: document.querySelectorAll(".nav-btn")[0],
    nextBtn: document.querySelectorAll(".nav-btn")[1],
    monthNames: [
      "January",
      "February",
      "March",
      "April",
      "May",
      "June",
      "July",
      "August",
      "September",
      "October",
      "November",
      "December",
    ],
    today: new Date(),
    currentMonth: new Date().getMonth(),
    currentYear: new Date().getFullYear(),
    selectedDayCell: null,
  };

  function initCalendar() {
    calendarConfig.calendarDay.textContent = calendarConfig.today.getDate();
    calendarConfig.calendarMonth.textContent =
      calendarConfig.monthNames[calendarConfig.today.getMonth()];
    calendarConfig.calendarYear.textContent =
      calendarConfig.today.getFullYear();

    generateCalendar(calendarConfig.currentMonth, calendarConfig.currentYear);
    setupCalendarNavigation();
  }

  function generateCalendar(month, year) {
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const startDay = firstDay.getDay();
    const totalDays = lastDay.getDate();

    calendarConfig.daysContainer.innerHTML = "";

    for (let i = 0; i < 42; i++) {
      const dayCell = document.createElement("div");
      dayCell.classList.add("day-cell");

      const dateNum = i - startDay + 1;

      if (i >= startDay && dateNum <= totalDays) {
        dayCell.classList.add("in-month");
        dayCell.dataset.day = dateNum;

        const number = document.createElement("span");
        number.className = "day-number";
        number.textContent = dateNum;
        dayCell.appendChild(number);

        const taskContainer = document.createElement("div");
        taskContainer.classList.add("task-container");
        dayCell.appendChild(taskContainer);

        const isToday =
          dateNum === calendarConfig.today.getDate() &&
          month === calendarConfig.today.getMonth() &&
          year === calendarConfig.today.getFullYear();
        if (isToday) dayCell.classList.add("today");

        dayCell.addEventListener("click", () =>
          openTaskModal(dateNum, month, year, dayCell)
        );
      } else {
        dayCell.classList.add("empty");
      }
      calendarConfig.daysContainer.appendChild(dayCell);
    }
    calendarConfig.currentMonthEl.textContent = `${calendarConfig.monthNames[month]} (${year})`;
    loadTasks(year, month);
  }

  function openTaskModal(day, month, year, cell) {
    const modal = new bootstrap.Modal(document.getElementById("taskModal"));
    calendarConfig.selectedDayCell = cell;

    document.getElementById(
      "modal-date-display"
    ).textContent = `${day} ${calendarConfig.monthNames[month]} ${year}`;

    modal.show();
  }

  function setupCalendarNavigation() {
    calendarConfig.prevBtn.addEventListener("click", () => {
      calendarConfig.currentMonth--;
      if (calendarConfig.currentMonth < 0) {
        calendarConfig.currentMonth = 11;
        calendarConfig.currentYear--;
      }
      generateCalendar(calendarConfig.currentMonth, calendarConfig.currentYear);
    });

    calendarConfig.nextBtn.addEventListener("click", () => {
      calendarConfig.currentMonth++;
      if (calendarConfig.currentMonth > 11) {
        calendarConfig.currentMonth = 0;
        calendarConfig.currentYear++;
      }
      generateCalendar(calendarConfig.currentMonth, calendarConfig.currentYear);
    });
  }

  initCalendar();
});

const categoryColors = {
  work: "#83bad4",
  study: "#b6bbff",
  health: "#ffb6b6",
  leisure: "#a0d995",
};

document.getElementById("saveTask").addEventListener("click", () => {
  const title = document.getElementById("taskTitle").value.trim();
  const description = document.getElementById("taskDescription").value.trim();
  const startTime = document.getElementById("startTime").value.trim();
  const endTime = document.getElementById("endTime").value.trim();
  const category = document.getElementById("taskCategory").value.trim();
  const priority = parseInt(
    document.querySelector('input[name="priority"]:checked').value,
    10
  );
  const reminder = document.getElementById("taskReminder").checked ? 1 : 0;
  const date = document.getElementById("modal-date-display").textContent.trim();

  fetch("save_task.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      title,
      description,
      startTime,
      endTime,
      category,
      priority,
      reminder,
      date,
    }),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.status === "success") {
        alert("Task saved!");
        bootstrap.Modal.getInstance(
          document.getElementById("taskModal")
        ).hide();
      } else {
        alert("Failed to save task: " + data.message);
      }
    })
    .catch((err) => {
      console.error(err);
      alert("Something went wrong while saving the task.");
    });
});

function openTaskModal(dateString) {
  document.getElementById("modal-date-display").textContent = dateString;
  new bootstrap.Modal(document.getElementById("taskModal")).show();
}

function renderTask(task) {
  const el = document.createElement("div");
  el.classList.add("task-item");
  el.style.borderLeft = `4px solid ${categoryColors[task.category] || "#ccc"}`;
  el.textContent = task.title;
  return el;
}

document.getElementById("addCategoryBtn").addEventListener("click", () => {
  const input = document.getElementById("newCategoryInput");
  const select = document.getElementById("taskCategory");
  const help = document.getElementById("categoryHelp");
  const value = input.value.trim();

  if (!value) {
    help.textContent = "Category name cannot be empty.";
    help.classList.remove("d-none");
    return;
  }

  const exists = [...select.options].some(
    (opt) => opt.text.toLowerCase() === value.toLowerCase()
  );
  if (exists) {
    help.textContent = "Category already exists.";
    help.classList.remove("d-none");
    return;
  }

  const option = new Option(value, value.toLowerCase());
  select.add(option);
  select.value = option.value;
  input.value = "";
  help.classList.add("d-none");
});

function loadTasks(year, month) {
  fetch(`get_task.php?year=${year}&month=${month + 1}`)
    .then((res) => res.json())
    .then((tasks) => {
      document
        .querySelectorAll(".task-container")
        .forEach((c) => (c.innerHTML = ""));

      tasks.forEach((task) => {
        console.log("Loaded task object:", task);
        const day = new Date(task.date).getDate();
        const dayCell = document.querySelector(`.day-cell[data-day="${day}"]`);
        if (!dayCell) {
          console.warn(`No day cell for ${day}/${month + 1}/${year}`);
          return;
        }

        const taskContainer = dayCell.querySelector(".task-container");
        if (!taskContainer) return;

        const taskDiv = document.createElement("div");
        taskDiv.textContent = task.title;
        taskDiv.dataset.id = task.id;

        const color = categoryColors[task.category] || "#ccc";
        Object.assign(taskDiv.style, {
          backgroundColor: color,
          color: "#fff",
          padding: "2px 6px",
          marginBottom: "4px",
          borderRadius: "4px",
          fontSize: "0.85em",
          cursor: "pointer",
        });

        taskDiv.addEventListener("click", (e) => {
          console.log("Clicked task ID:", taskDiv.dataset.id);
          e.stopPropagation();
          showTaskDetails(taskDiv.dataset.id);
        });

        taskContainer.appendChild(taskDiv);
      });
    })
    .catch((err) => console.error("Failed to load tasks:", err));
}

function showTaskDetails(taskId) {
  fetch(`get_task_detail.php?task_id=${encodeURIComponent(taskId)}`)
    .then((res) => res.json())
    .then((task) => {
      console.log("Task detail received:", task);
      if (task.error) {
        alert(task.error);
        return;
      }

      document.getElementById("detail-date-lg").textContent = task.task_date;
      document.getElementById("detail-title-lg").textContent = task.title;
      document.getElementById("detail-desc-lg").textContent = task.description;
      document.getElementById("detail-start-lg").textContent = task.start_time;
      document.getElementById("detail-end-lg").textContent = task.end_time;
      document.getElementById("detail-cat-lg").textContent = task.category;

      document.getElementById("detail-date-sm").textContent = task.task_date;
      document.getElementById("detail-title-sm").textContent = task.title;
      document.getElementById("detail-desc-sm").textContent = task.description;
      document.getElementById("detail-start-sm").textContent = task.start_time;
      document.getElementById("detail-end-sm").textContent = task.end_time;
      document.getElementById("detail-cat-sm").textContent = task.category;
    })
    .catch((err) => {
      console.error("Failed to load task details:", err);
      alert("Could not retrieve task details.");
    });
}

let isEditingTodo = false;
let editingTodoElement = null;

document.addEventListener("DOMContentLoaded", () => {
  const modalEl = document.getElementById("todoModal");
  const bsModal = new bootstrap.Modal(modalEl);
  const saveBtn = document.getElementById("saveTodoBtn");
  const addBtns = document.querySelectorAll(".floating-add-btn");

  const listContainers = document.querySelectorAll("#todo-list-container");

  const statusClass = (id) =>
    ({ 1: "red", 2: "yellow", 3: "green" }[+id] || "grey");
  const priorityClass = (id) =>
    ({ 1: "high-priority", 2: "medium-priority", 3: "low-priority" }[+id] ||
    "no-priority");
  const hhmm = (t) => (t ? t.slice(0, 5) : "");

  function renderTodos(todos) {
    listContainers.forEach((c) => {
      if (c.offsetParent !== null) c.innerHTML = "";
    });

    todos.forEach((todo) => {
      const card = `
  <div class="task-card" data-id="${todo.id}">
    <div class="task-text">
      <span class="task-name">${todo.title}</span>
      <span class="time">(${hhmm(todo.start_time)} - ${hhmm(
        todo.end_time
      )})</span>
    </div>
    <div class="task-indicators">
      <span class="status-dot ${statusClass(todo.status_id)}"></span>
      <span class="status-dot ${priorityClass(todo.priority_id)}"></span>
      <button class="icon-btn delete-btn">🗑️</button>
    </div>
  </div>`;

      listContainers.forEach((c) => c.insertAdjacentHTML("beforeend", card));
    });
    attachDeleteHandlers();
  }

  function attachDeleteHandlers() {
    document.querySelectorAll(".delete-btn").forEach((btn) => {
      btn.addEventListener("click", async (e) => {
        const card = e.target.closest(".task-card");
        const todoId = card.getAttribute("data-id");

        const confirmed = confirm("Are you sure you want to delete this task?");
        if (!confirmed) return;

        try {
          const res = await fetch("delete_todo.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id: todoId }),
          });

          const data = await res.json();
          if (!res.ok || data.status !== "success") {
            throw new Error(data.message || "Failed to delete task");
          }

          loadTodos();
        } catch (err) {
          console.error("Delete failed:", err.message);
          alert("Failed to delete task – " + err.message);
        }
      });
    });
  }

  function openAddModal() {
    isEditingTodo = false;
    editingTodoElement = null;
    document.getElementById("todoTitle").value = "";
    document.querySelector(
      "input[name='todoStatus'][value='1']"
    ).checked = true;
    document.querySelector(
      "input[name='todoPriority'][value='3']"
    ).checked = true;
    document.getElementById("todoStartTime").value = "";
    document.getElementById("todoEndTime").value = "";
    document.getElementById("todoDate").value = "";
    bsModal.show();
  }
  addBtns.forEach((btn) => btn.addEventListener("click", openAddModal));

  saveBtn.addEventListener("click", async () => {
    const today = new Date().toISOString().slice(0, 10);
    document.getElementById("todoDate").value = today;

    const title = document.getElementById("todoTitle").value.trim();
    const startTime = document.getElementById("todoStartTime").value;
    const endTime = document.getElementById("todoEndTime").value;
    const date = document.getElementById("todoDate").value;
    const statusId = document.querySelector(
      "input[name='todoStatus']:checked"
    ).value;
    const priorityId = document.querySelector(
      "input[name='todoPriority']:checked"
    ).value;

    if (!title || !startTime || !endTime || !date) {
      alert("Title, date, start & end times are required.");
      return;
    }

    try {
      const payload = {
        title,
        status: Number(statusId),
        priority: Number(priorityId),
        date,
        startTime,
        endTime,
      };
      if (isEditingTodo && editingTodoElement)
        payload.id = editingTodoElement.dataset.id;

      const res = await fetch("save_todo.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload),
      });
      const json = await res.json();
      if (!res.ok || json.status !== "success")
        throw new Error(json.message || "Save failed");

      await loadTodos();
      bsModal.hide();
    } catch (err) {
      console.error(err);
      alert("Failed to save task – " + err.message);
    }
  });

  async function loadTodos() {
    try {
      const res = await fetch("get_todo.php");
      const json = await res.json();
      if (json.status !== "success")
        throw new Error(json.message || "Fetch error");
      renderTodos(json.todos);
    } catch (err) {
      console.error(err);
      listContainers.forEach((c) => {
        if (c.offsetParent !== null)
          c.innerHTML = `<p style="color:red;">${err.message}</p>`;
      });
    }
  }

  loadTodos();
});
