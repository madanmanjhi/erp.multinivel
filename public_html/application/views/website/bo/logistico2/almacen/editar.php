	<?php if($this->session->flashdata('error')) {
		echo '<div class="alert alert-danger fade in">
								<button class="close" data-dismiss="alert">
									×
								</button>
								<i class="fa-fw fa fa-check"></i>
								<strong>Alerta </strong> '.$this->session->flashdata('error').'
			</div>'; 
	}
?>	
	
						
						<form id="actualizar" class="smart-form" method="POST" action="/bo/almacen/actualizar_almacen">
							<fieldset>
								<label class="input">Nombre
									<input style="width: 25rem;" type="text" name="nombre" id="nombre" placeholder="Nombre" class="form-control" value="<?php echo $almacen[0]->nombre; ?>" required>
								</label>
								
								<div class="row" style="width: 28rem;">
									<section class="col col-lg-12 col-md-12 col-sm-12 col-xs-12" id="busquedatodos" >
										<label class="label">Descripcion</label>
										<label class="textarea">								
											<textarea required rows="3" class="custom-scroll" name="descripcion" id="descripcion">
												<?php echo $almacen[0]->descripcion; ?>
											</textarea>
										</label>
									</section>
								</div>
								
								<label class="input">Ciudad
									<input style="width: 25rem;" type="text" name="ciudad" id="ciudad" placeholder="Ciudad" class="form-control" value="<?php echo $almacen[0]->ciudad; ?>" required>
								</label>
								
								<label class="input">Dirección
									<input style="width: 25rem;" type="text" name="direccion" id="direccion" placeholder="Direeccion" class="form-control" value="<?php echo $almacen[0]->direccion; ?>"required>
								</label>
								
								<label class="input">Telefono
									<input style="width: 25rem;" type="number" name="telefono" id="telefono" placeholder="Telefono" class="form-control" value="<?php echo $almacen[0]->telefono; ?>" required>
								</label>
								
								<label class="radio">
									<input style="width: 25rem;" type="radio" name="web" id="web" class="form-control" value="<?php echo $almacen[0]->web; ?>">
									<i></i>Almacen Web
								</label>
								
								<input type="text" id="id" name="id" value="<?php echo $almacen[0]->id_almacen; ?>" class="hide">
								<div class="row">
									<section  id="div_subir" style="width: 25rem;">
										<div style="width: 25rem;">
											<input type="submit" class="btn btn-success" style="margin-left: 66% ! important; width: 40%;" id="boton_subir" value="Actualizar" onclick="actualizar_almacen()">
										</div>
									</section>
								</div>	
									
							</fieldset>
							
						</form>
						