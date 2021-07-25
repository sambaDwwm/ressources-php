<?php 

    if(isset($_POST['submit'])){
		$newClasse = new ClassController();
		$newClasse->reservClasse();
	}
?>


<div class="container">
	<div class="row my-4">
		<div class="col-md-8 mx-auto">
			<?php include('./views/includes/alerts.php');?> <!-- to show alert -->
			<div class="card">
				<div class="card-header">Class Reservation</div>
				<div class="card-body bg-light">
					<a href="<?php echo BASE_URL;?>dashboard" class="btn btn-sm btn-secondary mr-2 mb-2">
						<i class="fas fa-home"></i>
					</a>
                    
					<form method="post" name="formulaire" >

						<div class="form-group">
							<label for="nom">Date*</label>
							<input type="date" name="date" class="form-control" placeholder="reservation" required>
						</div>
						
						<div class="form-group">
							<label for="prenom">Note*</label>
							<textarea  name="note" class="form-control" placeholder="note" required></textarea>
						</div>
						
						<div class="form-group">
							<label for="prenom">Horaire*</label>
							<select name="horaire" class="form-control" required>
								<option  ></option>
								<option  >08:00-10:00</option>
								<option  >10:00-12:00</option>
								<option  >12:00-14:00</option>
								<option  >14:00-16:00</option>
								<option  >16:00-18:00</option>
								
							</select>
						</div>
					<!-- thiis send -->
						<input type="hidden" name="reference" value="<?php echo $_SESSION['reference'];?>"> <!-- to sent the user who reserved -->

						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-primary btn-lg btn-block" name="submit">Reserve</button>
						</div>
                   
					</form>
                   
				</div>
			</div>
		</div>
	</div>
</div>