<?php 
include 'header.php'; 
?>

<div class="container" style="padding-bottom: 250px;">
	<h2 style=" width: 100%; border-bottom: 4px solid #ff8680"><b>Register</b></h2>
	<form action="proses/register.php" method="POST">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="nama">Nama</label>
					<input type="text" class="form-control" id="nama" placeholder="Nama" name="nama" required>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="email">Email</label>
					<input type="email" class="form-control" id="email" placeholder="Email" name="email" required>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="username">Username</label>
					<input type="text" class="form-control" id="username" placeholder="Username" name="username" required>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="telp">No Telepon</label>
					<input type="text" class="form-control" id="telp" placeholder="+62" name="telp" required>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="password">Password</label>
					<input type="password" class="form-control" id="password" placeholder="Password" name="password" required>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="konfirmasi">Konfirmasi Password</label>
					<input type="password" class="form-control" id="konfirmasi" placeholder="Konfirmasi Password" name="konfirmasi" required>
				</div>
			</div>
		</div>

		<button type="submit" class="btn btn-success">Register</button>
	</form>
</div>

<?php 
include 'footer.php'; 
?>
