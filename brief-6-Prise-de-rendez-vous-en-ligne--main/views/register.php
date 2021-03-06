<?php
	//cheking if we are already logged 
    if(isset($_SESSION["logged"]) && $_SESSION["logged"] === true){
        Redirect::to("home");
    }
    	//first, need to link with the controler to send NAME
    if(isset($_POST["submit"])){
        $createUser = new UsersController();
        $createUser->register();
    }
?>
<div class="container">
    <div class="row my-4">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        Inscription
                    </h3>
                </div>
                <div class="card-body">
                    <form method="post" class="mr-1">
                            <div class="form-group">
                                <input type="text"class="form-control"
                                name="reference" required autocomplete="off" placeholder="Reference" id="">
                            </div>
                        <div class="form-group">
                            <input type="text"class="form-control"
                            name="nom" required autocomplete="" placeholder="Nom" id="">
                        </div>
                        <div class="form-group">
                            <input type="text" autocomplete="off" class="form-control" name="prenom" 
                            placeholder="Prenom" id="">
                        </div>
                        <div class="form-group">
                            <input type="number" autocomplete="off" class="form-control" name="age" 
                            placeholder="Age" id="">
                        </div>
                        <div class="form-group">
                            <input type="email" autocomplete="off" class="form-control" name="email" 
                            placeholder="Email" id="">
                        </div>
                        <div class="form-group">
                            <input type="tel" autocomplete="off" class="form-control" name="tel" 
                            placeholder="Tel" id="">
                        </div>
                        <div class="form-group">
                            <button name="submit" class="btn btn-primary">
                                Inscription
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <a href="<?php echo BASE_URL;?>login" class="btn btn-link">
                        Connexion
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>