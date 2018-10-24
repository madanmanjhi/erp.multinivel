<div id="spinner-div"></div>
<div class="well">
    <fieldset>
        <div class="row">
            <?php foreach ($providers as $provider) : ?>
                <?php
                $nombre = substr($provider->hashkey, 0, 6);
                $nombre .= " ... ".substr($provider->hashkey, -6);
                ?>
                <div class="col-xs-12">
                    <a onclick="Enviar(<?= $provider->id; ?>,'<?= $nombre; ?>')">
                        <div class="well well-sm txt-color-white text-center link_dashboard" style="background:#3498db">
                            <?php
                            #$icon = '<img src="/template/img/payment/blockchain.png" alt="'.$nombre.'" />';
                            $icon = "<i class='fa fa-qrcode fa-3x'></i>";
                            ?>
                            <?=$icon;?>
                            <h1><?= $nombre; ?></h1>
                        </div>	
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </fieldset>
</div>
<script type="text/javascript">
    function Enviar(id, nombre) {
        bootbox.dialog({
            message: "Estas Seguro(a) que desea pagar con el # " + nombre+" ?",
            title: "Confirmar Transacción",
            className: "",
            buttons: {
                success: {
                    label: "Aceptar",
                    className: "btn-success",
                    callback: function () {
                        setiniciarSpinner();
                        Registrar(id);
                    }
                },
                cancelar: {
                    label: "Cancelar",
                    className: "btn-danger",
                    callback: function () {
                    }
                }
            }
        })
    }
    function Registrar(id) {
        $.ajax({
            data: {
                wx: id,
            },
            type: "POST",
            url: "pagarVentaBlockchain",
            success: function (msg) {
                FinalizarSpinner();
                bootbox.dialog({
                    message: msg,
                    title: "Confirmar Transacción",
                    className: "",
                    buttons: {
                        success: {
                            label: "Aceptar",
                            className: "btn-success",
                            callback: function () {
                                window.location = "/ov/dashboard";
                            }
                        }
                    }
                })
            }
        });
    }
</script>

