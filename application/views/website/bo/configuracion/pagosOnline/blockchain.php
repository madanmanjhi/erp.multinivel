<!-- MAIN CONTENT -->
<div id="content">
    <div class="row">
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <h1 class="page-title txt-color-blueDark">


                <a class="backHome" href="/bo"><i class="fa fa-home"></i> Menu</a>
                <span>
                    > <a href="/bo/configuracion">Configuración</a>
					> <a href="/bo/configuracion/formaspago">Formas de Pago</a>
					> BlockChain
				</span>
            </h1>
        </div>
    </div>
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
                    <header>
						<span class="widget-icon"> <i class="fa fa-bookmark"></i>
						</span>
                        <h2>BlockChain</h2>
                    </header>
                    <?php
                    $wallets = $blockchain[1];
                    $blockchain = $blockchain[0];
                    ?>
                    <!-- widget div-->
                    <div>
                        <form id="form_virtual" method="POST" action="/bo/virtual/actualizar_blockchain" role="form"
                              class="smart-form">
                            <fieldset>
                                <legend>Configuracion API</legend>
                                <input type="hidden" value="<?= $blockchain[0]->id; ?>" name="id">
                                <section id="usuario" class="col col-6">
                                    <label class="input">API key
                                        <input required type="password" name="key" placeholder="***"
                                               value="<?= $blockchain[0]->apikey; ?>">
                                    </label>
                                    <div class="note">
                                        <strong>Nota:</strong> En estado test : 78d9ce16-e1d6-47f7-acf1-f456409715f5
                                    </div>
                                </section>
                                <section class="col col-6">
                                    <label class="select">Moneda
                                        <select id="moneda" name="moneda">
                                            <option value="EUR">EUR</option>
                                            <option value="GBP">GBP</option>
                                            <option selected value="USD">USD</option>
                                            <option value="MXN">MXN</option>
                                        </select>
                                    </label>
                                    <div class="note">
                                        <strong> </strong>
                                    </div>
                                </section>
                                <section id="usuario" class="col col-6">
                                    <label class="checkbox">
                                        <input name="test" <?php if ($blockchain[0]->test == '1') echo "checked='checked'"; ?>
                                               type="checkbox">
                                        <i></i>Test
                                    </label>
                                </section>
                                <section id="usuario" class="col col-6">
                                    <label class="checkbox">
                                        <input name="estatus" <?php if ($blockchain[0]->estatus == 'ACT') echo "checked='checked'"; ?>
                                               type="checkbox">
                                        <i></i>Mostrar en Carrito de Compras
                                    </label>
                                </section>
                            </fieldset>
                            <fieldset>
                                <legend>Wallets</legend>
                                <?php foreach ($wallets as $index => $wallet) : ?>
                                <?php $id_data = $index+1; ?>
                                <div class="well col-md-6 padding-top-10" id="wx_<?=$id_data;?>">
                                    <section class="col col-8 wallet_<?=$id_data;?>">
                                        <i class="icon-append fa fa-money"></i>xPub :
                                        <label class="input">

                                            <textarea required type="text" name="wallet[]"
                                                   id="wallet_<?=$id_data;?>" class="wallet"
                                                   pattern="[A-z0-9--]{111,120}" onkeyup="validarhash(<?=$id_data;?>)"
                                                   rows="3" style="width: 100%;"
                                                   placeholder="111 caracteres"><?= $wallet->hashkey; ?></textarea>
                                        </label>
                                        <div class="note">
                                            <strong>Nota:</strong> En estado test :
                                            <abbr title="xpub6DCYLGKBqULSb45X9tKkg5gCX2QP1o4gx7D9QTruw7xhA6Rp21crjvL6G94Uij4Di6jWZ566t4kFj7Az9BRnBDMcakL81Bs7vhCRnCmgQ26">?</abbr>
                                        </div>
                                    </section>
                                    <section class="col col-4 wallet_per_<?=$id_data;?>">
                                        <?php $per = (sizeof($wallets)>1) ? $wallet->porcentaje : 100; ?>
                                        Porcentaje :
                                        <label class="input">
                                            <i class="icon-prepend">%</i>
                                            <input required type="number" name="wallet_per[]"
                                                   id="wallet_per_<?=$id_data;?>" class="wallet-per"
                                                   max="100" min="0" step="0.01"
                                                   placeholder="máximo: 100%" value="<?= $wallet->porcentaje; ?>">
                                        </label>
                                        <div class="note">
                                            <strong>Nota:</strong> 0 es deshabilitado
                                        </div>
                                    </section>
                                </div>
                                <?php endforeach; ?>
                            </fieldset>
                            <footer>
                                <input style="margin: 1rem;margin-bottom: 4rem;" type="submit" class="btn btn-success" value="Guardar" />
                            </footer>
                        </form>
                    </div>
                    <!-- end widget div -->
                </div>
                <!-- end widget -->
            </article>
            <!-- END COL -->
        </div>
        <div class="row">
            <!-- a blank row to get started -->
            <div class="col-sm-12">
                <br/> <br/>
            </div>
        </div>
        <!-- END ROW -->
    </section>
</div>

<script src="/template/js/plugin/jquery-form/jquery-form.min.js"></script>
<script src="/template/js/validacion.js"></script>
<script src="/template/js/plugin/fuelux/wizard/wizard.min.js"></script>
<script type="text/javascript">

    $("#form_virtual").submit(function (event) {
        event.preventDefault();
        iniciarSpinner();
        enviar();
    });

    $("#moneda").val("<?=$blockchain[0]->currency;?>");

    function validarhash(data=1) {
        var tag = "#wallet_"+data;
        var sec = "section.wallet_"+data;
        var aler = "#alert_"+data;
        var value = $(tag).val();
        var msg = "Por favor, Verifique y digite el Hash permitido.";
        var warn = "<div class='txt-color-red' id='alert_"+data+"'>"+msg+"</div>";

        $(aler).remove();
        if(value.length<111)
            $(sec).append(warn);

    }

    function enviar() {


        $.ajax({
            type: "POST",
            url: "/bo/virtual/actualizar_blockchain",
            data: $("#form_virtual").serialize()
        })
            .done(function (msg) {
                FinalizarSpinner();
                bootbox.dialog({
                    message: msg,
                    title: 'BlockChain',
                    buttons: {
                        success: {
                            label: "Aceptar",
                            className: "btn-success",
                            callback: function () {
                                location.href = "/bo/virtual/blockchain";

                            }
                        }
                    }
                });//fin done ajax

            });//Fin callback bootbox
    }


</script>

