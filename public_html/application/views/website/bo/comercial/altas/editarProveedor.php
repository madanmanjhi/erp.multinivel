

<form id="nueva" class="smart-form"  novalidate="novalidate" >
<fieldset>
<input type="text" class="hide" value="<?php echo $_POST['id']; ?>" name="id">
			
		<hr>
		<h4>Datos de proveedor</h4>
		<br>
		<div class="row">
		
		<div class=" col col-3">
		<label class="input  " > Nombre
		<input name="nombre" value="<?php echo $datosProveedor[0]->nombre?>" id="nombre" maxlength="60" size="30" required="" type="text">
		</label>
	   </div>
	   <div class="col col-3">
	   <label class="input"> Apellido
		<input name="apellido" value="<?php echo $datosProveedor[0]->apellido?>" id="apellido" maxlength="60" size="30" required="" type="text">
	  </label>
      </div>
	   <div class="col col-6">
	   <label class="input"> Email
		<input name="email" value="<?php echo $datosProveedor[0]->email?>" id="email" maxlength="60" size="30" required="" type="text">
		</label>
	   </div>
	   </div>
		<br>

		
		<?php 
		$tel=explode("-",$datosProveedor[0]->telefono);
				
		for($i = 1;$i < sizeof($tel);$i++)
		{	?>
				
		 <label class="input"> Telefono
		 <input name="telefono[]" value="<?php echo $tel[$i]; ?> " 
		        id="telefono"  type="text">
		 </label>
	   <?php 
		}
	   ?>
		
	
		<br>
		<h4>Direccion de proveedor</h4>
			<div class="row">
		<div class="col col-6">
			<label class="select">Selecciona el tipo de proveedor 
		<select id="tipo_proveedor" required name="tipo_proveedor">
		<? foreach ( $tipo_proveedor as $key ) {
				
			if($key->id_tipo == $datosProveedor[0]->mercancia){
					
				echo '<option selected value="'.$key->id_tipo.'">' . $key->descripcion . '</option>';

			}
			else {
				echo '<option value="'.$key->id_tipo.'">' . $key->descripcion . '</option>';

			}
		}
		?>
		</select>
							
			</label>
		</div>
		<div class="col col-6">
		   	<label class="select">Cambiar la empresa actual 
									<select id="empresa" required name="empresa">
								<? foreach ( $empresa as $key ) {
									
									if($datosProveedor[0]->id_empresa == $key->id_empresa){
										
										echo '<option selected value="' . $key->id_empresa . '">' . $key->nombre . '</option>';
									}
									else {
										echo '<option value="' . $key->id_empresa . '">' . $key->nombre . '</option>';
									}
								}
								?>
							     </select>
		</div>
		</div>
		
	
								
							<br>
							<label class="input"> Comision de producto
									<input name="comision" value="<?php echo $datosProveedor[0]->comision?>" id="comision" maxlength="60" size="30" required="" type="text">
							<br>
							
						
							
								
							
														<br>
							<hr>
							<h4>Direccion de Proveedor</h4>
							<br >
							<div class="row">
							<div class="col col-6">
								<label class="input"> Direccion de domicilio
									<input name="direccion" value="<?php echo $datosProveedor[0]->direccion?>" id="direccion" maxlength="60" size="30" required="" type="text">
								</label>
							</div>
							<div class="col col-6">
								<label class="input"> Ciudad
									<input name="ciudad" value="<?php echo $datosProveedor[0]->ciudad?>" id="ciudad" maxlength="60" size="30" required="" type="text">
								</label>
							</div>
							</div>
							
							
						<div class="row">
							<div class="col col-6">
									<label class="input"> Provincia
									<input name="provincia" value="<?php echo $datosProveedor[0]->provincia?>" id="provincia" maxlength="60" size="30" required="" type="text">
								</label>
							</div>
							<div class="col col-6">
							
								 <label class="select"> País
								 <select id="pais" required name="pais" onChange="ImpuestosPais()"> 
													<? foreach ( $pais as $key ) { ?>
													
								<?php if($key->Code==$datosProveedor[0]->pais){ ?>
								
									<option selected value="<?=$key->Code?>"> <?=$key->Name?></option>			
												
												<?php } else {?>
												
						            <option value="<?=$key->Code?>"> <?=$key->Name?></option>
						
													<?}}?>
									</select>
								</label>
							</div>
							</div>
							
<br>
									<label class="input"> Codigo Postal
									<input name="codigo_postal" value="<?php echo $datosProveedor[0]->codigo_postal ?>" id="provincia" maxlength="60" size="30" required="" type="text">
								</label>
							
							<hr>
									<br>
							<hr>
							<h4>Datos fiscales de proveedor</h4>	
							<br>
								<div class="row">
							<div class="col col-6">
										<label class="input"> Razon Social
									<input name="razonsocial" value="<?php echo $datosProveedor[0]->razon_social?>" id="razon" maxlength="60" size="30" required="" type="text">
								</label>
							</div>
							<div class="col col-6">
							
							<label class="input"> CURP
									<input name="CURP" value="<?php echo $datosProveedor[0]->curp?>" id="curp" maxlength="60" size="30" required="" type="text">
								</label>
							</div>
							</div>

								<div class="row">
							<div class="col col-8">
								<label class="input"> RFC
									<input name="RFC" value="<?php echo $datosProveedor[0]->rfc?>" id="rfc" maxlength="60" size="30" required="" type="text">
								</label>
							</div>
							<div class="col col-4">
							
							<label class="select"> 	Regimen fiscal 
										<select class="custom-scroll" name="regimen">
											<?foreach ($regimen as $key){?>
											
								<?php if($datosProveedor[0]->id_regimen == $key->id_regimen){ ?>
										<option selected value="<?=$key->id_regimen?>"><?=$key->abreviatura." ".$key->descripcion?></option>
									<?php }
									else { ?>
						                 <option value="<?=$key->id_regimen?>"><?=$key->abreviatura." ".$key->descripcion?></option>	
								   <?php	}	}?>
										</select>
										</label>
							</div>
							</div>	
					
						
							<?php 
		
				
		for($i = 0;$i < sizeof($cuentaBanco);$i++)
		{	?>
			<div class="row">
							<div class="col col-8">
							<input type="text" class="hide" value="<?php echo $cuentaBanco[$i]->id ?>" name="id_cuenta[]">
								<label class="select">Bancos 
											<select class="custom-scroll" name="banco[]" id="banco" required>
												<?foreach ($bancos as $key){?>
						<?php if(  $key->id_banco == $cuentaBanco[$i]->banco){ ?>							
						<option selected value="<?=$key->id_banco ?>"><?=$key->descripcion?></option>
						<?php }
									else { ?>
						<option value="<?=$key->id_banco ?>"><?=$key->descripcion?></option>			
												<?}}?>
											</select>
							</label>
											
						
							</div>							
		              <div class="col col-4">
			             <label class="input"> Cuenta
				         <input name="cuenta[]" value="<?php echo $cuentaBanco[$i]->cuenta?>" id="condicion_pago" maxlength="60" size="30" required="" type="text">
			        </label>
		</div>
	</div>					
							
		<?php }?>	
						
						 <label class="select"> Zona
						<select class="custom-scroll"
								name="zona">
								<?foreach ($zona as $key){?>
								<?php if($datosProveedor[0]->id_zona==$key->id_zona){?>
						<option selected value="<?=$key->id_zona?>"><?=$key->descripcion?></option>
						
									<?php }else{?>
						<option value="<?=$key->id_zona?>"><?=$key->descripcion?></option>			
									
									<?}}?>
								</select>
										</label>
							
							<br>
							<hr>
							<h4>Datos de cobro</h4>
							<br>
							<label class="input"> Condiciones de paso
									<input name="condicionesdepago" value="<?php echo $datosProveedor[0]->condicion_pago?>" id="condicion_pago" maxlength="60" size="30" required="" type="text">
								</label>
							<br>
							<div class="row">
							
							<div class="col col-6">
							<label class="input"> Tiempo promedio de entrega
									<input name="tiempoprimediodeentrega" value="<?php echo $datosProveedor[0]->promedio_entrega?>" id="promedio_entrega" maxlength="60" size="30" required="" type="text">
								</label>
							</div>
							
							<div class="col col-6">
								<label class="input"> Tiempo de entrega de documentacion
									<input name="tiempodeentregadedocumentos" value="<?php echo $datosProveedor[0]->promedio_entrega_documentacion?>" id="promedio_entrega_documentacion" maxlength="60" size="30" required="" type="text">
								</label>
							</div>
							</div>
							
								<br>
								<hr>
							<h4>Credito</h4>
							<br>
							<div class="row">
							<div class="col col-6">
							<label class="input"> Plazo de Pago
									<input name="plazodepago" value="<?php echo $datosProveedor[0]->plazo_pago?>" id="plazo_pago" maxlength="60" size="30" required="" type="text">
								</label>
							</div>
							<div class="col col-6">
							<label class="input"> Plazo de suspencion
									<input name="plazodesuspencion" value="<?php echo $datosProveedor[0]->plazo_suspencion?>" id="plazo_suspencion" maxlength="60" size="30" required="" type="text">
								</label>
							</div>
							</div>
							
							
							<div class="row">
							<div class="col col-6">
								<label class="input"> Plazo de suspencion de firma
									<input name="palzodesuspenciondefirma" value="<?php echo $datosProveedor[0]->plazo_suspencion_firma?>" id="plazo_suspencion_firma" maxlength="60" size="30" required="" type="text">
								</label>
							</div>
							<div class="col col-6">
						<label class="input"> Interes Moratorio
									<input name="interesmoratorio" value="<?php echo $datosProveedor[0]->interes_moratorio?>" id="interes_moratorio" maxlength="60" size="30" required="" type="text">
								</label>
							</div>
							</div>
								
								
									<div class="row">
							<div class="col col-6">
							<label class="input"> Dia de corte
									<input name="diadecorte" value="<?php echo $datosProveedor[0]->dia_corte?>" id="plazo_pago" maxlength="60" size="30" required="" type="text">
								</label>
							</div>
							<div class="col col-6">
								<label class="input"> Dia de pago
									<input name="diadepago" value="<?php echo $datosProveedor[0]->dia_corte?>" id="plazo_pago" maxlength="60" size="30" required="" type="text">
								</label>
							</div>
							</div>
							
						
								<label class="select">Impuesto 
										<select name="impuesto" id="impuesto">
									<?foreach ($impuesto as $key){
									if($key->id_impuesto==$datosProveedor[0]->id_impuesto){?>
									
						<option selected value="<?=$key->id_impuesto?>"><?=$key->descripcion." ".$key->porcentaje."%"?></option>												
								<?php }else{	?>
													
						<option value="<?=$key->id_impuesto?>"><?=$key->descripcion." ".$key->porcentaje."%"?></option>
										
									<?php }}?>
									
									
									
									
									
								</select>
										</label>
							<br>
							<div class="row">
							
							<?php  if( $datosProveedor[0]->credito_autorizado=='1'){ ?>
								   <div class="col col-3">
										Credito autorizado
										<div class="inline-group">
											<label class="radio"> <input type="radio" value="1"
												name="credito_autorizado" checked> <i></i>Si
											</label> <label class="radio"> <input type="radio" value="0"
												name="credito_autorizado"> <i></i>No
											</label>
										</div>
									</div>
									
							<?php }else{?>
							 <div class="col col-3">
										Credito autorizado
										<div class="inline-group">
											<label class="radio"> <input type="radio" value="1"
												name="credito_autorizado" > <i></i>Si
											</label> <label class="radio"> <input type="radio" value="0"
												name="credito_autorizado" checked> <i></i>No
											</label>
										</div>
									</div>
							
							<?php }?>
							  
									
									
							<?php  if( $datosProveedor[0]->credito_suspendido=='1'){ ?>
									<div class="col col-3">
										Credito suspendido
										<div class="inline-group">
											
											
											<label class="radio"> <input type="radio" value="1"
												name="credito_suspendido" checked > <i></i>Si
											</label> <label class="radio"> <input type="radio" value="0"
												name="credito_suspendido" > <i></i>No
											</label>
										  
									   </div> 
									</div>
							<?php }else{?>
							
								<div class="col col-3">
										Credito suspendido
										<div class="inline-group">
											
											
											<label class="radio"> <input type="radio" value="1"
												name="credito_suspendido" > <i></i>Si
											</label> <label class="radio"> <input type="radio" value="0"
												name="credito_suspendido" checked> <i></i>No
											</label>
										  
									   </div> 
									</div>
							<?php }?>
							
						
							</div>
							</fieldset>
							<footer>
								<a class="btn btn-success" onclick="enviar()">
									Guardar
								</a>
							</footer>
						</form>

<script src="/template/js/plugin/jquery-form/jquery-form.min.js"></script>
<script src="/template/js/validacion.js"></script>
<script src="/template/js/plugin/fuelux/wizard/wizard.min.js"></script>
<script type="text/javascript">
function enviar() {
	
	 $.ajax({
							type: "POST",
							url: "/bo/comercial/actualizar_proveedor",
							data: $('#nueva').serialize()
						})
						.done(function( msg ) {
							
									bootbox.dialog({
										message: msg,
										title: "Modificacion de Proveedor",
										buttons: {
											success: {
											label: "Ok!",
											className: "btn-success",
											callback: function() {
												location.href="/bo/comercial/listarProveedor";
												}
											}
										}
									});
						});//fin Done ajax
		
}

function ImpuestosPais(){
	var pa = $("#pais").val();
	
	$.ajax({
		type: "POST",
		url: "/bo/mercancia/ImpuestaPais",
		data: {pais: pa}
	})
	.done(function( msg )
	{
		$('#impuesto option').each(function() {
		    
		        $(this).remove();
		    
		});
		datos=$.parseJSON(msg);
	      for(var i in datos){
		      var impuestos = $('#impuesto');
		      $('#impuesto').each(function() {
				  $(this).append('<option value="'+datos[i]['id_impuesto']+'">'+datos[i]['descripcion']+' '+datos[i]['porcentaje']+'</option>');
			    
			});
	    	  
	        
	      }
	});
}
</script>