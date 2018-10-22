<div id="spinner-div"></div>
<div class="well">
    <fieldset>
        <div class="row">
            <legend>Total a Pagar</legend>
            <div class="col-md-6">
                <div class="well">
                    <h3><strong><?=$currency?>: </strong></h3>
                    <h2 class="no-padding">$ <?=$value?></h2>
                </div>
            </div>
            <div class="col-md-6">
                <div class="well" style="text-align: right">
                    <h3 ><strong><?=$xe?>: </strong></h3>
                    <h2 class="no-padding" ><?=$amount; ?></h2>
                </div>
            </div>
            <legend>Tasa(s) de cambio</legend>
                <?php foreach ($rates as $cur => $ticker) : ?>
                    <?php $sym = $ticker->symbol; $cuk= " " . $cur; ?>
                    <div class="backHome">
                        <div class="col-md-9" >
                            <?=$sym.round($ticker->m15,2).$cuk; ?>
                        </div>
                        <div class="col-md-3" ><b>1 <?=$xe?></b></div>
                        <div class="col-md-12" >
                            <table>
                                <tr>
                                    <td>precio: <?=  $sym.round($ticker->last,2).$cuk; ?></td>
                                    <td>compra: <?= $sym.round($ticker->buy,2).$cuk; ?></td>
                                    <td>venta: <?= $sym.round($ticker->sell,2).$cuk; ?></td>
                                </tr>
                            </table>
                        </div>

                    </div>


                <?php endforeach; ?>



        </div>
    </fieldset>
</div>
<style type="text/css">
    td {
        text-align: left;
    }
    div.backHome{
        height: auto !important;
        min-height: 3em;
    }
    .backHome td{
        font-size: x-small;
        padding-right: 1rem;
    }
</style>
<script type="text/javascript">
    function Enviar(id, nombre) {
        bootbox.dialog({
            message: "Estas Seguro(a) que desea pagar desde " + nombre+" ?",
            title: "Pago",
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
                prov: id,
            },
            type: "post",
            url: "CompropagoRegistrar",
            success: function (msg) {
                FinalizarSpinner();
                bootbox.dialog({
                    message: msg,
                    title: "Pago",
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

