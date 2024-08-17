<div class="card container">
    <form method="POST" action="" enctype="multipart/form-data">
        @csrf     
        <div class="row g-3 align-items-center m-1">                      
            <div class="col-auto">
                <div class="border border-primary rounded p-3 mb-1">
                    <label for="bulk_files" class="col-form-label">Upload Bulk Files:</label>              
                    <input type="file" name="bulk_files[]" id="bulk_files" class="form-control-file" multiple>
                    <small class="form-text text-muted">Upload Excel files only.</small> 
                </div>    
                <button type="submit" class="btn-sm btn-primary float-right">Upload Files</button>
            </div>
            <div class="col-auto">
            </div>              
            <div class="col-auto">              
            </div>               
        </div>        
    </form>
</div>