<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Shane Currie | Software Engineering Portfolio</title>
    <link rel="stylesheet" type="text/css" href="/styles.css">
    
    <!-- SEO Metadata -->
    <meta name="description" content="Explore Shane Currie's software engineering portfolio showcasing dynamic web apps and innovative coding solutions.">
    <meta name="keywords" content="Shane Currie, software engineering, portfolio, web development, PHP, MySQL, HTML, CSS, JavaScript">
    <meta name="author" content="Shane Currie">
    
    <!-- Open Graph Metadata for Social Media -->
    <meta property="og:title" content="Shane Currie | Software Engineering Portfolio">
    <meta property="og:description" content="Explore Shane Currie's software engineering projects, including dynamic web apps and innovative coding solutions.">
    <meta property="og:url" content="https://smcurrie.com">
    <!--<meta property="og:image" content="https://smcurrie.com/images/portfolio-banner.jpg">-->
    <meta property="og:type" content="website">
    <meta property="og:locale" content="en_CA">
    
    <!--Structured data markup SEO-->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Person",
      "name": "Shane Currie",
      "url": "https://smcurrie.com",
      "jobTitle": "Software Engineering Student",
      "description": "Explore Shane Currie's software engineering projects, including dynamic web apps, database management, and innovative programming solutions.",
      "sameAs": [
        "https://www.linkedin.com/in/shane-currie-24bb09293/",
        "https://github.com/ShaneC94/"
      ]
    }
    </script>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="container-nav">
            <a href="#projects">Projects</a>
            <a href="#about">About Me</a>
            <a href="#contact">Contact</a>
            <a href="#resume">Resume</a>
            <a href="https://github.com/ShaneC94/">GitHub</a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <h1>Shane Currie | Software Engineering Portfolio</h1>
        <p class="intro">
            Hi, I'm Shane, a third-year software engineering student with a passion for programming.<br>
            Take a look at some of my projects below!
        </p>
<br>

        <!-- Projects Section -->
        <section id="projects">
            <h2>Explore My University Projects</h2>
            <div class="project-container">
                <!-- Project 1: Little Italy -->
                <div class="project-card">
                    <h3>Little Italy</h3>
                    <p>Developed and deployed a dynamic restaurant website, "Little Italy," optimizing user experience across mobile and desktop devices. Integrated a MySQL database with PHP backend to securely store and manage user-submitted data.</p>
                    <a href="/LittleItaly/index.html" class="project-btn">View Project</a>
                </div>
        
                <!-- Project 2: OnMyWay -->
                <div class="project-card">
                    <h3>OnMyWay</h3>
                    <p>Led API integration efforts to incorporate weather data into a carpooling and commuting platform, providing real-time conditions and forecasts.</p>
                    <a href="https://github.com/ShaneC94/OnMyWay" class="project-btn">View Project</a>
                    <br><p>A cross-functional team project for my Data Management Systems course (3700U SOFE).</p><p>Awarded with Best Project Design - Fall 2024 semester.</p>
                </div>
                
                <!-- Project 3: To-Do List -->
                <div class="project-card">
                    <h3>To-Do List</h3>
                    <p>A simple, yet effective, to-do list application for managing daily tasks, with sorting functionality by priority or due date. Designed to improve productivity through streamlined task management, utilizing a clean and intuitive interface for adding, deleting, and managing tasks.</p>
                    <a href="/ToDoList/index.html" class="project-btn">View Project</a>
                </div>
            </div>
        </section>
<br><br>
        
        <!-- Upcoming Projects Section -->
        <section id="upcomingProjects">
            <h2>Explore My Personal Projects Under Development</h2>
            <div class="project-container">
                <!-- Project 1: Fantasy Football -->
                <div class="project-card">
                    <h3>Fantasy Football Tracker</h3>
                    <p>A fantasy football tracker for my league, integrated with the Sleeper API, and automating data synchronization with cron jobs. It displays transaction logs and weekly scores against the median, offering a streamlined view of league activity.</p>
                    <a href="/FFTracker/" class="project-btn">View Project</a>
                    <br><p>Future updates will include displaying the record of each team, weekly matchups, draft results, and much more!</p>
                </div>
                
                <!-- Project 2: Trip Planner -->
                <div class="project-card">
                    <h3>Trip Planner</h3>
                    <p>A road trip planning tool with weather API integration to help avoid bad weather along your route. Built with HTML, CSS, JavaScript, PHP, and SQL, and integrated with Mapbox for map visualization.</p>
                    <a href="/TripPlanner/" class="project-btn">View Project</a>
                    <br><p>Future updates will enhance backend functionality, utilize Business Intelligence technologies for data analytics, and expand the trip planning horizon.</p>
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
            <p>Interested in learning more about my skills and experience? Click the button below to download my resume.</p>
            <div class="contact-buttons">
            <a href="/Currie - Resume.pdf" download class="resume-btn">Download Resume</a>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; <span id="currentYear"></span> Shane Currie | All Rights Reserved</p>
    </footer>

    <!-- JavaScript -->
    <script>
        // Dynamically update the year in the footer
        document.getElementById('currentYear').textContent = new Date().getFullYear();
    </script>
</body>
</html>
