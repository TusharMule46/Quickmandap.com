let currentPackage = "";

document.addEventListener('DOMContentLoaded', function() {
        // ✅ Book from Modal with login check
    window.bookFromModal = function() {
        fetch('check_login.php')
            .then(response => response.json())
            .then(data => {
                if (data.loggedIn) {
                    window.location.href = `book.php?package=${currentPackage}`;
                } else {
                    window.location.href = "login.php";
                }
            })
            .catch(error => {
                console.error("Error checking login status:", error);
            });
    };

    // ✅ Book directly from package button
    window.bookNow = function(packageName) {
        fetch('check_login.php')
            .then(response => response.json())
            .then(data => {
                if (data.loggedIn) {
                    window.location.href = `book.php?package=${packageName}`;
                } else {
                    window.location.href = "login.php";
                }
            })
            .catch(error => {
                console.error("Error checking login status:", error);
            });
    };

    // Declare globally so it's accessible across modal functions
    let currentPackage = '';

    // Mobile Navigation Toggle
    const hamburger = document.querySelector('.hamburger');
    const navLinks = document.querySelector('.nav-links');
    const authButtons = document.querySelector('.auth-buttons');
    
    if (hamburger) {
        hamburger.addEventListener('click', function() {
            this.classList.toggle('active');
            
            if (!document.querySelector('.mobile-menu')) {
                const mobileMenu = document.createElement('div');
                mobileMenu.classList.add('mobile-menu');
                
                const navClone = navLinks.cloneNode(true);
                const authClone = authButtons.cloneNode(true);
                
                mobileMenu.appendChild(navClone);
                mobileMenu.appendChild(authClone);
                document.body.appendChild(mobileMenu);
                
                const style = document.createElement('style');
                style.textContent = `
                    .mobile-menu {
                        position: fixed;
                        top: 70px;
                        left: 0;
                        width: 100%;
                        background-color: white;
                        padding: 20px;
                        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
                        z-index: 999;
                        display: none;
                    }
                    
                    .mobile-menu.active {
                        display: block;
                    }
                    
                    .mobile-menu .nav-links {
                        display: flex;
                        flex-direction: column;
                        width: 100%;
                    }
                    
                    .mobile-menu .nav-links li {
                        margin: 10px 0;
                    }
                    
                    .mobile-menu .auth-buttons {
                        display: flex;
                        flex-direction: column;
                        width: 100%;
                        margin-left: 0;
                        margin-top: 20px;
                    }
                    
                    .mobile-menu .auth-buttons .btn {
                        margin: 5px 0;
                    }
                    
                    .hamburger.active span:nth-child(1) {
                        transform: rotate(45deg) translate(5px, 5px);
                    }
                    
                    .hamburger.active span:nth-child(2) {
                        opacity: 0;
                    }
                    
                    .hamburger.active span:nth-child(3) {
                        transform: rotate(-45deg) translate(7px, -6px);
                    }
                `;
                
                document.head.appendChild(style);
            }
            
            const mobileMenu = document.querySelector('.mobile-menu');
            mobileMenu.classList.toggle('active');
        });
    }

    
    // ✅ Open Modal and set current package
    window.openModal = function(packageName) {
        currentPackage = packageName;

        if (window.packageDetails && packageDetails[packageName]) {
            document.getElementById("modalTitle").textContent = packageDetails[packageName].title;
            document.getElementById("modalPrice").textContent = packageDetails[packageName].price;
        }

        document.getElementById("packageModal").style.display = "block";
    };

    // Testimonial Slider
    const testimonials = document.querySelectorAll('.testimonial-card');
    const dots = document.querySelectorAll('.dot');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    
    if (testimonials.length > 0) {
        let currentIndex = 0;
        
        function showTestimonial(index) {
            testimonials.forEach(testimonial => testimonial.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));
            
            testimonials[index].classList.add('active');
            dots[index].classList.add('active');
            currentIndex = index;
        }
        
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => showTestimonial(index));
        });
        
        if (prevBtn && nextBtn) {
            prevBtn.addEventListener('click', () => {
                let index = currentIndex - 1;
                if (index < 0) index = testimonials.length - 1;
                showTestimonial(index);
            });
            
            nextBtn.addEventListener('click', () => {
                let index = currentIndex + 1;
                if (index >= testimonials.length) index = 0;
                showTestimonial(index);
            });
        }
        
        setInterval(() => {
            let index = currentIndex + 1;
            if (index >= testimonials.length) index = 0;
            showTestimonial(index);
        }, 5000);
    }
    
    // Sticky Header
    const header = document.querySelector('header');
    
    if (header) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                header.style.padding = '10px 0';
                header.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
            } else {
                header.style.padding = '15px 0';
                header.style.boxShadow = '0 5px 15px rgba(0, 0, 0, 0.1)';
            }
        });
    }
    
    // Smooth scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });
    });
});
