<?php $title = 'Giới thiệu - Sắc Việt'; ?>

<!-- Custom CSS for About Page -->
<style>
.hero-about {
    background: linear-gradient(135deg, rgba(223, 185, 185, 0.9), rgba(90, 0, 0, 0.8)), url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');
    background-size: cover;
    background-position: center;
    min-height: 60vh;
    display: flex;
    align-items: center;
    color: white;
}

.section-divider {
    height: 4px;
    background: linear-gradient(90deg, #8b0000, #ffc107);
    border-radius: 2px;
    margin: 3rem auto;
    width: 100px;
}

.feature-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    border: none;
    height: 100%;
}

.feature-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.feature-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #8b0000, #5a0000);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    color: white;
    font-size: 2rem;
}

.stats-section {
    background: linear-gradient(135deg, rgba(139, 0, 0, 0.05), rgba(255, 193, 7, 0.05));
    border-radius: 20px;
    padding: 3rem 2rem;
}

.stat-item {
    text-align: center;
    padding: 1rem;
}

.stat-number {
    font-size: 3rem;
    font-weight: 900;
    color: #8b0000;
    display: block;
}

.timeline {
    position: relative;
    padding: 2rem 0;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 50%;
    top: 0;
    bottom: 0;
    width: 4px;
    background: linear-gradient(to bottom, #8b0000, #ffc107);
    transform: translateX(-50%);
}

.timeline-item {
    position: relative;
    margin: 2rem 0;
    width: 50%;
}

.timeline-item:nth-child(odd) {
    left: 50%;
    padding-left: 1rem;
    text-align: left;
}

.timeline-item:nth-child(even) {
    left: 0;
    padding-right: 1rem;
    text-align: right;
}

.timeline-content {
    background: white;
    padding: 1.5rem;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    position: relative;
}

.timeline-year {
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    background: #8b0000;
    color: white;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.9rem;
    z-index: 10;
    margin-left: -320px;
}

/* 2016 và 2022 qua bên phải */
.timeline-item:nth-child(even) .timeline-year {
    margin-left: 320px;
}

.team-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.team-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.team-image {
    height: 250px;
    background: linear-gradient(135deg, #8b0000, #ffc107);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 4rem;
}

.values-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin: 3rem 0;
}

.value-item {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    text-align: center;
    transition: all 0.3s ease;
}

.value-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.15);
}

@media (max-width: 768px) {
    .timeline::before {
        left: 30px;
    }
    
    .timeline-item {
        width: 100%;
        left: 0 !important;
        padding-left: 4rem !important;
        padding-right: 0 !important;
    }
    
    .timeline-year {
        left: 30px;
    }
}
</style>

<!-- Hero Section -->
<section class="hero-about py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-3 fw-bold mb-4">Về Sắc Việt</h1>
                <p class="lead fs-4 mb-4">
                    Hành trình mang đến những bộ trang phục truyền thống Việt Nam tinh tế và hiện đại
                </p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="#story" class="btn btn-warning btn-lg text-dark fw-bold">
                        <i class="fas fa-book-open me-2"></i>Câu chuyện của chúng tôi
                    </a>
                    <a href="#collection" class="btn btn-outline-warning btn-lg text-warning fw-bold">
                        <i class="fas fa-tshirt me-2"></i>Xem bộ sưu tập
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5">
    <div class="container">
        <div class="stats-section text-center">
            <div class="row">
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-item">
                        <span class="stat-number" data-count="10">0</span>
                        <h5 class="text-dark fw-bold">Năm Kinh Nghiệm</h5>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-item">
                        <span class="stat-number" data-count="5000">0</span>
                        <h5 class="text-dark fw-bold">Khách Hàng Hài Lòng</h5>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-item">
                        <span class="stat-number" data-count="300">0</span>
                        <h5 class="text-dark fw-bold">Mẫu Trang Phục</h5>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-item">
                        <span class="stat-number" data-count="5">0</span>
                        <h5 class="text-dark fw-bold">Chi Nhánh</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Story Section -->
<section id="story" class="py-5 bg-white">
    <div class="container">
        <div class="row align-items-center mb-5">
            <div class="col-lg-6">
                <div class="pe-lg-4">
                    <h2 class="display-5 fw-bold mb-4">Câu Chuyện Của Sắc Việt</h2>
                    <p class="lead mb-4">
                        Bắt đầu từ một xưởng may nhỏ tại Đà Nẵng năm 2013, Sắc Việt ra đời với sứ mệnh
                        gìn giữ và phát triển những bộ trang phục truyền thống Việt Nam.
                    </p>
                    <p class="mb-4">
                        Mỗi sản phẩm đều được thiết kế tinh tế, từ áo dài thướt tha, áo tứ thân duyên dáng
                        đến những bộ cánh dân tộc đặc sắc, kết hợp giữa văn hóa truyền thống và phong cách hiện đại.
                    </p>
                    <div class="d-flex align-items-center mt-4">
                        <img src="https://images.pexels.com/photos/34889454/pexels-photo-34889454.jpeg" 
                             class="rounded-circle me-3" width="60" height="60" alt="Founder">
                        <div>
                            <h6 class="mb-0">Phạm Bích Hiền</h6>
                            <small class="text-muted">Founder & CEO</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="https://images.pexels.com/photos/34889454/pexels-photo-34889454.jpeg"
                     class="img-fluid rounded-4 shadow-lg" alt="Bộ sưu tập Sắc Việt">
            </div>
        </div>
    </div>
</section>

<!-- Timeline Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold" style="color: #8b0000;">Hành Trình Phát Triển</h2>
            <div class="section-divider mx-auto"></div>
            <p class="lead text-dark">Những cột mốc quan trọng trong sự phát triển của Sắc Việt</p>
        </div>
        <div class="timeline">
            <div class="timeline-item">
                <div class="timeline-content">
                    <h5 style="color: #8b0000;">Khởi Nguồn</h5>
                    <p class="text-dark">Mở xưởng may nhỏ tại Đà Nẵng, phục vụ khách hàng yêu thích áo dài và trang phục dân tộc.</p>
                </div>
                <div class="timeline-year">2013</div>
            </div>
            <div class="timeline-item">
                <div class="timeline-content">
                    <h5 style="color: #8b0000;">Mở Rộng Bộ Sưu Tập</h5>
                    <p class="text-dark">Ra mắt bộ sưu tập áo dài cưới, áo dài lễ hội, nhận được sự yêu thích từ khách hàng khắp miền Trung.</p>
                </div>
                <div class="timeline-year">2016</div>
            </div>
            <div class="timeline-item">
                <div class="timeline-content">
                    <h5 style="color: #8b0000;">Đặt Mua Online</h5>
                    <p class="text-dark">Ra mắt website đặt hàng trực tuyến, phục vụ khách hàng trên toàn quốc.</p>
                </div>
                <div class="timeline-year">2019</div>
            </div>
            <div class="timeline-item">
                <div class="timeline-content">
                    <h5 style="color: #8b0000;">Chuỗi Cửa Hàng</h5>
                    <p class="text-dark">Mở 3 chi nhánh tại Hà Nội, TP.HCM và Đà Nẵng, nâng cao trải nghiệm mua sắm trực tiếp.</p>
                </div>
                <div class="timeline-year">2022</div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold" style="color: #8b0000;">Giá Trị Cốt Lõi</h2>
            <div class="section-divider mx-auto"></div>
            <p class="lead text-dark">Những nguyên tắc định hướng mọi hoạt động của Sắc Việt</p>
        </div>
        <div class="values-grid">
            <div class="value-item">
                <div class="feature-icon"><i class="fas fa-heart"></i></div>
                <h4 style="color: #8b0000;">Tận Tâm</h4>
                <p class="text-dark">Chúng tôi phục vụ khách hàng với sự tận tâm và chuyên nghiệp, luôn đặt trải nghiệm của bạn lên hàng đầu.</p>
            </div>
            <div class="value-item">
                <div class="feature-icon"><i class="fas fa-palette"></i></div>
                <h4 style="color: #8b0000;">Sáng Tạo</h4>
                <p class="text-dark">Thiết kế độc đáo, kết hợp tinh hoa truyền thống với phong cách hiện đại, tạo nên những bộ trang phục tinh tế.</p>
            </div>
            <div class="value-item">
                <div class="feature-icon"><i class="fas fa-leaf"></i></div>
                <h4 style="color: #8b0000;">Tự Nhiên</h4>
                <p class="text-dark">Sử dụng nguyên liệu vải chất lượng, an toàn và thân thiện với môi trường.</p>
            </div>
            <div class="value-item">
                <div class="feature-icon"><i class="fas fa-star"></i></div>
                <h4 style="color: #8b0000;">Chất Lượng</h4>
                <p class="text-dark">Không ngừng cải tiến quy trình sản xuất để mang đến những sản phẩm hoàn hảo nhất.</p>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold" style="color: #8b0000;">Tại Sao Chọn Sắc Việt?</h2>
            <div class="section-divider mx-auto"></div>
            <p class="lead text-dark">Những điều đặc biệt làm nên sự khác biệt</p>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-award"></i></div>
                        <h4 style="color: #8b0000;">Uy Tín & Chất Lượng</h4>
                    <p>Sản phẩm được kiểm định kỹ lưỡng, đảm bảo chất lượng và tinh tế trong từng chi tiết.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-shipping-fast"></i></div>
                    <h4 style="color: #8b0000;">Giao Hàng Nhanh</h4>
                    <p class="text-dark">Đặt hàng trực tuyến, giao hàng toàn quốc nhanh chóng và an toàn.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-mobile-alt"></i></div>
                    <h4 style="color: #8b0000;">Công Nghệ Hiện Đại</h4>
                    <p>Website và app thông minh, hỗ trợ lựa chọn mẫu, thanh toán và theo dõi đơn hàng dễ dàng.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5" style="background: linear-gradient(135deg, #8b0000, #5a0000); color: #fff;">
    <div class="container">
        <div class="row align-items-center">
            <!-- Nội dung chính -->
            <div class="col-lg-7">
                <h2 class="display-5 fw-bold mb-3">Khám Phá Phong Cách Sắc Việt</h2>
                <p class="lead mb-4">
                    Trải nghiệm bộ sưu tập trang phục truyền thống Việt Nam với thiết kế tinh tế, sang trọng và hiện đại.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="/collection" class="btn btn-warning btn-lg fw-bold text-dark shadow-sm" 
                       style="transition: transform 0.3s;">
                        <i class="fas fa-tshirt me-2"></i>Xem Bộ Sưu Tập
                    </a>
                    <a href="/contact" class="btn btn-outline-light btn-lg fw-bold" 
                       style="transition: transform 0.3s;">
                        <i class="fas fa-phone me-2"></i>Liên Hệ Ngay
                    </a>
                </div>
            </div>
            <!-- Hotline -->
            <div class="col-lg-5 text-center mt-4 mt-lg-0">
                <div class="bg-white bg-opacity-10 rounded-4 p-4 shadow-lg" style="backdrop-filter: blur(10px);">
                    <i class="fas fa-phone-alt fa-3x mb-3"></i>
                    <h5 class="fw-bold mb-1">Hotline 24/7</h5>
                    <h3 class="fw-bold mb-2">1900 1234</h3>
                    <p class="mb-0">Luôn sẵn sàng phục vụ bạn</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- JavaScript for Animations -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate statistics
    const statNumbers = document.querySelectorAll('.stat-number');
    
    const animateStats = () => {
        statNumbers.forEach(stat => {
            const target = parseInt(stat.getAttribute('data-count'));
            const increment = target / 100;
            let current = 0;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                
                if (target >= 1000) {
                    stat.textContent = Math.floor(current).toLocaleString() + '+';
                } else {
                    stat.textContent = Math.floor(current) + '+';
                }
            }, 20);
        });
    };
    
    // Intersection Observer for animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                if (entry.target.classList.contains('stats-section')) {
                    animateStats();
                }
                
                // Add fade-in animation
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });
    
    // Observe elements for animation
    document.querySelectorAll('.feature-card, .team-card, .value-item, .stats-section, .timeline-item').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'all 0.6s ease';
        observer.observe(el);
    });
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>
