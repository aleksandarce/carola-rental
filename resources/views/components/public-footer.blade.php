<footer class="main_footer">
    <div class="container">
        <div class="footer-top-outer">
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                    <div class="footer_widget footer_about_widget">
                        <figure class="footer_widget_logo">
                            <a href="{{ route('home') }}"><img src="{{ asset('carola/assets/images/footer-logo.png') }}" alt="Carola"></a>
                        </figure>
                        <p>57 Heol Isaf Station Road, Cardiff, UK</p>
                        <ul class="footer-info-list">
                            <li><a href="mailto:info@example.com">info@example.com</a></li>
                            <li><a href="tel:+442920214012">029 2021 4012</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                    <div class="footer_widget footer_resources_widget">
                        <h4 class="footer_widget_title">Quick Links</h4>
                        <ul class="resources_page_list">
                            <li style="color: white;"><a href="{{ route('home') }}">Home</a></li>
                            <li style="color: white;"><a href="{{ route('cars.index') }}">Cars</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                    <div class="footer_widget footer_community_widget">
                        <h4 class="footer_widget_title">Opening Hours</h4>
                        <p style="color: white;">Mon - Fri: 8:00 am - 6:00 pm</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom-outer">
            <div class="copyright">Copyright &copy; {{ now()->year }} <a href="{{ route('home') }}">&nbsp;Carola</a>.&nbsp; All Rights Reserved</div>
            <ul class="social-links">
                <li><a href="#"><i class="fab fa-square-facebook"></i></a></li>
                <li><a href="#"><i class="fab fa-square-twitter"></i></a></li>
                <li><a href="#"><i class="fab fa-linkedin"></i></a></li>
            </ul>
        </div>
    </div>
</footer>
