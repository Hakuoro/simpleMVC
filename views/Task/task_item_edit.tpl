<form class="form-horizontal" action="/task/save" method="post" enctype="multipart/form-data">
	<input type="hidden" name="id" value="{{id}}">
	<div class="form-group">
		<label for="inputName" class="col-sm-2 control-label">Username</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="inputName" name="username" value="{{username}}" placeholder="Username">
		</div>
	</div>
	<div class="form-group">
		<label for="inputEmail3" class="col-sm-2 control-label">Email</label>
		<div class="col-sm-10">
			<input type="email" class="form-control" id="inputEmail3" name="username" value="{{email}}" placeholder="Email">
		</div>
	</div>
	<div class="form-group">
		<label for="inputName" class="col-sm-2 control-label">Text</label>
		<div class="col-sm-10">
			<textarea class="form-control" name="body" rows="3">{{body}}</textarea>
		</div>
	</div>
	<div class="form-group">
		<label for="exampleInputFile" class="col-sm-2 control-label">Image</label>
		<div class="col-sm-10">
			<input type="file" id="exampleInputFile" name="image">
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<div class="checkbox">
				<label>
					<input type="checkbox" name="status" {{status_check}}> Close task
				</label>
			</div>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-default" id="showButton">Show</button> <button type="submit" class="btn btn-default">Save</button>
		</div>
	</div>
</form>