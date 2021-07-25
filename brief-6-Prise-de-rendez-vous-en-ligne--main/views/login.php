<?php
	//cheking if we are already logged  using cokies
    if(isset($_SESSION["logged"]) && $_SESSION["logged"] === true){
        Redirect::to("home");
    }
	//first, need to link with the controler to send NAME
    $loginUser = new UsersController();
    $loginUser->auth();
    
?>
<div class="container">
    <div class="row my-4">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        Welcome
                    </h3>
                </div>
                <div class="card-body">
                    <button onclick="window.location.href='<?php echo BASE_URL;?>register'" class="btn btn-primary btn-lg btn-block">
                        NEW USER
                    </button>
                    <form method="post" class="mr-1">
                    <div class="form-group">
                        <br>
                        <br>
                        <div class="form-group">
                            <input  type="text" class="form-control" name="reference" 
                            placeholder="reference" id="">
                        </div>
                        
                        <!-- <div class="form-group">
                            <input autocomplete="off" type="password" class="form-control" name="password" 
                            placeholder="Mot de passe" id="">
                        </div> -->
                        
                        <div class="form-group">
                            <button name="submit" class="btn btn-primary btn-lg btn-block">
                                Connexion
                            </button>
                        </div>
                    </form>
                </div>
                
            </div>
        </div>
    </div>
</div>