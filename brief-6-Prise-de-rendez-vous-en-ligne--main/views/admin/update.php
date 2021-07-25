<?php 
	if(isset($_POST['id'])){
		$OneClasse = new ClassController();
		$classe = $OneClasse->getOne();
		
	}else{
		Redirect::to('home');
	}
	if(isset($_POST['submit'])){
		$updClasse = new ClassController();
		$updClasse->updateClasse();
	}
?>

<div class="container">
	<div class="row my-4">
		<div class="col-md-8 mx-auto">
			<div class="card">
				<div class="card-header">Update Class</div>
				<div class="card-body bg-light">
					<a href="<?php echo BASE_URL;?>dashboard" class="btn btn-sm btn-secondary mr-2 mb-2">
						<i class="fas fa-home"></i>
					</a>
                    
					<form method="post">
                    

						<div class="form-group">
							<label for="nom">Date*</label>
							<input type="date" name="date" class="form-control" value="<?php echo $classe->date; ?>">
							<input type="hidden" name="id" value="<?php echo $classe->id;?>"> important to send id 
						</div>
						<div class="form-group">
							<label for="prenom">Note*</label>
							<textarea  name="note" class="form-control" ><?php echo $classe->note; ?></textarea>
						</div>
						<div class="form-group">
							<label for="prenom">Horaire*</label>
							<select name="horaire" class="form-control" >
								<option  ></option>
								<option <?php echo $classe->horaire == '08:00-10:00' ? 'selected' : '';?> >08:00-10:00</option>
								<option <?php echo $classe->horaire == '10:00-12:00' ? 'selected' : '';?> >10:00-12:00</option>
								<option <?php echo $classe->horaire == '12:00-14:00' ? 'selected' : '';?> >12:00-14:00</option>
								<option <?php echo $classe->horaire == '14:00-16:00' ? 'selected' : '';?>>14:00-16:00</option>
								<option <?php echo $classe->horaire == '16:00-18:00' ? 'selected' : '';?>>16:00-18:00</option>
								<option <?php echo $classe->horaire == '1' ? 'disabled' : '';?>>16:00-18:00</option>
								
							</select>
						</div>
						
						<div class="form-group">
							<button type="submit" class="btn btn-primary" name="submit">Update</button>
						</div>
					</form>
                   
				</div>
			</div>
		</div>
	</div>
</div>