<?php 
	
		$data = new ClassController();
		$cllasses = $data-> getOneClasse();
		//print_r($cllasses);
		//print();	
?>


<div class="container">
	<div class="row my-4">
		<div class="col-md-12 mx-auto">
		 <?php include('./views/includes/alerts.php');?> <!-- to show alert -->
			<div class="card">
                <div class="card-body bg-light">
				
                        <!-- the add button -->
                        <a href="<?php echo BASE_URL;?>home" class="btn btn-sm btn-primary mr-2 mb-2">
                            <i class="fas fa-plus">ADD</i>
                        </a>
						<!-- the username and logout after click on it -->
						<a href="<?php echo BASE_URL;?>logout" title="Déconnexion" class="btn btn-link mr-2 mb-2">
						<i class="fas fa-user mr-20"> <?php echo $_SESSION['reference'];?></i>
						</a>
					
					<table class="table table-hover">
					  <thead>
					    <tr>
					      <th scope="col">#Id</th>
					      <th scope="col">Date</th>
					      <th scope="col">Note</th>
					      <th scope="col">Horaire</th>
					      <th scope="col">Action</th>
						  
					    </tr>
					  </thead>
					  <tbody>
					    <?php foreach($cllasses as $classes):?>
					    	<tr>
						      <th scope="row"><?php echo $classes['id']; ?></th>
						      <td><?php echo $classes['date'];?></td>
						      <td><?php echo $classes['note'];?></td>
						      <td><?php echo $classes['horaire'];?></td>
						      
						      <td class="d-flex flex-row">
                              <!-- update and delete button -->
						      	<form method="post" class="mr-1" action="update"> 	<!-- send us to update page with the id  -->
						      		<input type="hidden" name="id" value="<?php echo $classes['id'];?>"> <!-- send id  -->
						      		<button class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></button>
						      	</form>
						      	<form method="post" class="mr-12" action="delete">	<!-- send us to delete page with the id  -->
						      		<input type="hidden" name="id" value="<?php echo $classes['id'];?>">
						      		<button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
						      	</form>
						      </td>
					
						    </tr>
					    <?php endforeach;?> 
					  </tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>