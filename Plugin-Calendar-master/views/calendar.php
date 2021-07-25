<?php ?>

<link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css'>
<link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css'>


<div class="container">
	<div class="col-md-10">
		<div id='calendar'>

		</div>
	</div>
	<div class="col-md-10">
		<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button>
		<!-- Modal -->
		  <div class="modal fade" id="myModal" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Modal Header</h4>
					</div>
					<div class="modal-body">
					 <form action="" method="POST">
					  <div class="form-group">
					    <label for="number_person">Nombre de personne:</label>
					    <input type="number" class="form-control" id="number_person" name="number_person" min="1" required>
					  </div>
					  <div class="form-group">
					    <label for="name">Nom de la personne référente :</label>
					    <input type="text" class="form-control" id="name" name="name" required>
					  </div>
					  <div class="form-group">
					    <label for="activity">Activité :</label>
					    <input type="text" class="form-control" id="activity" name="activity" required>
					  </div>
					  <div class="form-group">
					    <label for="date">Date :</label>
					    <input type="date" class="form-control" id="date" name="date" required>
					  </div>
					  <div class="form-group">
					    <label for="hour_start">Heure de début : </label>
					    <input type="time" class="form-control" id="hour_start" name="hour_start" required>
					  </div>
					  <div class="form-group">
					    <label for="hour_end">Heure de fin : </label>
					    <input type="time" class="form-control" id="hour_end" name="hour_end" required>
					  </div>
					  <div class="form-group">
					    <label for="location">Lieux de l'activité:</label>
					    <input type="text" class="form-control" id="location" name="location"required>
					  </div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal" id="submit_modal">Add</button>
					</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<script src='https://code.jquery.com/jquery-1.11.2.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js'></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.8.0/fullcalendar.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
