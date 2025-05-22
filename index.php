<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlueFocus</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Kodchasan&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="bootstrap.css">
</head>

<body>

    <header class="header">
        <div class="logo-area">
            <img src="resources/logo.png" alt="Logo" class="logo" />
            <span class="app-name">BlueFocus</span>
        </div>
        <div class="header-buttons">
            <div id="authButtons">
                <button data-bs-toggle="modal" data-bs-target="#authModal" onclick="switchAuth('login')">Login</button>
                <button data-bs-toggle="modal" data-bs-target="#authModal" onclick="switchAuth('register')">Register</button>
            </div>
            <div id="logoutButton" class="d-none">
                <button onclick="logout()">Logout</button>
            </div>
        </div>

    </header>

    <div class="main-container container-fluid">
        <div class="row w-100">

            <div class="col-lg-8 col-12 p-0">
                <main class="calendar-container h-100">

                    <div class="calendar-header">
                        <div class="calendar-date">
                            <span class="calendar-day">13</span>,
                            <span class="calendar-month">May</span>
                            <span class="calendar-year">2025</span>
                        </div>
                        <div class="calendar-nav">
                            <button class="nav-btn">‹</button>
                            <span class="current-month">May (2025)</span>
                            <button class="nav-btn">›</button>
                        </div>
                    </div>

                    <div class="calendar-weekdays">
                        <div>sun</div>
                        <div>mon</div>
                        <div>tue</div>
                        <div>wed</div>
                        <div>thu</div>
                        <div>fri</div>
                        <div>sat</div>
                    </div>

                    <div class="calendar-days" id="calendar-days">
                        <div class="day-cell" data-day="1">
                            <span class="day-number">1</span>
                            <div class="task-container"></div>
                        </div>
                    </div>

                </main>
            </div>

            <div class="col-lg-4 d-none d-lg-flex flex-column p-0">
                <div class="box fixed-height-box mb-2" id="task-details-section">
                    <aside class="info-container h-100">
                        <div>
                            <label class="todo-header"> Task Data </label>
                            <label>(select a task to view) </label><br />
                        </div>
                        <div id="task-details-content">
                            <label class="task-info">Date : <span id="detail-date-lg"></span></label><br />
                            <label class="task-info mt-1">Title : <span id="detail-title-lg"></span></label><br />
                            <label class="task-info mt-1">Description : <span id="detail-desc-lg"></span></label><br />
                            <label class="task-info mt-1">Start Time : <span id="detail-start-lg"></span></label><br />
                            <label class="task-info mt-1">End Time : <span id="detail-end-lg"></span></label><br />
                            <label class="task-info mt-1">Category : <span id="detail-cat-lg"></span></label>
                        </div>
                    </aside>
                </div>

                <div class="box flex-fill mt-2" id="todo-section">
                    <aside class="info-container h-100">
                        <div class="todo-header">To Do</div>
                        <div id="todo-list-container">
                            <label class="task-name"></label> <label class="time"></label>
                            <div class="task-indicators">
                                <span class="status-dot red"></span>
                                <span class="status-dot low-priority"></span>
                                <button class="icon-btn delete-btn">🗑️</button>
                            </div>
                        </div>
                        <button class="floating-add-btn" data-bs-toggle="modal" data-bs-target="#todoModal">+</button>
                    </aside>
                </div>

            </div>

            <div class="col-12 d-block d-lg-none p-0">
                <div class="box fixed-height-box mb-2" id="task-details-section">
                    <aside class="info-container h-100">
                        <div>
                            <label class="todo-header"> Task Data </label>
                            <label>(select a task to view) </label><br />
                        </div>
                        <div id="task-details-content">
                            <label class="task-info">Date : <span id="detail-date-sm"></span></label><br />
                            <label class="task-info mt-1">Title : <span id="detail-title-sm"></span></label><br />
                            <label class="task-info mt-1">Description : <span id="detail-desc-sm"></span></label><br />
                            <label class="task-info mt-1">Start Time : <span id="detail-start-sm"></span></label><br />
                            <label class="task-info mt-1">End Time : <span id="detail-end-sm"></span></label><br />
                            <label class="task-info mt-1">Category : <span id="detail-cat-sm"></span></label>
                        </div>
                    </aside>
                </div>

                <div class="box flex-fill mt-2" id="todo-section">
                    <aside class="info-container">
                        <div class="todo-header">To Do</div>
                        <div id="todo-list-container">
                            <label class="task-name"></label> <label class="time"></label>
                            <div class="task-indicators">
                                <span class="status-dot red"></span>
                                <span class="status-dot low-priority"></span>
                                <button class="icon-btn delete-btn">🗑️</button>
                            </div>
                        </div>
                        <button class="floating-add-btn" data-bs-toggle="modal" data-bs-target="#todoModal">+</button>
                    </aside>
                </div>

            </div>
        </div>

        <div class="modal fade" id="authModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content rounded-4 shadow">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Login</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form id="authForm">
                        <div class="modal-body">

                            <div id="usernameField" class="mb-3 d-none">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Enter username">
                            </div>

                            <div id="emailField" class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
                            </div>

                            <div id="passwordField" class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter password">
                            </div>

                            <input type="hidden" id="hiddenEmail" />

                            <div id="codeField" class="mb-3 d-none">
                                <label for="code" class="form-label">Verification Code</label>
                                <input type="text" class="form-control" id="code" name="code" placeholder="Enter the 6-digit code">
                            </div>

                            <div id="newPasswordField" class="mb-3 d-none">
                                <label for="newPassword" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="newPassword" name="newPassword" placeholder="Enter new password">
                            </div>

                            <div id="confirmNewPasswordField" class="mb-3 d-none">
                                <label for="confirmNewPassword" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="confirmNewPassword" name="confirmNewPassword" placeholder="Confirm new password">
                            </div>

                            <div class="form-text text-center">
                                <span id="forgotPasswordLink" class="text-forgot fw-semibold" role="button" onclick="toggleForgotPassword()">Forgot Password?</span>
                            </div>

                            <div class="form-text text-center">
                                <span id="switchLink" class="text-auth1 fw-semibold" role="button" onclick="toggleAuth()">Don't have an account? Register</span>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn-auth w-100">Continue</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="taskModalLabel">
                            Add Task - <span id="modal-date-display"></span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <label for="taskTitle" class="form-label fw-semibold">Task title <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control mb-1" id="taskTitle" placeholder="E.g. ‘Math Past Paper #3’"
                            aria-describedby="taskTitleHelp">
                        <small id="taskTitleHelp" class="invalid-feedback">Title is required.</small>

                        <label for="taskDescription" class="form-label fw-semibold mt-3">Description (optional)</label>
                        <textarea class="form-control" id="taskDescription" rows="3"
                            placeholder="Extra notes, page numbers, links …"></textarea>

                        <div class="row mt-3">
                            <div class="col-6">
                                <label for="startTime" class="form-label fw-semibold">Start</label>
                                <input type="time" class="form-control" id="startTime">
                            </div>
                            <div class="col-6">
                                <label for="endTime" class="form-label fw-semibold">End</label>
                                <input type="time" class="form-control" id="endTime">
                            </div>
                        </div>
                        <small id="timeError" class="text-danger d-none">End time must be after start time.</small>

                        <label for="taskCategory" class="form-label fw-semibold mt-3">Category</label>
                        <div class="input-group mb-2">
                            <select class="form-select" id="taskCategory">
                                <option selected disabled>Select category</option>
                                <option value="study">Study</option>
                                <option value="revision">Revision</option>
                                <option value="exam">Exam</option>
                                <option value="break">Break</option>
                            </select>
                        </div>
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" id="newCategoryInput" placeholder="Add new category">
                            <button class="btn btn-outline-secondary" type="button" id="addCategoryBtn" title="Add Category">+</button>
                        </div>

                        <label class="form-label fw-semibold mt-3">Priority</label>
                        <div class="d-flex flex-wrap gap-5 mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="priority" id="priorityLow" value="1" checked>
                                <label class="form-check-label" for="priorityLow">Low</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="priority" id="priorityMedium" value="2">
                                <label class="form-check-label" for="priorityMedium">Medium</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="priority" id="priorityHigh" value="3">
                                <label class="form-check-label" for="priorityHigh">High</label>
                            </div>
                        </div>

                        <div class="form-check form-switch mt-3">
                            <input class="form-check-input" type="checkbox" id="taskReminder">
                            <label class="form-check-label" for="taskReminder">Set reminder</label>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-auth w-100" id="saveTask">Save task</button>
                    </div>
                    <small id="modalMessage" class="text-success d-none mt-2"></small>
                </div>
            </div>
        </div>

        <div class="modal fade" id="todoModal" tabindex="-1" aria-labelledby="todoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 shadow">
                    <div class="modal-header">
                        <h5 class="modal-title" id="todoModalLabel">Add / Edit Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <label class="form-label fw-semibold">Task Title <span class="text-danger">*</span></label>
                        <input type="text" id="todoTitle" class="form-control mb-3" placeholder="E.g. 'Complete Homework'">

                        <label class="form-label fw-semibold">Status</label>
                        <div class="d-flex flex-wrap gap-3 mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="todoStatus" id="statusNotStarted"
                                    value="1">
                                <label class="form-check-label" for="statusNotStarted">Pending</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="todoStatus" id="statusInProgress"
                                    value="2">
                                <label class="form-check-label" for="statusInProgress">In Progress</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="todoStatus" id="statusDone" value="3">
                                <label class="form-check-label" for="statusDone">Completed</label>
                            </div>
                        </div>

                        <label class="form-label fw-semibold">Priority</label>
                        <div class="d-flex flex-wrap gap-3 mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="todoPriority" id="priorityLow" value="1">
                                <label class="form-check-label" for="priorityLow">Low</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="todoPriority" id="priorityMedium"
                                    value="2">
                                <label class="form-check-label" for="priorityMedium">Medium</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="todoPriority" id="priorityHigh" value="3">
                                <label class="form-check-label" for="priorityHigh">High</label>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label fw-semibold">Start Time</label>
                                <input type="time" class="form-control" id="todoStartTime">
                            </div>
                            <div class="col">
                                <label class="form-label fw-semibold">End Time</label>
                                <input type="time" class="form-control" id="todoEndTime">
                            </div>
                        </div>
                    </div>

                    <input type="hidden" id="todoDate" name="scheduled_date" value="">

                    <div class="modal-footer">
                        <button type="button" class="btn-auth w-100" id="saveTodoBtn">Save Task</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="script.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

</body>

</html>