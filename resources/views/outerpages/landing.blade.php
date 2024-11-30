@extends('layouts.login_master')

@section('content')
    <div class="page-content login-cover mb-4">       
        <div class="row p-4"> 
            <div class="col-md-6">
                <img src="{{ asset('assets/pics/erpuse.png') }}" class="img-fluid rounded" alt="Student Registration">
            </div>
            <div class="col-md-6 pb-4">
                <header class="p-2 text-center" id="theheader">
                    <p class=" tsh" >Mbuku ERP</p>
                    <p class=" tsh2">The Ultimate Solution for Schools</p>
                    <a href="login.html" class="btn btn-lg" id="startbtn">Get Started</a>
                </header>            
            </div>            
        </div>
        <div class="container-fluid p-4" style="background-color: #fff;">
        
            <div class="row container m-auto p-2 " >
                <div class="text-center  col-md-12 p-2" >
                    <h1 id="modules">ERP Modules</h1>
                </div>
            
                <div class="card col-md-4 text-center m-auto p-5" id="features2">
                    <h4>Admissions</h4>                                        
                </div>
                <div class="card col-md-4 text-center m-auto p-5" id="features" style="border-top:1px solid white;">
                    <h4>Exam Analysis</h4>                       
                </div>
                <div class="card col-md-4 text-center m-auto p-5" id="features2">
                    <h4>Fee Collection</h4>                          
                </div>          
            
                <div class="card col-md-4 m-auto text-center  p-5" id="features">
                    <h4>Staff Management</h4>                            
                </div>
                <div class="card col-md-4 text-center m-auto p-5" id="features2">
                    <h4>Inventory Management</h4>                            
                </div>
                <div class="card col-md-4 text-center m-auto   p-5" id="features">
                    <h4>Library</h4>                              
                </div>

                <div class="card col-md-4 text-center m-auto p-5" id="features2">
                    <h4>Attendance Module</h4>                                        
                </div>
                <div class="card col-md-4 text-center m-auto p-5" id="features" style="border-top:1px solid white;">
                    <h4>AI Powered Learning</h4>                       
                </div>
                <div class="card col-md-4 text-center m-auto p-5" id="features2">
                    <h4>Android App</h4>                          
                </div> 
            </div>                    
        </div>
            
       
          <!-- Footer -->
  <footer class=" text-white pt-5 pb-4">
    <div class="container text-md-left">
      <div class="row text-md-left">
        
        <!-- Contact Form -->
        <div class="col-md-6 mx-auto mt-3 ">
          <h5 class="text-uppercase mb-4 font-weight-bold">Contact Us</h5>
          <form class="p-2">
            <div class="row">
              <div class="col-md-6 form-group">
                <label for="contactName">Name</label>
                <input type="text" class="form-control" id="contactName" placeholder="Your Name">
              </div>
              <div class="col-md-6 form-group">
                <label for="contactEmail">Phone Number</label>
                <input type="text" class="form-control" id="contactPhone" placeholder="Your Phone Number">
              </div>
              <div class="col-md-6 form-group">
                <label for="contactEmail">Email address</label>
                <input type="email" class="form-control" id="contactEmail" placeholder="Your Email">
              </div>
              <div class="col-md-6 form-group">
                <label for="contactMessage">Message</label>
                <textarea class="form-control" id="contactMessage" rows="3" placeholder="Your Message"></textarea>
              </div>
            </div>
            <button type="submit" class="btn btn-primary">Send Message</button>
          </form>
        </div>

        <!-- Location Details -->
        <div class="col-md-3  mx-auto mt-3">
          <h5 class="text-uppercase mb-4 font-weight-bold">Our Location</h5>
          <p>
            <i class="fas fa-home mr-3"></i>Nairobi
          </p>
          <p>
            <i class="fas fa-envelope mr-3"></i> tgrkelvins@gmail.com
          </p>
          <p>
            <i class="fas fa-phone mr-3"></i> +254792774536
          </p>
          <p>
            <i class="fas fa-print mr-3"></i> +254792774536
          </p>
        </div>

        <!-- Social Media Links -->
        <div class="col-md-3  mx-auto mt-3">
          <h5 class="text-uppercase mb-4 font-weight-bold">Follow Us</h5>
          <a href="#" class="btn btn-primary btn-block">
            <i class="fab fa-facebook-f mr-2"></i> Facebook
          </a>
          <a href="#" class="btn btn-info btn-block">
            <i class="fab fa-twitter mr-2"></i> Twitter
          </a>
          <a href="#" class="btn btn-danger btn-block">
            <i class="fab fa-instagram mr-2"></i> Instagram
          </a>
          <a href="#" class="btn btn-secondary btn-block">
            <i class="fab fa-linkedin mr-2"></i> LinkedIn
          </a>
        </div>
        
      </div>
      
      <hr class="mb-4">
      
      <div class="row align-items-center">
        <div class="col-md-7 col-lg-8">
          <p class="text-center text-md-left">© 2024 Copyright:
            <a href="#" class="text-white">
              <strong>mbukuerp.com</strong>
            </a>
          </p>
        </div>
        <div class="col-md-5 col-lg-4">
          <div class="text-center text-md-right">
            <ul class="list-unstyled list-inline">
              <li class="list-inline-item">
                <a href="#" class="btn-floating btn-sm text-white"><i class="fab fa-facebook-f"></i></a>
              </li>
              <li class="list-inline-item">
                <a href="#" class="btn-floating btn-sm text-white"><i class="fab fa-twitter"></i></a>
              </li>
              <li class="list-inline-item">
                <a href="#" class="btn-floating btn-sm text-white"><i class="fab fa-google-plus-g"></i></a>
              </li>
              <li class="list-inline-item">
                <a href="#" class="btn-floating btn-sm text-white"><i class="fab fa-linkedin-in"></i></a>
              </li>
              <li class="list-inline-item">
                <a href="#" class="btn-floating btn-sm text-white"><i class="fab fa-instagram"></i></a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      
    </div>
  </footer>
  <!-- End Footer -->

    </div>
    @endsection
