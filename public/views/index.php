<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NetProbe - Projects</title>
    <script src="https://code.jquery.com/jquery-4.0.0.js"
        integrity="sha256-9fsHeVnKBvqh3FB2HYu7g2xseAZ5MlN6Kz/qnkASV8U=" crossorigin="anonymous"></script>
    <script src="../script/tab.js"></script>
</head>

<body>
    <aside class="sidebar">
        <header class="title">
            <h1>NetProbe</h1>
        </header>
        <section class="projects">
            <div class="label">
                <h2>Projects</h2>
                <button id="add_new_project">Add New Project</button>
            </div>
            <nav class="lists">
                <a class="item"></a>
            </nav>
        </section>
        <section class="tasks">
            <div class="label">
                <h2>Tasks</h2>
                <button id="add_new_project">Add New Tasks</button>
            </div>
            <nav class="lists">
                <a class="item"></a>
            </nav>
        </section>
    </aside>
    <main>
        <section class="information">
            <h3 class="title" id="project_name"></h3>
            <h4 id="task_name"></h4>
        </section>
        <section class="parameter">
            <nav class="tabs">
                <a href="?t=header">Header</a>
                <a href="?t=query">Query</a>
                <a href="?t=body">Body</a>
            </nav>
        </section>
    </main>
</body>
</html>