@extends('layouts.login_master')

@section('content')
<div class="mycontainer m-auto mb-4">
    <h3 class="mt-1 mb-4 text-center">Contact Us</h3>  
        <form class="row p-3">
            <div class="col-md-6">            
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" placeholder="Enter your name">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" placeholder="Enter your email">
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" class="form-control" id="phone" placeholder="Enter the phone number">
                </div>           
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea class="form-control" id="message" rows="7" placeholder="Enter your message"></textarea>
                </div>
                <button type="submit" class="btn blogin form-control mb-3">Submit</button>            
            </div>
        </form>   
</div>    
@endsection
