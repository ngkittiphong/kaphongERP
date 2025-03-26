<div role="tabpanel" class="tab-pane profile fade" id="profile">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="text-center">
                <!--<img src="{{ asset('assets/images/faces/face7.png') }}" class="img-responsive img-circle user-avatar" alt=""/>-->
                <div
                    class="slim"
                    data-size="300,300"
                    data-ratio="1:1"
                    data-shape="circle"
                    data-instant-edit="true"
                    style="
                        width: 200px; 
                        height: 200px;
                        margin: 0 auto;
                        border-radius: 50%;
                        overflow: hidden;"
                >
                    <!-- Default avatar image -->
                    <img 
                        src="{{ asset('assets/images/faces/face_default.png') }}" 
                        alt="Default Icon" 
                        class="img-fluid"
                    />

                    <!-- File input for uploading/replacing the image -->
                    <input 
                        type="file" 
                        name="slim" 
                        accept="image/jpeg, image/png, image/gif"
                    />
                </div>
                
                <h4 class="no-margin-bottom m-t-10">session.profile.fullname</h4>
                <div class="text-light text-size-small text-white">session.type.name</div>							
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12  m-t-5">
            <button type="button" class="btn btn-block bg-primary mt-33" data-toggle="modal" data-target="#new-email"> change nickname</button>
    </div>
    <div class="col-md-12 col-sm-12  m-t-5">
            <button type="button" class="btn btn-block bg-warning mt-33" data-toggle="modal" data-target="#new-email"> change password</button>
    </div>
    
    
    <div class="col-md-12 col-sm-12 m-t-40">
        <label>Sign name</label>
        <div
            class="slim"
            data-size="300,150"
            data-ratio="1:2"
            data-instant-edit="true"
            style="
                width: 200px; 
                height: 100px;
                margin: 0 auto;
                border-radius: 5%;
                overflow: hidden;"
        >
            <!-- Default avatar image -->
            <img 
                src="{{ asset('assets/images/faces/face_default.png') }}" 
                alt="Default Icon" 
                class="img-fluid"
            />

            <!-- File input for uploading/replacing the image -->
            <input 
                type="file" 
                name="slim" 
                accept="image/jpeg, image/png, image/gif"
            />
        </div>
    </div>
</div>