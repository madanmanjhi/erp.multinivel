<!-- MAIN CONTENT -->
 
	<section id="widget-grid" class="">
		<!-- START ROW -->
		<div class="row">

			<!-- NEW COL START -->
			<article class="col-sm-12 col-md-12 col-lg-12">
				<!-- Widget ID (each widget will need unique ID)-->
				<div class="jarviswidget" id="wid-id-1"
					data-widget-editbutton="false" data-widget-custombutton="false"
					data-widget-colorbutton="false">
					<!-- widget options:
						usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">
						
						data-widget-colorbutton="false"	
						data-widget-editbutton="false"
						data-widget-togglebutton="false"
						data-widget-deletebutton="false"
						data-widget-fullscreenbutton="false"
						data-widget-custombutton="false"
						data-widget-collapsed="true" 
						data-widget-sortable="false"
						
					-->
					<header class="col col-md-12">
						<span class="widget-icon "> <i class="fa fa-edit fa-2x"></i>
						</span>						
						<h2 class="pull-left">REGÍSTRATE</h2>
						<h4 class="pull-right">Formulario de Afiliación:</h4>
						 
					</header>

					<!-- widget div-->
					<div>

						<!-- widget edit box -->
						<div class="jarviswidget-editbox">
							<!-- This area used as dropdown edit box -->

						</div>
						<!-- end widget edit box -->
						<!-- widget content -->
						<div class="widget-body">
							<div id="myTabContent1" class="tab-content padding-10">
								<div class="tab-pane fade in active" id="s1">
									<div id="uno" class="row fuelux">
										<div id="myWizard" class="wizard">

											<ul class="steps">

												<li data-target="#step1" class="active"><span
													class="badge badge-info">1</span>
													Datos del registro
													<span class="chevron"></span></li>
												<li data-target="#step2" >
												<span class="badge badge-info">2</span>
													Datos personales
													<span class="chevron"></span></li>
											</ul>
											<div id="acciones" class="actions">
												<button type="button"
													class="final btn btn-sm btn-primary btn-prev">
													<i class="fa fa-arrow-left"></i>Anterior
												</button>
												<button type="button"
													class="final btn btn-sm btn-success btn-next"
													data-last="Registrarse" disabled="disabled">
													Siguiente<i class="fa fa-arrow-right"></i>
												</button>
											</div>
										</div>
										<form id="nuevo_afiliado" class="" action="/afiliar.php" method="POST">
										<div class="step-content">
											<div class="form-horizontal" id="fuelux-wizard">
												<div class="step-pane active" id="step1">
													<div id="register" class="smart-form">
														<legend>Información de cuenta</legend>
														
														<fieldset>
															<div class="row">															
															<section id="usuario" class="col col-6">
																<label class="input"> <i
																	class="icon-prepend fa fa-user"></i> <input
																	id="username" onkeyup="use_username()" required
																	type="text" name="username" placeholder="Usuario">
																</label>
															</section>
															<section id="correo" class="col col-6">
																<label class="input"> <i
																	class="icon-prepend fa fa-envelope-o"></i> <input
																	id="email" onkeyup="use_mail()" required type="email"
																	name="email" placeholder="Email">
																</label>
															</section>
														</div> 
														<div class="row">
															<section class="col col-6">
																<label class="input"> <i
																	class="icon-prepend fa fa-lock"></i> <input
																	id="password" onkeyup="confirm_pass()" required
																	type="password" name="password"
																	placeholder="Contraseña">
																</label>
															</section>
															<section id="confirmar_password" class="col col-6">
																<label class="input"> <i
																	class="icon-prepend fa fa-lock"></i> <input
																	id="confirm_password" onkeyup="confirm_pass()" required
																	type="password" name="confirm_password"
																	placeholder="Repite contraseña">
																</label>
															</section>
														</div>
														</fieldset>
														<fieldset>
															<section class="col col-3"></section>
															<section class="col col-4">

															<label  for="captcha" class="input">Captcha </label>
                                                                <div class=" dato_cptch " onshow="this.focus">
																<!-- data-sitekey ? segun dns -->
																<div class="g-recaptcha creativecontactform_required"
																	data-sitekey="[[G_KEY]]"> </div>
															</div>
															
															</section>
														</fieldset>
													</div>
												</div>
												<div class="step-pane" id="step2">
													<div id="checkout-form" class="smart-form" >
													<legend class="hide">Condiciones del Servicio</legend>
														<fieldset class="hide">
															
															<section class="col col-1">
																<label class="checkbox pull-right"> <input
																	id="descontar" name="agreement" type="checkbox">
																	<i></i>
																</label>
															</section>
															<section class="col col-10" style="text-align: justify">
																[[agreement]]</section>
														</fieldset>                                                                                                        
                                                                                                        <legend>Datos personales del afiliado</legend>
														<fieldset>															
															<div class="row">
																<section class="col col-4">
																	<label class="input"> <i
																		class="icon-prepend fa fa-user"></i> <input
																		id="nombre" required type="text" name="nombre"
																		placeholder="Nombre">
																	</label>
																</section>
																<section class="col col-4">
																	<label class="input"> <i
																		class="icon-prepend fa fa-user"></i> <input
																		id="apellido" required type="text" name="apellido"
																		placeholder="Apellido">
																	</label>
																</section>
																<section class="col col-4">
																	<label class="input"> <i
																		class="icon-prepend fa fa-calendar"></i> <input
																		required id="datepicker" type="text" name="nacimiento"
																		placeholder="Fecha de nacimiento" readonly="readonly">
																	</label>
																</section>
															</div><div class="row">
																<section class="col col-4" id="key">
																	<label id="key_" class="input"> <i
																		class="icon-prepend fa fa-barcode"></i> <input
																		id="keyword" onkeyup="check_keyword()"
																		placeholder="Número de identificación" type="text"
																		name="keyword">
																	</label>
																</section>
																<section class="col col-4">
																	<label class="select"> <select id="tipo_fiscal"
																		required name="fiscal">
																			<option value="1">Fisica</option>
																			<option value="2">Moral</option>
																	</select>
																	</label> Tipo de persona
																</section>
																<section class="col col-4">
																	<label class="input"> <i
																		class="icon-prepend fa fa-skype"></i> <input
																		id="skype" required type="text" name="skype"
																		placeholder="Cuenta Skype">
																	</label>
																</section>
															</div>
															<input type="hidden" name="tipo_plan" id="tipo_plan">
															<div class="row">
																<div id="tel"
																	class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
																	<section class="col col-4">
																		<label class="input"> <i
																			class="icon-prepend fa fa-phone"></i> <input required
																			name="fijo[]" placeholder="(99) 99-99-99-99"
																			type="tel">
																		</label>
																	</section>
																	<section class="col col-4">
																		<label class="input"> <i
																			class="icon-prepend fa fa-mobile"></i> <input
																			required name="movil[]"
																			placeholder="(999) 99-99-99-99-99" type="tel">
																		</label>
																	</section>
																</div>
																<section class="col col-6">
																	<button type="button" onclick="agregar('1')"
																		class="btn btn-primary">
																		&nbsp;Agregar <i class="fa fa-mobile"></i>&nbsp;
																	</button>
																	<button type="button" onclick="agregar('2')"
																		class="btn btn-primary">
																		&nbsp;Agregar <i class="fa fa-phone"></i>&nbsp;
																	</button>
																</section>

															</div>
														</fieldset>
														<fieldset class="hide">
															
															<section class="col col-3"></section>
															<section class="col col-1" style="text-align: justify">
																<label class="checkbox pull-right"> <input
																	id="nodirecto" name="profundidad" type="checkbox">
																	<i></i>
																</label>
															</section>
															<section class="col col-6" style="text-align: justify">
																[[profundidad]]</section>
														</fieldset>
														<fieldset>
															<legend>Datos co-aplicante</legend>
															<div class="row">
																<section class="col col-4">
																	<label class="input"> <input
																		placeholder="Nombre" type="text" name="nombre_co">
																	</label>
																</section>
																<section class="col col-4">
																	<label class="input"> <input
																		placeholder="Apellido" type="text" name="apellido_co">
																	</label>
																</section>
																<section class="col col-4" id="key_co">
																	<label id="key_1" class="input"> <i
																		class=" icon-prepend fa fa-mobile-phone"></i> <input
																		onkeyup="" placeholder="Telefono Movil" type="tel"
																		name="keyword_co" id="keyword_co">
																	</label>
																</section>
															</div>
														</fieldset>
														<fieldset>
															<legend>Dirección del afiliado</legend>
															<div id="dir" class="row">
																<section class="col col-4">
																	<div class="form-group">
																		<label>País</label> <select style="width: 100%"
																			class="select2" id="pais" required name="pais">
																			<option value="ABW">Aruba</option>
																			<option value="AFG">Afghanistan</option>
																			<option value="AGO">Angola</option>
																			<option value="AIA">Anguilla</option>
																			<option value="ALB">Albania</option>
																			<option value="AND">Andorra</option>
																			<option value="ANT">Netherlands Antilles</option>
																			<option value="ARE">United Arab Emirates</option>
																			<option value="ARG">Argentina</option>
																			<option value="ARM">Armenia</option>
																			<option value="ASM">American Samoa</option>
																			<option value="ATG">Antigua and Barbuda</option>
																			<option value="AUS">Australia</option>
																			<option value="AUT">Austria</option>
																			<option value="AZE">Azerbaijan</option>
																			<option value="BDI">Burundi</option>
																			<option value="BEL">Belgium</option>
																			<option value="BEN">Benin</option>
																			<option value="BFA">Burkina Faso</option>
																			<option value="BGD">Bangladesh</option>
																			<option value="BGR">Bulgaria</option>
																			<option value="BHR">Bahrain</option>
																			<option value="BHS">Bahamas</option>
																			<option value="BIH">Bosnia and Herzegovina</option>
																			<option value="BLR">Belarus</option>
																			<option value="BLZ">Belize</option>
																			<option value="BMU">Bermuda</option>
																			<option value="BOL">Bolivia</option>
																			<option value="BRA">Brazil</option>
																			<option value="BRB">Barbados</option>
																			<option value="BRN">Brunei</option>
																			<option value="BTN">Bhutan</option>
																			<option value="BWA">Botswana</option>
																			<option value="CAF">Central African Republic </option>
																			<option value="CAN">Canada</option>
																			<option value="CHE">Switzerland</option>
																			<option value="CHL">Chile</option>
																			<option value="CHN">China</option>
																			<option value="CMR">Cameroon</option>
																			<option value="COD">Congo, The Democratic Republic of the</option>
																			<option value="COG">Congo</option>
																			<option value="COK">Cook Islands</option>
																			<option value="COL">Colombia</option>
																			<option value="COM">Comoros</option>
																			<option value="CPV">Cape Verde</option>
																			<option value="CRI">Costa Rica</option>
																			<option value="CUB">Cuba</option>
																			<option value="CXR">Christmas Island</option>
																			<option value="CYM">Cayman Islands</option>
																			<option value="CYP">Cyprus</option>
																			<option value="CZE">Czech Republic</option>
																			<option value="DEU">Germany</option>
																			<option value="DJI">Djibouti</option>
																			<option value="DMA">Dominica</option>
																			<option value="DNK">Denmark</option>
																			<option value="DOM">Dominican Republic</option>
																			<option value="DZA">Algeria</option>
																			<option value="ECU">Ecuador</option>
																			<option value="EGY">Egypt</option>
																			<option value="ERI">Eritrea</option>
																			<option value="ESH">Western Sahara</option>
																			<option value="ESP">Spain</option>
																			<option value="EST">Estonia</option>
																			<option value="ETH">Ethiopia</option>
																			<option value="FIN">Finland</option>
																			<option value="FJI">Fiji Islands</option>
																			<option value="FLK">Falkland Islands</option>
																			<option value="FRA">France</option>
																			<option value="FRO">Faroe Islands</option>
																			<option value="FSM">Micronesia, Federated States of</option>
																			<option value="GAB">Gabon</option>
																			<option value="GBR">United Kingdom</option>
																			<option value="GEO">Georgia</option>
																			<option value="GHA">Ghana</option>
																			<option value="GIB">Gibraltar</option>
																			<option value="GIN">Guinea</option>
																			<option value="GLP">Guadeloupe</option>
																			<option value="GMB">Gambia</option>
																			<option value="GNB">Guinea-Bissau</option>
																			<option value="GNQ">Equatorial Guinea</option>
																			<option value="GRC">Greece</option>
																			<option value="GRD">Grenada</option>
																			<option value="GRL">Greenland</option>
																			<option value="GTM">Guatemala</option>
																			<option value="GUF">French Guiana</option>
																			<option value="GUM">Guam</option>
																			<option value="GUY">Guyana</option>
																			<option value="HKG">Hong Kong</option>
																			<option value="HND">Honduras</option>
																			<option value="HRV">Croatia</option>
																			<option value="HTI">Haiti</option>
																			<option value="HUN">Hungary</option>
																			<option value="IDN">Indonesia</option>
																			<option value="IND">India</option>
																			<option value="IOT">British Indian Ocean Territory</option>
																			<option value="IRL">Ireland</option>
																			<option value="IRN">Iran</option>
																			<option value="IRQ">Iraq</option>
																			<option value="ISL">Iceland</option>
																			<option value="ISR">Israel</option>
																			<option value="ITA">Italy</option>
																			<option value="JAM">Jamaica</option>
																			<option value="JOR">Jordan</option>
																			<option value="JPN">Japan</option>
																			<option value="KAZ">Kazakstan</option>
																			<option value="KEN">Kenya</option>
																			<option value="KGZ">Kyrgyzstan</option>
																			<option value="KHM">Cambodia</option>
																			<option value="KIR">Kiribati</option>
																			<option value="KNA">Saint Kitts and Nevis</option>
																			<option value="KOR">South Korea</option>
																			<option value="KWT">Kuwait</option>
																			<option value="LAO">Laos</option>
																			<option value="LBN">Lebanon</option>
																			<option value="LBR">Liberia</option>
																			<option value="LBY">Libyan Arab Jamahiriya</option>
																			<option value="LCA">Saint Lucia</option>
																			<option value="LIE">Liechtenstein</option>
																			<option value="LKA">Sri Lanka</option>
																			<option value="LSO">Lesotho</option>
																			<option value="LTU">Lithuania</option>
																			<option value="LUX">Luxembourg</option>
																			<option value="LVA">Latvia</option>
																			<option value="MAC">Macao</option>
																			<option value="MAR">Morocco</option>
																			<option value="MCO">Monaco</option>
																			<option value="MDA">Moldova</option>
																			<option value="MDG">Madagascar</option>
																			<option value="MDV">Maldives</option>
																			<option value="MEX">Mexico</option>
																			<option value="MHL">Marshall Islands</option>
																			<option value="MKD">Macedonia</option>
																			<option value="MLI">Mali</option>
																			<option value="MLT">Malta</option>
																			<option value="MMR">Myanmar</option>
																			<option value="MNG">Mongolia</option>
																			<option value="MNP">Northern Mariana Islands</option>
																			<option value="MOZ">Mozambique</option>
																			<option value="MRT">Mauritania</option>
																			<option value="MSR">Montserrat</option>
																			<option value="MTQ">Martinique</option>
																			<option value="MUS">Mauritius</option>
																			<option value="MWI">Malawi</option>
																			<option value="MYS">Malaysia</option>
																			<option value="MYT">Mayotte</option>
																			<option value="NAM">Namibia</option>
																			<option value="NCL">New Caledonia</option>
																			<option value="NER">Niger</option>
																			<option value="NFK">Norfolk Island</option>
																			<option value="NGA">Nigeria</option>
																			<option value="NIC">Nicaragua</option>
																			<option value="NIU">Niue</option>
																			<option value="NLD">Netherlands</option>
																			<option value="NOR">Norway</option>
																			<option value="NPL">Nepal</option>
																			<option value="NRU">Nauru</option>
																			<option value="NZL">New Zealand</option>
																			<option value="OMN">Oman</option>
																			<option value="PAK">Pakistan</option>
																			<option value="PAN">Panama</option>
																			<option value="PCN">Pitcairn</option>
																			<option value="PER">Peru</option>
																			<option value="PHL">Philippines</option>
																			<option value="PLW">Palau</option>
																			<option value="PNG">Papua New Guinea</option>
																			<option value="POL">Poland</option>
																			<option value="PRI">Puerto Rico</option>
																			<option value="PRK">North Korea</option>
																			<option value="PRT">Portugal</option>
																			<option value="PRY">Paraguay</option>
																			<option value="PSE">Palestine</option>
																			<option value="PYF">French Polynesia</option>
																			<option value="QAT">Qatar</option>
																			<option value="REU">RÃ©union</option>
																			<option value="ROM">Romania</option>
																			<option value="RUS">Russian Federation</option>
																			<option value="RWA">Rwanda</option>
																			<option value="SAU">Saudi Arabia</option>
																			<option value="SDN">Sudan</option>
																			<option value="SEN">Senegal</option>
																			<option value="SGP">Singapore</option>
																			<option value="SHN">Saint Helena</option>
																			<option value="SJM">Svalbard and Jan Mayen</option>
																			<option value="SLB">Solomon Islands</option>
																			<option value="SLE">Sierra Leone</option>
																			<option value="SLV">El Salvador</option>
																			<option value="SMR">San Marino</option>
																			<option value="SOM">Somalia</option>
																			<option value="SPM">Saint Pierre and Miquelon</option>
																			<option value="STP">Sao Tome and Principe</option>
																			<option value="SUR">Suriname</option>
																			<option value="SVK">Slovakia</option>
																			<option value="SVN">Slovenia</option>
																			<option value="SWE">Sweden</option>
																			<option value="SWZ">Swaziland</option>
																			<option value="SYC">Seychelles</option>
																			<option value="SYR">Syria</option>
																			<option value="TCA">Turks and Caicos Islands</option>
																			<option value="TCD">Chad</option>
																			<option value="TGO">Togo</option>
																			<option value="THA">Thailand</option>
																			<option value="TJK">Tajikistan</option>
																			<option value="TKL">Tokelau</option>
																			<option value="TKM">Turkmenistan</option>
																			<option value="TMP">East Timor</option>
																			<option value="TON">Tonga</option>
																			<option value="TTO">Trinidad and Tobago</option>
																			<option value="TUN">Tunisia</option>
																			<option value="TUR">Turkey</option>
																			<option value="TUV">Tuvalu</option>
																			<option value="TWN">Taiwan</option>
																			<option value="TZA">Tanzania</option>
																			<option value="UGA">Uganda</option>
																			<option value="UKR">Ukraine</option>
																			<option value="UMI">United States Minor Outlying Islands</option>
																			<option value="URY">Uruguay</option>
																			<option value="USA">United States</option>
																			<option value="UZB">Uzbekistan</option>
																			<option value="VAT">Holy See (Vatican City State)</option>
																			<option value="VCT">Saint Vincent and the Grenadines</option>
																			<option value="VEN">Venezuela</option>
																			<option value="VGB">Virgin Islands, British </option>
																			<option value="VIR">Virgin Islands, U.S.</option>
																			<option value="VNM">Vietnam</option>
																			<option value="VUT">Vanuatu</option>
																			<option value="WLF">Wallis and Futuna</option>
																			<option value="WSM">Samoa</option>
																			<option value="YEM">Yemen</option>
																			<option value="ZAF">South Africa</option>
																			<option value="ZMB">Zambia</option>
																			<option value="ZWE">Zimbabwe</option>
																		</select>
																	</div>
																</section>
																<section id="municipio" class="col col-4">
																	<label class="input"> Estado <input type="text"
																		name="estado">
																	</label>
																</section>

																<section id="municipio" class="col col-4">
																	<label class="input"> Municipio <input
																		type="text" name="municipio">
																	</label>
																</section>
														</div><div id="dir2" class="row">
																<section id="colonia" class="col col-4">
																	<label class="input"> Colonia <input
																		type="text" name="colonia">
																	</label>
																</section>

																<section class="col col-4">
																	<label class="input"> Dirección de domicilio <input
																		required type="text" name="calle">
																	</label>
																</section>

																<section class="col col-4">
																	<label class="input"> Código postal <input
																		required type="text" id="cp" name="cp">
																	</label>
																</section>

															</div>
														</fieldset>
														<fieldset class="hide">
															<legend>Estadistica</legend>
															<div class="row">
																<section class="col col-3">
																	Estado civil <label class="select"> <select
																		name="civil">
																			<option value="1">Soltero/a</option>
																			<option value="2">Casado/a</option>
																			<option value="3">Divorciado/a</option>
																			<option value="4">Viudo/a</option>
																			<option value="5">Union libre</option>
																	</select>
																	</label>
																</section>
																<section class="col col-2">
																	Sexo&nbsp;
																	<div class="inline-group">
																		<label class="radio"> <input checked
																			type="radio" value="1" name="sexo" placeholder="sexo">
																			<i></i>Masculino
																		</label> <label class="radio"> <input type="radio"
																			value="2" name="sexo" placeholder="sexo"> <i></i>Femenino
																		</label>
																	</div>
																</section>
																<section class="col col-2">
																	Estudio&nbsp;
																	<div class="inline-group">
																		<label class="radio"> <input checked
																			type="radio" value="1" name="estudios"> <i></i>Primaria
																		</label> <label class="radio"> <input type="radio"
																			value="2" name="estudios"> <i></i>Secundaria
																		</label> <label class="radio"> <input type="radio"
																			value="3" name="estudios"> <i></i>Preparatoria
																		</label> <label class="radio"> <input type="radio"
																			value="4" name="estudios"> <i></i>Carrera
																			trunca
																		</label> <label class="radio"> <input type="radio"
																			value="5" name="estudios"> <i></i>Licenciatura
																		</label> <label class="radio"> <input type="radio"
																			value="6" name="estudios"> <i></i>Maestría
																		</label>
																	</div>
																</section>
																<section class="col col-2">
																	Ocupación&nbsp;
																	<div class="inline-group">
																		<label class="radio"> <input checked
																			type="radio" value="1" name="ocupacion"> <i></i>Estudiante
																		</label> <label class="radio"> <input type="radio"
																			value="2" name="ocupacion"> <i></i>Ama de
																			casa
																		</label> <label class="radio"> <input type="radio"
																			value="3" name="ocupacion"> <i></i>Empleado
																		</label> <label class="radio"> <input type="radio"
																			value="4" name="ocupacion"> <i></i>Negocio
																			propio
																		</label>
																	</div>
																</section>
																<section class="col col-2">
																	Tiempo dedicado&nbsp;
																	<div class="inline-group">
																		<label class="radio"> <input checked
																			type="radio" value="1" name="tiempo_dedicado">
																			<i></i>Tiempo completo
																		</label> <label class="radio"> <input type="radio"
																			value="2" name="tiempo_dedicado"> <i></i>Medio
																			tiempo
																		</label>
																	</div>
																</section>
															</div>
														</fieldset>
													</div>

												</div>

											</div>
										</div>
										</form>
									</div>
								</div>

							</div>
						</div>
						<!-- end widget content -->

					</div>
					<!-- end widget div -->
				</div>
				<!-- end widget -->
			</article>
			<!-- END COL -->
		</div> 
		<!-- END ROW -->
	</section>
	<!-- end widget grid --> 
	
<!-- PAGE RELATED PLUGIN(S) -->



				 



