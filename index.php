<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>My Portfolio</title>
    <link rel="stylesheet" type="text/css" href="/styles.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="container-nav">
            <a href="#projects">Projects</a>
            <a href="#about">About Me</a>
            <a href="#contact">Contact</a>
            <a href="#resume">Resume</a>
            <a href="https://github.com/ShaneC94/smcurrie">GitHub</a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <h1>Welcome to My Portfolio</h1>
        <p class="intro">
            Hi, I'm Shane, a third-year software engineering student with a passion for programming.<br>
            Take a look at some of my projects below!
        </p>
<br><br>

        <!-- Projects Section -->
        <section id="projects">
            <h2>Explore My Projects</h2>
            <div class="project-container">
                <!-- Project 1: Little Italy -->
                <div class="project-card">
                    <h3>Little Italy</h3>
                    <p>A restaurant website featuring menu displays, reservation systems, and contact forms, built with HTML, CSS, JavaScript, PHP, and SQL.</p>
                    <a href="/LittleItaly/index.html" class="project-btn">View Project</a>
                </div>
        
                <!-- Project 2: Trip Planner -->
                <div class="project-card">
                    <h3>Trip Planner</h3>
                    <p>A road trip planning tool that uses weather APIs to help avoid bad weather along your route, built with HTML, CSS, JavaScript, PHP, and SQL, and integrated with Mapbox for map visualization.</p>
                    <a href="/TripPlanner/index.php" class="project-btn">View Project</a>
                </div>
        
                <!-- Project 3: To-Do List -->
                <div class="project-card">
                    <h3>To-Do List</h3>
                    <p>A simple to-do list app for managing daily tasks, built with HTML, CSS, and JavaScript.</p>
                    <a href="/ToDoList/index.html" class="project-btn">View Project</a>
                </div>
                
                <!-- Project 4: OnMyWay -->
                <div class="project-card">
                    <h3>OnMyWay</h3>
                    <p>Led API integration efforts to incorporate weather data into a carpooling and commuting platform, providing real-time conditions and forecasts.</p>
                    <a href="https://github.com/ShaneC94/OnMyWay" class="project-btn">View Project</a>
                    <br><p>A group project for my Data Management Systems course (3700U SOFE). Awarded with Best Project Design - Fall 2024 semester.</p>
                </div>
            </div>
        </section>
<br><br>
        
        <!-- Upcoming Projects Section -->
        <section id="upcomingProjects">
            <h2>Explore My Projects I'm Currently Developing</h2>
            <div class="project-container">
                <!-- Project 1: Fantasy Football -->
                <div class="project-card">
                    <h3>Fantasy Football Tracker</h3>
                    <p>A fantasy football tracker for my league, developed using HTML, CSS, JavaScript, PHP, and SQL, and integrated with the Sleeper API. It displays transaction logs of roster changes and weekly matchups against the median, offering a streamlined view of league activity.</p>
                    <a href="/FFTracker/" class="project-btn">View Project</a>
                    <br><p>Future updates will include displaying the W-L-T of each team, the weekly matchup against another team, draft results displaying players picked with which draft pick, and much more!</p>
                </div>
            </div>
        </section>
<br><br>

        <!-- About Me Section -->
        <section id="about">
            <h2>About Me</h2>
            <p>
                I'm a Canadian Armed Forces veteran with <strong>5 years of experience as Military Police</strong>. My time in the forces taught me discipline, attention to detail, and leadership skills that I now apply to my studies as a software engineering student.
            </p>
            <p>
                Currently, I'm a <strong>third-year Software Engineering student at Ontario Tech University</strong>, based out of Toronto, Ontario. My focus is on developing strong technical foundations, including <strong>software development, database management, and system design</strong>, using tools like <strong>HTML, CSS, JavaScript, PHP, and MySQL</strong>.
            </p>
            <p>
                I'm seeking a <strong>summer internship/co-op position</strong> where I can contribute to meaningful projects, build versatile skills, and gain hands-on experience in software and data management.
            </p>
        </section>
<br><br>

        <!-- Contact Section -->
        <section id="contact">
            <h2>Contact Me</h2>
            <div class="contact-buttons">
                <a href="mailto:smcurrie24@gmail.com" class="email">ðŸ“§ Email Me</a>
                <a href="https://www.linkedin.com/in/shane-currie-24bb09293/" target="_blank" class="linkedin">ðŸ”— LinkedIn</a>
            </div>
        </section>
<br><br>
        
        <!-- Resume Section -->
        <section id="resume">
            <h2>Download My Resume</h2>
            <p>
                Interested in learning more about my skills and experience? Click the button below to download my resume.
            </p>
            <a href="/Currie - Resume.pdf" download class="resume-btn">Download Resume</a>
        </section>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Shane Currie | All Rights Reserved</p>
    </footer>
</body>
</html>
