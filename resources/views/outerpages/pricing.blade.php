@extends('layouts.login_master')

@section('content')
    <div class="page-content login-cover mb-4">

        <!-- Main content -->
        <div class="content-wrapper">

            <!-- Content area -->
            <div class="content d-flex justify-content-center align-items-center">

                <div class="container text-center my-5 ">
                    <h3 class="mb-4 text-white">Our Pricing</h3>
                    <div class="row">
                      <!-- Basic Plan -->
                      <div class="col-md-4">
                        <div class="card mb-4 shadow-sm">
                          <div class="card-header">
                            <h4 class="my-0 font-weight-normal">Silver Plan</h4>
                          </div>
                          <div class="card-body">
                            <h1 class="card-title pricing-card-title">Kshs 150,000 <small class="text-muted">/ lifetime</small></h1>
                            <ul class="list-unstyled mt-3 mb-4">
                              <li>Registration Module</li>
                              <li>Exams Module</li>
                              <li>Finance Module</li>
                              <li>Training Inclusive</li>
                              <li>24hr Support</li>
                            </ul>
                            <button type="button" class="btn btn-lg btn-block blogin">Buy</button>
                          </div>
                        </div>
                      </div>
                      <!-- Pro Plan -->
                      <div class="col-md-4">
                        <div class="card mb-4 shadow-sm">
                          <div class="card-header">
                            <h4 class="my-0 font-weight-normal">Bronze Plan</h4>
                          </div>
                          <div class="card-body">
                            <h1 class="card-title pricing-card-title">Kshs 250,000 <small class="text-muted">/ lifetime</small></h1>
                            <ul class="list-unstyled mt-3 mb-4">
                                <li>Registration Module</li>
                                <li>Exams Module</li>
                                <li>Finance Module</li>
                                <li>Human Resource Module</li>
                                <li>Library Module</li>
                                <li>24hr Support</li>
                                <li>Training Inclusive</li>
                            </ul>
                            <button type="button" class="btn btn-lg btn-block blogin">Buy</button>
                          </div>
                        </div>
                      </div>
                      <!-- Enterprise Plan -->
                      <div class="col-md-4">
                        <div class="card mb-4 shadow-sm">
                          <div class="card-header">
                            <h4 class="my-0 font-weight-normal">Gold Plan</h4>
                          </div>
                          <div class="card-body">
                            <h1 class="card-title pricing-card-title">Kshs 400,000 <small class="text-muted">/ lifetime</small></h1>
                            <ul class="list-unstyled mt-3 mb-4">
                                <li>Registration Module</li>
                                <li>Exams Module</li>
                                <li>Finance Module</li>
                                <li>Human Resource Module</li>
                                <li>Library Module</li>
                                <li>24hr Support</li>
                                <li>Attendance Suport</li>
                                <li>AI Powered E-learning</li> 
                                <li>Android App</li>
                                <li>Training Inclusive</li>
                            </ul>
                            <button type="button" class="btn btn-lg btn-block blogin">Buy</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

            </div>


        </div>

    </div>
    @endsection
