<?php 
$pageTitle = "About Us";
include 'header.php';
?>

<style>
    .container {
        max-width: 1400px;
        margin: 50px auto;
        padding: 0 20px;
    }

    .about-section {
        background-color: #f8f9fa;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        margin-top: 20px;
    }

    .about-section h2 {
        color: #333;
        margin-bottom: 20px;
        font-family: 'Poppins', sans-serif;
        font-size: 2em;
    }

    .about-section p {
        color: #666;
        line-height: 1.6;
        font-size: 1.1em;
        margin-bottom: 15px;
    }

    @keyframes highlightPulse {
        0% { background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); }
        50% { background: linear-gradient(135deg, #FFEDFA 0%, #c3cfe2 100%); }
        100% { background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); }
    }

    .highlight-animation {
        animation: highlightPulse 1s ease;
    }

    /* Responsive Design */
    @media screen and (max-width: 768px) {
        .container {
            margin: 30px auto;
        }

        .about-section {
            padding: 20px;
        }

        .about-section h2 {
            font-size: 1.5em;
        }

        .about-section p {
            font-size: 1em;
        }
    }
</style>

<div class="container">
    <div class="about-section">
        <h2>About Riddhi Siddhi Enterprises</h2>
        <p>Welcome to Riddhi Siddhi Enterprises, your trusted partner in industrial excellence. With years of experience in the field, we specialize in providing high-quality industrial products and solutions that meet the diverse needs of our clients.</p>
        <p>Our commitment to quality, reliability, and customer satisfaction has made us a leading name in the industry. We take pride in offering innovative solutions that help businesses optimize their operations and achieve their goals.</p>
        <p>At Riddhi Siddhi Enterprises, we believe in building long-term relationships with our clients through transparent business practices and exceptional service. Our team of experts is always ready to assist you in finding the perfect solutions for your industrial needs.</p>
    </div>
</div>

<script>
    function highlightAbout() {
        const aboutParagraph = document.querySelector('.about-section p');
        if (aboutParagraph) {
            // Add a highlight animation class
            aboutParagraph.classList.add('highlight-animation');
            
            // Remove the animation class after it completes
            setTimeout(() => {
                aboutParagraph.classList.remove('highlight-animation');
            }, 1000);

            // Scroll to the about section
            aboutParagraph.scrollIntoView({ behavior: 'smooth' });
        } else {
            console.error('About paragraph not found');
        }
    }
</script>

    <div class="container">
        <div class="about-section">
            
            <h1 style="font-family: 'Montserrat', sans-serif; background: linear-gradient(45deg, #2D336B, #4B0082); color: white; padding: 10px 20px; border-radius: 8px;">About Us</h1>
            <img src="image/blue hand gloves.png" alt="Blue Industrial Hand Gloves" style="float: left; width: 300px; height: auto; margin: 0 20px 20px 0; animation: floatAndRotate 3s ease-in-out infinite;">
            <style>
                @keyframes floatAndRotate {
                    0% {
                        transform: translateY(0) rotate(0);
                        filter: brightness(1);
                    }
                    50% {
                        transform: translateY(-10px) rotate(5deg);
                        filter: brightness(1.1);
                    }
                    100% {
                        transform: translateY(0) rotate(0);
                        filter: brightness(1);
                    }
                }
            </style>
            
            <p style="font-family: 'Montserrat', sans-serif; font-size: 20px; color:black; line-height: 1.6; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); padding: 20px;padding-top: 30px; border-radius: 10px;">At Riddhi Siddhi Enterprises, we specialize in manufacturing high-quality hand gloves and protective sleeves designed for industrial workers. Our products cater to various industries, ensuring safety, comfort, and durability in every use.

                We understand the diverse needs of workplaces, which is why we offer gloves in different qualities—ranging from disposable, use-and-throw options to durable, long-lasting varieties. Our protective sleeves provide additional safety, making them essential for hazardous work environments.
                
                With a commitment to excellence and customer satisfaction, we continuously innovate to provide the best protective gear that meets industry standards. Whether you need gloves for heavy-duty industrial work or lightweight disposable options, we have the perfect solution for your business.
                
                Trust Riddhi Siddhi Enterprises to keep your hands safe while you focus on the work that matters.</p>
        </div>
    </div>

    <script>
        // Add smooth scrolling for navigation links
        document.querySelectorAll('nav a').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                // Only prevent default for hash links (#)
                if (this.getAttribute('href').startsWith('#')) {
                    e.preventDefault();
                    const section = document.querySelector(this.getAttribute('href'));
                    if(section) {
                        section.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                }
            });
        });
        // Create and append products section
        // Add image preloading
        // Preload images
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('button').forEach(button => {
                button.addEventListener('click', function() {
                    if(this.textContent === 'Add to Cart') {
                        alert('Product added to cart successfully!');
                    } else if(this.textContent === 'Buy Now') {
                        window.location.href = 'checkout.html';
                    }
                });
            });
        });
        const productsSection = document.createElement('div');
        productsSection.className = 'products-section';
        productsSection.innerHTML = `
            <style>
                .featured-banner {
                    background: linear-gradient(45deg, #2D336B, #4B0082);
                    color: white;
                    padding: 15px;
                    text-align: center;
                    border-radius: 8px;
                    margin-bottom: 30px;
                    font-family: 'Montserrat', sans-serif;
                    animation: bannerGlow 2s infinite;
                }

                @keyframes bannerGlow {
                    0% { box-shadow: 0 0 5px rgba(45, 51, 107, 0.5); }
                    50% { box-shadow: 0 0 20px rgba(45, 51, 107, 0.8); }
                    100% { box-shadow: 0 0 5px rgba(45, 51, 107, 0.5); }
                }

                .featured-banner i {
                    margin-right: 10px;
                    animation: bounce 1s infinite;
                }

                @keyframes bounce {
                    0%, 100% { transform: translateY(0); }
                    50% { transform: translateY(-5px); }
                }
            </style>
            <div class="featured-banner">
                <i class="fas fa-star"></i>
                Featured Products - Special Discount Up to 20% Off!
            </div>
            <h1 style="font-family: 'Montserrat', sans-serif; background: linear-gradient(45deg, #2D336B, #4B0082); color: white; padding: 10px 20px; border-radius: 8px; margin-top: 40px;">Our Products</h1>
            <style>
                .products-section > div > div {
                    transition: transform 0.3s ease, box-shadow 0.3s ease;
                }
                
                .products-section > div > div:hover {
                    transform: translateY(-10px);
                    box-shadow: 0 10px 20px rgba(45, 51, 107, 0.2);
                }

                .products-section > div > div:hover button {
                    transform: scale(1.05);
                }

                .products-section button {
                    transition: transform 0.2s ease;
                }
            </style>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; padding: 20px; background-color: rgba(248, 249, 250, 0.9); border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); backdrop-filter: blur(5px); position: relative;">
                 <style>
                button {
                    transition: all 0.3s ease !important;
                    position: relative;
                    overflow: hidden;
                }

                button:hover {
                    transform: translateY(-3px) scale(1.05) !important;
                    box-shadow: 0 5px 15px rgba(45, 51, 107, 0.4) !important;
                }

                button:active {
                    transform: translateY(1px) scale(0.98) !important;
                }

                button::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: -100%;
                    width: 100%;
                    height: 100%;
                    background: linear-gradient(
                        120deg,
                        transparent,
                        rgba(255, 255, 255, 0.2),
                        transparent
                    );
                    transition: 0.5s;
                }

                button:hover::before {
                    left: 100%;
                }

                button:hover {
                    background: linear-gradient(45deg, #2D336B, #4B0082) !important;
                    color: white !important;
                    letter-spacing: 0.5px;
                }
            </style>
                <div style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); padding: 20px; border-radius: 10px; text-align: center; height: 100%; display: flex; flex-direction: column;">
                    <img src="image/blueHand.png" alt="Safety Gloves" style="max-width: 200px; height: auto; border-radius: 8px; margin: 0 auto; transition: all 0.5s ease;" onmouseover="this.style.transform='translateY(-10px) scale(1.1)'; this.style.filter='drop-shadow(0 10px 15px rgba(45, 51, 107, 0.3))'" onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.filter='none'">
                    <h3 style="font-family: 'Montserrat', sans-serif; color: #2D336B; margin: 15px 0;">Safety Gloves</h3>
                    <p style="font-family: 'Montserrat', sans-serif; margin-bottom: 20px; flex-grow: 1;">Heavy-duty safety gloves with reinforced palm grip. Ideal for industrial and construction work.</p>
                    <div style="display: flex; gap: 10px; justify-content: center; margin-top: auto;">
                        <button style="background: #2D336B; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">Buy Now</button>
                        <button style="background: #4B0082; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">Add to Cart</button>
                    </div>
                </div>
                <div style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); padding: 20px; border-radius: 10px; text-align: center;">
                    <img src="image/blue Hand Gloves.png" alt="Blue Threaded Hand Gloves" style="max-width: 200px; height: auto; border-radius: 8px; transition: all 0.5s ease;" onmouseover="this.style.transform='translateY(-10px) scale(1.1)'; this.style.filter='drop-shadow(0 10px 15px rgba(45, 51, 107, 0.3))'" onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.filter='none'">
                    <h3 style="font-family: 'Montserrat', sans-serif; color: #2D336B; margin: 15px 0;">Blue Threaded Hand Gloves</h3>
                    <p style="font-family: 'Montserrat', sans-serif; margin-bottom: 20px;">Premium quality blue threaded hand gloves perfect for industrial use. Provides excellent grip and protection.</p>
                    <div style="display: flex; gap: 10px; justify-content: center;">
                        <button style="background: #2D336B; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">Buy Now</button>
                        <button style="background: #4B0082; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">Add to Cart</button>
                    </div>
                </div>
                
                <div style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); padding: 20px; border-radius: 10px; text-align: center;">
                    <img src="image/nylon.png" alt="Nylon Safety Hand Gloves" style="max-width: 200px; height: auto; border-radius: 8px; transition: all 0.5s ease;" onmouseover="this.style.transform='translateY(-10px) scale(1.1)'; this.style.filter='drop-shadow(0 10px 15px rgba(45, 51, 107, 0.3))'" onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.filter='none'">
                    <h3 style="font-family: 'Montserrat', sans-serif; color: #2D336B; margin: 15px 0;">Nylon Safety Hand Gloves</h3>
                    <p style="font-family: 'Montserrat', sans-serif; margin-bottom: 20px;">Premium nylon safety gloves with PU coating for enhanced grip. Breathable, lightweight and ideal for precision work. Features excellent dexterity and abrasion resistance. Perfect for assembly, packaging and general handling tasks.</p>
                    <div style="display: flex; gap: 10px; justify-content: center;">
                        <button style="background: #2D336B; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">Buy Now</button>
                        <button style="background: #4B0082; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">Add to Cart</button>
                    </div>
                </div>
                
                <div style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); padding: 20px; border-radius: 10px; text-align: center;">
                    
                    <img src="image/grey_enhanced.png" alt="Grey Threaded Hand Gloves" style="max-width: 200px; height: auto; border-radius: 8px; transition: all 0.5s ease;" onmouseover="this.style.transform='translateY(-10px) scale(1.1)'; this.style.filter='drop-shadow(0 10px 15px rgba(45, 51, 107, 0.3))'" onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.filter='none'">
                    <h3 style="font-family: 'Montserrat', sans-serif; color: #2D336B; margin: 15px 0;">Grey Threaded Hand Gloves</h3>
                    <p style="font-family: 'Montserrat', sans-serif; margin-bottom: 20px;">Premium quality grey threaded hand gloves perfect for industrial use. Provides excellent grip and protection..</p>
                    <div style="display: flex; gap: 10px; justify-content: center;">
                        <button style="background: #2D336B; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">Buy Now</button>
                        <button style="background: #4B0082; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">Add to Cart</button>
                        <button style="background: #2D336B; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">View Details</button>
                        </div>
                </div>
                 <style>
                    .products-section button:hover {
                        background: linear-gradient(45deg, #4B0082, #2D336B) !important;
                        transform: scale(1.1) !important;
                        box-shadow: 0 0 15px rgba(45, 51, 107, 0.3);
                        transition: all 0.3s ease;
                    }
                 </style>
                 <div style="text-align: center; margin-top: 30px; grid-column: 1 / -1;" onclick="window.location.href='shop.php'">
                <div style="display: flex; justify-content: center; width: 100%;">
                <button style="background: linear-gradient(45deg, #2D336B, #4B0082); color: white; border: none; padding: 15px 40px; border-radius: 5px; cursor: pointer; font-family: 'Montserrat', sans-serif; font-size: 16px; transition: transform 0.3s ease, box-shadow 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(45, 51, 107, 0.2)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">More...</button>
            </div>

            </div>
        `;
        
        document.querySelector('.container').appendChild(productsSection);
    </script>
    
    <footer style="background: linear-gradient(45deg, #3D4380, #6B2099); color: white; padding: 15px 0; margin-top: 30px; font-family: 'Montserrat', sans-serif; text-align: center;">
        <div style="display: flex; justify-content: space-around; padding: 20px 0; border-bottom: 1px solid rgba(255,255,255,0.2);">
            <!-- Where We Are Section -->
            <div style="text-align: center;">
                <h3 style="margin-bottom: 15px; font-size: 1.2em;">Where We Are <i class="fas fa-map-marker-alt" style="color: white; margin-left: 5px;"></i></h3>
                <div style="display: flex; align-items: center; margin-bottom: 10px;">
                    <p style="margin: 0;"> Additional MIDC Satara, 415004</p>
                </div>
            </div>
    
            <!-- Social Media Section -->
            <div style="text-align: center;">
                <h3 style="margin-bottom: 15px; font-size: 1.2em;">Connect With Us</h3>
                <div style="display: flex; gap: 20px; justify-content: center;">
                    <a href="#" style="color: white; text-decoration: none; transition: all 0.3s ease;">
                        <i class="fab fa-facebook-f" style="font-size: 24px;" onmouseover="this.style.transform='rotate(360deg) scale(1.2)'; this.style.color='#4267B2'" onmouseout="this.style.transform='rotate(0) scale(1)'; this.style.color='white'"></i>
                    </a>
                    <a href="#" style="color: white; text-decoration: none; transition: all 0.3s ease;">
                        <i class="fab fa-twitter" style="font-size: 24px;" onmouseover="this.style.transform='translateY(-5px) scale(1.2)'; this.style.color='#1DA1F2'" onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.color='white'"></i>
                    </a>
                    <a href="#" style="color: white; text-decoration: none; transition: all 0.3s ease;">
                        <i class="fab fa-instagram" style="font-size: 24px;" onmouseover="this.style.transform='rotate(-45deg) scale(1.2)'; this.style.color='#E1306C'" onmouseout="this.style.transform='rotate(0) scale(1)'; this.style.color='white'"></i>
                    </a>
                    <a href="#" style="color: white; text-decoration: none; transition: all 0.3s ease;">
                        <i class="fab fa-linkedin-in" style="font-size: 24px;" onmouseover="this.style.transform='translateX(5px) scale(1.2)'; this.style.color='#0077B5'" onmouseout="this.style.transform='translateX(0) scale(1)'; this.style.color='white'"></i>
                    </a>
                </div>
            </div>
        </div>
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
            <p style="margin: 5px 0;">No Copyright Claimed</p>
            <p style="margin: 5px 0;">For more information, contact: <a href="mailto:riddhisiddhi.satara@gmail.com" style="color: white; text-decoration: none; border-bottom: 1px dotted white;">riddhisiddhi.satara@gmail.com</a></p>
        </div>
    </footer>
</body>
</html>
