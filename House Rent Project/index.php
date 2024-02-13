<?php
include "include/main.php";
noLoginSession();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <!-- Tailwind CSS CDN link -->
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="styles.css" />
</head>

<body>
    <!-- hello world -->
    <!-- ---------------------------------------------------------------------------------------------------------------------------------------------------------naverbar  SECTION START FROM HERE --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
    <div class="relative w-full bg-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-2 sm:px-6 lg:px-8">
            <div class="inline-flex items-center space-x-2">
                <span class="font-bold">Tinder For Housing</span>
            </div>
            <div class="hidden grow items-start lg:flex">
                <ul class="ml-12 inline-flex space-x-8">
                    <li>
                        <a href="#home" class="inline-flex items-center text-sm font-semibold text-gray-800 hover:text-gray-900">
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="#about" class="inline-flex items-center text-sm font-semibold text-gray-800 hover:text-gray-900">
                            About Us
                        </a>
                    </li>
                    <li>
                        <a href="#contact" class="inline-flex items-center text-sm font-semibold text-gray-800 hover:text-gray-900">
                            Contact
                        </a>
                    </li>
                    <li>
                        <a href="#faq" class="inline-flex items-center text-sm font-semibold text-gray-800 hover:text-gray-900">
                            FAQs
                        </a>
                    </li>
                    <li>
                        <a href="questionnaire .php" class="inline-flex items-center text-sm font-semibold text-gray-800 hover:text-gray-900">
                            Questionnaire
                        </a>
                    </li>
                </ul>
            </div>
            <div class="hidden space-x-2 lg:block">
                <a href="signin.php">
                    <button type="button" class="rounded-md bg-transparent px-3 py-2 text-sm font-semibold text-black hover:bg-black/10 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black">
                        Log In
                    </button></a>
                <a href="signup.php">
                    <button type="button" class="rounded-md border border-black px-3 py-2 text-sm font-semibold text-black shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black">
                        Sign up
                    </button></a>
                <a href="admin.php">
            </div>
            <div class="lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6 cursor-pointer">
                    <line x1="4" y1="12" x2="20" y2="12"></line>
                    <line x1="4" y1="6" x2="20" y2="6"></line>
                    <line x1="4" y1="18" x2="20" y2="18"></line>
                </svg>
            </div>
        </div>
    </div>
    <!-- ---------------------------------------------------------------------------------------------------------------------------------------------------------HERO  SECTION START FROM HERE --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
    <div>
        <center><?php include './message.php'; ?></center>
    </div>
    <div class="hero" id="home">
        <div class="hero-content">
            <h1 class="hero-title bg-black">Find Your Perfect Home</h1>
            <p class="hero-description bg-black">
                Discover a seamless housing experience for students and landlords.
            </p>
            <div class="hero-buttons">
                <a href="signin.php"><button type="button" class="rounded-md bg-black px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-black/80 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black">
                        Login
                    </button></a>
                <a href="signup.php">
                    <button type="button" class="rounded-md bg-black px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-black/80 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black">
                        SignUp
                    </button></a>
            </div>
        </div>
    </div>

    <!-- ---------------------------------------------------------------------------------------------------------------------------------------------------------ABOUT US  SECTION START FROM HERE --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
    <div class="about-section" id="about">
        <div class="about-content">
            <h1 class="about-title">About Us</h1>
            <p class="about-description">
                We are dedicated to revolutionizing the way students find housing and
                landlords connect with tenants.
            </p>
            
        </div>
    </div>

    <!-- ---------------------------------------------------------------------------------------------------------------------------------------------------------CONTACT US  SECTION START FROM HERE --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
    <div class="contact-section" id="contact">
        <div class="contact-content">
            <h1 class="contact-title">Contact Us</h1>
            <p class="contact-description">
                Have any questions or suggestions? Feel free to get in touch with us!
            </p>
            <form class="contact-form" method="post">
                <input type="text" name="name" placeholder="Your Name" required class="form-input" />
                <input type="email" name="email" placeholder="Your Email" required class="form-input" />
                <textarea name="message" placeholder="Your Message" class="form-input" required rows="4"></textarea>
                <button type="submit" name="submit" class="form-submit">Send Message</button>
            </form>
        </div>
    </div>

    <!-- ---------------------------------------------------------------------------------------------------------------------------------------------------------FAQs   SECTION START FROM HERE --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

    <!-- Rest of the questions and answers -->

    <div class="container mx-auto px-4 py-8" id="faq">
        <h1 class="text-2xl font-bold mb-4">Frequently Asked Questions</h1>
        <div class="space-y-4">
            <div class="mb-4">
                <div class="faq-question" onclick="toggleAnswer(1)">
                    <span class="font-bold">1. What is the idea that you have for your app?</span>
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2 h-4 w-4 question-icon">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </span>
                </div>
                <div class="faq-answer" id="faq-answer-1">
                    The project idea is to create a platform to connect international
                    students and landlords for easy housing and tenant-finding.
                </div>
            </div>

            <div class="mb-4">
                <div class="faq-question" onclick="toggleAnswer(2)">
                    <span class="font-bold">2. What part is 2D and what part is 3D?</span>
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2 h-4 w-4 question-icon">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </span>
                </div>
                <div class="faq-answer" id="faq-answer-2">
                    <strong>Aspects in 2D:</strong>
                    <ul>
                        <li>
                            The app's general appearance, style, and navigation, including
                            buttons, icons, profiles, and chat windows, are all displayed in
                            a 2D format.
                        </li>
                        <li>
                            User profiles often housing and student information, images, and
                            bio sections in a 2D style.
                        </li>
                        <li>Text messaging.</li>
                    </ul>
                    <strong>Aspects in 3D:</strong>
                    <ul>
                        <li>
                            Profiles and might create student lounges to meet for finding
                            roommates.
                        </li>
                        <li>
                            I am still not sure if I will be putting up any other 3D
                            aspects.
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mb-4">
                <div class="faq-question" onclick="toggleAnswer(3)">
                    <span class="font-bold">3. What is your original, simple hypothesis about this app?</span>
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2 h-4 w-4 question-icon">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </span>
                </div>
                <div class="faq-answer" id="faq-answer-3">
                    Providing a streamlined platform for finding housing and improving
                    the overall experience for both students and landlords.
                </div>
            </div>

            <div class="mb-4">
                <div class="faq-question" onclick="toggleAnswer(4)">
                    <span class="font-bold">4. Why is this idea important or needed in the world?</span>
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2 h-4 w-4 question-icon">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </span>
                </div>
                <div class="faq-answer" id="faq-answer-4">
                    Housing is a basic life necessity, and in a world of housing crisis,
                    especially on an island like Victoria, we very much need a safe
                    space for both landlords and students to be able to find a living
                    space to survive. Scams are increasing day by day, and so is the
                    population of Victoria on both sides, which is the reason this
                    platform is super needed right now.
                </div>
            </div>

            <div class="mb-4">
                <div class="faq-question" onclick="toggleAnswer(5)">
                    <span class="font-bold">5. How do you plan to use XR emotionally to immerse the user in
                        the experience?</span>
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2 h-4 w-4 question-icon">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </span>
                </div>
                <div class="faq-answer" id="faq-answer-5">
                    To use Extended Reality (XR) to emotionally immerse users in the
                    experience of my platform, some of the basic ideas I have:
                    <ul>
                        <li>
                            <strong>Swiping:</strong> By providing the option of either
                            swiping left or right to the property and the people, on both
                            ends, we can give them the liberty and sense of freedom to
                            choose their own right fit in a fun and exciting way.
                        </li>
                        <li>
                            <strong>Registration and verification:</strong> Develop a
                            registration process that confirms both students' and landlords'
                            identities. This will aid in the establishment of trust and the
                            security of the platform.
                        </li>
                        <li>
                            <strong>Virtual property tools:</strong> Virtual property tours
                            to ensure students can digitally tour properties and interact
                            with them as if they were physically present. This can elicit
                            emotions and assist them in connecting with future living
                            settings.
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mb-4">
                <div class="faq-question" onclick="toggleAnswer(6)">
                    <span class="font-bold">6. List 3 reasons why this idea is or is not good for XR.</span>
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2 h-4 w-4 question-icon">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </span>
                </div>
                <div class="faq-answer" id="faq-answer-6">
                    <strong>3 reasons why this idea is good for XR:</strong>
                    <ul>
                        <li>Personalisation and Customisation</li>
                        <li>Social Interaction and Collaboration</li>
                        <li>Real-Time Communication</li>
                    </ul>
                </div>
            </div>

            <div class="mb-4">
                <div class="faq-question" onclick="toggleAnswer(7)">
                    <span class="font-bold">7. List 3 reasons why this idea is or is not better in 3D.</span>
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2 h-4 w-4 question-icon">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </span>
                </div>
                <div class="faq-answer" id="faq-answer-7">
                    <strong>3 reasons why this idea is better in 3D:</strong>
                    <ul>
                        <li>Uniqueness and flexibility</li>
                        <li>Interaction and Social relations</li>
                        <li>Engagement in actual time</li>
                    </ul>
                </div>
            </div>

            <div class="mb-4">
                <div class="faq-question" onclick="toggleAnswer(8)">
                    <span class="font-bold">8. In what way is each of MR and VR possibly useful for the
                        project?</span>
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2 h-4 w-4 question-icon">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </span>
                </div>
                <div class="faq-answer" id="faq-answer-8">
                    These technologies can offer unique benefits to my project:
                    <ul>
                        <li>
                            <strong>Mixed Reality (MR):</strong> MR can provide a
                            combination of real-world and digital experiences, allowing
                            users to visualize properties in the real world while
                            interacting with virtual elements. This can be useful for
                            virtual property tours, interactive mapping, and spatial
                            understanding.
                        </li>
                        <li>
                            <strong>Virtual Reality (VR):</strong> VR can create fully
                            immersive experiences where users can explore properties and
                            interact with virtual environments. This can be beneficial for
                            creating virtual property showrooms, simulating real-life
                            scenarios, and enhancing the overall user experience.
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mb-4">
                <div class="faq-question" onclick="toggleAnswer(9)">
                    <span class="font-bold">9. What is the current state of VR and AR for your app, and what
                        does your plan entail?</span>
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2 h-4 w-4 question-icon">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </span>
                </div>
                <div class="faq-answer" id="faq-answer-9">
                    The current state of VR and AR for my app is in the planning and
                    exploration phase. The plan entails:
                    <ul>
                        <li>
                            Researching and identifying the most suitable VR and AR
                            technologies and platforms for the project.
                        </li>
                        <li>
                            Collaborating with experts or development teams specializing in
                            VR and AR to leverage their expertise and ensure effective
                            implementation.
                        </li>
                        <li>
                            Designing and developing VR and AR features and functionalities
                            that align with the app's goals and enhance the user experience.
                        </li>
                        <li>
                            Testing and iterating on the VR and AR components to ensure
                            optimal performance and user satisfaction.
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mb-4">
                <div class="faq-question" onclick="toggleAnswer(10)">
                    <span class="font-bold">10. What key benefits are you aiming to achieve with XR
                        implementation?</span>
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2 h-4 w-4 question-icon">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </span>
                </div>
                <div class="faq-answer" id="faq-answer-10">
                    The key benefits of XR implementation in my app include:
                    <ul>
                        <li>
                            Enhanced user engagement and immersion through interactive and
                            visually appealing experiences.
                        </li>
                        <li>
                            Improved visualization and understanding of properties through
                            virtual tours and spatial mapping.
                        </li>
                        <li>
                            Streamlined and efficient communication between students and
                            landlords through XR-powered chat and collaboration features.
                        </li>
                        <li>
                            Increased trust and credibility by leveraging XR for virtual
                            property showcases and verification processes.
                        </li>
                    </ul>
                </div>
            </div>
            <div class="mb-4">
                <div class="faq-question" onclick="toggleAnswer(10)">
                    <span class="font-bold">11. Fill in an Empathy map (page 107) based on observation or your own
                        assumptions about your users.</span>
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2 h-4 w-4 question-icon">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </span>
                </div>
                <div class="faq-answer" id="faq-answer-10">
                    The key benefits of XR implementation in my app include:
                    <ul>
                        <li>
                            An empathy map is a visual representation of the user's thoughts, feelings, actions, and
                            needs. It helps in understanding the user's perspective and designing solutions that meet
                            their requirements. To create an empathy map, you can follow the steps outlined in page 107
                            of the resource material. It involves gathering insights about the users through
                            observation, interviews, or assumptions, and then mapping those insights onto the empathy
                            map.

                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
    <div class="footer">
        <p>&copy; 2023 Your Company. All rights reserved.</p>
    </div>
    <script src="index.js"></script>
</body>

</html>